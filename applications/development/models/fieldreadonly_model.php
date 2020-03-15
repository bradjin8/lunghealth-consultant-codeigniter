<?php

class Fieldreadonly_model extends MY_Model
{
    
    const       db_table            = 'Field';
    
    public function setID($intId)
    {
        $this->_data[Field_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Field_model::db_table.'ID'];
    }
    
    public function clear()
    {
        parent::clear();
        $this->_visibilities = array();
    }
    
    public function retrieve()
    {   
        $this->readFromDatabase(Field_model::db_table, array(Field_model::db_table."ID","QuestionID","DbTableName","DbFieldName","DbDataType","ValidationString","ValidationMode","LabelString","ValidationText","ControlType","Required","ControlLabelString"));
        $this->_data['strFieldNameForForm'] = $this->_data['DbTableName']."_".$this->_data['DbFieldName'];
        
        return $this;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        
        return $objObject;
    }
}