<?php
class FlowLibrary {
        
        protected $_arrFlowHistory    = array();
        protected $_arrScreenHistory  = array();
	protected $_arrFlows          = array();
        protected $_arrScreens        = array();
        protected $_arrScreensToFlows = array();
        protected $_arrSidebarScreens = array();
        protected $_objReview         = null;
        protected $_isSessionActive   = false;
        protected $_lastUpdateBlank   = false;
        
        
        function __construct()
        {
            $this->setDefaults();
            
                $CI =& get_instance();
            
                if ($CI->session->userdata('intReviewID'))
                {
                    $this->loadSession();
                }
            
        }
        
        public function setReviewIdAndUpdate($intReviewId,$objPatient)
        {
            $CI =& get_instance();
            $CI->load->model('review_model','review_model');
            if ($CI->review_model->setReviewID($intReviewId)->retrieve()->isLoaded())
            {
                $CI->review_model->doSetupAPISession($objPatient);
            }
        }
        
        public function setAuditIdAndLoad($intAuditId)
        {
            $CI =& get_instance();
            $CI->load->model('auditconsultationvalues_model','acv_model');
            if ($CI->acv_model->setAuditID($intAuditId)->retrieve()->isLoaded())
            {
                /*
                echo "<pre>";
                var_dump($CI->review_model->getAsObject());
                die();
                */
                $this->_objReview = $CI->acv_model->getAsObject();
                $CI->session->set_userdata(array(
                             'arrFlowHistory'       => unserialize($this->_objReview->agcsystem_arrFlowHistory),
                             'arrScreenHistory'     => unserialize($this->_objReview->agcsystem_arrScreenHistory),
                             'arrFlows'             => unserialize($this->_objReview->agcsystem_arrFlows),
                             'arrScreens'           => unserialize($this->_objReview->agcsystem_arrScreens),
                             'arrScreensToFlows'    => unserialize($this->_objReview->agcsystem_arrScreensToFlows),
                             'arrSidebarScreens'    => unserialize($this->_objReview->agcsystem_arrSidebarScreens),
                             'intReviewID'          => $intAuditId,
                             'booAudit'             => true
                                ));
                $this->loadSession();
                return $this->_arrScreenHistory[0];
                
            }
            else 
            {
                $CI->session->set_flashdata('strTitle','ERROR FL/SETAUDITIDANDLOAD/1 Review Not Found');
                $CI->session->set_flashdata('strMessage','The AUDIT_ID did not match one stored in the database and therefore could not be loaded.  The following may be helpful.');
                redirect('/error/', 'refresh');
            }
        }
        
        public function isCurrentReviewADatabaseStub()
        {
            $CI =& get_instance();
            $CI->load->model('review_model','review_model');
            return $CI->review_model->isReviewAStub($this->_objReview->intReviewID);
        }
                        
        
        public function setReviewIdAndLoad($intReviewId)
        {
            $CI =& get_instance();
            $CI->load->model('review_model','review_model');
            if ($CI->review_model->setReviewID($intReviewId)->retrieve()->isLoaded())
            {
                /*
                echo "<pre>";
                var_dump($CI->review_model->getAsObject());
                die();
                */
                $this->_objReview = $CI->review_model->getAsObject();
                $CI->session->set_userdata(array(
                             'arrFlowHistory'       => unserialize($this->_objReview->agcsystem_arrFlowHistory),
                             'arrScreenHistory'     => unserialize($this->_objReview->agcsystem_arrScreenHistory),
                             'arrFlows'             => unserialize($this->_objReview->agcsystem_arrFlows),
                             'arrScreens'           => unserialize($this->_objReview->agcsystem_arrScreens),
                             'arrScreensToFlows'    => unserialize($this->_objReview->agcsystem_arrScreensToFlows),
                             'arrSidebarScreens'    => unserialize($this->_objReview->agcsystem_arrSidebarScreens),
                             'intReviewID'          => $intReviewId,
                             'booAudit'             => false
                                ));
                $this->loadSession();
                if ((count($this->_arrScreenHistory)-1) >= 0)
                {
                    return $this->_arrScreenHistory[(count($this->_arrScreenHistory))-1];
                }
                else
                {
                    $CI->session->set_flashdata('strTitle','ERROR FL/SETREVIEWIDANDLOAD/2 Screen History Error');
                    $CI->session->set_flashdata('strMessage','Loading the ReviewID ('.$intReviewId.') returned an invalid Screen History Record [possibly a stub].  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }
            }
            else 
            {
                $CI->session->set_flashdata('strTitle','ERROR FL/SETREVIEWIDANDLOAD/1 Review Not Found');
                $CI->session->set_flashdata('strMessage','The ReviewID did not match one stored in the database and therefore could not be loaded.  The following may be helpful.');
                redirect('/error/', 'refresh');
            }
        }


        public function setDefaults()
        {
            $CI =& get_instance();
            $CI->load->config('flowlibrary', TRUE);
        }
        
        
        public function isSessionActive()
        {
            return $this->_isSessionActive;
        }
        
        public function getReviewID()
        {
            return $this->_objReview->intReviewID;
        }
        
        public function getSidebarScreens()
        {
            return $this->_arrSidebarScreens;
        }
        
        public function getFlowIDForScreenName($strScreenName)
        {
            return $this->_arrScreensToFlows[$strScreenName];
        }
        
        public function getScreenPositionInFlow($strScreenName, $intFlowID = -1)
        {
            if ($intFlowID == -1)
            {
                $intFlowID = $this->getFlowIDForScreenName($strScreenName);
            }
            return array_search($strScreenName, $this->_arrFlows[$intFlowID]->arrScreens);
        }
        
        public function getPercentageCompletion()
        {
            return $this->_arrFlows[$this->_arrFlowHistory[count($this->_arrFlowHistory)-1]]->PercentOfJourney;
        }
        
        public function getScreenCountInFlow($intFlowID)
        {
            return count($this->_arrFlows[$intFlowID]->arrScreens);
        }
        
        public function clearSession()
        {
            $CI =& get_instance();
            
            $CI->session->sess_destroy();
        }
        
        public function createReview($strType,$objUser,$objPatient)
        {
            $CI =& get_instance();
            
            
            $CI->load->model('review_model','review_model');
            
            $this->_objReview = $CI->review_model->create($strType,$objUser)->doSetupAPISession($objPatient)->getAsObject();
            
            
            if ($strType !== '1A')
            {
                 $CI->review_model->doImportPreviousConsultation($objPatient);
                 $CI->review_model->clear();
                 
                 
                 if ($CI->review_model->setReviewID($this->_objReview->intReviewID)->retrieve()->isLoaded())
                    {
                        $this->_objReview = $CI->review_model->getAsObject();
                    }
                 
                 
            }
			else
			{
				$this->_objReview = $CI->review_model->doSetupPatientDrugsFromAPI()->getAsObject();
			}
            
            $CI->review_model->clear();
            
            if (($this->_objReview != null) && property_exists($this->_objReview, 'intReviewID') && ($this->_objReview->intReviewID > 0))
            {
                $CI->session->set_userdata('intReviewID',$this->_objReview->intReviewID);
                $this->_isSessionActive = true;
            }
        }
        
        
        public function updateOtherDrugs($arrInputs = array())
        {
            $CI =& get_instance();
            $CI->load->model('review_model','review_model');
            if ($CI->review_model->setReviewID($this->_objReview->intReviewID)->retrieve()->isLoaded())
            {
                $CI->review_model->deleteOtherDrugs();
                $arrOtherDrugsFields = array();
                foreach ($arrInputs as $key => $val)
                {
                    $arrFieldName = explode('_',$key);
                    if (count($arrFieldName) > 1)
                    {
                        if ($arrFieldName[1]=== 'OtherDrugs')
                        {
                            $arrOtherDrugsFields[$arrFieldName[2]][$arrFieldName[3]] = $val;
                        }
                    }
                }
                if (count($arrOtherDrugsFields) > 0)
                {
                    $CI->review_model->insertOtherDrugs($arrOtherDrugsFields);
                }
            }
            
        }
        
        private function serialiseSessionData($arrInput)
        {
            $CI =& get_instance();
            $arrSession = $CI->session->all_userdata();
            $arrInput['agcsystem_arrFlowHistory']       = serialize($arrSession['arrFlowHistory']);
            $arrInput['agcsystem_arrScreenHistory']     = serialize($arrSession['arrScreenHistory']);
            $arrInput['agcsystem_arrFlows']             = serialize($arrSession['arrFlows']);
            $arrInput['agcsystem_arrScreens']           = serialize($arrSession['arrScreens']);
            $arrInput['agcsystem_arrScreensToFlows']    = serialize($arrSession['arrScreensToFlows']);
            $arrInput['agcsystem_arrSidebarScreens']    = serialize($arrSession['arrSidebarScreens']);
            return $arrInput;
        }
        
        public function storeReviewData($arrInputs = array())
        {
            $CI =& get_instance();
            $CI->load->model('review_model','review_model');
            
            $booSelective = true;
            
            if (count($arrInputs) == 0)
            {
                $arrInputs = (array) $this->_objReview;
                $booSelective = false;
            }
            else
            {
                $booDifferences = false;
                foreach (explode('|',$arrInputs['updated_fields']) as $field)
                {
                    if (!array_key_exists($field, $arrInputs))
                    {
                        $arrInputs[$field] = '';
                    }
                    if (property_exists($this->_objReview, $field) && ($this->_objReview->{$field} !== $arrInputs[$field]))
                    {
                        $booDifferences = true;
                    }
                }
                if (!$booDifferences)
                {
                    $this->_lastUpdateBlank = true;
                    return false;
                }
                
            }
            
            $arrInputs = $this->serialiseSessionData($arrInputs);
            
            if ($CI->review_model->setReviewID($this->_objReview->intReviewID)->retrieve()->isLoaded())
            {
                $this->_objReview = $CI->review_model->updateValues($arrInputs, $booSelective)->store()->getAsObject();
                return true;
            }
            else
            {
                $CI->session->set_flashdata('strTitle','ERROR FL/STOREREVIEWDATA/1 Review Not Found');
                $CI->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database and therefore the new data could not be stored.  The following may be helpful.');
                redirect('/error/', 'refresh');
            }
        }
        
        public function getReviewObject()
        {
            return $this->_objReview;
        }
        
        protected function loadSessionFlows()
        {
            $CI =& get_instance();
            $arrFlows = array();
            
            foreach ($CI->session->userdata('arrFlows') as $intFlowID)
            {
                $arrFlows[$intFlowID] = $this->getFlowObject($intFlowID);
            }
            return $arrFlows;
        }
        
        protected function loadSessionSidebarScreens()
        {
            $CI =& get_instance();
            $CI->load->model('screen_model','s_model');
            
            $arrSidebarScreens = array();
            
            foreach($CI->session->userdata('arrSidebarScreens') as $intFlowID => $arrSidebarScreensInput)
            {
                
                foreach ($arrSidebarScreensInput as $arrSidebarScreen)
                {
                    
                    $CI->s_model->setID($arrSidebarScreen['intScreenID'])->retrieve();
                    if ($CI->s_model->isLoaded())
                    {
                        
                        $objScreen = $CI->s_model->getAsObject();
                        $arrSidebarScreens[$intFlowID][] = array('intValidated' => $arrSidebarScreen['intValidated']
                                                                , 'strScreenName' => $objScreen->ScreenName
                                                                , 'strScreenDisplayText' => $objScreen->ScreenDisplayText
                                                                , 'intScreenID' => $arrSidebarScreen['intScreenID']
                                                                );
                        
                    }
                    else
                    {
                        //error
                    }
                    $CI->s_model->clear();
                }
            }
            
            return $arrSidebarScreens;
        }
        
        protected function loadSessionScreens()
        {
            $CI =& get_instance();
            $arrScreens = array();
            
            foreach ($CI->session->userdata('arrScreens') as $strScreenName)
            {
                $arrScreens[$strScreenName] = $this->getScreenObject($strScreenName);
            }
            return $arrScreens;
        }
        
        public function loadSession()
        {
            $CI =& get_instance();
            if ($CI->session->userdata('arrFlowHistory'))
            {
                $this->_arrFlowHistory = $CI->session->userdata('arrFlowHistory');
            }
            
            if ($CI->session->userdata('arrScreenHistory'))
            {
                $this->_arrScreenHistory = $CI->session->userdata('arrScreenHistory');
            }
            
            if ($CI->session->userdata('arrSidebarScreens'))
            {
                $this->_arrSidebarScreens = $this->loadSessionSidebarScreens();
                
            }
            
            if ($CI->session->userdata('arrFlows'))
            {
                $this->_arrFlows = $this->loadSessionFlows();
            }
            
            if ($CI->session->userdata('arrScreens'))
            {
                $this->_arrScreens = array();
            }
            
            if ($CI->session->userdata('arrScreensToFlows'))
            {
                $this->_arrScreensToFlows = $CI->session->userdata('arrScreensToFlows');
            }
            
            
            $this->_isSessionActive = false;
            if ($CI->session->userdata('intReviewID') && (is_numeric($CI->session->userdata('intReviewID'))) && ($CI->session->userdata('intReviewID') > 0))
            {
                if ($CI->session->userdata('booAudit') !== true)
                {
                    $CI->load->model('review_model','review_model');
                    $CI->review_model->setReviewID($CI->session->userdata('intReviewID'))->retrieve();
                    if ($CI->review_model->isLoaded())
                    {
                        $this->_objReview = $CI->review_model->getAsObject();
                        $CI->review_model->clear();
                        if (($this->_objReview != null) && property_exists($this->_objReview, 'intReviewID') && ($this->_objReview->intReviewID > 0))
                        {
                            $this->_isSessionActive = true;
                        }
                    }
                    else
                    {
                        $CI->session->set_flashdata('strTitle','ERROR FL/LOADSESSION/1 Review Not Found');
                        $CI->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                }
                else
                {
                    $CI->load->model('Auditconsultationvalues_Model','audit_model');
                    $CI->audit_model->setAuditID($CI->session->userdata('intReviewID'))->retrieve();
                    
                    if ($CI->audit_model->isLoaded())
                    {
                        $this->_objReview = $CI->audit_model->getAsObject();
                        $CI->audit_model->clear();
                        if (($this->_objReview != null) && property_exists($this->_objReview, 'intReviewID') && ($this->_objReview->intReviewID > 0))
                        {
                            $this->_isSessionActive = true;
                        }
                    }
                    else
                    {
                        $CI->session->set_flashdata('strTitle','ERROR FL/LOADSESSION/2 Audit Not Found');
                        $CI->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                }
                
                
                 
            }
            
            
        }
        
        protected function getSessionFlowsArray()
        {
            $arrFlows = array();
            foreach($this->_arrFlows as $key => $val)
            {
                $arrFlows[] = $key;
            }
            return $arrFlows;
        }


        protected function getSessionScreensArray()
        {
            $arrScreens = array();
            foreach($this->_arrScreens as $key => $val)
            {
                $arrScreens[$key] = $val['int'];
            }
            return $arrScreens;
        }
        
        protected function getSessionSidebarScreensArray()
        {
            $arrSidebarScreens = array();
            
            
            
            if (count($this->_arrSidebarScreens) > 0)
            {
                
                foreach ($this->_arrSidebarScreens as $intFlowID => $arrSidebarScreensInput)
                {
                    
                    
                    
                
                    foreach ($arrSidebarScreensInput as $arrSidebarScreen)
                    {
                        
                        $arrSidebarScreens[$intFlowID][] = array('intValidated' => $arrSidebarScreen['intValidated']
                                                                            , 'intScreenID' => $arrSidebarScreen['intScreenID']
                                                                            );
                    }
                }
            }
           
            
            
            return $arrSidebarScreens;
        }
        
        protected function getFlowObject($intFlowID)
        {
            $CI =& get_instance();
            $CI->load->model('flow_model','flow_model');
            $CI->flow_model->setID($intFlowID)->retrieveAndGetScreens();
            
            if ($CI->flow_model->isLoaded())
            {
                $objFlow = $CI->flow_model->getAsObject();
                $CI->flow_model->clear();
                return $objFlow;
            }
            else
            {
                return false;
            }
            
        }

        protected function getScreenObject($strScreenName)
        {
            $CI =& get_instance();
            $CI->load->model('screen_model','s_model');
            return $CI->s_model->setScreenName($strScreenName)->retrieve()->getAsObject();
        }

        public function updateSession()
        {
            $CI =& get_instance();
            $CI->session->set_userdata(array(
                             'arrFlowHistory'       => $this->_arrFlowHistory,
                             'arrScreenHistory'     => $this->_arrScreenHistory,
                             'arrFlows'             => $this->getSessionFlowsArray(),
                             'arrScreens'           => $this->getSessionScreensArray(),
                             'arrScreensToFlows'    => $this->_arrScreensToFlows,
                             'arrSidebarScreens'    => $this->getSessionSidebarScreensArray(),
                             'intReviewID'          => $this->_objReview->intReviewID
            ));
            
            
        }
        
        
        public function pushFlowID($intFlowID)
        {
            $CI =& get_instance();
            if ($intFlowID >0)
            {
                $this->loadSession();
                if (!in_array($intFlowID,$this->_arrFlowHistory))
                {
                    
                    $objFlowObject = $this->getFlowObject($intFlowID);
                    if ($objFlowObject != false)
                    {
                        $this->_arrFlows[$intFlowID] = $objFlowObject;
                        $CI->load->model('screen_model','s_model');
                        foreach ($this->_arrFlows[$intFlowID]->arrScreens as $strScreenName)
                        {
                            $this->_arrScreensToFlows[$strScreenName] = $intFlowID;
                            $CI->s_model->setScreenName($strScreenName)->retrieve();
                            
                            
                            
                            if ($CI->s_model->isLoaded())
                            {
                                
                                $objScreen = $CI->s_model->getAsObject();
                                $this->_arrSidebarScreens[$intFlowID][] = array('intValidated' => 0
                                                                        , 'strScreenName' => $strScreenName
                                                                        , 'strScreenDisplayText' => $objScreen->ScreenDisplayText
                                                                        , 'intScreenID' => $objScreen->ScreenID
                                                                        );
                            }
                            else
                            {
                                //error
                            }
                            $CI->s_model->clear();
                             
                        }
                        
                        
                        
                        $this->_arrFlowHistory[] = $intFlowID;
                        $this->updateSession();
                    }
                    else
                    {
                        $CI->session->set_flashdata('strTitle','ERROR FL/PUSHFLOWID/1 Flow Not Found');
                        $CI->session->set_flashdata('strMessage','The FlowID did not match one stored in the database.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                }
            }   
        }
        
        
        protected function markSuccessiveScreensUnvalidated($intCurrentFlow,$strCurrentScreenName)
        {
            $intFlowPosition = array_search($intCurrentFlow, $this->_arrFlowHistory);
            if ($intFlowPosition != (count($this->_arrFlowHistory)-1))
            {
                // slice the array, returning just the index positions after the current position, but not the last...
                $intOffset = 1;
                foreach (array_slice($this->_arrFlowHistory, $intFlowPosition,-1) as $intFlowID)
                {
                    // we need to get all screens within this flow
                    // - however if this is the current flow (first loop) then we ignore previous screens...
                    $arrScreens = $this->_arrFlows[$intFlowID]->arrScreens;
                    if ($intFlowID == $intCurrentFlow)
                    {
                        $arrScreens = array();
                        // okay, this is the current flow.  We need to ignore the previous screens, not mark
                        // the current screen as invalid (validation already done by submit) and check if a next 
                        // screen exists in this flow...
                        $intScreenPosition = array_search($strCurrentScreenName, $this->_arrFlows[$intFlowID]->arrScreens);
                        if (array_key_exists($intScreenPosition+1,$this->_arrFlows[$intFlowID]->arrScreens))
                        {
                            // yes, there is more screens in this flow.  Slice the array for just these next screens
                            $arrScreens = array_slice($this->_arrFlows[$intFlowID]->arrScreens
                                                        , $intScreenPosition+1);
                        }
                        
                    }
                    
                    foreach ($arrScreens as $strScreenName)
                    {
                        $this->screenValidated($strScreenName,2);
                    }
                    
                    $intOffset++;
                }
                $this->updateSession();
            }
        }
        
        protected function removeFlowsAfter($intFlowPosition)
        {
            /*echo "<pre>";
            echo "<hr /><h1>Before</h1>";
            echo "<h3>arrFlows</h3>";
            var_dump($this->_arrFlows);
            echo "<h3>arrFlowHistory</h3>";
            var_dump($this->_arrFlowHistory);
            echo "<h3>arrScreensToFlows</h3>";
            var_dump($this->_arrScreensToFlows);
            echo "<h3>arrSidebarScreens</h3>";
            var_dump($this->_arrSidebarScreens);
            echo "<h3>arrScreenHistory</h3>";
            var_dump($this->_arrScreenHistory);*/
            
            /*echo "<hr />";
            echo "<h1>during</h1>";*/
            $intFlowID = $this->_arrFlowHistory[$intFlowPosition];
            /*echo "intFlowID = ".$intFlowID."/r/n";
            echo "intFlowPosition = ".$intFlowPosition."/r/n";*/
            
            
            $arrScreensToRemove = array();
            
            // get all Screen Names to be deleted
            foreach(array_slice($this->_arrFlows,$intFlowPosition+1) as $objFlow)
            {
                foreach ($objFlow->arrScreens as $strScreenName)
                {
                    $arrScreensToRemove[] = $strScreenName;
                }
            }
            /*echo "<h3>arrScreensToRemove</h3>";
            var_dump($arrScreensToRemove);
            */
            $arrNewScreensToFlows = array();
            foreach($this->_arrScreensToFlows as $strScreenRef => $intFlowRef)
            {
                if (!in_array($strScreenRef,$arrScreensToRemove))
                {
                    $arrNewScreensToFlows[$strScreenRef] = $intFlowRef;
                }
            }
            $this->_arrScreensToFlows = $arrNewScreensToFlows;
            
            $arrNewScreenHistory = array();
            foreach($this->_arrScreenHistory as $strScreenRef)
            {
                if (!in_array($strScreenRef, $arrScreensToRemove))
                {
                    $arrNewScreenHistory[] = $strScreenRef;
                }
            }
            $this->_arrScreenHistory = $arrNewScreenHistory;
            
            
            $this->_arrFlowHistory          = array_slice($this->_arrFlowHistory, 0, $intFlowPosition+1);
            $this->_arrSidebarScreens       = array_slice($this->_arrSidebarScreens,0,$intFlowPosition+1,true);
            $this->_arrFlows                = array_slice($this->_arrFlows,0,$intFlowPosition+1, true);
            /*echo "<hr /><h1>After</h1>";
            echo "<h3>arrFlows</h3>";
            var_dump($this->_arrFlows);
            echo "<h3>arrFlowHistory</h3>";
            var_dump($this->_arrFlowHistory);
            echo "<h3>arrScreensToFlows</h3>";
            var_dump($this->_arrScreensToFlows);
            echo "<h3>arrSidebarScreens</h3>";
            var_dump($this->_arrSidebarScreens);
            echo "<h3>arrScreenHistory</h3>";
            var_dump($this->_arrScreenHistory);
            die();*/
            
            
            $this->updateSession();
        }


        public function runSuccessiveFlowExits($intCurrentFlow,$strCurrentScreenName)
        {
            // if not the very last last flow...
            
            $intFlowPosition = array_search($intCurrentFlow, $this->_arrFlowHistory);
            if ($intFlowPosition != (count($this->_arrFlowHistory)-1))
            {
                // first, mark all successive screens as invalidated
                $this->markSuccessiveScreensUnvalidated($intCurrentFlow, $strCurrentScreenName);
                
                
                // slice the array, returning just the index positions after the current position, but not the last...
                $intOffset = 1;
                foreach (array_slice($this->_arrFlowHistory, $intFlowPosition, -1) as $intFlowID)
                {
                    // we need to run all screen exits within this flow before Flow Exits
                    // - however if this is the current flow (first loop) then we ignore previous screens...
                    $arrScreens = $this->_arrFlows[$intFlowID]->arrScreens;
                    if ($intFlowID == $intCurrentFlow)
                    {
                        $arrScreens = array();
                        // okay, this is the current flow.  We need to ignore the previous screens, not run
                        // the current screen exit function (already done by controller) and check if a next 
                        // screen exists in this flow...
                        $intScreenPosition = array_search($strCurrentScreenName, $this->_arrFlows[$intFlowID]->arrScreens);
                        if (array_key_exists($intScreenPosition+1,$this->_arrFlows[$intFlowID]->arrScreens))
                        {
                            // yes, there is more screens in this flow.  Slice the array for just these next screens
                            $arrScreens = array_slice($this->_arrFlows[$intFlowID]->arrScreens
                                                        , $intScreenPosition+1);
                        }
                        
                    }
                    
                    foreach ($arrScreens as $strScreenName)
                    {
                        $this->screenChangeRoutine($strScreenName,'entry');
                        $this->screenChangeRoutine($strScreenName,'exit');
                        //$this->screenValidated($strScreenName);
                    }
                    
                    $intOutputFlowID = $this->exitFlow($intFlowID);
                    
                    if ($this->_arrFlowHistory[$intFlowPosition+$intOffset]!=$intOutputFlowID)
                    {
                        // okay, this wasn't the flow we were expecting.  We need to remove the flows (and screens) that 
                        // are now erroneous.  This is to remove all flows after the previous one...
                        $this->removeFlowsAfter($intFlowPosition+$intOffset-1);
                        
                        
                        
                        $this->pushFlowID($intOutputFlowID);
                        redirect('flowcontroller/flowchanged/'.$strCurrentScreenName, 'refresh');
                    }
                    
                    $intOffset++;
                }
            }
        }
        
        
        public function exitFlow($intFlowID)
        {
            $CI =& get_instance();
            
            if (array_key_exists($intFlowID, $this->_arrFlows) && property_exists($this->_arrFlows[$intFlowID], 'FieldID'))
            {
                if (($this->_arrFlows[$intFlowID]->FieldID != null) && ($this->_arrFlows[$intFlowID]->FieldID != ''))
                {
                    // firat get function definition

                    $CI->load->model('field_model','field_model');
                    $objField = $CI->field_model->setID($this->_arrFlows[$intFlowID]->FieldID)->retrieve()->getAsObject();

                    if (strlen($objField->ValidationString) > 0)
                    {
                        $arrFieldValidationText = explode('(',$objField->ValidationString);
                        $strFunctionName = $arrFieldValidationText[0];

                        if (method_exists($this,$strFunctionName))
                        {
                            $intReturn = $this->runExitFlowFunction($intFlowID,str_getcsv(substr($arrFieldValidationText[1], 0, -1)),$strFunctionName);



                            $this->_objReview->{$objField->strFieldNameForForm} = $intReturn;

                            $this->storeReviewData();





                            return $intReturn;





                            //return $this->runExitFlowFunction($intFlowID,str_getcsv(substr($arrFieldValidationText[1], 0, -1)),$strFunctionName);
                        }
                        else
                        {
                            //method doesn't exist...
                            $this->throwErrorRedirect('ERROR FL/EXITFLOW/1 Flow Exit Function Missing'
                                                        , 'There was no Flow Exit Function for FlowID '.$intFlowID.', the name specified was not found in the library. The following may be helpful.');
                        }
                    }
                    else
                    {
                        $this->throwErrorRedirect('ERROR FL/EXITFLOW/2 No Flow Field ValidationString'
                                                    ,'There was no Function Declaration stored against the Field for the <strong>FLOW</strong>ID '.$intFlowID.'. The following may be helpful.');

                    }
                }
                else
                {
                    $this->throwErrorRedirect('ERROR FL/EXITFLOW/3 No Flow FieldID'
                                                ,'There was no Field ID marked against the <strong>FLOW</strong>ID '.$intFlowID.' to load from the Database, or it wasn\'t correct. The following may be helpful.');

                }
            }
            else
            {
                $this->throwErrorRedirect('ERROR FL/EXITFLOW/4 Flow Declared to Exit From Does Not Belong'
                                                ,'The Flow you tried to exit from (<strong>FLOW</strong>ID '.$intFlowID.') does not exist in the current route through the review. The following may be helpful.');

            }
        }
        
        
        public function getNextScreen($intFlowID,$strPreviousScreen = "")
        {
            
            if (strlen($strPreviousScreen) == 0)
            {
                //return first of a particular flow
                return $this->_arrFlows[$intFlowID]->arrScreens[0];
            }
            else
            {
                $intScreenPosition = array_search($strPreviousScreen, $this->_arrFlows[$intFlowID]->arrScreens);

                if (($intScreenPosition+1) < count($this->_arrFlows[$intFlowID]->arrScreens))
                {
                    // return next screen in current flow...
                    
                    return $this->_arrFlows[$intFlowID]->arrScreens[$intScreenPosition+1];
                }
                else 
                {
                    // need to find next flow.
                    $strInvalidScreen = $this->getFirstInvalidScreen($strPreviousScreen);
                    if (strlen($strInvalidScreen) === 0)
                    {
                        return $this->exitFlow($intFlowID);
                    }
                    else
                    {
                        redirect('flowcontroller/screensinvalid/'.$strInvalidScreen, 'refresh');
                    }
                }
            }
        }
        
        
        public function runExitFlowFunction($intFlowID,$arrParametersForFlowFunction,$strFunctionName)
        {
            $CI =& get_instance();
            

            $arrParameters = array();
            foreach ($arrParametersForFlowFunction as $strFieldDefinitions)
            {
                if (is_numeric($strFieldDefinitions))
                {
                    $arrParameters[] = (int)$strFieldDefinitions;
                }
                else
                {
                    if (strlen(trim($strFieldDefinitions)) > 0)
                    {
                        $arrFieldDefinition = explode(',',$strFieldDefinitions);
                        $CI->field_model->clear();

                        if ($CI->field_model->retrieveFromTableAndFieldNames($arrFieldDefinition[0],$arrFieldDefinition[1])->isLoaded())
                        {
                            $objFieldReference = $CI->field_model->getAsObject();

                            $arrParameters[] = $this->_objReview->{$objFieldReference->strFieldNameForForm};
                        }
                    }
                    else 
                    {
                        $arrParameters[] = '';
                    }

                }
            }


            if (count($arrParameters) == count($arrParametersForFlowFunction))
            {
                return $this->{$strFunctionName}($arrParameters);
            }
            else
            {
                $this->throwErrorRedirect('ERROR FL/RUNEXITFLOWFUNCTION/1 Invalid Parameters'
                                            , 'The parameters supplied could not be entered into exit routine of the flow ('.$intFlowID
                                                .' - Expected '.count($arrParametersForFlowFunction).', Recieved '.count($arrParameters).'). The following may be helpful.');
            }
            
        }
        
        
        public function screenChangeRoutine($strScreenName,$strMode = 'exit')
        {
            $CI =& get_instance();
            
            
            $CI->load->model('screen_model','s_model');
            $CI->s_model->setScreenName($strScreenName)->retrieve();
            
            
            
            if ($CI->s_model->isLoaded() && array_key_exists($strScreenName,$this->_arrScreensToFlows))
            {
                $CI->load->model('field_model','f_model');
                $objScreen = $CI->s_model->getAsObject();
                $CI->s_model->clear();
                if ((($strMode === 'exit') && ($objScreen->FieldID != ''))
                        || (($strMode === 'entry') && ($objScreen->FieldIDPre != '')))
                {
                    $strFieldID = '';
                    switch ($strMode)
                    {
                        case 'entry':
                            $strFieldID = $objScreen->FieldIDPre;
                            break;
                        case 'exit':
                            $strFieldID = $objScreen->FieldID;
                            break;
                    }
                    
                    
                    
                    $CI->f_model->setID($strFieldID)->retrieve();
                    if ($CI->f_model->isLoaded())
                    {
                        $objField = $CI->f_model->getAsObject();
                        $CI->f_model->clear();
                        $arrFieldValidationText = explode('(',$objField->ValidationString);
                        $strFunctionName = $arrFieldValidationText[0];

                        if (method_exists($this,$strFunctionName))
                        {
                            $arrParametersForScreenFunction = str_getcsv(substr($arrFieldValidationText[1], 0, -1));
                            $arrParameters = array();
                            $arrParameterNames = array();
                            foreach ($arrParametersForScreenFunction as $strFieldDefinitions)
                            {
                                if (is_numeric($strFieldDefinitions))
                                {
                                    $arrParameters[] = (int)$strFieldDefinitions;
                                }
                                else
                                {
                                    $arrFieldDefinition = explode(',',$strFieldDefinitions);
                                    $CI->f_model->clear();

                                    if ($CI->f_model->retrieveFromTableAndFieldNames($arrFieldDefinition[0],$arrFieldDefinition[1])->isLoaded())
                                    {
                                        $objFieldReference = $CI->f_model->getAsObject();

                                        $arrParameters[] = $this->_objReview->{$objFieldReference->strFieldNameForForm};
                                        $arrParameterNames[] = $arrFieldDefinition[0]."_".$arrFieldDefinition[1];
                                    }
                                    else
                                    {
                                        $arrParameterNames[] = 'MISSING_PARAMETER';
                                    }

                                }
                            }


                            if (count($arrParameters) == count($arrParametersForScreenFunction))
                            {
                                $this->_objReview->{$objField->strFieldNameForForm} = $this->{$strFunctionName}($arrParameters);
                                $this->storeReviewData();
                            }
                            else
                            {
                                $strParamaterReferences = implode(',',$arrParameterNames);
                                $CI->session->set_flashdata('strTitle','ERROR FL/SCREENCHANGEROUTINE/4 Parameter Failure');
                                $CI->session->set_flashdata('strMessage','The parameters (['.substr($arrFieldValidationText[1], 0, -1).'] versus ['.$strParamaterReferences.']) supplied could not be entered into exit routine ('.$strFunctionName.') of the screen. The following may be helpful.');
                                redirect('/error/', 'refresh');
                            }

                        }
                        else
                        {
                            $CI->session->set_flashdata('strTitle','ERROR FL/SCREENCHANGEROUTINE/3 Function Failure');
                            $CI->session->set_flashdata('strMessage','Could not reference the function name related to the exit routine of the screen. The following may be helpful.');
                            redirect('/error/', 'refresh');
                        }
                    }
                    else
                    {
                        $CI->session->set_flashdata('strTitle','ERROR FL/SCREENCHANGEROUTINE/2 Field Failure');
                        $CI->session->set_flashdata('strMessage','Could not load the field related to the exit function of the screen. The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                }
            }
            else
            {
                $CI->session->set_flashdata('strTitle','ERROR FL/SCREENCHANGEROUTINE/1 Screen Inappropriate');
                $CI->session->set_flashdata('strMessage','Could not load the screen. The following may be helpful.');
                redirect('/error/', 'refresh');
            }
            
        }
        
        public function pushScreenName($strScreenName)
        {
            $CI =& get_instance();
            $this->loadSession();
            
            $CI->load->model('screen_model','s_model');
            $CI->s_model->setScreenName($strScreenName)->retrieve();
            
            $intReturn = -1;
            
            if ($CI->s_model->isLoaded() && array_key_exists($strScreenName,$this->_arrScreensToFlows))
            {
                $intReturn = $intFlowId = $this->_arrScreensToFlows[$strScreenName];
                $objScreen = $CI->s_model->getAsObject();
                
                if ($CI->session->userdata('booAudit') !== true)
                {
                    if ($objScreen->FieldIDPre != '')
                    {
                        $this->screenChangeRoutine($strScreenName,'entry');
                    }
                }
                
                if (!in_array($strScreenName,$this->_arrScreenHistory))
                {
                    $this->_arrScreenHistory[] = $strScreenName;
                    $this->updateSession();
                }
            }
            
            
            
            return $intReturn;
        }
        
        public function getPreviousScreen($strScreenName)
        {
            $strReturn = '';
            if (array_key_exists($strScreenName,$this->_arrScreensToFlows))
            {
                $intArrayPosition = array_search($strScreenName,$this->_arrScreenHistory);
                if ($intArrayPosition && ($intArrayPosition > 0))
                {
                    $strReturn = $this->_arrScreenHistory[$intArrayPosition-1];
                }
            }
            return $strReturn;
        }
        
        public function getScreenValidated($strScreenName)
        {
            if (array_key_exists($strScreenName,$this->_arrScreensToFlows))
            {
                
                $intFlowId = $this->_arrScreensToFlows[$strScreenName];
                
                $intScreenPosition = -1;
                $intCounter = 0;
                
                foreach ($this->_arrSidebarScreens[$intFlowId] as $arrSidebarScreen)
                {
                    
                    if ($arrSidebarScreen['strScreenName'] === $strScreenName)
                    {
                        
                        $intScreenPosition = $intCounter;
                    }
                    $intCounter++;
                }
                
                if ($intScreenPosition != -1)
                {
                    return $this->_arrSidebarScreens[$intFlowId][$intScreenPosition]['intValidated'];
                }
                
                return 0;
            }
        }
        
        public function getFirstInvalidScreen($strCurrentScreen)
        {
            $strReturn = '';
            $booCurrentScreenReached = false;
            foreach ($this->_arrSidebarScreens as $intFlowId => $arrFlowDescription)
            {
                foreach ($arrFlowDescription as $intScreenPosition => $arrScreen)
                {
                    if ((strlen($strReturn) === 0) && ($arrScreen['intValidated'] < 1) && !$booCurrentScreenReached)
                    {
                        $strReturn = $arrScreen['strScreenName'];
                    }
                    if ($strCurrentScreen === $arrScreen['strScreenName'])
                    {
                        $booCurrentScreenReached = true;
                    }
                }
            }
            return $strReturn;
        }
        
        public function screenValidated($strScreenName,$intValidated=0)
        {
            
            if (array_key_exists($strScreenName,$this->_arrScreensToFlows))
            {
                
                $intFlowId = $this->_arrScreensToFlows[$strScreenName];
                
                $intScreenPosition = -1;
                $intCounter = 0;
                foreach ($this->_arrSidebarScreens[$intFlowId] as $arrSidebarScreen)
                {
                    
                    if ($arrSidebarScreen['strScreenName'] === $strScreenName)
                    {
                        
                        $intScreenPosition = $intCounter;
                    }
                    $intCounter++;
                }
                
                if ($intScreenPosition != -1)
                {
                    $this->_arrSidebarScreens[$intFlowId][$intScreenPosition]['intValidated'] = $intValidated;
                    /*var_dump($this->_arrSidebarScreens[$intFlowId][$intScreenPosition]);
                    die();*/
                    $this->updateSession();
                }
                
            }
            
        }
        
        
        
        
        
        
        
        protected function throwErrorRedirect($strTitle = '',$strMessage ='')
        {
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle',$strTitle);
            $CI->session->set_flashdata('strMessage',$strMessage);
            redirect('/error/', 'refresh');
        }
        
        
        
}