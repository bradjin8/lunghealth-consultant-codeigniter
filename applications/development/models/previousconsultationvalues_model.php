<?php

class Previousconsultationvalues_Model extends CI_Model {
    
    const       table                 = 'ConsultationValuesPrevious';
    const       fieldname_table       = 'Field';
    
    private     $_data                = array();
    private     $_isloaded            = false;
    private     $_review_id           = -1;
    protected   $_otherdrugsdata       = array();
   
    
    public function performReviewSetup($intConsultationValue)
    {
        $this->db->select('CONCAT(DbTableName,"_",DbFieldName) AS ReviewField', false);
        $this->db->from(self::fieldname_table, false);
        $this->db->where('DbTableName IS NOT NULL', null, false);
        
        $objQuery = $this->db->get();
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result_array() as $arr)
            {
                $this->_data[$arr['ReviewField']] = array('ReviewID'=>$intConsultationValue
                                    ,'FieldName'=>$arr['ReviewField']
                                    ,'FieldValue'=>''
                                    ,'Timestamp'=>date('Y-m-d H:i:s'));
                
            }
            //die();
        }
    }
    
    
    
    public function deleteAllRowsForPatientID($intPatientId)
    {
        $this->db->query('CALL DeletePreviousConsultationsByPatientId('.$intPatientId.');');
    }
    
    public function setConsultationID($intReviewID)
    {
        $this->_review_id = (int) $intReviewID;
        return $this;
    }
    
    public function store()
    {
        if (count($this->_data) > 0)
        {
            $this->db->insert_batch(self::table,$this->_data);
            
            
            
        }
        return $this;
    }
    
    public function addValue($objValue)
    {
        $this->_data[$objValue->FieldName] = array('ReviewID'=>$objValue->ConsultationId
                                    ,'FieldName'=>$objValue->FieldName
                                    ,'FieldValue'=>$objValue->FieldValue
                                    ,'Timestamp'=>date('Y-m-d H:i:s',$objValue->Timestamp));
    }
    
    
    function deleteOtherDrugs()
    {
        if ($this->_review_id > 0)
        {
            //$this->db->delete('OtherDrugs', array('OtherDrugsReviewID' => $this->_review_id));
			$this->db->delete('OtherDrugsPrevious', array('OtherDrugsReviewID' => $this->_review_id));
        }
    }
    
    function addOtherDrugs($strInput)
    {
        if (($this->_review_id > 0) && (strlen($strInput)>0))
        {
            $arrImportedDrugs = unserialize($strInput);
            $arrNewDrugsData = array();
            foreach($arrImportedDrugs as $arrOtherDrug)
            {
                $arrNewDrugsData[] = array('OtherDrugsName'=>$arrOtherDrug['OtherDrugsName']
                                            ,'OtherDrugsUsage'=>$arrOtherDrug['OtherDrugsUsage']
                                            ,'OtherDrugsType'=>$arrOtherDrug['OtherDrugsType']
                                            ,'OtherDrugsReviewID'=>$this->_review_id);
            }
			
			//var_dump($arrNewDrugsData);die();
            //$this->db->insert_batch('OtherDrugs', $arrNewDrugsData); 
			$this->db->insert_batch('OtherDrugsPrevious', $arrNewDrugsData); 
        }
    }
    
    function retrieveOtherDrugsArrayFromSerializedData()
    {
        $arrReturn = array();
        if ($this->_review_id > 0)
        {
            
            
            $this->db->select('FieldValue');
            $this->db->from(self::table, false);
            $this->db->where('ReviewID',$this->_review_id);
            $this->db->where('FieldName','arrOtherDrugs');
            
            $objQuery = $this->db->get();
            
            if ($objQuery->num_rows() > 0)
            {
                
                $objResult = $objQuery->result();
                $arrTempReturn = array();
                if (property_exists($objResult[0], 'FieldValue'))
                {
                    $arrTempReturn = unserialize($objResult[0]->FieldValue);
                }
                foreach ($arrTempReturn as $arrOtherDrug)
                {
                    $arrReturn[] = array(
                        'Name' => $arrOtherDrug['OtherDrugsName'],
                        'Type' => $arrOtherDrug['OtherDrugsType'],
                        'Usage' => $arrOtherDrug['OtherDrugsUsage']
                    );
                }
                
                
            }
        }
        
        return $arrReturn;
    }
    
    function retrieveOtherDrugs()
    {
        if ($this->_review_id > 0)
        {
            $this->_otherdrugsdata = array();
            $this->db->select('OtherDrugsName,OtherDrugsUsage,OtherDrugsType');
            $this->db->from('OtherDrugsPrevious');
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
    
    public function isLoaded()
    {
        return $this->_isloaded;
    }
    
    
    
    
    public function retrieve()
    {
	
	
		//var_dump($this->_review_id);die();
		
        if ($this->_review_id > 0)
        {
            $this->db->select('FieldName,FieldValue');
            $this->db->from(self::table);
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
        
		/*
		var_dump($objReviewData);
		echo "<br>";
		var_dump($this->_otherdrugsdata);
		echo "<br>";
		var_dump($this->_review_id);
		
		die();
		*/
		
        return $objReviewData;
    }
    
}
