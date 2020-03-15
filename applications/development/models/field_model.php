<?php

class Field_model extends MY_Model
{
    protected   $_visibilities      = array();
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
        $this->readFromDatabase(Field_model::db_table, array(Field_model::db_table."ID","QuestionID","DbTableName","DbFieldName","DbDataType","ValidationString","ValidationMode","LabelString","ValidationText","ControlType","Required","ControlLabelString","DontRemoveContents"));
        $this->_data['strFieldNameForForm'] = $this->_data['DbTableName']."_".$this->_data['DbFieldName'];
        return $this;
    }
    
    public function retrieveFromTableAndFieldNames($strTableName,$strFieldName)
    {
        $strTable = Field_model::db_table;
        $arrFieldsRequired = array(Field_model::db_table."ID","QuestionID","DbTableName","DbFieldName","DbDataType","ValidationString","ValidationMode","LabelString","ValidationText","ControlType","Required","ControlLabelString","DontRemoveContents");
        if ((strlen($strTableName) > 0)
                && (strlen($strFieldName) > 0))
        {
            
           
            $this->db->select(implode(',', $arrFieldsRequired));
            $this->db->from($strTable);
            $this->db->where("DbTableName",$strTableName);
            $this->db->where("DbFieldName",$strFieldName);
            
            
            $this->db->limit(1);
            
           
            
            $objQuery = $this->db->get();
            if ($objQuery->num_rows() > 0)
            {
                $this->_isloaded = true;
                $arrField = $objQuery->row_array();
                foreach($arrField as $key=>$val)
                {
                    $this->_data[$key] = $val;
                }
                $this->_data['strFieldNameForForm'] = $this->_data['DbTableName']."_".$this->_data['DbFieldName'];
            }
            
        }
        return $this;
    
    }
    
    public function retrieveAndGetVisibilities()
    {
        $this->retrieve();
        $this->load->model('Visibility_model', 'vb_model');
        
        foreach($this->vb_model->getAllFromFieldId($this->getID()) as $intV)
        {
            $this->_visibilities[] = $this->vb_model->setID($intV)->retrieve()->getAsObject();
            $this->vb_model->clear();
        }
        
        return $this;
    }
    
    public function getAllFromQuestionId($intQId)
    {
        $arrFs = array();
        $this->db->select(Field_model::db_table.'ID');
        $this->db->from(Field_model::db_table);
        $this->db->where('QuestionID',$intQId);
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrFs[] = $objResult->{Field_model::db_table.'ID'};
            }
           
        }
        return $arrFs;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->visibilities = $this->_visibilities;
        return $objObject;
    }
}