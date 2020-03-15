<?php

class Flow_model extends MY_Model
{
    
    const       db_table            = 'Flow';
    protected   $_screens           = array();


    public function setID($intId)
    {
        $this->_data[Flow_model::db_table."ID"] = $intId;
        return $this;
    }
    
    public function getID()
    {
        return $this->_data[Flow_model::db_table.'ID'];
    }
    
   public function clear()
    {
        parent::clear();
        
        $this->_screens = array();
    }
    
    public function retrieve()
    {
        $this->readFromDatabase(Flow_model::db_table, array(Flow_model::db_table."ID", "ScreenOrder", "FieldID", "PercentOfJourney"));
        if ($this->isLoaded())
        {
            $this->_data['arrScreens'] = explode(",",$this->_data['ScreenOrder']);
        }
        return $this;
    }
    
    public function retrieveAndGetScreens()
    {
        $this->retrieve();
        if ($this->isLoaded())
        {
            $this->load->model('screen_model','s_model');
            foreach ($this->_data['arrScreens'] as $strScreenName)
            {
                $this->_screens[$strScreenName] = $this->s_model->setScreenName($strScreenName)->retrieve()->getAsObject();
                $this->s_model->clear();
            }
        }
        return $this;
    }
    
    public function getAsObject() {
        $objObject = parent::getAsObject();
        $objObject->screens     = $this->_screens;
        return $objObject;
    }
}