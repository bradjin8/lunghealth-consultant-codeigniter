<?php

class Screen_model extends MY_Model
{
    
    const       db_table            = 'Screen';
    


    public function setID($intId)
    {
        $this->_data[Screen_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Screen_model::db_table.'ID'];
    }
    
    
    public function setScreenName($strName)
    {
        $this->_data[Screen_model::db_table."Name"] = $strName;
        return $this;
    }
    
    public function getScreenName()
    {
        return $this->_data[Screen_model::db_table."Name"];
    }
    
    public function retrieve()
    {
        $strTable =Screen_model::db_table;
        $arrFields = array(Screen_model::db_table."ID",Screen_model::db_table."Name",Screen_model::db_table."DisplayText","FieldID","FieldIDPre");
        
        if (array_key_exists($strTable.'ID',$this->_data) || array_key_exists($strTable.'Name', $this->_data))
        {
            
            if (count($arrFields) > 0)
            {
                $this->db->select(implode(',', $arrFields));

            }
            else
            {
                $this->db->select('*');
            }
            $this->db->from($strTable);
            
            if (array_key_exists($strTable.'ID', $this->_data))
            {
                $this->db->where($strTable.'ID',$this->_data[$strTable.'ID']);
            }
            
            if (array_key_exists($strTable.'Name', $this->_data))
            {
                $this->db->where($strTable.'Name',$this->_data[$strTable.'Name']);
            }
            
            $this->db->limit(1);
            
            
            $objQuery = $this->db->get();
            if ($objQuery->num_rows() > 0)
            {
                $this->_isloaded = true;
                $arrScreen = $objQuery->row_array();
                foreach($arrScreen as $key=>$val)
                {
                    $this->_data[$key] = $val;
                }
            }
            
        }
        return $this;
    }
}