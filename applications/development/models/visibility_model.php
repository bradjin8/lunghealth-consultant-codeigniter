<?php

class Visibility_model extends MY_Model
{
    
    protected   $_queries           = array();
    const       db_table            = 'Visibility';
    
    public function setID($intId)
    {
        $this->_data[Visibility_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Visibility_model::db_table.'ID'];
    }
    
    public function clear()
    {
        parent::clear();
        $this->_queries = array();
    }
        
    public function retrieve()
    {
        return $this->readFromDatabase(Visibility_model::db_table, array(Visibility_model::db_table."ID","LayoutID","FieldID","EvaluationType","Function","Operator","Criteria"));
    }
        
    public function retrieveWithFieldInfo()
    {
        $this->db->select(Visibility_model::db_table.'.*, Question.QuestionGroupID');
        $this->db->from(Visibility_model::db_table);
        $this->db->join('Field',Visibility_model::db_table.'.FieldID = Field.FieldID','left');
        $this->db->join('Question','Field.QuestionID = Question.QuestionID','left');
        $this->db->where(Visibility_model::db_table.'ID',$this->_data[Visibility_model::db_table.'ID']);
        $this->db->limit(1);
        $objQuery = $this->db->get();
        if ($objQuery->num_rows() > 0)
        {

            $arrUser = $objQuery->row_array();
            foreach($arrUser as $key=>$val)
            {
                $this->_data[$key] = $val;
            }
        }
        return $this;
    }
    
    public function getAllFromFieldId($intFId)
    {
        $arrVs = array();
        $this->db->select(Visibility_model::db_table.'ID');
        $this->db->from(Visibility_model::db_table);
        $this->db->where('FieldID',$intFId);
        $objQuery = $this->db->get();
        $this->_queries[] = $this->db->last_query();
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrVs[] = $objResult->{Visibility_model::db_table.'ID'};
            }
           
        }
        return $arrVs;
    }    
    
    public function getAllFromLayoutId($intLId)
    {
        $arrVs = array();
        $this->db->select(Visibility_model::db_table.'ID');
        $this->db->from(Visibility_model::db_table);
        $this->db->where('LayoutID',$intLId);
        $objQuery = $this->db->get();
        $this->_queries[] = $this->db->last_query();
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrVs[] = $objResult->{Visibility_model::db_table.'ID'};
            }
           
        }
        return $arrVs;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->queries = $this->_queries;
        return $objObject;
    }
    
}