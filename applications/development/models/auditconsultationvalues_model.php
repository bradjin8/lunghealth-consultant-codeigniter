<?php

class Auditconsultationvalues_Model extends CI_Model {
    
    const       table                 = 'ConsultationValuesAudit';
    const       fieldname_table       = 'Field';
    
    private     $_data                = array();
    private     $_isloaded            = false;
    private     $_review_id           = -1;
    protected   $_otherdrugsdata       = array();
   
    
    function clear()
    {
        $this->_data= array();
        $this->_review_id = -1;
        $this->_isloaded = false;
    }
    
    public function performReviewSetup($intConsultationValue)
    {
        $this->deleteAllAuditRowsFor($intConsultationValue);
        
        $this->db->select('CONCAT(DbTableName,"_",DbFieldName) AS ReviewField', false);
        $this->db->from(Auditconsultationvalues_Model::fieldname_table, false);
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
            $this->_data['agcsystem_READONLY'] = array('ReviewID'=>$intConsultationValue
                                    ,'FieldName'=>'agcsystem_READONLY'
                                    ,'FieldValue'=>'TRUE'
                                    ,'Timestamp'=>date('Y-m-d H:i:s'));
            //die();
        }
    }
    
    public function deleteAllAuditRowsFor($intAuditId)
    {
        $this->db->delete(Auditconsultationvalues_Model::table, array('ReviewID' => $intAuditId)); 
        $this->deleteOtherDrugs($intAuditId);
    }
    
    /*
    public function deleteAllRowsForPatientID($intPatientId)
    {
        $this->db->query('CALL DeletePreviousConsultationsByPatientId('.$intPatientId.');');
    }*/
    
    public function setAuditID($intReviewID)
    {
        $this->_review_id = $intReviewID;
        return $this;
    }
    
    public function store()
    {
        
        
        if (count($this->_data) > 0)
        {
            $this->db->insert_batch(Auditconsultationvalues_Model::table,$this->_data);
         
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
    
    
    function deleteOtherDrugs($intAuditId)
    {
        $this->db->delete('OtherDrugsAudit', array('OtherDrugsReviewID' => $intAuditId));
    }
    
    function addOtherDrugs($strInput)
    {
        
        if (($this->_review_id > 0) && (strlen($strInput)>0))
        {
            
            $arrImportedDrugs = unserialize($strInput);
            if (count($arrImportedDrugs ) > 0)
            {
                $arrNewDrugsData = array();
                foreach($arrImportedDrugs as $arrOtherDrug)
                {
                    $arrNewDrugsData[] = array('OtherDrugsName'=>$arrOtherDrug['OtherDrugsName']
                                                ,'OtherDrugsUsage'=>$arrOtherDrug['OtherDrugsUsage']
                                                ,'OtherDrugsType'=>$arrOtherDrug['OtherDrugsType']
                                                ,'OtherDrugsReviewID'=>$this->_review_id);
                }
                $this->db->insert_batch('OtherDrugsAudit', $arrNewDrugsData); 
            }
        }
    }
    
    function retrieveOtherDrugs()
    {
        if ($this->_review_id > 0)
        {
            $this->_otherdrugsdata = array();
            $this->db->select('OtherDrugsName,OtherDrugsUsage,OtherDrugsType');
            $this->db->from('OtherDrugsAudit');
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
        if ($this->_review_id > 0)
        {
            $this->db->select('FieldName,FieldValue');
            $this->db->from(Auditconsultationvalues_Model::table);
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
        
        $objReviewData->arrOtherDrugs = array();
        
        $this->retrieveOtherDrugs();
        $objReviewData->arrOtherDrugs = $this->_otherdrugsdata;
        
        return $objReviewData;
    }
    
}
