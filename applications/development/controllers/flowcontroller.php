<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FlowController extends MY_Controller {
    
        public function flow($intInput = -1)
        {
            $intStartFlow = 1000;
            $this->load->library('FlowLibrary');
            $this->load->library('FlowAlgorithmsLibrary',array(),'flowalgorithmslibrary');
            
            if (is_numeric($intInput) && ($intInput > 0))
            {
                /*
                if (($intInput == $intStartFlow)&&($strSessionCreate == 'true'))
                {
                    if ($this->flowalgorithmslibrary->isSessionActive())
                    {
                        $this->flowalgorithmslibrary->clearSession();
                        redirect('/flowcontroller/flow/'.$intInput.'/true', 'refresh');
                    }
                    $this->flowalgorithmslibrary->createReview();
                }
                */
                if ($this->flowalgorithmslibrary->isSessionActive())
                {
                    $this->flowalgorithmslibrary->pushFlowID($intInput);
                    redirect('/flowcontroller/screen/'.$this->flowalgorithmslibrary->getNextScreen($intInput), 'refresh');
                }
                else
                {
                    $this->throwErrorRedirect('ERROR FC/FLOW/1 No Review ID'
                                                , 'There was no Review ID stored in the Session to load from the Database.  Did you try to load the wrong flow?  The following may be helpful.');
                }
            }
            else
            {
                $this->throwErrorRedirect('ERROR FC/FLOW/2 No Flow ID'
                                                , 'There was no Flow ID to load from the Database, or it wasn\'t correct. The following may be helpful.');
            }
        }
        
        
	public function screen($strScreenName = '')
	{
            $this->load->library('FlowLibrary');
            $this->load->library('FlowAlgorithmsLibrary');
            $strCurrentPage          = "flowcontroller/screen";
            $this->load->model('questiongrouppage_model','qgp_model');
           
            
            
            $objQgp = $this->qgp_model->setScreenName($strScreenName)->retrieveAndGetQuestionGroups()->getAsObject();

            if ($this->qgp_model->isLoaded())
            {
                $intFlowId = $this->flowalgorithmslibrary->pushScreenName($strScreenName);
                ///var_dump($this->session->all_userdata());
                if ($intFlowId > 0)
                {
                    
                    //$this->patient_model->loadPatient($this->session->userdata('objPatient'));
                    ///var_dump($this->session->all_userdata());
                    
                    /*$objPatient = $this->patient_model->getAsObject();
                    $this->session->set_userdata('objPatient', $objPatient);
                    */
                    
                    if ($this->input->post('btnFormSubmitNext') || $this->input->post('agcscreenname_'.$strScreenName.'_destination'))
                    {
                        //$this->patient_model->loadNewData($this->input->post());
                        //$objPatient = $this->patient_model->getAsObject();
                        //$this->session->set_userdata('objPatient', $objPatient);
                        /*
                        echo "<pre>";
                        var_dump($this->input->post());
                        die();
                        */
                        if ($this->input->post('agcscreenname_'.$strScreenName.'_validated') === 'true')
                        {
                            $this->flowalgorithmslibrary->screenValidated($strScreenName, 1);
                            
                        }
                        else
                        {
                            if ($this->input->post('agcscreenname_'.$strScreenName.'_validated') === 'false')
                            {
                                $this->flowalgorithmslibrary->screenValidated($strScreenName, -1);
                            }
                            
                        }
                        /*
                        if ($this->input->post('agcscreenname_'.$strScreenName.'_destination'))
                        {
                            echo "<pre>";
                            var_dump($this->input->post());
                            die();
                        }*/
                        
                        
                        
                        
                        //CORBAN CHANGE 2015-07-29 and 2015-10-02
                        //if ($strScreenName === 'CMNew2')
						if ($strScreenName === 'CMNew2' || $strScreenName === 'NPRX1' || $strScreenName === 'NPRX2')
                        {
                            $this->flowalgorithmslibrary->updateOtherDrugs($this->input->post());
                        }
                        
                        
                                                
                        
                        
                        if ($this->flowalgorithmslibrary->storeReviewData($this->input->post()))
                        {
                            $this->flowalgorithmslibrary->screenChangeRoutine($strScreenName,'exit');
                        
                            $this->flowalgorithmslibrary->runSuccessiveFlowExits($intFlowId, $strScreenName);                        
                        }
                        
                        if ($this->input->post('btnFormSubmitNext'))
                        {
                            $intFlowID                  = $this->flowalgorithmslibrary->getFlowIDForScreenName($strScreenName);
                            $intScreenFlowPosition      = $this->flowalgorithmslibrary->getScreenPositionInFlow($strScreenName,$intFlowID);
                            $intFlowScreenCount         = $this->flowalgorithmslibrary->getScreenCountInFlow($intFlowID);
                             
                            
                                if (($intFlowID < 20000) || ($intScreenFlowPosition < ($intFlowScreenCount-1)))
                                {
                                    $mixRoute = $this->flowalgorithmslibrary->getNextScreen($intFlowId,$strScreenName);
                                    if (is_numeric($mixRoute))
                                    {
                                        if ($mixRoute > 0)
                                        {

                                                redirect('/flowcontroller/flow/'.$mixRoute, 'refresh');

                                        }
                                    }
                                    else
                                    {
                                        redirect('/flowcontroller/screen/'.$mixRoute, 'refresh');
                                    }
                                }
                                else
                                {
                                    redirect('/finish/pushtoapi', 'refresh');
                                }
                            
                            
                        }
                        else
                        {
                            if ($this->input->post('agcscreenname_'.$strScreenName.'_destination') === "ABANDON")
                            {
                                $CI =& get_instance();
                                if (!$this->flowalgorithmslibrary->isCurrentReviewADatabaseStub())
                                {
                                    $CI->session->set_flashdata('strTitle',"Consultation has been saved");
                                    $CI->session->set_flashdata('strMessage',"Your consultation has been stored for completion another time.");
                                }
                                else
                                {
                                    $CI->session->set_flashdata('strTitle',"Consultation has not been saved");
                                    $CI->session->set_flashdata('strMessage',"No data was entered in this consultation.");
                                }
                                redirect('/information/abandon/', 'refresh');
                            }
                            else
                            {
                                redirect('/flowcontroller/screen/'.$this->input->post('agcscreenname_'.$strScreenName.'_destination'), 'refresh');
                            }
                        }
                    }
                    

                    
                    $this->showScreen(
                            $objQgp
                            , $this->flowalgorithmslibrary->getReviewObject()
                            , $strCurrentPage
                            , $strScreenName
                            , $this->flowalgorithmslibrary->getSidebarScreens()
                            , $this->flowalgorithmslibrary->getPreviousScreen($strScreenName)
                            , $this->flowalgorithmslibrary->getScreenValidated($strScreenName)
                            );
                    
                }
                else
                {
                    ///error
                    $this->throwErrorRedirect('ERROR FC/SCREEN/2 Orphan Screen('.$strScreenName.'=>'.$intFlowId.')'
                                                , 'The screen requested ('.$strScreenName.'=>'.$intFlowId.') does not belong to a flow you have begun. The following may be helpful.');
                }
            }
            else 
            {
                $this->throwErrorRedirect('ERROR FC/SCREEN/3 Screen Not Found'
                                                , 'The screen requested does not exist. The following may be helpful.');
            }
            
	}
        
        /*
        
        public function drugsTest($strDrugType = '')
        {
            $this->load->model('apidrugs_model','d_model');
            
            
            
            
            $strDrug = $this->d_model->getLabelFor('FBA7449C-FE2A-4C3B-8416-6E8494EF93EC');
            
            
            $arrDrugs = $this->d_model->getValuesForDropDown($strDrugType);
            
            
            
            
            
            echo "<pre>";
            
            echo "The Drug with ID:FBA7449C-FE2A-4C3B-8416-6E8494EF93EC has the label ".$strDrug.". \r\n\r\n";
            
            
            var_dump($arrDrugs);
            exit();
        }
        
        
        public function drugsTestTwo()
        {
            $this->load->model('apidrugs_model','d_model');
            $this->d_model->getDrugsXMLTest();
            
        }
        */
        private function showScreen($objQgp, $objReview, $strCurrentPage, $strScreenName, $arrSidebarScreens, $strPreviousScreen, $intScreenValidated)
        {
            $this->load->library('FlowLibrary');
            $this->load->library('FlowAlgorithmsLibrary');
            
            
            
            $this->load->helper('form');            
            $this->document->setTitleString($objQgp->ScreenDisplayText);
            $this->document->setH1String($objQgp->ScreenDisplayText);
            
            $this->document->addScriptToScriptArray('canvasjs.js');
            
            $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
            $arrVariablesForTemplate['arrPageVariables']['objQgp']                  = $objQgp;
            $arrVariablesForTemplate['arrPageVariables']['objReview']               = $objReview;
            
            $arrVariablesForTemplate['arrPageVariables']['strScreenName']           = $strScreenName;
            $arrVariablesForTemplate['arrPageVariables']['strPreviousScreen']       = $strPreviousScreen;
            $arrVariablesForTemplate['arrPageVariables']['intScreenValidated']      = $intScreenValidated;
            
            $arrVariablesForTemplate['arrPageVariables']['intFlowID']               = $this->flowalgorithmslibrary->getFlowIDForScreenName($strScreenName);
            $arrVariablesForTemplate['arrPageVariables']['intScreenFlowPosition']   = $this->flowalgorithmslibrary->getScreenPositionInFlow($strScreenName
                                                                                                                        ,$arrVariablesForTemplate['arrPageVariables']['intFlowID']);
            $arrVariablesForTemplate['arrPageVariables']['intFlowScreenCount']      = $this->flowalgorithmslibrary->getScreenCountInFlow($arrVariablesForTemplate['arrPageVariables']['intFlowID']);
            
            $arrVariablesForTemplate['arrPageVariables']['arrModels']               = array();
            
            $this->load->model('apidrugs_model',                    'd_model');
            $this->load->model('previousconsultationvalues_model',  'pcv_model');
            $this->load->model('previousconsultationexacerbation_model',  'pce_model');
            
            $arrVariablesForTemplate['arrPageVariables']['arrModels']['drugs_model']                        = $this->d_model;
            $arrVariablesForTemplate['arrPageVariables']['arrModels']['pcv_model']                          = $this->pcv_model;
            $arrVariablesForTemplate['arrPageVariables']['arrModels']['pce_model']                          = $this->pce_model;
            
            $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplate']   = 'flow_screens';
            $arrVariablesForTemplate['arrHeaderVariables']['objReview']           = $objReview;
            
            $arrVariablesForTemplate['arrSidebarVariables']['arrSidebarScreens']   = $arrSidebarScreens;
            $arrVariablesForTemplate['arrSidebarVariables']['strScreenName']       = $strScreenName;
            $arrVariablesForTemplate['arrSidebarVariables']['floPercentageComplete']   = $this->flowalgorithmslibrary->getPercentageCompletion();
            
            
            $this->load->view('template',$arrVariablesForTemplate);
        }
        
        public function flowChanged($strScreenName)
        {
            $this->load->library('FlowLibrary');
            $this->load->library('FlowAlgorithmsLibrary');
            $strCurrentPage = 'flowcontroller/flowchanged';
            $this->document->setTitleString('Flow Changed');
            $this->document->setH1String('Flow Changed');
            $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
            $arrVariablesForTemplate['arrPageVariables']['strScreenName']       = $strScreenName;
            
                        
            
            $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplate']    = 'flow_screens';
            $arrVariablesForTemplate['arrHeaderVariables']['objReview']             = $this->flowalgorithmslibrary->getReviewObject();
            $arrVariablesForTemplate['arrSidebarVariables']['arrSidebarScreens']    = $this->flowalgorithmslibrary->getSidebarScreens();
            $arrVariablesForTemplate['arrSidebarVariables']['strScreenName']        = '';
             $arrVariablesForTemplate['arrSidebarVariables']['floPercentageComplete']   = $this->flowalgorithmslibrary->getPercentageCompletion();
            $this->load->view('template',$arrVariablesForTemplate);
        }
        
        public function screensInvalid($strScreenName)
        {
            $this->load->library('FlowLibrary');
            $this->load->library('FlowAlgorithmsLibrary');
            $strCurrentPage = 'flowcontroller/screensinvalid';
            $this->document->setTitleString('Incomplete Information');
            $this->document->setH1String('Incomplete Information');
            $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
            $arrVariablesForTemplate['arrPageVariables']['strScreenName']       = $strScreenName;
            
                        
            
            $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplate']    = 'flow_screens';
            $arrVariablesForTemplate['arrHeaderVariables']['objReview']             = $this->flowalgorithmslibrary->getReviewObject();
            $arrVariablesForTemplate['arrSidebarVariables']['arrSidebarScreens']    = $this->flowalgorithmslibrary->getSidebarScreens();
            $arrVariablesForTemplate['arrSidebarVariables']['strScreenName']        = '';
             $arrVariablesForTemplate['arrSidebarVariables']['floPercentageComplete']   = $this->flowalgorithmslibrary->getPercentageCompletion();
            $this->load->view('template',$arrVariablesForTemplate);
        }
        
    
        public function session_view()
        {
            echo "DONE!\r\n <pre>";
            var_dump($this->session->all_userdata());
        }
        
        public function session_destroy()
        {
            echo "DONE!\r\n <pre>";
            $this->session->sess_destroy();
        }
        
        protected function throwErrorRedirect($strTitle = '',$strMessage ='')
        {
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle',$strTitle);
            $CI->session->set_flashdata('strMessage',$strMessage);
            redirect('/error/', 'refresh');
        }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */