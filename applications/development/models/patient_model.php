<?php

class Patient_Model extends CI_Model {
    
    protected $_data=array(); 
    const       fieldname_table            = 'Field';
    
    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        
        
        $this->db->select('LOWER(CONCAT(DbTableName,DbFieldName)) AS PatientField', false);
        $this->db->from(Patient_Model::fieldname_table, false);
        $this->db->where('DbTableName IS NOT NULL', null, false);
        
        
        $objQuery = $this->db->get();
        if ($objQuery->num_rows() > 0)
        {
            foreach($objQuery->result_array() as $arr)
            {
                //var_dump($arr['PatientField']);
                $this->_data[$arr['PatientField']] = null;
            }
            //die();
        }
        
        
    }
    
    
    function loadPatient($mixPatient)
    {
        
        if ($mixPatient)
        {
            foreach ($this->_data as $key=>$val)
            {
                if (property_exists($mixPatient, $key))
                {
                    //echo "loadPatient setting ".$key." to ".$mixPatient->{$key}." \r\n";
                    $this->{'set'.$key}($mixPatient->{$key});
                }
            }
        }
        return $this;
            
    }
    
  
    function loadNewData($arrInputData = array())
    {
        
        foreach ($this->_data as $key=>$val)
        {
            
            if (array_key_exists($key,$arrInputData))
            {
                //echo "loadNewData setting ".$key." to ".$arrInputData[$key]." \r\n";
                $this->{'set'.$key}($arrInputData[$key]);
            }
        }
        
        return $this;
            
    }
    
    
    function clear()
    {
        $this->_data= array();
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
