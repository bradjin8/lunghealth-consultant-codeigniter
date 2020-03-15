<h3>Drug Therapy</h3>
<?php
/*
if (strlen($objReview->Therapy_TherapyStream)>0) 
{
echo "<p>";
switch ($objReview->Therapy_TherapyStream) {
	case 1:	
		echo "Asthma control is good today. The recommendation is to consider a withdrawal of therapy but discuss with patient. Asthma is variable - most will stop using but keep inhalers available in case of URTI or other trigger in the future.";
		break;
	case 2:	
		echo "Asthma control is good today. The recommendation is to maintain but reconsider diagnosis at next visit if still very well controlled.";
		break;
	case 3:	
		echo "Asthma control is less than perfect. The recommendation is to consider Inhaled corticosteroid in low dose. Follow up in 3 months to check response.";
		break;
	case 4:	
		echo "Asthma control is currently poor. The recommendation is to consider Inhaled corticosteroid in medium dose. Follow up in 3 months to check response.";
		break;
	case 5:	
		echo "Asthma control is good today. The recommendation is to consider a withdrawal/decrease of therapy or same SABA and low dose Inhaled corticosteroid.";
		break;
	case 6:	
		echo "Asthma control is good today. The recommendation is to maintain same SABA and low dose Inhaled corticosteroid.";
		break;
	case 7:	
		echo "Asthma control is less than perfect. The recommendation is to offer SABA and Inhaled corticosteroid/long-acting beta agonist. Follow up in 3 months to check response.";
		break;
	case 8:	
		echo "Asthma control is currently poor. The recommendation is to offer SABA and medium dose Inhaled corticosteroid/long-acting beta agonist. Follow up in 1 months to check response.";
		break;
	case 9:	
		echo "Asthma control is good today. The recommendation is to maintain current medication.";
		break;
	case 10:	
		echo "Asthma control is good today. The recommendation is to consider step down to low dose Inhaled corticosteroid.";
		break;
	case 11:	
		echo "Asthma control is less than perfect. The recommendation is to consider switch to a (s)MART approach to therapy. Follow up in 3 months to check response.";
		break;
	case 12:	
		echo "Asthma control is currently poor. The recommendation is to try using same drugs in a (s)MART regime or move to step 4 therapy.  Patient would benefit from an expert review.";
		break;
	case 13:	
		echo "Asthma control is currently poor. The recommendation is to maintain same regime but arrange extra education/support and review";
		break;
        default:
            break;
}
echo "</p>";
}
*/

if (strlen($objReview->Therapy_TherapyMessage)>0){
	echo "<p>";
	echo "The therapy recommendation from this consultation is: <i>".$objReview->Therapy_TherapyMessage."</i>";
	echo "</p>";

}

$drugs_model = $arrModels['drugs_model'];


//echo "<h1>".$drugs_model->getUsageLableFor($objReview->{"CurrentMedication_CurrentDrugISABANewDrug"}, $objReview->{"CurrentMedication_CurrentDrugISABANewUsage"})."TEST"."</h1>";


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
        99 => 'DrugNasalSteroid',
        98 => 'DrugBoneProphylaxis'
			
        );

//$drugCode = "DrugISABA";

foreach($arrNames as $drugCode){

	if ($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}!= null or $objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}!= '') {              
    echo '<p>';              
       if ($objReview->{"MedicationReview_Review".$drugCode."ChangeType"}=== null or $objReview->{"MedicationReview_Review".$drugCode."ChangeType"}=== '')
	   {       
              echo $drugs_model->getLabelFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}) . ' ' . $drugs_model->getUsageLableFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}, $objReview->{"CurrentMedication_Current".$drugCode."NewUsage"}) . ' remains unchanged.';
       }       
       if ($objReview->{"MedicationReview_Review".$drugCode."ChangeType"}==='stop')
	   {       
              echo $drugs_model->getLabelFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"} ). ' '. $drugs_model->getUsageLableFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}, $objReview->{"CurrentMedication_Current".$drugCode."NewUsage"}) . ' has been withdrawn.';
       }       
       if ($objReview->{"MedicationReview_Review".$drugCode."ChangeType"}==='changedose')
	   {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}).' '.$drugs_model->getUsageLableFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}, $objReview->{"CurrentMedication_Current".$drugCode."NewUsage"}) . ' has been changed to ' . $drugs_model->getLabelFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}).' '.$drugs_model->getUsageLableFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}, $objReview->{"MedicationReview_Review".$drugCode."NewUsage"}).'.';
       }       
       if ($objReview->{"MedicationReview_Review".$drugCode."ChangeType"}==='changetherapy')
	   {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}).' '.$drugs_model->getUsageLableFor($objReview->{"CurrentMedication_Current".$drugCode."NewDrug"}, $objReview->{"CurrentMedication_Current".$drugCode."NewUsage"}) . ' has been changed to ' . $drugs_model->getLabelFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}).' '.$drugs_model->getUsageLableFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}, $objReview->{"MedicationReview_Review".$drugCode."NewUsage"}).'.';
       }       
        echo '</p>';              
	}
              
	if ($objReview->{"MedicationReview_Review".$drugCode."ChangeType"}==='start') {              
			echo '<p>';              
			   echo $drugs_model->getLabelFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}).' '.$drugs_model->getUsageLableFor($objReview->{"MedicationReview_Review".$drugCode."NewDrug"}, $objReview->{"MedicationReview_Review".$drugCode."NewUsage"}).' has been started.';       
				echo '</p>';              
	} 

}







// 

//var_dump($drugs_model);die();

/*



if ($objReview->CurrentMedication_CurrentDrugISABANewDrug!= null or $objReview->CurrentMedication_CurrentDrugISABANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugISABAChangeType=== null or $objReview->MedicationReview_ReviewDrugISABAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISABANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugISABANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISABAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISABANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugISABANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISABAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugISABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISABANewDrug).' '.$objReview->MedicationReview_ReviewDrugISABANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISABAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugISABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISABANewDrug).' '.$objReview->MedicationReview_ReviewDrugISABANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugISABAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISABANewDrug).' '.$objReview->MedicationReview_ReviewDrugISABANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugICSNewDrug!= null or $objReview->CurrentMedication_CurrentDrugICSNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugICSChangeType=== null or $objReview->MedicationReview_ReviewDrugICSChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugICSNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugICSNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugICSChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugICSNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugICSNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugICSChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugICSNewDrug).' '.$objReview->CurrentMedication_CurrentDrugICSNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugICSNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugICSChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugICSNewDrug).' '.$objReview->CurrentMedication_CurrentDrugICSNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugICSNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugICSChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugICSNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugILABANewDrug!= null or $objReview->CurrentMedication_CurrentDrugILABANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugILABAChangeType=== null or $objReview->MedicationReview_ReviewDrugILABAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILABANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugILABANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILABAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILABANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugILABANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILABAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugILABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILABANewDrug).' '.$objReview->MedicationReview_ReviewDrugILABANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILABAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugILABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILABANewDrug).' '.$objReview->MedicationReview_ReviewDrugILABANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugILABAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILABANewDrug).' '.$objReview->MedicationReview_ReviewDrugILABANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugCombNewDrug!= null or $objReview->CurrentMedication_CurrentDrugCombNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugCombChangeType=== null or $objReview->MedicationReview_ReviewDrugCombChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCombNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugCombNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCombChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCombNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugCombNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCombChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCombNewDrug).' '.$objReview->CurrentMedication_CurrentDrugCombNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCombNewDrug).' '.$objReview->MedicationReview_ReviewDrugCombNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCombChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCombNewDrug).' '.$objReview->CurrentMedication_CurrentDrugCombNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCombNewDrug).' '.$objReview->MedicationReview_ReviewDrugCombNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugCombChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCombNewDrug).' '.$objReview->MedicationReview_ReviewDrugCombNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugILAANewDrug!= null or $objReview->CurrentMedication_CurrentDrugILAANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugILAAChangeType=== null or $objReview->MedicationReview_ReviewDrugILAAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILAANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugILAANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILAAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILAANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugILAANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILAAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugILAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILAANewDrug).' '.$objReview->MedicationReview_ReviewDrugILAANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugILAAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugILAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugILAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILAANewDrug).' '.$objReview->MedicationReview_ReviewDrugILAANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugILAAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugILAANewDrug).' '.$objReview->MedicationReview_ReviewDrugILAANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugLTRANewDrug!= null or $objReview->CurrentMedication_CurrentDrugLTRANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugLTRAChangeType=== null or $objReview->MedicationReview_ReviewDrugLTRAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugLTRANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugLTRANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugLTRAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugLTRANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugLTRANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugLTRAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugLTRANewDrug).' '.$objReview->CurrentMedication_CurrentDrugLTRANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugLTRANewDrug).' '.$objReview->MedicationReview_ReviewDrugLTRANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugLTRAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugLTRANewDrug).' '.$objReview->CurrentMedication_CurrentDrugLTRANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugLTRANewDrug).' '.$objReview->MedicationReview_ReviewDrugLTRANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugLTRAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugLTRANewDrug).' '.$objReview->MedicationReview_ReviewDrugLTRANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug!= null or $objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugTheophyllineChangeType=== null or $objReview->MedicationReview_ReviewDrugTheophyllineChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugTheophyllineNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugTheophyllineChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugTheophyllineNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugTheophyllineChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug).' '.$objReview->CurrentMedication_CurrentDrugTheophyllineNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugTheophyllineNewDrug).' '.$objReview->MedicationReview_ReviewDrugTheophyllineNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugTheophyllineChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugTheophyllineNewDrug).' '.$objReview->CurrentMedication_CurrentDrugTheophyllineNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugTheophyllineNewDrug).' '.$objReview->MedicationReview_ReviewDrugTheophyllineNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugTheophyllineChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugTheophyllineNewDrug).' '.$objReview->MedicationReview_ReviewDrugTheophyllineNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug!= null or $objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType=== null or $objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug).' '.$objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugPDE4InhibitorNewDrug).' '.$objReview->MedicationReview_ReviewDrugPDE4InhibitorNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewDrug).' '.$objReview->CurrentMedication_CurrentDrugPDE4InhibitorNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugPDE4InhibitorNewDrug).' '.$objReview->MedicationReview_ReviewDrugPDE4InhibitorNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugPDE4InhibitorChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugPDE4InhibitorNewDrug).' '.$objReview->MedicationReview_ReviewDrugPDE4InhibitorNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugMucolyticNewDrug!= null or $objReview->CurrentMedication_CurrentDrugMucolyticNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugMucolyticChangeType=== null or $objReview->MedicationReview_ReviewDrugMucolyticChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugMucolyticNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugMucolyticNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugMucolyticChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugMucolyticNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugMucolyticNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugMucolyticChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugMucolyticNewDrug).' '.$objReview->CurrentMedication_CurrentDrugMucolyticNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugMucolyticNewDrug).' '.$objReview->MedicationReview_ReviewDrugMucolyticNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugMucolyticChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugMucolyticNewDrug).' '.$objReview->CurrentMedication_CurrentDrugMucolyticNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugMucolyticNewDrug).' '.$objReview->MedicationReview_ReviewDrugMucolyticNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugMucolyticChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugMucolyticNewDrug).' '.$objReview->MedicationReview_ReviewDrugMucolyticNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugISAANewDrug!= null or $objReview->CurrentMedication_CurrentDrugISAANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugISAAChangeType=== null or $objReview->MedicationReview_ReviewDrugISAAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISAANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugISAANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISAAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISAANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugISAANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISAAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugISAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISAANewDrug).' '.$objReview->MedicationReview_ReviewDrugISAANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugISAAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugISAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugISAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISAANewDrug).' '.$objReview->MedicationReview_ReviewDrugISAANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugISAAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugISAANewDrug).' '.$objReview->MedicationReview_ReviewDrugISAANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugOBANewDrug!= null or $objReview->CurrentMedication_CurrentDrugOBANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugOBAChangeType=== null or $objReview->MedicationReview_ReviewDrugOBAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugOBANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugOBANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugOBAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugOBANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugOBANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugOBAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugOBANewDrug).' '.$objReview->CurrentMedication_CurrentDrugOBANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugOBANewDrug).' '.$objReview->MedicationReview_ReviewDrugOBANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugOBAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugOBANewDrug).' '.$objReview->CurrentMedication_CurrentDrugOBANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugOBANewDrug).' '.$objReview->MedicationReview_ReviewDrugOBANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugOBAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugOBANewDrug).' '.$objReview->MedicationReview_ReviewDrugOBANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugNebSABANewDrug!= null or $objReview->CurrentMedication_CurrentDrugNebSABANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugNebSABAChangeType=== null or $objReview->MedicationReview_ReviewDrugNebSABAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSABANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugNebSABANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSABAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSABANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugNebSABANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSABAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebSABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSABANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSABANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSABAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSABANewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebSABANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSABANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSABANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugNebSABAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSABANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSABANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugNebSAANewDrug!= null or $objReview->CurrentMedication_CurrentDrugNebSAANewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugNebSAAChangeType=== null or $objReview->MedicationReview_ReviewDrugNebSAAChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSAANewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugNebSAANewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSAAChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSAANewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugNebSAANewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSAAChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebSAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSAANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSAANewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebSAAChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebSAANewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebSAANewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSAANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSAANewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugNebSAAChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebSAANewDrug).' '.$objReview->MedicationReview_ReviewDrugNebSAANewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugNebICSNewDrug!= null or $objReview->CurrentMedication_CurrentDrugNebICSNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugNebICSChangeType=== null or $objReview->MedicationReview_ReviewDrugNebICSChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebICSNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugNebICSNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebICSChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebICSNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugNebICSNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebICSChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebICSNewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebICSNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugNebICSNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugNebICSChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugNebICSNewDrug).' '.$objReview->CurrentMedication_CurrentDrugNebICSNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugNebICSNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugNebICSChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugNebICSNewDrug).' '.$objReview->MedicationReview_ReviewDrugNebICSNewUsage.' has been started.';       
        echo '</p>';              
} 


if ($objReview->CurrentMedication_CurrentDrugCromolynsNewDrug!= null or $objReview->CurrentMedication_CurrentDrugCromolynsNewDrug!= '') {              
    echo '<p>';              
       if ($objReview->MedicationReview_ReviewDrugCromolynsChangeType=== null or $objReview->MedicationReview_ReviewDrugCromolynsChangeType=== '') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCromolynsNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugCromolynsNewUsage . ' remains unchanged.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCromolynsChangeType==='stop') {       
              echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCromolynsNewDrug ). ' '. $objReview->CurrentMedication_CurrentDrugCromolynsNewUsage . ' has been withdrawn.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCromolynsChangeType==='changedose') {       
              echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCromolynsNewDrug).' '.$objReview->CurrentMedication_CurrentDrugCromolynsNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCromolynsNewDrug).' '.$objReview->MedicationReview_ReviewDrugCromolynsNewUsage.'.';
       }       
       if ($objReview->MedicationReview_ReviewDrugCromolynsChangeType==='changetherapy') {       
              echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugCromolynsNewDrug).' '.$objReview->CurrentMedication_CurrentDrugCromolynsNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCromolynsNewDrug).' '.$objReview->MedicationReview_ReviewDrugCromolynsNewUsage.'.';
       }       
        echo '</p>';              
}
              
if ($objReview->MedicationReview_ReviewDrugCromolynsChangeType==='start') {              
    echo '<p>';              
       echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugCromolynsNewDrug).' '.$objReview->MedicationReview_ReviewDrugCromolynsNewUsage.' has been started.';       
        echo '</p>';              
} 
	

if ($objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug!= null or $objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug!= '') {	
    echo "<p>";
	if ($objReview->MedicationReview_ReviewDrugAntibioticsChangeType=== null or $objReview->MedicationReview_ReviewDrugAntibioticsChangeType=== '') {	
		echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug) . ' ' . $objReview->CurrentMedication_CurrentDrugAntibioticsNewUsage . ' remains unchanged.';
	}	
	if ($objReview->MedicationReview_ReviewDrugAntibioticsChangeType==='stop') {	
		echo $drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug) . ' '. $objReview->CurrentMedication_CurrentDrugAntibioticsNewUsage . ' has been withdrawn.';
	}	
	if ($objReview->MedicationReview_ReviewDrugAntibioticsChangeType==='changedose') {	
		echo 'The dose/device of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug).' '.$objReview->CurrentMedication_CurrentDrugAntibioticsNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugAntibioticsNewDrug).' '.$objReview->MedicationReview_ReviewDrugAntibioticsNewUsage.'.';
	}	
	if ($objReview->MedicationReview_ReviewDrugAntibioticsChangeType==='changetherapy') {	
		echo 'The therapy of '.$drugs_model->getLabelFor($objReview->CurrentMedication_CurrentDrugAntibioticsNewDrug).' '.$objReview->CurrentMedication_CurrentDrugAntibioticsNewUsage . ' has been changed to ' . $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugAntibioticsNewDrug).' '.$objReview->MedicationReview_ReviewDrugAntibioticsNewUsage.'.';
	}
        echo "</p>";
}		
if ($objReview->MedicationReview_ReviewDrugAntibioticsChangeType==='start') {	
    echo "<p>";
	echo $drugs_model->getLabelFor($objReview->MedicationReview_ReviewDrugAntibioticsNewDrug).' '.$objReview->MedicationReview_ReviewDrugAntibioticsNewUsage.' has been started.';
        echo "</p>";
}

//var_dump($objReview->arrOtherDrugs);

*/


/*
$arrOtherDrugsNames = array(
	'other' => 'Other',
	'nsaid' => 'NSAID/Aspirin',
	'betablocker' => 'Beta Blocker',
	'nasalsteroid' => 'Nasal Steroid',
	'antibiotic'=> 'Antibiotic',
	'diuretic'=> 'Diuretic',
	'boneprophylaxis'=> 'Bone Prophylaxis'	
);

*/





if(property_exists ( $objReview ,"arrOtherDrugs" ) and count($objReview->arrOtherDrugs) >=1){

	echo "<h3>Non-Respiratory Therapy</h3></p>";
	echo "<p>Patient is currently taking the following non-respiratory medication:</p>";

	echo "<table style='width:100%'>
	  <tr>
		<th>Name</th>
		<th>Usage</th> 
		<th>Type</th>
	  </tr>";

		foreach($objReview->arrOtherDrugs as $OtherDrug){
			
			$drugTypeDisplay = '';
			
			switch ($OtherDrug["OtherDrugsType"]) {
				case 'other':
					$drugTypeDisplay = 'Other';
					break;
				case 'nsaid':
					$drugTypeDisplay = 'NSAID/Aspirin';
					break;
				case 'betablocker':
					$drugTypeDisplay = 'Beta Blocker';
					break;
				case 'nasalsteroid':
					$drugTypeDisplay = 'Nasal Steroid';
					break;
				case 'antibiotic':
					$drugTypeDisplay = 'Antibiotic';
					break;
				case 'diuretic':
					$drugTypeDisplay = 'Diuretic';
					break;		
				case 'boneprophylaxis':
					$drugTypeDisplay = 'Bone Prophylaxis';
					break;
				default:
				   $drugTypeDisplay = $OtherDrug["OtherDrugsType"];		
			}
					
			echo "<tr>";
				echo "<td>".$OtherDrug["OtherDrugsName"]."</td>";
				echo "<td>".$OtherDrug["OtherDrugsUsage"]."</td>";
				echo "<td>".$drugTypeDisplay."</td>";
			echo "</tr>";
		}
		
	echo "</table>";
} else {
	echo "<p>Patient is currently taking no non-respiratory medication</p>";
}
