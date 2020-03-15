<?php

class Question_model extends MY_Model
{
    
   
    const       db_table            = 'Question';
    
    public function setID($intId)
    {
        $this->_data[Question_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Question_model::db_table.'ID'];
    }
    
    public function clear()
    {
        parent::clear();
        
    }
    
    public function retrieve()
    {
        return $this->readFromDatabase(Question_model::db_table, array(Question_model::db_table."ID",Question_model::db_table."DisplayText",Question_model::db_table."Name",Question_model::db_table."CssClass","ReqNumber","QuestionGroupID"));
    }
    
    
    public function getAllFromQuestionGroupId($intQGId)
    {
        $arrQs = array();
        $this->db->select(Question_model::db_table.'ID');
        $this->db->from(Question_model::db_table);
        $this->db->where('QuestionGroupID',$intQGId);
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrQs[] = $objResult->{Question_model::db_table.'ID'};
            }
           
        }
        return $arrQs;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        
        return $objObject;
    }
    
}