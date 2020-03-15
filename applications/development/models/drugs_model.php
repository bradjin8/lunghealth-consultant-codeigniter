<?php

class Drugs_model extends CI_Model
{
    const       db_table            = 'Drugs';
    
    
    
    public function getAllFromTypeID($intTID)
    {
        $arrDs = array();
        $this->db->select('drugid, name, bdp_equivalent');
        $this->db->from(Drugs_model::db_table);
        $this->db->where('drugtype',$intTID);
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrDs[] = $objResult;
            }
           
        }
        return $arrDs;
    }
    
    /*public function getValuesForDropDown($intTID)
    {
        $arrOutput = array(''=>array('label'=>'','title'=>''));
        $arrDs = $this->getAllFromTypeID($intTID);
        foreach ($arrDs as $objDrug)
        {
            $arrOutput[$objDrug->drugid] = array('label'=>$objDrug->name,'title'=>$objDrug->bdp_equivalent);
        }
        return $arrOutput;
    }
    
    public function getLabelFor($strInput)
    {
        $strReturn = "";
        
        $this->db->select('name');
        $this->db->from(Drugs_model::db_table);
        $this->db->where('drugid',$strInput);
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            $objResult = $objQuery->result();
            
           
            
            $strReturn = $objResult[0]->name;
           
        }
        
        return $strReturn;
    }*/
}