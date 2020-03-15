<?php

class Review_Model extends CI_Model {
    
    protected   $_review_id           = -1;
    protected   $_data                = array(); 
    const       fieldname_table       = 'Field';
    const       table                 = 'ConsultationValues';
    protected   $_isloaded            = false;
    protected   $_otherdrugsdata       = array();
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->performReviewSetup();
    }
    
    public function performReviewSetup()
    {
        $this->db->select('CONCAT(DbTableName,"_",DbFieldName) AS ReviewField', false);
        $this->db->from(Review_Model::fieldname_table, false);
        $this->db->where('DbTableName IS NOT NULL', null, false);
        
        $objQuery = $this->db->get();
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result_array() as $arr)
            {
                $this->_data[$arr['ReviewField']] = null;
            }
            
            $this->_data['agcsystem_arrFlowHistory']       = null;
            $this->_data['agcsystem_arrScreenHistory']     = null;
            $this->_data['agcsystem_arrFlows']             = null;
            $this->_data['agcsystem_arrScreens']           = null;
            $this->_data['agcsystem_arrScreensToFlows']    = null;
            $this->_data['agcsystem_arrSidebarScreens']    = null;
            
            //die();
        }
    }
    
    public function __call($method, $args) {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = strtolower(substr($method,3));
                $data = null;
                if (array_key_exists($key,$this->_data))
                {
                    $data = $this->_data[$key];
                }
                return $data;
                break;
            case 'set' :
                $key = strtolower(substr($method,3));
                $this->_data[$key] = isset($args[0]) ? $args[0] : null;
                return $this;
                break;
            default :
                die("Fatal error: Call to undefined function " . $method);
        }
    }
    
    public function isLoaded()
    {
        return $this->_isloaded;
    }
    
    function clear()
    {
        $this->_data= array();
        $this->_review_id = -1;
        $this->_isloaded = false;
    }
    
    function getReviewIdForNhsNumber($intNHSNumber)
    {
        $this->db->select('ReviewID');
        
        $this->db->from(Review_Model::table);
        $this->db->where('FieldValue',      $intNHSNumber);
        $this->db->where('FieldName',       'InitialPatientDetails_NhsNumber');
        
        $this->db->limit(1);
        
        $this->db->order_by('Timestamp DESC');
        $objQuery = $this->db->get();
            
        if ($objQuery->num_rows() > 0)
        {
            return $objQuery->row()->ReviewID;
        }
        
        return -1;
    }
    
    function getReviewIdForPatientId($intPatientId)
    {
        $this->db->select('ReviewID');
        
        $this->db->from(Review_Model::table);
        $this->db->where('FieldValue',      $intPatientId);
        $this->db->where('FieldName',       'PatientDetails_PatientId');
        
        $this->db->limit(1);
        
        $this->db->order_by('Timestamp DESC');
        $objQuery = $this->db->get();
            
        if ($objQuery->num_rows() > 0)
        {
            
            $intReviewId = $objQuery->row()->ReviewID;
            //$arrScreenHistory = agcsystem_arrScreenHistory
                
            if (!$this->isReviewAStub($intReviewId))
            {
                return $intReviewId;
            }
            else
            {
                $this->deleteAllRowsForPatientID($intPatientId);
            }
        }
        
        return -1;
    }
    
    
    public function isReviewAStub($intReviewId)
    {
        $this->db->select('FieldValue');
        
        $this->db->from(Review_Model::table);
        $this->db->where('ReviewID',        $intReviewId);
        $this->db->where('FieldName',       'agcsystem_arrScreenHistory');

        $this->db->limit(1);    

        $objQuery = $this->db->get();

        if ($objQuery->num_rows() > 0)
        {
            $arrScreenHistory = unserialize($objQuery->row()->FieldValue);
            if (is_array($arrScreenHistory) && ((count($arrScreenHistory)-1) >= 0))
            {
                return false;
            }
            else
            {
                return true;
            }

        }
    }
    
    function getStartDateFromReviewId($intReviewId)
    {
        $this->db->select('Timestamp');
        
        $this->db->from(Review_Model::table);
        $this->db->where('ReviewId',      $intReviewId);
        $this->db->where('FieldName',       'agcsystem_ReviewStartTime');
        
        $this->db->limit(1);
        
        $this->db->order_by('Timestamp DESC');
        $objQuery = $this->db->get();
            
        if ($objQuery->num_rows() > 0)
        {
            return $objQuery->row()->Timestamp;
        }
        
        return -1;
    }
    
    function setReviewID($intReviewID)
    {
        $this->_review_id = $intReviewID;
        return $this;
    }
    
    function getReviewID()
    {
        return $this->_review_id;
    }
         
    function retrieve()
    {
        if ($this->_review_id > 0)
        {
            $this->db->select('FieldName,FieldValue');
            $this->db->from(Review_Model::table);
            $this->db->where('ReviewID',$this->_review_id);
            $this->db->where('FieldName IS NOT NULL', null, false);
            $objQuery = $this->db->get();
            
            if ($objQuery->num_rows() > 0)
            {
                foreach($objQuery->result_array() as $arr)
                {
                    $this->_data[$arr['FieldName']] = $arr['FieldValue'];
                }
                $this->_isloaded = true;
            }
            
        }
        return $this;
    }
    
    function retrieveWithTimestamps()
    {
        if ($this->_review_id > 0)
        {
            $this->db->select('ConsValID,FieldName,FieldValue,Timestamp');
            $this->db->from(Review_Model::table);
            $this->db->where('ReviewID',$this->_review_id);
            $this->db->where('FieldName IS NOT NULL', null, false);
            $objQuery = $this->db->get();
            
            if ($objQuery->num_rows() > 0)
            {
                foreach($objQuery->result_array() as $arr)
                {
                    $this->_data[$arr['FieldName']] = array('FieldValue'=>$arr['FieldValue']
                                                                ,'Timestamp'=>$arr['Timestamp']
                                                                ,'ConsValID'=>$arr['ConsValID']);
                }
                $this->_isloaded = true;
            }
            
        }
        return $this;
    }
    
    function retrieveOtherDrugs()
    {
        if ($this->_review_id > 0)
        {
            $this->_otherdrugsdata = array();
            $this->db->select('OtherDrugsName,OtherDrugsUsage,OtherDrugsType');
            $this->db->from('OtherDrugs');
            $this->db->where('OtherDrugsReviewID',$this->_review_id);
            
            $objQuery = $this->db->get();
            
            if ($objQuery->num_rows() > 0)
            {
                foreach($objQuery->result_array() as $arr)
                {
                    $this->_otherdrugsdata[] = $arr;
                }
                
            }
            
        }
        return $this;
    }
    
    function deleteOtherDrugs()
    {
        if ($this->_review_id > 0)
        {
            $this->db->delete('OtherDrugs', array('OtherDrugsReviewID' => $this->_review_id));
			//$this->db->delete('OtherDrugsPrevious', array('OtherDrugsReviewID' => $this->_review_id));
        }
    }
    
    function insertOtherDrugs($arrInput,$intPreviousConsultationId)
    {
	
		$this->db->delete('OtherDrugs', array('OtherDrugsReviewID' => $this->_review_id));
		$this->db->delete('OtherDrugsPrevious', array('OtherDrugsReviewID' => $intPreviousConsultationId));
		
        if (($this->_review_id > 0) && (count($arrInput)>0))
        {
            $arrNewDrugsData = array();
			$arrOldDrugsData = array();
            foreach($arrInput as $arrOtherDrug)
            {
                $arrNewDrugsData[] = array('OtherDrugsName'=>$arrOtherDrug['Name']
                                            ,'OtherDrugsUsage'=>$arrOtherDrug['Usage']
                                            ,'OtherDrugsType'=>$arrOtherDrug['Type']
                                            ,'OtherDrugsReviewID'=>$this->_review_id);
				if($intPreviousConsultationId != null)
				{							
				$arrOldDrugsData[] = array('OtherDrugsName'=>$arrOtherDrug['Name']
                                            ,'OtherDrugsUsage'=>$arrOtherDrug['Usage']
                                            ,'OtherDrugsType'=>$arrOtherDrug['Type']
                                            ,'OtherDrugsReviewID'=>$intPreviousConsultationId);
				}
            }
            $this->db->insert_batch('OtherDrugs', $arrNewDrugsData); 
			
			if($intPreviousConsultationId != null){
				$this->db->insert_batch('OtherDrugsPrevious', $arrOldDrugsData); 
			}
        }
    }
    
    function create($strType,$objUser)
    {
        $this->performReviewSetup();
        
        $arrFields = array();
        $this->db->query('INSERT INTO '.Review_Model::table.' (ReviewID) SELECT IFNULL(MAX(ReviewID)+1,1) FROM '.Review_Model::table);
        $intInsertID = $this->db->insert_id();
        $this->db->select('ReviewID', false);
        $this->db->from(Review_Model::table, false);
        $this->db->where('ConsValID',$intInsertID);
        $objInnerQuery = $this->db->get();
        if ($objInnerQuery->num_rows() > 0)
        {
            $objReviewID = $objInnerQuery->row();
            $this->_review_id = $objReviewID->ReviewID;
            
            $this->_data['InitialPatientDetails_Username']      = $objUser->FirstName." ".$objUser->LastName;
            $this->_data['agcsystem_APIUserId']                 = $objUser->UserId;
            $this->_data['agcsystem_ReviewStartTime']           = date('Y-m-d\TH:i:s');
            $this->_data['PatientDetails_ConsultationNumber']   = count($this->session->userdata('objSetup_ApiConsultations'))+1;
            $this->_data['agcsystem_PreviousConsultationId']    = -1;
            $this->_data['agcsystem_PreviousExacerbationId']    = -1;
            $this->_data['agcsystem_ReviewStatus']              = "Incomplete";
                    
            if (strlen($strType) > 0)
            {
                $this->_data['AssessmentDetails_AssessmentType'] = $strType;
            }
            
            foreach ($this->_data as $key=>$val)
            {
                $arrFields[] = array('ReviewID'=>$this->_review_id,'FieldName'=>$key,'FieldValue'=>null);
            }
            
            $this->db->insert_batch(Review_Model::table,$arrFields);
            
            
            /*
            $CI =& get_instance();
            
            if ($CI->input->cookie('agc_APIVars_token') && (strlen($CI->input->cookie('agc_APIVars_token')) >0))
            {
                $CI->load->model('apiuser_model','apiuser');
                $CI->load->model('apipatient_model','apipatient');
                
            }*/
        }
        else
        {
            die();
        }
        return $this;
    }
    
    function doSetupAPISession($objApiPatient)
    {
        $CI =& get_instance();
        
        $this->_data['agcsystem_APIToken']                  = $CI->input->cookie('agc_APIVars_token');
        
        
        $this->_data['InitialPatientDetails_FirstName']     = $objApiPatient->FirstName;
        $this->_data['InitialPatientDetails_Surname']       = $objApiPatient->LastName;
        $this->_data['InitialPatientDetails_NhsNumber']     = $objApiPatient->NhsNumber;
        $this->_data['InitialPatientDetails_Sex']           = substr($objApiPatient->Gender, 0, 1);
        $this->_data['InitialPatientDetails_DateOfBirth']   = date('d/m/Y',$objApiPatient->DateOfBirth);
        $this->_data['PatientDetails_PatientDetailsTitle']  = $objApiPatient->Title;
        $this->_data['PatientDetails_PatientId']            = $objApiPatient->PatientId;
        $this->_data['ClinicalExam_HeightM']                = $objApiPatient->Height;
        $this->_data['ClinicalExam_WeightKg']               = $objApiPatient->Weight;
        
        // age
            $tz  = new DateTimeZone('Europe/London');
 
            $this->_data['InitialPatientDetails_Age'] = DateTime::createFromFormat('d/m/Y', $this->_data['InitialPatientDetails_DateOfBirth'], $tz)
                                                            ->diff(new DateTime('now', $tz))
                                                            ->y;
        
        $this->store();
        
        return $this;
    }
	
	function doSetupPatientDrugsFromAPI()
	{
		$CI =& get_instance();
		//drug_type,
		//echo"HERE1";
		$CI->load->model('apigpsystemdrugs_model','apigpsystemdrugs');
		//echo"HERE1.5";
		$arrGpSystemDrugs = $CI->apigpsystemdrugs->getGpSystemDrugs();
		//echo"HERE2";
		foreach($arrGpSystemDrugs as $key => $value)
		{
			$this->_data[$key] = $value;
			//var_dump($key);
			//var_dump($value);
		}
		$this->store();
		//echo"HERE3";
		//die();
		return $this;
	}
    
    function doImportPreviousConsultation($objApiPatient)
    {
        $CI =& get_instance();
        $CI->load->model('apiconsultation_model','apiconsultation');
        $CI->load->model('previousconsultationvalues_model','pcv_model');
        $CI->load->model('previousconsultationexacerbation_model','pce_model');
        
        //var_dump($CI->apiconsultation->getPreviousCompletedConsultationsFor($objApiPatient->PatientId));
        
        $arrPreviousConsultations = $CI->apiconsultation->getPreviousCompletedConsultationsFor($objApiPatient->PatientId);
        
        
        
        $intPreviousConsultationId = $arrPreviousConsultations['Non-Exacerbation'];
        $mixPreviousExacerbationId = false;
        if (array_key_exists('Exacerbation',$arrPreviousConsultations))
        {
            $mixPreviousExacerbationId = $arrPreviousConsultations['Exacerbation'];
        }
        
        
        
        
        //ImportOtherDrugs from previous consultations
        $arrImportedDrugs = array();
        $arrDrugs = $CI->pcv_model->setConsultationID($intPreviousConsultationId)->retrieveOtherDrugsArrayFromSerializedData();
        foreach ($arrDrugs as $arrDrug)
        {
            $arrDrug['OtherDrugsReviewID'] = $this->_review_id;
            $arrImportedDrugs[] = $arrDrug;
        }
        $this->insertOtherDrugs($arrImportedDrugs,$intPreviousConsultationId);
        
        
        
        
        
        $intCurrentConsultationId = $this->_review_id;
        
        $this->_data['agcsystem_PreviousConsultationId'] = $intPreviousConsultationId;
        
        
        if ($mixPreviousExacerbationId)
        {
            $this->_data['agcsystem_PreviousExacerbationId'] = $mixPreviousExacerbationId;
        }
        
        
        
        $this->store();
        
        
        
        if ($intPreviousConsultationId)
        {
            // previous cons has been put into DB - previous table.
            $this->db->query('CALL UpdateConsultationValuesWithPrevious('.$intCurrentConsultationId.','.$intPreviousConsultationId.');');
            
        }
        
        
        
        return $this;
    }
    
    
    function store()
    {
        $arrFields = array();
        foreach ($this->_data as $key=>$val)
        {
            $arrFields[] = array('ReviewID'=>$this->_review_id,'FieldName'=>$key,'FieldValue'=>$val);
        }
        $this->db->where('ReviewID',$this->_review_id);
        $this->db->where('FieldName IS NOT NULL', null, false);
        $this->db->delete(Review_Model::table);
        $this->db->insert_batch(Review_Model::table,$arrFields);
        return $this;
    }
    
    
  
    function updateValues($arrInputData, $booSelective)
    {
        
        if (array_key_exists('updated_fields', $arrInputData) && $booSelective)
        {
            if ($arrInputData['updated_fields'] != '')
            {    
                $arrFields = explode('|',$arrInputData['updated_fields']);
                $arrSystemFields = array('agcsystem_arrFlowHistory','agcsystem_arrScreenHistory','agcsystem_arrFlows','agcsystem_arrScreens','agcsystem_arrScreensToFlows','agcsystem_arrSidebarScreens');
                foreach ($arrSystemFields as $strTheField)
                {
                    if (!array_search($strTheField,$arrFields))
                    {
                        $arrFields[] = $strTheField;
                    }
                }
                foreach ($arrFields as $key)
                {

                    if (array_key_exists($key,$arrInputData))
                    {
                        //echo "loadNewData setting ".$key." to ".$arrInputData[$key]." \r\n";
                        $this->_data[$key] = $arrInputData[$key];
                    }
                    else 
                    {
                        $this->_data[$key] = '';
                    }
                }
                
            }
        }
        else
        {
            foreach ($this->_data as $key=>$val)
            {

                if (array_key_exists($key,$arrInputData))
                {
                    //echo "loadNewData setting ".$key." to ".$arrInputData[$key]." \r\n";
                    $this->_data[$key] = $arrInputData[$key];
                }
            }
        }
        return $this;
            
    }
    
    
   
    public function getAsObject()
    {
        $objReviewData = new stdClass();
        
        foreach ($this->_data as $key => $val)
        {
            if ($key != '')
            {
                $objReviewData->{$key} = $val;
            }
            
        }
        
        $objReviewData->intReviewID = $this->_review_id;
        
        $this->retrieveOtherDrugs();
        $objReviewData->arrOtherDrugs = $this->_otherdrugsdata;
        
        return $objReviewData;
    }
    
    public function getAsArrayForApiPush()
    {
        $arrReviewData = array();
        
        foreach ($this->_data as $key => $val)
        {
            if ($key != '')
            {
                $arrReviewData['data'][$key] = $val;
            }
            
        }
        
        $arrReviewData['id'] = $this->_review_id;
        
        $this->retrieveOtherDrugs();
        $arrReviewData['otherdrugs'] = $this->_otherdrugsdata;
        
        return $arrReviewData;
    }
    
    
    public function deleteReview($intReviewId)
    {
        
        
        
        $this->db->where('ReviewID',$intReviewId);
        $this->db->delete(Review_Model::table);
    }
    
    public function deleteAllRowsForPatientID($intPatientId)
    {
        $this->db->query('CALL DeleteConsultationsByPatientId('.$intPatientId.');');
    }
}
