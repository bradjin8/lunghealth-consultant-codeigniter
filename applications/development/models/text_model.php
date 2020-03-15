<?php

class Text_model extends MY_Model
{
    
    const       db_table            = 'Text';
    
    public function setID($intId)
    {
        $this->_data[Text_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Text_model::db_table.'ID'];
    }
    
   
    
    public function retrieve()
    {
        return $this->readFromDatabase(Text_model::db_table, array(Text_model::db_table."ID", Text_model::db_table, Text_model::db_table."Type", Text_model::db_table."CssCLass"));
    }
    
    
}