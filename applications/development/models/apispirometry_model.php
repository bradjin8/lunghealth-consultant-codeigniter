<?php

class Apispirometry_model extends Api_model
{   
    protected $resObjectSpirometry      = null;
    protected $arrSpirometryValues      = array();
    protected $strApiKey                = '';
    
    private function sendSpirometryValuesToApi($intPatientId, $arrSpirometry)
    {
        $resXML = new DOMDocument('1.0', 'utf-8');
        $resXML->formatOutput = true;
        $resRootNode = $resXML->createElementNS('http://schemas.datacontract.org/2004/07/LungHealth.Core.API.Models.DomainEntities', 'Spirometry');
        $resXML->appendChild($resRootNode);
        $resRootNode->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
        
        
        $resItem = $resXML->createElement('ConsultationId',1);
        $resRootNode->appendChild($resItem);
        
        $resItem = $resXML->createElement('DateOfSpirometry',date('Y-m-d\TH:i:s.uP', $arrSpirometry['timestamp']));
        $resRootNode->appendChild($resItem);
        
		$resItem = $resXML->createElement('Historic',true);
        $resRootNode->appendChild($resItem);

        $arrSpirometryFields = array('PEF');
        
        foreach($arrSpirometryFields as $strSpirometryField)
        {
            if (array_key_exists($strSpirometryField, $arrSpirometry['spirometry_data']) 
                    && ($arrSpirometry['spirometry_data'][$strSpirometryField] != ''))
            {
                $resItem = $resXML->createElement($strSpirometryField,$arrSpirometry['spirometry_data'][$strSpirometryField]);
                $resRootNode->appendChild($resItem);
            }
                
        }

        $resItem = $resXML->createElement('PatientId',$intPatientId);
        $resRootNode->appendChild($resItem);
        
        $arrSpirometryFields = array('PostFEV1','PostFVC','PostSVC','PreFEV1','PreFVC','PreSVC','PredFEV1','PredFVC','PredPEF');
        
        foreach($arrSpirometryFields as $strSpirometryField)
        {
            if (array_key_exists($strSpirometryField, $arrSpirometry['spirometry_data']) 
                    && ($arrSpirometry['spirometry_data'][$strSpirometryField] != ''))
            {
                $resItem = $resXML->createElement($strSpirometryField,$arrSpirometry['spirometry_data'][$strSpirometryField]);
                $resRootNode->appendChild($resItem);
            }
                
        }
        $resItem = $resXML->createElement('SpirometryId',1);
        $resRootNode->appendChild($resItem);
        
        $strXML = $resXML->saveXML();
        
        $CI =& get_instance();
        $CI->load->helper('file');
        write_file('./api_xml_logs/spiro_'.date('Y-m-d_His')."_".$intPatientId.".xml", $strXML);
        
        $objResponse = $this->sendCurlData('Patient','AddPatientSpirometryResults',$strXML,true);
        
        if ($objResponse)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    public function sendSpirometryToApi($intPatientId,$arrSpirometry)
    {
        if ($intPatientId)
        {
            return $this->sendSpirometryValuesToApi($intPatientId, $arrSpirometry);
        }
        else
        {
            return false;
        }
    }
    
    
    private function retrieveSpirometry($intPatientId)
    {
        $objS = $this->getCurlData('Patient','GetSpirometriesByPatient',array('patientId' => $intPatientId),true);
        if ($objS)
        {
            $this->resObjectSpirometry = $objS;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getSpirometry($intPatientId)
    {
        $arrReturn = array();
        
        if ($this->retrieveSpirometry($intPatientId))
        {
            $strQuery = '/ArrayOfSpirometry';
            
            $objNodeList    = $this->resObjectSpirometry->data->query($strQuery)->item(0)->getElementsByTagName("Spirometry");
            
            foreach ($objNodeList as $objNode)
            {
                $objSpirometry = new stdClass();
                if (
                        !(
                                $objNode->getElementsByTagName("DateOfSpirometry")->item(0)->hasAttributes()
                                && ($objNode->getElementsByTagName("DateOfSpirometry")->item(0)->hasAttribute('i:nil'))
                                && ($objNode->getElementsByTagName("DateOfSpirometry")->item(0)->getAttributeNode('i:nil')->nodeValue === 'true')
                        )
                   )
                {
                    $timestamp              = $this->getTimestampFromAPIDate($objNode->getElementsByTagName("DateOfSpirometry")->item(0)->nodeValue);
                    $objSpirometry->ConsultationId  = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
                    $objSpirometry->Date            = date('d/m/Y',$timestamp);
                    $array_indexing = date('Ymd',$timestamp);
                    $arrTargets = array(
						"Historic",
                        "PreFEV1",
                        "PreFVC",
                        "PreSVC",
                        "PostFEV1",
                        "PostFVC",
                        "PostSVC",
                        "PredFEV1",
                        "PredFVC",
                        "PEF",
                        "PredPEF"
                        );

                    foreach($arrTargets as $strTarget)
                    {
                        $objSpirometry->{$strTarget} = $objNode->getElementsByTagName($strTarget)->item(0)->nodeValue;
                    }
                    
                    $array_index_check = 1;
                    $array_indexing_test = $array_indexing;
                    
                    while (array_key_exists($array_indexing_test,$arrReturn))
                    {
                        $array_indexing_test = $array_indexing+$array_index_check;
                        $array_index_check++;
                    }
                    
                    $array_indexing = $array_indexing_test;
                    $arrReturn[$array_indexing] = $objSpirometry;
                }
                
            }
            
            
        }
        
        krsort($arrReturn);
        
        return array_values($arrReturn);
    }
    
}