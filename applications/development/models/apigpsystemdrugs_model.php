<?php

// GET Patient/GetCurrentPatientDrugsByDrugType?drugTypeId={drugTypeId}

class apigpsystemdrugs_model extends Api_model
{   
    protected $resObjectGpSystemDrugs      = null;
    protected $arrGpSystemDrugsValues      = array();
    protected $strApiKey                = '';
    
    
    private function retrieveGpSystemDrug($intDrugTypeId)
    {
        $objS = $this->getCurlData('Patient','GetCurrentPatientDrugsByDrugType',array('drugTypeId' => $intDrugTypeId),true);
        if ($objS)
        {
            $this->resObjectGpSystemDrugs = $objS;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getGpSystemDrug($intDrugTypeId)
    {
        $arrReturn = array();
        
        if ($this->retrieveGpSystemDrug($intDrugTypeId))
        {
            $strQuery = '/PatientDrug';
            
            $objNodeList    = $this->resObjectGpSystemDrugs->data->query($strQuery)->item(0)->getElementsByTagName("Drug");
            
            foreach ($objNodeList as $objNode)
            {
				$arrDrug = array();
				$arrTargets = array(
								  "NoOfTimes",
								  "NoPerDose",
								  "DrugId",
								  );

				foreach($arrTargets as $strTarget)
				{
					$arrDrug[$strTarget] = $objNode->getElementsByTagName($strTarget)->item(0)->nodeValue;
				}
				
				$arrReturn['id'] = $arrDrug['DrugId'];
				//$arrReturn['usage'] = $arrDrug['NoPerDose']. '-' .$arrDrug['NoOfTimes'];
				$arrReturn['usage'] = $arrDrug['NoPerDose']. '-' .explode(',',$arrDrug['NoOfTimes'])[0];
			}
                
            
            
            
        }
        
        
        
		
        return $arrReturn;
    }
	
	public function getGpSystemDrugs()
	{
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
        foreach ($arrNames as $intDrugTypeId => $strName)
        {
			$arrGpSystemDrug = $this->getGpSystemDrug($intDrugTypeId);
			//$arrGpSystemDrug = array('id'=>'','usage'=>'');
			if (count($arrGpSystemDrug)>0 && array_key_exists('id',$arrGpSystemDrug) && $arrGpSystemDrug['id'] !== '')
			{
				$arrReturn['CurrentMedication_Current'.$strName.'NewDrug'] = $arrGpSystemDrug['id'];
				$arrReturn['CurrentMedication_Current'.$strName.'NewUsage'] = $arrGpSystemDrug['usage'];
			}
        }
		return $arrReturn;
	}
    
}