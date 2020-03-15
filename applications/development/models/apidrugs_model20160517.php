<?php

class Apidrugs_model extends Api_model
{   
    protected $resObjectDrugs = null;
    
    public function getAllDrugsFromApi()
    {
        if (($this->resObjectDrugs === null) || (!$this->resObjectDrugs->success))
        {
			$objDs = $this->getCurlData('Drug','GetDrugs',array(),true);
            if ($objDs)
            {
                $this->resObjectDrugs = $objDs;
				//print_r($this);
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
    
	//?
    /*public function getAllDrugsFromApiWithTypeID($intTypeID)
    {
        $arrDs = array();
		echo "<script> console.log( 'getAllDrugsFromApiWithTypeID' ); </script>";
        
        if($this->getAllDrugsFromApi())
        {
            //$strQuery = '/ArrayOfMedication/Medication[DrugTypeId = "'.$intTypeID.'"]';
			$strQuery = '/ArrayOfDrug/Drug[DrugTypeId = "'.$intTypeID.'"]';
            
            return $this->resObjectDrugs->data->query($strQuery);
            
        }
        
        return $arrDs;

    }*/
    
	//Populates current drug on MRNew
    public function getDrugFromApiWithExternalID($strExternalID)
    {
        $arrDs = array();
        
        if($this->getAllDrugsFromApi())
        {

			$strQuery = '/ArrayOfDrug/Drug[DrugId = "'.$strExternalID.'"]'; 
			//print_r($this);
            return $this->resObjectDrugs->data->query($strQuery);
			
        }
        //print_r($this);
        return $arrDs;
        
    }
    
	//Populates current drug on CMNew2
    public function getDrugsByTypeID()
    {
        $arrDrugs = array();
		
        if($this->getAllDrugsFromApi())
        {
			$strQuery = '/ArrayOfDrug/Drug';
            
            $arrNodes = $this->resObjectDrugs->data->query($strQuery);
            
            /*foreach($arrNodes as $objNode)
            {
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->label              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->drugname              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdpequiv    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->locality     = $objNode->getElementsByTagName("Locality")->item(0)->nodeValue;
				$arrDrugs[$objNode->getElementsByTagName("DrugTypeId")->item(0)->nodeValue][$objNode->getElementsByTagName("DrugId")->item(0)->nodeValue] = $objDrug;
            }*/
			
			/*
			foreach($arrNodes as $objNode)
            {
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->label              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->drugname              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdpequivalentvalue    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->locality     = $objNode->getElementsByTagName("Locality")->item(0)->nodeValue;
				$objDrug->nooftimes     = $objNode->getElementsByTagName("NoOfTimes")->item(0)->nodeValue;
				$objDrug->noperdose     = $objNode->getElementsByTagName("NoPerDose")->item(0)->nodeValue;
				
				
				
				$arrDrugs[$objNode->getElementsByTagName("DrugTypeId")->item(0)->nodeValue][$objNode->getElementsByTagName("DrugId")->item(0)->nodeValue] = $objDrug;
            }
			*/
			
			foreach($arrNodes as $objNode)
            {
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->label              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->drugname              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdpequivalentvalue    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->locality     = $objNode->getElementsByTagName("Locality")->item(0)->nodeValue;
				$objDrug->nooftimes     = $objNode->getElementsByTagName("NoOfTimes")->item(0)->nodeValue;
				$objDrug->noperdose     = $objNode->getElementsByTagName("NoPerDose")->item(0)->nodeValue;

                $objDrug->doseunit     = $objNode->getElementsByTagName("DoseUnit")->item(0)->nodeValue;
                $objDrug->doseunit     = $objNode->getElementsByTagName("DoseUnit")->item(0)->nodeValue;
				$objDrug->dose     = $objNode->getElementsByTagName("Dose")->item(0)->nodeValue;
				$objDrug->frequency     = $objNode->getElementsByTagName("Frequency")->item(0)->nodeValue;
				
				
				//$objDrug->devicename     = $objNode->getElementsByTagName("Device")->item(0)->getElementsByTagName("DeviceName")->item(0)->nodeValue;
				
				$objDrug->deviceid     = $objNode->getElementsByTagName("DeviceId")->item(0)->nodeValue;
				
				/*
				foreach ($DeviceNodeSing as $DeviceNode) {
					echo $book->nodeValue, PHP_EOL;
				}
				
				*/
				
				//var_dump($DeviceNode);
				
				/*
				$objDrug->devicename     = $DeviceNode->getElementsByTagName("DeviceName")->item(0)->nodeValue;
				*/
				
				
				
				$arrDrugs[$objNode->getElementsByTagName("DrugTypeId")->item(0)->nodeValue][$objNode->getElementsByTagName("DrugId")->item(0)->nodeValue] = $objDrug;
            }
			
			
			
        }

        return $arrDrugs;

    }
    
    public function getDrugsByTypeName()
    {
        $arrDrugs = $this->getDrugsByTypeID();
        $arrReturn = array();
        $arrNames = array(
		
		1=> 'DrugISABA',
		2=> 'DrugISAA',
        3=> 'DrugILABA',       
        4=> 'DrugILAA',
		5=> 'DrugICS',		
		6=> 'DrugComb',
		7=> 'DrugMucolytic',
		8=> 'DrugDiuretic',
		9=> 'DrugPDE4Inhibitor',
		10 => 'DrugOralSteroids',
		11 => 'DrugAntibiotics',
        12 => 'DrugLTRA',
        13 => 'DrugTheophylline',
        14 => 'DrugNebSABA',
        15 => 'DrugNebSAA',   
        16 => 'DrugOBA',
        17 => 'DrugAnti-IgE',
		18=> 'DrugInjectableBetaAgonist',
		19 => 'DrugCromolyns',
		20=> 'DrugLABALAMA',
		21=> 'DrugNebulisedSABASAMA',
		22=> 'DrugLongActingInjectableSteroids',
		
        99 => 'NasalSteroid',
        98 => 'BoneProphylaxis'
			
        );
        foreach ($arrNames as $strNewIndex)
        {
            $arrReturn[$strNewIndex] = array();

        }
        
        foreach($arrDrugs as $id => $drugarray)
        {
            $arrReturn[$arrNames[$id]] = $drugarray;
        }
        
        return $arrReturn;
		
    }
    
    
    /*public function getDrugsForTypeID($intTypeID)
    {
        
        $arrDrugs = array();
        $arrNodes = $this->getAllDrugsFromApiWithTypeID($intTypeID);
        
        if ($arrNodes)
        {
            
            foreach($arrNodes as $objNode)
            {
                
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->name              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdp_equivalent    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->locality     = $objNode->getElementsByTagName("Locality")->item(0)->nodeValue;
                $arrDrugs[] = $objDrug;
            }
        }
        
        return $arrDrugs;
    }
    
    public function getValuesForDropDown($intTypeID)
    {
        $arrOutput = array(''=>array('label'=>'','title'=>''));
        $arrDs = $this->getDrugsForTypeID($intTypeID);
        
        foreach ($arrDs as $objDrug)
        {
            $arrOutput[$objDrug->drugid] = array('extid'=>$objDrug->drugid, 'label'=>$objDrug->name,'title'=>$objDrug->bdp_equivalent, 'locality' => $objDrug->locality);
        }       
        
        /*
        foreach ($arrOutput as $key => $row) 
        {
            $labels[$key] = $row['label'];
        }*/

        // Sort the data with volume descending, edition ascending
        // Add $data as the last parameter, to sort by the common key
        //array_multisort($labels, SORT_ASC, $arrOutput);
     /*   
        return $arrOutput;
    }*/
	
 
    public function getLabelFor($strExternalID)
    {
        $strReturn = "";
        
        $arrNodes = $this->getDrugFromApiWithExternalID($strExternalID);
        
        
        if ($arrNodes)
        {
            
            foreach($arrNodes as $objNode)
            {
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->name              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdp_equivalent    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->locality     = $objNode->getElementsByTagName("Locality")->item(0)->nodeValue;
                $arrDrugs[] = $objDrug;
            }
            
            if ($arrNodes->length === 1)
            { 
				$strReturn = $arrNodes->item(0)->getElementsByTagName("DrugName")->item(0)->nodeValue;
            }
        }
        
        return $strReturn;
    }
	
	public function getUsageLableFor($strExternalID, $strUsageText)
    {
	
	/*
	
		$arrNodes = $this->getDrugFromApiWithExternalID($strExternalID);
		
		if ($arrNodes)
        {
            
            foreach($arrNodes as $objNode)
            {
                $objDrug = new stdClass();
				$objDrug->drugid            = $objNode->getElementsByTagName("DrugId")->item(0)->nodeValue;
				$objDrug->name              = $objNode->getElementsByTagName("DrugName")->item(0)->nodeValue;
                $objDrug->bdp_equivalent    = $objNode->getElementsByTagName("BdpEquivalence")->item(0)->nodeValue;
                $objDrug->dose_unit     = $objNode->getElementsByTagName("DoseUnit")->item(0)->nodeValue;
				$objDrug->no_per_dose     = $objNode->getElementsByTagName("NoPerDose")->item(0)->nodeValue;
				$objDrug->frequency     = $objNode->getElementsByTagName("Frequency")->item(0)->nodeValue;
				$objDrug->no_of_times     = $objNode->getElementsByTagName("NoOfTimes")->item(0)->nodeValue;
				$objDrug->device_id     = $objNode->getElementsByTagName("DeviceId")->item(0)->nodeValue;
                $arrDrugs[] = $objDrug;
            }
            
        }		
		
		$strDeviceText = '';
		$strDose = '';
        $strDaily = '';
        $strLabel = '';
		
		if (strlen($strUsageText) >=3)
        {
					$arrParts = explode('-', $strUsageText);
						
						if (count($arrParts) > 1)
						{
							$strDose = $arrParts[0];
							$strDaily = $arrParts[1];

						} else {
							$strDaily = $arrParts[0];	
						}
					
					
					switch($arrNodes->item(0)->getElementsByTagName("DeviceId")->item(0)->nodeValue)
					{
							case '1':
								$strDeviceText = 'dose';
								break;
							case '2':
								$strDeviceText = 'puff';
								break;
							case '3':
								$strDeviceText =  'nebule';
								break;
							case '4':
								$strDeviceText =  'tablet';
								break;
							case '5':
								$strDeviceText = 'dose';
								break;
							case '6':
								$strDeviceText =  $strDose.' '.$objNode->getElementsByTagName("DoseUnit")->item(0)->nodeValue.' dose';
								break;
							case '7':
								$strDeviceText =  'dose';
								break;
							default:
								$strDeviceText =  'dose';
								break;
							
					}
					
			$strFreq = $objNode->getElementsByTagName("DoseUnit")->item(0)->nodeValue;
			
			
			switch($strDaily)
            {
                case '1':
                    $strDailyLab = 'Once';
                    break;
                case '2':
                    $strDailyLab = 'Twice';
                    break;
                case '3':
                    $strDailyLab = 'Thrice';
                    break;
                case '4':
                    $strDailyLab = 'Four times';
                    break;
                case 'prn':
                    $strDailyLab = 'As Required';
                    break;
                case 'smart':
                    $strDailyLab = 'SMART';
                    break;
                default:
                    $strDailyLab = $strDaily.' times';
            }
			
			$strDailyLab = $strDailyLab.' '.$strFreq;
			
			if (strlen($strDose) > 0)
            {
                switch($strDose)
                {
                    case '1':
                        $strLabel = $strDose.' '$strDeviceText.', '.$strDailyLab;
                        break;
                    default:
                        $strLabel = $strDose.' '.$strDeviceText.'s, '.$strDailyLab;
                }
            }
		}
		
		*/
		
		
		$strLabel = ''; // DELETE THIS
		return $strLabel;
	}   		
		
	
	
}