<h3>Exacerbation Review</h3>
<p>
 
<?php

// Variable to determine if to show the full treatment option sections in report
$ExacerbationTreatmentOption = "N";
 
switch ($objReview->AssessmentDetails_AssessmentType){
    case '1A':
        echo 'During the patient\'s Initial Assessment, symptoms indicating a possible exacerbation were observed and an Exacerbation Review was undertaken during the consultation.';
        break;
    case 'FU':
        echo 'During the patient\'s Routine Follow-up Review, symptoms indicating a possible exacerbation were observed and an Exacerbation Review was undertaken during the consultation.';
        break;
    case 'AR':
        echo 'During the patient\'s Annual Review, symptoms indicating a possible exacerbation were observed and an Exacerbation Review was undertaken during the consultation.';
        break;
    case 'EX':
        echo 'The patient presented to the Lung Health Asthma software today with a possible exacerbation. An exacerbation review was undertaken.';
        break;
    default:
        echo 'Error - not a valid consultation.';
}

//Plan A Exit
if($objReview->Audit_Flow3001Result == 20000 || $objReview->Audit_Flow4001Result == 20000){
	echo ' However, there was no significant increase in asthma symptoms and that makes it unlikely they had an asthma exacerbation. ';
	if ($objReview->Exacerbation_AlternativePathway == "A") {
		echo 'It was decided instead to manage other factors and leave asthma control unaltered. A summary of what was chosen for this patient is prestented below:</p>';
		echo '<p><i>'.$objReview->Exacerbation_AlternativeTreatmentDetails.'</i></p>';
	} else {
		echo 'It was decided instead to proceed to a regular follow-up review.</p>';
	}
	
//No Plan A Exit
} else {
	echo ' The patient reported that:</p><ul>';
	if($objReview->Exacerbation_ChestSymptomsWorse == 'Y'){
		echo '<li>Chest symptoms are significantly worse than usual</li>';
	}
	if($objReview->Exacerbation_WakingAtNight == 'Y'){
		echo '<li>They are waking up at night or waking more than usual due to their asthma</li>';
	}
	if($objReview->Exacerbation_InterferingWithUsualActivities == 'Y'){
		echo '<li>Symptoms are interfering with their usual day-to-day activities</li>';
	}
	if($objReview->Exacerbation_UsingRelieverMoreThanUsual == 'Y'){
		echo '<li>They are using their reliever inhaler more than usual</li>';
	}
	if($objReview->Exacerbation_PeakFlowLessThanUsual == 'Y'){
		echo '<li>Peak Flow values are less than usual</li>';
	}
	echo '</ul>';
}
	
//No PEF Exit
if ($objReview->Exacerbation_SeverityMessages == "E1") {
	echo ' However, PEF was not carried out today and in the absence of an objective measure of airflow limitation the software package was not able to make recommendations. ';
	switch ($objReview->Exacerbation_AlternativePathway2) {
		case "A":
			echo 'It was decided by the clinician that it was probably not an asthma exacerbation. A ';
			break;
		case "B":
			echo 'The patient presented with signs of a mild exacerbation, and a ';
			break;
		case "C":
			echo 'The patient presented with signs of a moderate exacerbation, and a ';
			break;
		case "D":
			echo 'The patient presented with signs of a severe exacerbation, and a ';
			break;
	}
	echo 'summary of what was chosen instead for this patient is presented below:</p>';
	echo '<p><i>'.$objReview->Exacerbation_AlternativeTreatmentDetails.'</i></p>';
	if ($objReview->Exacerbation_AlternativePathway2 == "D") {
		echo '<p><b>In addition, a hospital referral was made with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'</i></b></p>';
	}	
	
//Full Exacerbation Review
} else {
	
	//Symptom Severity
	echo '<h3>Symptom Severity</h3><ul>';
	if($objReview->Exacerbation_HomePeakFlows == 'N'){
		echo '<li>Home peak flows were not recorded.</li>';
	}             
   
	if($objReview->Exacerbation_PEFDeclined == 'Y'){
		echo '<li>A PEF was not recorded today and this was presumed to mean the patient was experiencing a severe or very severe exacerbation.</li>';
	} else {
		echo '<li>The patient recorded a PEF of ' . $objReview->PEF_CurrentPEF . ', which is ' . round($objReview->PEF_CurrentPEFPctPredicted) . '% of ' . strtolower($objReview->PEF_BestOrPredicted) . '.</li>';
	}

	if($objReview->Exacerbation_CompleteSentences == 'Y'){
		echo '<li>The patient was able to speak in complete sentences, which is <b>normal</b>.</li>';
	} else {
		echo '<li>The patient was not able to speak in complete sentences, which is <b>abnormal</b>.</li>';
	}

	if($objReview->ClinicalExam_RespiratoryRate < 25){
		echo '<li>The patient\'s respiratory rate was ' . $objReview->ClinicalExam_RespiratoryRate . ' breaths per minute, which is <b>normal</b>.</li>';
	} else {
		echo '<li>The patient\'s respiratory rate was ' . $objReview->ClinicalExam_RespiratoryRate . ' breaths per minute, which is <b>abnormal</b>.</li>';
	}

	if($objReview->ClinicalExam_PulseBpm < 110){
		echo '<li>The patient\'s heart rate was ' . $objReview->ClinicalExam_PulseBpm . ' beats per minute, which is <b>normal</b>.</li>';
	} else {
		echo '<li>The patient\'s heart rate was ' . $objReview->ClinicalExam_PulseBpm . ' beats per minute, which is <b>abnormal</b>.</li>';
	}

	if($objReview->Exacerbation_Sa02Declined == 'Y'){
		echo '<li>Arterial Oxygen Saturation was not recorded today and this was presumed to be because the test was not available.</li>';
	} else {
		if($objReview->Exacerbation_CurrentSaO2 > 92){
			echo '<li>The patient\'s Arterial Oxygen Saturation was ' . $objReview->Exacerbation_CurrentSaO2 . '%, which is <b>normal</b>.</li>';
		} else {
			echo '<li>The patient\'s Arterial Oxygen Saturation was ' . $objReview->Exacerbation_CurrentSaO2 . '%, which is <b>abnormal</b>.</li>';
		}
	}
	
	//Summary
	echo '</ul><h3>Summary</h3><p>';
	switch ($objReview->Exacerbation_SeverityMessages) {
		case "E2":
			echo 'This patient presented with a low PEF, and hypoxia suggesting a severe asthma attack. Acute therapy has been initiated and hospital referral made, with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'.</i><b> A provisional follow up should be made in the practice for 4 weeks time.</b>';
			break;
		case "E3":
			if ($objReview->Exacerbation_AlternativePathway3 == "A") {
				echo 'This patient presented with a low PEF, and hypoxia suggesting a severe asthma attack. Acute therapy has been initiated and hospital referral made, with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'.</i><b> A provisional follow up should be made in the practice for 4 weeks time.</b>';
			} else {
				echo 'This patient presented with worsening respiratory symptoms, a low PEF and a low SaO2. A clinical decision to manage them at home was made. Because this is beyond the scope of the software, no further appointment exists at the moment. A summary of what was chosen instead for this patient is presented below:</p><p><i>'.$objReview->Exacerbation_AlternativeTreatmentDetails.'</i></p>';
			}
			break;
		case "E4":
			if ($objReview->Exacerbation_ManageAtHome == "Continue") {
				$ExacerbationTreatmentOption = "Y";
			} else {
				echo 'This patient presented with worsening symptoms, a low PEF, and other signs suggesting a severe asthma attack. There was a limited response to high dose bronchodilators and so a hospital referral was made, with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'.</i><b> A provisional follow up should be made in the practice for 4 weeks time.</b>';
			}
			break;
		case "E5":
			if ($objReview->Exacerbation_SymptomImproved50 == "Y" || $objReview->Exacerbation_ManageAtHome == "Continue") {
				$ExacerbationTreatmentOption = "Y";
			} else {
				echo 'This patient presented with worsening symptoms, a low PEF, and other signs suggesting a severe asthma attack. There was a limited response to high dose bronchodilators and so a hospital referral was made, with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'.</i><b> A provisional follow up should be made in the practice for 4 weeks time.</b>';
			}
			break;
		case "E6":
			if ($objReview->Exacerbation_SymptomImproved75 == "Y" || $objReview->Exacerbation_AlternativePathway4 == "A") {
				$ExacerbationTreatmentOption = "Y";
			} else {
				echo 'This patient presented with a an increase in respiratory symptoms that at first appeared to be worsening asthma. But the peak flow was only marginally reduced. There was little or no response to high dose bronchodilators and further assessment found other issues that may explain the situation for which follow up will be planned outside of the asthma program. A summary of what was chosen instead for this patient is presented below:</p><p><i>'.$objReview->Exacerbation_AlternativeTreatmentDetails.'</i></p>';
				if ($objReview->Exacerbation_AddOralSteroids == "Y" || $objReview->Exacerbation_AddAntibiotics == "Y") {
					echo '<p>However we still provided the patient with a course of steroids or antibiotics:</p><ul>';
					if ($objReview->Exacerbation_AddAntibiotics == "Y") {
						echo '<li>Antibiotic: '.$objReview->Exacerbation_Antibiotic.' for '.$objReview->Exacerbation_AntibioticDays.' days</li>';
					}
					if ($objReview->Exacerbation_AddOralSteroids == "Y") {
						echo '<li>Oral prednisilone tablets: '.$objReview->Exacerbation_OralPrednisiloneMg.' mg for '.$objReview->Exacerbation_OralPrednisiloneDays.' days</li>';
					}
					echo '</ul>';
				}
			}
			break;
		case "E7":
			$ExacerbationTreatmentOption = "Y";
			break;
	}
	
	//Exacerbation Treatment Section
	if ($ExacerbationTreatmentOption == "Y") {
		
		//Calculate RCP3 Score:
		$RCP3Array = array($objReview->CurrentControl_DifficultySleeping,$objReview->CurrentControl_UsualAsthmaSymptoms,$objReview->CurrentControl_InterferedWithUsualActivities);
		$RCP3Score = 0;
		for($x = 0; $x < count($RCP3Array); $x++) {
			if ($RCP3Array[$x] == "Y") {
				$RCP3Score+=1;
			}
		}
		
		//Set review weeks text from value
		$PlannedReview = '';
		switch ($objReview->Exacerbation_ReviewWeeks){
			case '2':
				$PlannedReview = '2 weeks';
				break;
			case '4':
				$PlannedReview = '1 month';
				break;
			case '13':
				$PlannedReview = '3 months';
				break;
			case '26':
				$PlannedReview = '6 months';
				break;
		}
		
		if ($objReview->Exacerbation_SeverityMessages == "E4" || $objReview->Exacerbation_SeverityMessages == "E5") {
			echo 'This is a significant exacerbation that has responded to high dose bronchodilators.</p>';			
		} else {
			echo 'This patient presented with an increase in respiratory symptoms that suggest a worsening of their asthma. The Peak Flow was only a little reduced suggesting a relatively mild exacerbation.</p>';
		}
		
		echo '<p>The RCP score for last week is <b>'.$RCP3Score.'/3</b> and the patient has required extra bronchodilation <b>'.$objReview->Exacerbation_RelieverNoPerDay.'</b> times per day.</p>';
		
		if ($objReview->NonPharmaRx_ManagementPlanUnderstandTreatment == "Y" || $objReview->NonPharmaRx_ManagementPlanCreated == "Y"){
			echo '<p>A written management plan has been supplied and it has been confirmed that the patient understands it and has been advised on how to monitor changes in the next few weeks.';
		} elseif ($objReview->NonPharmaRx_ManagementPlanCreated == "N"){
			echo '<p>A written management plan has <b>not</b> been supplied or is <b>not</b> understood by the patient, with the reason: <i>'.$objReview->NonPharmaRx_ManagementPlanWhyNotProvided.'</i>. However they have been advised on how to monitor changes in the next few weeks.';
		}		
		if ($objReview->AssessmentDetails_PEFMeterIssued == "Y") {
			echo ' A PEF Meter has been issued to the patient.</p>';
		} else {
			echo ' A PEF Meter has <b>not</b> been issued to the patient this time.</p>';
		}
		
		if ($objReview->Exacerbation_OralPrednisiloneMg != "" || $objReview->Exacerbation_Antibiotic != "" || $objReview->Exacerbation_InhaledSteroid != "" || $objReview->Exacerbation_AsthmaDrug != "") {
			echo '<p>The following prescribing actions were made:</p><ul>';
			if ($objReview->Exacerbation_InhaledSteroid != "") {
				echo '<li>Inhaled steroid changed to: '.$objReview->Exacerbation_InhaledSteroid.'</li>';
			}
			if ($objReview->Exacerbation_AsthmaDrug != "") {
				echo '<li>New drug for asthma: '.$objReview->Exacerbation_AsthmaDrug.'</li>';
			}
			if ($objReview->Exacerbation_Antibiotic != "") {
				echo '<li>Antibiotic: '.$objReview->Exacerbation_Antibiotic.' for '.$objReview->Exacerbation_AntibioticDays.' days</li>';
			}
			if ($objReview->Exacerbation_OralPrednisiloneMg != "") {
				echo '<li>Oral prednisilone tablets: '.$objReview->Exacerbation_OralPrednisiloneMg.' mg for '.$objReview->Exacerbation_OralPrednisiloneDays.' days</li>';
			}
			echo '</ul>';
		}
		
		echo '<p><b>An earlier planned review should be made for '.$PlannedReview.'</b></p>';		
		
		$this->load->view('reports/sections/inhaler-technique',$objReview);
		
	}
               
}








?>