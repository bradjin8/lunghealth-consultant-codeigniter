<?php

class Apicontrol_model extends Api_model
{   
    protected $resObjectControl      = null;
    protected $arrControlValues      = array();
    protected $strApiKey                = '';
    
    private function retrieveControl($intPatientId)
    {
        $objS = $this->getCurlData('Patient','GetAsthmaControlsByPatient',array('patientId' => $intPatientId),true);
        if ($objS)
        {
            $this->resObjectControl = $objS;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getControl($intPatientId)
    {
        $arrReturn = array();
        
        if ($this->retrieveControl($intPatientId))
        {
            $strQuery = '/ArrayOfAsthmaControl';
            
            $objNodeList    = $this->resObjectControl->data->query($strQuery)->item(0)->getElementsByTagName("AsthmaControl");
            
            foreach ($objNodeList as $objNode)
            {
                $objControl = new stdClass();
                
                $timestamp              = $this->getTimestampFromAPIDate($objNode->getElementsByTagName("DateCreated")->item(0)->nodeValue);
                $objControl->ConsultationId  = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
                $objControl->Date            = date('d/m/Y',$timestamp);
               
                $arrTargets = array(
                    "Control13",
                    "Control45",
                    "MedicationStep"
                    );
                
                foreach($arrTargets as $strTarget)
                {
                    $objControl->{$strTarget} = $objNode->getElementsByTagName($strTarget)->item(0)->nodeValue;
                }
                
                $arrReturn[] = $objControl;
                
            }
            
            
        }
        return $arrReturn;
    }
    
}