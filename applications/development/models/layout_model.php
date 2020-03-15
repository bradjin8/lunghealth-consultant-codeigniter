<?php

class Layout_model extends MY_Model
{
    
    protected   $_visibilities      = array();
    const       db_table            = 'Layout';
    
    public function setID($intId)
    {
        $this->_data[Layout_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Layout_model::db_table.'ID'];
    }
    
    public function clear()
    {
        parent::clear();
        $this->_visibilities = array();
    }
    
    public function retrieve()
    {
        return $this->readFromDatabase(Layout_model::db_table, array(Layout_model::db_table."ID","QuestionGroupID","ContentID","ContentType","Row","SpanOrder","SpanWidth","LayoutCssClass","VisabilityLogic","SpanOffset"));
    }
    
    public function retrieveAndGetVisibilities()
    {
        $this->retrieve();
        $this->load->model('Visibility_model', 'v_model');
        
        foreach($this->v_model->getAllFromLayoutId($this->getID()) as $intV)
        {
            $this->_visibilities[] = $this->v_model->setID($intV)->retrieveWithFieldInfo()->getAsObject();
            $this->v_model->clear();
        }
        
        return $this;
    }
    
    public function getAllFromQuestionGroupId($intQGId)
    {
        $arrLs = array();
        $this->db->select(Layout_model::db_table.'ID');
        $this->db->from(Layout_model::db_table);
        $this->db->where('QuestionGroupID',$intQGId);
        $this->db->order_by('Row ASC, SpanOrder ASC');
        
        $objQuery = $this->db->get();
        
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result() as $objResult)
            {
                $arrLs[] = $objResult->{Layout_model::db_table.'ID'};
            }
           
        }
        return $arrLs;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->visibilities = $this->_visibilities;
        return $objObject;
    }
    
}