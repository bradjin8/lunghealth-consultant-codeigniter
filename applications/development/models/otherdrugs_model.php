<?php

class Otherdrugs_model extends MY_Model
{
    
    protected   $_questiongroups    = array();
    
    const       db_table            = 'OtherDrugs';
    
    
    
    public function setID($intId)
    {
        $this->_data[Questiongrouppage_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Questiongrouppage_model::db_table.'ID'];
    }
    
}