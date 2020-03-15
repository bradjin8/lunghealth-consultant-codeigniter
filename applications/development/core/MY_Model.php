<?php

class MY_Model extends CI_Model {
    
    protected $_data                =array(); 
    protected $_isloaded            =false;
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function clear()
    {
        $this->_data= array();
        $this->_isloaded = false;
    }
    
    public function isLoaded()
    {
        return $this->_isloaded; 
    }
   
    public function __call($method, $args) {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = strtolower(substr($method,3));
                $data = null;
                if (array_key_exists($key,$this->_data))
                {
                    $data = $this->_data[$key];
                }
                return $data;
                break;
            case 'set' :
                $key = strtolower(substr($method,3));
                $this->_data[$key] = isset($args[0]) ? $args[0] : null;
                return $this;
                break;
            default :
                die("Fatal error: Call to undefined function " . $method);
        }
    }
    
    
    protected function readFromDatabase($strTable, $arrFields = array(), $strOrder = "")
    {
        if (array_key_exists($strTable.'ID',$this->_data))
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
            $this->db->where($strTable.'ID',$this->_data[$strTable.'ID']);
            
            
            $this->db->limit(1);
            
            if (strlen($strOrder) > 0)
            {
                $this->db->order_by($strOrder);
            }
            
            $objQuery = $this->db->get();
            if ($objQuery->num_rows() > 0)
            {
                $this->_isloaded = true;
                $arrUser = $objQuery->row_array();
                foreach($arrUser as $key=>$val)
                {
                    $this->_data[$key] = $val;
                }
            }
            
        }
        return $this;
    }
    
    
    public function getAsObject()
    {
        $objUserData = new stdClass();
        
        foreach ($this->_data as $key => $val)
        {
            $objUserData->{$key} = $val;
        }
        
        return $objUserData;
    }
    
}
