<?php

class Questiongrouppage_model extends MY_Model
{
    
    protected   $_questiongroups    = array();
    
    const       db_table            = 'Screen';
    
    
    
    public function setID($intId)
    {
        $this->_data[Questiongrouppage_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Questiongrouppage_model::db_table.'ID'];
    }

    
    
    
    public function setScreenName($strName)
    {
        $this->_data[Questiongrouppage_model::db_table."Name"] = $strName;
        return $this;
    }
    
    public function getScreenName()
    {
        return $this->_data[Questiongrouppage_model::db_table.'Name'];
    }
    
    public function retrieve()
    {
        $strTable =Questiongrouppage_model::db_table;
        $arrFields = array(Questiongrouppage_model::db_table."ID",Questiongrouppage_model::db_table."Name",Questiongrouppage_model::db_table."DisplayText");
    
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
                $arrQgp = $objQuery->row_array();
                foreach($arrQgp as $key=>$val)
                {
                    $this->_data[$key] = $val;
                }
            }
            
        }
        return $this;
    }
    
    public function retrieveAndGetQuestionGroups()
    {
        $this->retrieve();
        if ($this->isLoaded())
        {
            $this->load->model('questiongroup_model','qg_model');
            foreach ($this->qg_model->getAllFromScreenId($this->getID()) as $intQG)
            {
                $this->_questiongroups[$intQG] = $this->qg_model->setID($intQG)->retrieveAndGetQuestions()->getAsObject();
                $this->qg_model->clear();
            }
        }
        return $this;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->questiongroups = $this->_questiongroups;
        return $objObject;
    }
    
    
}