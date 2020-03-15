<?php

class Questiongroup_model extends MY_Model
{
    
    protected   $_questions         = array();
    protected   $_fields            = array();
    protected   $_fieldsreadonly    = array();
    protected   $_layouts           = array();
    protected   $_texts             = array();
    protected   $_tables            = array();

    const       db_table            = 'QuestionGroup';
    
    public function setID($intId)
    {
        $this->_data[Questiongroup_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Questiongroup_model::db_table.'ID'];
    }
    
    public function clear()
    {
        parent::clear();
        $this->_questions = array();
        $this->_fields   = array();
        $this->_layouts = array();
        $this->_texts = array();
        $this->_tables = array();
    }
    
    public function retrieve()
    {
        return $this->readFromDatabase(Questiongroup_model::db_table
                                        , array(Questiongroup_model::db_table."ID",Questiongroup_model::db_table."Name",Questiongroup_model::db_table."CssClass",Questiongroup_model::db_table."PageOrder",Questiongroup_model::db_table."DisplayText")
                                        ,Questiongroup_model::db_table."PageOrder ASC, ".Questiongroup_model::db_table."ID ASC");
    }
    
    public function retrieveAndGetQuestions()
    {
        $this->retrieve();
        
        $this->load->model('Question_model', 'q_model');
        $this->load->model('Field_model', 'f_model');
        $this->load->model('Fieldreadonly_model', 'fro_model');
        $this->load->model('Text_model', 't_model');
        $this->load->model('Tabledata_model', 'td_model');
        
        $this->load->model('Layout_model', 'l_model');
        foreach($this->l_model->getAllFromQuestionGroupId($this->getID()) as $intL)
        {
            $objLayout = $this->l_model->setID($intL)->retrieveAndGetVisibilities()->getAsObject();
            $this->_layouts[$objLayout->Row][$objLayout->SpanOrder] = $objLayout;
            
            
            foreach ($objLayout->visibilities as $objVisibility)
            {
                $this->_fields[$objVisibility->FieldID] = $this->f_model->setID($objVisibility->FieldID)->retrieve()->getAsObject();
                $this->f_model->clear();
            }
            
            
            switch ($objLayout->ContentType)
            {
                case "Text":
                    $this->_texts[$objLayout->ContentID] = $this->t_model->setID($objLayout->ContentID)->retrieve()->getAsObject();
                    $this->t_model->clear();
                    break;
                case "Tabledata":
                    $this->_tables[$objLayout->ContentID] = $this->td_model->setID($objLayout->ContentID)->retrieve()->getAsObject();
                    $this->td_model->clear();
                    break;
                case "Field":
                    if (!array_key_exists($objLayout->ContentID, $this->_fields))
                    {
                    $this->_fields[$objLayout->ContentID] = $this->f_model->setID($objLayout->ContentID)->retrieve()->getAsObject();
                    $this->f_model->clear();
                    }
                    break;
                case "Question":
                    $this->_questions[$objLayout->ContentID] = $this->q_model->setID($objLayout->ContentID)->retrieve()->getAsObject();
                    $this->q_model->clear();
                    break;
                case "Field-readonly":
                    
                    
                    $this->_fieldsreadonly[$objLayout->ContentID] = $this->fro_model->setID($objLayout->ContentID)->retrieve()->getAsObject();
                    $this->fro_model->clear();
                    break;
            }
            
            $this->l_model->clear();
        }
        
        
        
        
        
        
        return $this;
    }
    
    public function getAllFromScreenId($intScreenId)
    {
        $arrQGs = array();
        $this->db->select(Questiongroup_model::db_table.'ID');
        $this->db->from(Questiongroup_model::db_table);
        $this->db->where('ScreenID',$intScreenId);
        $this->db->order_by(Questiongroup_model::db_table."PageOrder ASC, ".Questiongroup_model::db_table."ID ASC");
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrQGs[] = $objResult->{Questiongroup_model::db_table.'ID'};
            }
           
        }
        return $arrQGs;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->questions       = $this->_questions;
        $objObject->fields          = $this->_fields;
        $objObject->fieldsreadonly  = $this->_fieldsreadonly;
        $objObject->layouts         = $this->_layouts;
        $objObject->texts           = $this->_texts;
        $objObject->tables          = $this->_tables;
        return $objObject;
    }
    
}