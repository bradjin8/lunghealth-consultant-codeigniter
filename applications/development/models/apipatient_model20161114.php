<?php

class Apipatient_model extends Api_model
{   
    protected $resObjectPatient = null;
    
    public function retrievePatient()
    {
        if (($this->resObjectPatient === null) || (!$this->resObjectPatient->success))
        {
            $objP = $this->getCurlData('Patient','GetNextAppointment',array(),true);
            if ($objP)
            {
                $this->resObjectPatient = $objP;
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    
    public function getPatientObject()
    {
        $objP = new stdClass();
        
        if ($this->retrievePatient())
        {
            $strQuery = '/Patient';
            
            $objNodeList = $this->resObjectPatient->data->query($strQuery);
            
            
            $objNode = $objNodeList->item(0);
            $objP->FirstName        = $objNode->getElementsByTagName("FirstName")->item(0)->nodeValue;
            $objP->LastName         = $objNode->getElementsByTagName("LastName")->item(0)->nodeValue;
            $objP->Title            = $objNode->getElementsByTagName("Title")->item(0)->nodeValue;
            $objP->DateOfBirth      = $this->getTimestampFromAPIDate($objNode->getElementsByTagName("DateOfBirth")->item(0)->nodeValue);
            $objP->Gender           = $objNode->getElementsByTagName("Gender")->item(0)->nodeValue;
            $objP->NhsNumber        = $objNode->getElementsByTagName("NhsNumber")->item(0)->nodeValue;
            $objP->PatientId        = $objNode->getElementsByTagName("PatientId")->item(0)->nodeValue;
            $objP->Height           = $objNode->getElementsByTagName("Height")->item(0)->nodeValue;
            $objP->Weight           = $objNode->getElementsByTagName("Weight")->item(0)->nodeValue;
            
        }
        
        return $objP;
    }
    
    
}