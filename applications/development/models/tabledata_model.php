<?php

class Tabledata_model extends MY_Model
{
    
    const       db_table            = 'Table';
    
    public function setID($intId)
    {
        $this->_data[Tabledata_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Tabledata_model::db_table.'ID'];
    }
    
   
    
    public function retrieve()
    {
        return $this->readFromDatabase(Tabledata_model::db_table, array(Tabledata_model::db_table."ID", Tabledata_model::db_table."Type", Tabledata_model::db_table."CssClass",Tabledata_model::db_table."Contents"));
    }
    
    
}