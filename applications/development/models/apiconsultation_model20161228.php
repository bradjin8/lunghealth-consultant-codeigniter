<?php

class Apiconsultation_model extends Api_model
{   
    protected $resObjectConsultation = null;
    protected $arrConsultationValues = array();
    protected $strApiKey             = '';
    
    
    private function sendConsultationValuesToApi($intConsultationId, $objReview)
    {
        $resXML = new DOMDocument('1.0', 'utf-8');
        $resXML->formatOutput = true;
        $resRootNode = $resXML->createElementNS('http://schemas.datacontract.org/2004/07/LungHealth.Core.Presentation.Models.Domain', 'ArrayOfAsthmaConsultationValueModel');
        $resXML->appendChild($resRootNode);
        $resRootNode->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
        /*
        foreach ($arrDetails as $strKey => $strValue)
        {
            $resModel = $resXML->createElement($strKey,$strValue);
            $resRootNode->appendChild($resModel);
        }
        */
        
        
        
        foreach ($objReview as $strProperty => $arrValue)
        {
            if ((is_array($arrValue) && (count($arrValue)>0) && ($strProperty !== 'arrOtherDrugs'))
                    && (strlen($arrValue['FieldValue']) > 0))
            {   
                $resModel = $resXML->createElement('AsthmaConsultationValueModel');
                $resRootNode->appendChild($resModel);
            
                $resItem = $resXML->createElement('AsthmaConsultationValueId',$arrValue['ConsValID']);
                $resModel->appendChild($resItem);
                $resItem = $resXML->createElement('ConsultationId',$intConsultationId);
                $resModel->appendChild($resItem);
                $resItem = $resXML->createElement('DateCreated',date('Y-m-d\TH:i:s.uP', strtotime($arrValue['Timestamp'])));
                $resModel->appendChild($resItem);
                $resItem = $resXML->createElement('FieldName',$strProperty);
                $resModel->appendChild($resItem);
                $resItem = $resXML->createElement('FieldValue');
                $resModel->appendChild($resItem);
                $resItem->appendChild($resXML->createTextNode($arrValue['FieldValue']));
            }
            else
            {
                if ($strProperty === 'arrOtherDrugs')
                {
                    $resModel = $resXML->createElement('AsthmaConsultationValueModel');
                    $resRootNode->appendChild($resModel);

                    $resItem = $resXML->createElement('AsthmaConsultationValueId','1000000');
                    $resModel->appendChild($resItem);
                    $resItem = $resXML->createElement('ConsultationId',$intConsultationId);
                    $resModel->appendChild($resItem);
                    $resItem = $resXML->createElement('DateCreated',date('Y-m-d\TH:i:s.uP'));
                    $resModel->appendChild($resItem);
                    $resItem = $resXML->createElement('FieldName',$strProperty);
                    $resModel->appendChild($resItem);
                    $resItem = $resXML->createElement('FieldValue', serialize($arrValue));
                    $resModel->appendChild($resItem);
                }
            }
            
            
        }
        
        
        $strXML = $resXML->saveXML();
        
        $CI =& get_instance();
        $CI->load->helper('file');
        write_file('./api_xml_logs/'.date('Y-m-d_His')."_".$intConsultationId.".xml", $strXML);
        
        $objConsultation = $this->sendCurlData('Asthma','AddConsultationValues',$strXML,true);
        
        if ($objConsultation)
        {
            /*$this->resObjectConsultation = $objConsultation;
            
            
            
            $CI->load->model('review_model','r_model');
            $CI->r_model->deleteReview($objReview->intReviewID);
            */
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function instantiateConsultation($arrDetails)
    {
        $resXML = new DOMDocument('1.0', 'utf-8');
        $resXML->formatOutput = true;
        $resRootNode = $resXML->createElementNS('http://schemas.datacontract.org/2004/07/LungHealth.Core.API.Models.DomainEntities', 'Consultation');
        $resXML->appendChild($resRootNode);
        $resRootNode->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
        
        foreach ($arrDetails as $strKey => $strValue)
        {
            $resModel = $resXML->createElement($strKey,$strValue);
            $resRootNode->appendChild($resModel);
        }
        
        $strXML = $resXML->saveXML();
        
       
        
        
        $objConsultation = $this->sendCurlData('Patient','CreateConsultation',$strXML,true,true);
        
        if ($objConsultation)
        {
            $this->resObjectConsultation = $objConsultation;
            return true;
        }
        else
        {
            return false;
        }
         
    }
    
    protected function createConsultation($objReview)
    {
        $arrConsultationData = array(
                'ConsultationEndTime'          => date('Y-m-d\TH:i:s')
                ,'ConsultationFlows'            => $objReview->agcsystem_arrFlowHistory['FieldValue']
                ,'ConsultationId'                => $objReview->intReviewID
                ,'ConsultationStartTime'        => $objReview->agcsystem_ReviewStartTime['FieldValue']
                ,'ConsultationStatus'           => $objReview->agcsystem_ReviewStatus['FieldValue']
                ,'ConsultationType'             => $objReview->AssessmentDetails_AssessmentType['FieldValue']
                
                ,'DiseaseId'                    => 1
                ,'PatientId'                    => $objReview->PatientDetails_PatientId['FieldValue']
                ,'UserId'                       => $objReview->agcsystem_APIUserId['FieldValue']
            );
        if ($this->instantiateConsultation($arrConsultationData))
        {
            $strQuery = '/Consultation';
            
            /*echo "<pre>";
            var_dump($this->resObjectConsultation->response);
            
            */
            
            /*$objRoot        = ;*/
            return $this->resObjectConsultation->data->query($strQuery)->item(0)->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
            
        }
        else
        {
            return false;
        }
        
    }
    
    public function sendConsultationToApi($objReview)
    {
        $intApiConsultationId = $this->createConsultation($objReview);
        
        if ($intApiConsultationId)
        {
            return $this->sendConsultationValuesToApi($intApiConsultationId, $objReview);
        }
        else
        {
            return false;
        }
    }
    
    private function retrieveConsultation($intConsultationId)
    {
        $objP = $this->getCurlData('Asthma','GetConsultationValues',array('consultationId' => $intConsultationId),true);
        if ($objP)
        {
            $this->resObjectConsultation = $objP;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function getAndStoreConsultation($intConsultationId,$booExacerbation)
    {
        
        
        $CI =& get_instance();
        if (!$booExacerbation)
        {
            $CI->load->model('previousconsultationvalues_model','pc_model');
        }
        else
        {
            $CI->load->model('previousconsultationexacerbation_model','pc_model');
        }
        $CI->pc_model->performReviewSetup($intConsultationId);
        if ($this->retrieveConsultation($intConsultationId))
        {
            $strQuery = '/ArrayOfAsthmaConsultationValueModel';
            
            /*echo "<pre>";
            var_dump($this->resObjectConsultation->response);
            
            */
            
            /*$objRoot        = ;*/
            $objNodeList    = $this->resObjectConsultation->data->query($strQuery)->item(0)->getElementsByTagName("AsthmaConsultationValueModel");
            
            
            
            
            foreach ($objNodeList as $objNode)
            {
                $objCV = new stdClass();
                
                /*
                foreach($objNode->childNodes as $objChildNodeItem)
                {
                    
                }
                */
                $raw_date = $objNode->getElementsByTagName("DateCreated")->item(0)->nodeValue;
                $arrDateTimeParts = explode('T',$raw_date);
                $arrDateParts = explode('-',$arrDateTimeParts[0]);
                $arrTimeParts = explode(':',explode('.',$arrDateTimeParts[1])[0]);
                
                
                $timestamp = mktime((int) $arrTimeParts[0]
                                        ,(int) $arrTimeParts[1]
                                        ,(int) $arrTimeParts[2]
                                        ,(int) $arrDateParts[1]
                                        ,(int) $arrDateParts[2]
                                        ,(int) $arrDateParts[0]);
                
                
                $objCV->ConsultationId  = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
                $objCV->FieldName       = $objNode->getElementsByTagName("FieldName")->item(0)->nodeValue;
                $objCV->FieldValue      = $objNode->getElementsByTagName("FieldValue")->item(0)->nodeValue;
                $objCV->Timestamp       = $timestamp;
                
                $CI->pc_model->addValue($objCV);
                
                
                
                
            }
            $CI->pc_model->store();
            /*
            var_dump($objC);
            
            die();
            $objC->ConsultationId        = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;*/
            
            
        }
        
        
    }
    
    private function retrievePreviousConsultationId($intPatientId)
    {
        
        $objP = $this->getCurlData('Patient','GetLatestCompletePatientConsultation',array('patientId' => $intPatientId),true);
        if ($objP)
        {
            
            $this->resObjectConsultation = $objP;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function getPreviousConsultationId($intPatientId)
    {
        $objC = new stdClass();
        if ($this->retrievePreviousConsultationId($intPatientId))
        {
            $strQuery = '/Consultation';
            
            $objNodeList = $this->resObjectConsultation->data->query($strQuery);
            $objNode = $objNodeList->item(0);
            if ($objNode->getElementsByTagName("ConsultationId")->length >0)
            {

                $objC->ConsultationId        = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
            }
        }
        return $objC;
    }
    
    public function getPreviousConsultationFor($intPatientId)
    {
        $this->resObjectConsultation = null;
        
        $objCID = $this->getPreviousConsultationId($intPatientId);
        
        if (property_exists($objCID, 'ConsultationId'))
        {
            $this->resObjectConsultation = null;
            return $this->getConsultation($objCID->ConsultationId);
        }
        else
        {
            return false;
        }
        
    }
    
    public function getPreviousCompletedConsultationsFor($intPatientId)
    {
        $arrReturn = array();
        $CI =& get_instance();
        $CI->load->model('outcomes_model','outcomes_model');
        $this->resObjectConsultation = null;
        
        $arrCompletedConsultations = array();
        
        // loop through all previous consultations... 
		
		//var_dump($this->getAllPreviousConsultationsFor($intPatientId));
		//die();
        
        foreach ($this->getAllPreviousConsultationsFor($intPatientId) as $objConsultation)
        {
			
			$lookupFlow = $objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows)-1];
			
			if($lookupFlow == "20000")
			{
				$lookupFlow = $objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows)-2];
			}
			
            $objConsultation->Status = 
                            $CI->outcomes_model->getStatus($lookupFlow);
            if (!$objConsultation->Status)
            {
                $CI->session->set_flashdata('strTitle','ERROR APICONSULTATION_MODEL/getPreviousCompletedConsultationsFor/1 Unknown Consultation Status Received');
                $CI->session->set_flashdata('strMessage','The remote API returned a Status ('
                                                .$objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows)-2]
                                                .') which the Outcomes Model has no record of. The following may be helpful.');
                redirect('/error/', 'refresh');

            }
            
			//echo "<p>";
			//var_dump($objConsultation->arrConsultationFlows);
			//echo "</p>";
			
           //var_dump($objConsultation);
            
            //check if completed
            if ($objConsultation->Status->State != 'Aborted')
            {
                // separate the EXs and non-Exs
                switch($objConsultation->ConsultationType)
                {
                    case 'FU':
                    case 'AR':
                    case '1A':
                        if (!array_key_exists('NON-EX', $arrCompletedConsultations) || ($arrCompletedConsultations['NON-EX']['timestamp'] < $objConsultation->Timestamp))
                        {
                            $arrCompletedConsultations['NON-EX'] = array('timestamp' => $objConsultation->Timestamp,'id' => $objConsultation->ConsultationId);
                        }
                        
                        break;
                    case 'EX':
                        if (!array_key_exists('EX', $arrCompletedConsultations) || ($arrCompletedConsultations['EX']['timestamp'] < $objConsultation->Timestamp))
                        {
                            $arrCompletedConsultations['EX'] = array('timestamp' => $objConsultation->Timestamp,'id' => $objConsultation->ConsultationId);
                        }
                        break;
                    default :
                        break;
                }
                
                
            }
            
                     
        }  
        
        //var_dump($arrCompletedConsultations);die();
        
        if (count($arrCompletedConsultations) > 0)
        {
            if (array_key_exists('EX', $arrCompletedConsultations))
            {
                $arrReturn['Exacerbation'] = $arrCompletedConsultations['EX']['id'];
                $this->getAndStoreConsultation($arrCompletedConsultations['EX']['id'],true);
            }
            
            if (count($arrCompletedConsultations['NON-EX']) > 0)
            {
                $arrReturn['Non-Exacerbation'] = $arrCompletedConsultations['NON-EX']['id'];
                $this->getAndStoreConsultation($arrCompletedConsultations['NON-EX']['id'],false);
            }
            
            
        }
            
       // die();
            
        return $arrReturn;
        
    }
    
    
    public function getConsultationForAudit($intConsultationId, $strConsultationType)
    {
        $intReturn = -1;
        $CI =& get_instance();
        $CI->load->model('auditconsultationvalues_model','acv_model');
        $CI->acv_model->performReviewSetup($intConsultationId);
        $this->resObjectConsultation = null;
        
        if ($this->retrieveConsultation($intConsultationId))
        {
            $strQuery = '/ArrayOfAsthmaConsultationValueModel';
            
            $objNodeList    = $this->resObjectConsultation->data->query($strQuery)->item(0)->getElementsByTagName("AsthmaConsultationValueModel");
            
            
            
            
            foreach ($objNodeList as $objNode)
            {
                $objCV = new stdClass();


                $raw_date = $objNode->getElementsByTagName("DateCreated")->item(0)->nodeValue;
                $arrDateTimeParts = explode('T',$raw_date);
                $arrDateParts = explode('-',$arrDateTimeParts[0]);
                $arrTimeParts = explode(':',explode('.',$arrDateTimeParts[1])[0]);


                $timestamp = mktime((int) $arrTimeParts[0]
                                        ,(int) $arrTimeParts[1]
                                        ,(int) $arrTimeParts[2]
                                        ,(int) $arrDateParts[1]
                                        ,(int) $arrDateParts[2]
                                        ,(int) $arrDateParts[0]);


                $objCV->ConsultationId  = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
                $objCV->FieldName       = $objNode->getElementsByTagName("FieldName")->item(0)->nodeValue;
                $objCV->FieldValue      = $objNode->getElementsByTagName("FieldValue")->item(0)->nodeValue;
                $objCV->Timestamp       = $timestamp;

                if ($objCV->FieldName === 'arrOtherDrugs')
                {
                    $CI->acv_model->setAuditID($intConsultationId)->addOtherDrugs($objCV->FieldValue);
                }
                else
                {
                    
                        
                    
                        if ($strConsultationType !== '1A')
                        {
                            $arrAmendForAudit = array('agcsystem_arrFlowHistory'
                                                    ,'agcsystem_arrScreenHistory'
                                                    ,'agcsystem_arrFlows'
                                                    ,'agcsystem_arrScreens'
                                                    ,'agcsystem_arrScreensToFlows'

                                                );
                            if (in_array($objCV->FieldName, $arrAmendForAudit))
                            {
                                $objCV->FieldValue = serialize(array_slice(unserialize($objCV->FieldValue), 1));
                            }

                            if ($objCV->FieldName == 'agcsystem_arrSidebarScreens')
                            {
                                $arrOriginal = unserialize($objCV->FieldValue);
                                $arrModified = array();
                                $counter = 0;
                                foreach ($arrOriginal as $key => $val)
                                {
                                    if ($counter > 0)
                                    {
                                        $arrModified[$key] = $val;
                                    }
                                    $counter++;
                                }

                                $objCV->FieldValue = serialize($arrModified);
                            }
                        }
                        $CI->acv_model->addValue($objCV);
                    
                }


            }
            $CI->acv_model->store();
            $intReturn = $intConsultationId;
        }
        return $intReturn;
    }


    private function retrieveAllPreviousConsultationsFor($intPatientId)
    {
        $objP = $this->getCurlData('Patient','GetAllPatientConsultations',array('patientId' => $intPatientId),true);
        if ($objP)
        {
            $this->resObjectConsultation = $objP;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getAllPreviousConsultationsFor($intPatientId)
    {
        $this->resObjectConsultation = null;
        
        $arrConsultations = array();        
        if ($this->retrieveAllPreviousConsultationsFor($intPatientId))
        {
            $strQuery = '/ArrayOfConsultation';
            
            $objNodeList    = $this->resObjectConsultation->data->query($strQuery)->item(0)->getElementsByTagName("Consultation");
            
            
            foreach ($objNodeList as $objNode)
            {
                $objConsultation = new stdClass();
                
                $raw_date = $objNode->getElementsByTagName("ConsultationEndTime")->item(0)->nodeValue;
                $arrDateTimeParts = explode('T',$raw_date);
                $arrDateParts = explode('-',$arrDateTimeParts[0]);
                $arrTimeParts = explode(':',explode('.',$arrDateTimeParts[1])[0]);
                
                $timestamp = mktime((int) $arrTimeParts[0]
                                        ,(int) $arrTimeParts[1]
                                        ,(int) $arrTimeParts[2]
                                        ,(int) $arrDateParts[1]
                                        ,(int) $arrDateParts[2]
                                        ,(int) $arrDateParts[0]);
                     
                               
                $objConsultation->ConsultationId            = $objNode->getElementsByTagName("ConsultationId")->item(0)->nodeValue;
                $objConsultation->arrConsultationFlows      = unserialize($objNode->getElementsByTagName("ConsultationFlows")->item(0)->nodeValue);
                $objConsultation->ConsultationType          = $objNode->getElementsByTagName("ConsultationType")->item(0)->nodeValue;
                $objConsultation->Timestamp                 = $timestamp;
                
                
                $objConsultation->ApiStatus                 = $objNode->getElementsByTagName("ConsultationStatus")->item(0)->nodeValue;
                
                
                
                $arrConsultations[] = $objConsultation;
            }
           
            
        }
        
        return $arrConsultations;
        
    }
    
}