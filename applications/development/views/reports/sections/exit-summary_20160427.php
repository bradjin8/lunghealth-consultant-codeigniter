<p><?php 
	
	//METHOD 1 - END REPORT
	$CI =& get_instance();
	$arrScreenHistory = $CI->session->userdata("arrScreenHistory");
	end($arrScreenHistory);
	$penultimateScreen = prev($arrScreenHistory);    
	echo "<script>console.log('Last Screen M1 (remove after testing): ".$penultimateScreen."');</script>";
	
	//METHOD 2 - BEGINNING REPORT
	$strScreenHistory = $objReview->agcsystem_arrScreenHistory;
	$strScreenHistorySubstring = strrev( $strScreenHistory );
	$intFirstDoubleQuote = strpos ( $strScreenHistorySubstring , "\"" );
	$strScreenHistorySubstring = substr( $strScreenHistorySubstring , $intFirstDoubleQuote+1);
	$intFirstDoubleQuote = strpos ( $strScreenHistorySubstring , "\"" );
	$strLastScreen = strrev (substr( $strScreenHistorySubstring , 0 ,$intFirstDoubleQuote) );
	echo "<script>console.log('Last Screen M2 (remove after testing): ".$strLastScreen."');</script>";
	
	$exitScreen = $penultimateScreen ?: $strLastScreen;
	echo "<script>console.log('Last Screen coalesced (remove after testing): ".$exitScreen."');</script>";
	
	$genderSubjectivePronounUpper = ($objReview->InitialPatientDetails_Sex === "M"? "He":"She");
	$genderSubjectivePronounLower = ($objReview->InitialPatientDetails_Sex === "M"? "he":"she");
	$genderObjectivePronounLower = ($objReview->InitialPatientDetails_Sex === "M"? "him":"her");
	$genderPossessivePronounLower = ($objReview->InitialPatientDetails_Sex === "M"? "his":"her");
	
	$MaximumEffort = $objReview->Spirometry_MaximumEffort;
	$SecondaryCareAsthmaReferral = $objReview->FirstAssessment_SecondaryCareAsthmaReferral;
	$SecondaryCareAsthmaReferralDetails = $objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails;
	$ReasonForNoReferralTextOnly = $objReview->FirstAssessment_ReasonForNoReferralTextOnly;
	
	$PrebronchodilatorFEV1 = $objReview->Spirometry_PrebronchodilatorFEV1;
	$PostbronchodilatorFEV1 = $objReview->Spirometry_PostbronchodilatorFEV1;
	$PrebronchodilatorFVC = $objReview->Spirometry_PrebronchodilatorFVC;
	$PostbronchodilatorFVC = $objReview->Spirometry_PostbronchodilatorFVC;
	
	$SpirometryReferOrContinue = $objReview->LungFunction_SpirometryReferOrContinue;
	
	$CurrentMedicationLevel = $objReview->CurrentMedication_CurrentMedicationLevel;
	$MedicationLevelAtStartOfLastVisit = $objReview->CurrentMedication_MedicationLevelAtStartOfLastVisit;
	
	$NoDiagnosisAlternativeTreatmentDetails = $objReview->FirstAssessment_NoDiagnosisAlternativeTreatmentDetails;
	
	$AccessoryMusclesForBreathing = $objReview->ClinicalExam_AccessoryMusclesForBreathing;
	$CentrallyCyanosed = $objReview->ClinicalExam_CentrallyCyanosed;
	$TracheaPos = $objReview->ClinicalExam_TracheaPos;
	$Percussion = $objReview->ClinicalExam_Percussion;
	$PercussionDullWhere = $objReview->ClinicalExam_PercussionDullWhere;
	$BreathSounds = $objReview->ClinicalExam_BreathSounds;
	$Crackles = $objReview->ClinicalExam_Crackles;
	$CracklesWhere = $objReview->ClinicalExam_CracklesWhere;

	//Restrictive Pattern Spirometry Exit:
	if($exitScreen == "Spiro" && $MaximumEffort == "Y") {
		
		$maxFEV1 = max( $PrebronchodilatorFEV1 , $PostbronchodilatorFEV1);
		$maxFVC = max( $PrebronchodilatorFVC , $PostbronchodilatorFVC);
		
		echo "<p>" . $genderSubjectivePronounUpper . " was entered to the asthma package expecting that the primary diagnosis would be confirmed. However the spirometry (FEV1: " . number_format($maxFEV1,1) . " (L/min), " . "FVC: " . number_format($maxFVC,1) . " (L/min)) shows a restrictive defect i.e. no airway obstruction and lungs that are reduced in size.</p><p>A restrictive defect is <b>not</b> seen in asthma, and if confirmed would indicate that there is likely to be a different explanation for their respiratory symptoms. Further, more detailed lung function investigations should be performed, ";
		
		if($SecondaryCareAsthmaReferral == "Y"){
			echo "and a referral has been made with the details: <i>" . $SecondaryCareAsthmaReferralDetails . "</i></p><p>If the referral confirms that lung function is restrictive then inhalers are unlikely to be required.</p>";
		} else {
			echo "but a referral has not been made with the reason: <i>" . $ReasonForNoReferralTextOnly . "</i>";
		}
		
		echo  "<p>No further appointment has been made in this package.</p>";
	
	//Not Maximum Effort Spirometry Exit	
	} elseif ($exitScreen == "Spiro" && $MaximumEffort == "N") {
		
		echo "<p>" . $genderSubjectivePronounUpper . " is unable to perform a reliable spirometric test today and so assessment and guidance within the computerised algorithm is not possible.</p><p>You have elected to ";
		
		if($SpirometryReferOrContinue == "refer" && $SecondaryCareAsthmaReferral == "Y") {
			echo "refer for specialist advice with the details: <i>" . $SecondaryCareAsthmaReferralDetails . ". </i>Subject to their advice " . $genderSubjectivePronounLower . " may be suitable for re-introduction to the computerised guidance package in future. For now, management should continue in the traditional manner.</p>";
		} elseif ($SpirometryReferOrContinue == "refer" && $SecondaryCareAsthmaReferral == "N") {
			echo "refer for specialist advice, but a referral has not yet been made with the reason: <i>" . $ReasonForNoReferralTextOnly . ". </i>For now, management should continue in the traditional manner.</p>";
		} else {
			echo "manage them in the practice today, but outside of the computerised guidance package.</p>";
		}
	
	//Clinical Exam Exit
	} elseif ($exitScreen == "CE") {
		
		echo "<p>" . $genderSubjectivePronounUpper . " was entered into the asthma management package on the presumption that the diagnosis was correct. Abnormal clinical findings:<ul>";

		if($AccessoryMusclesForBreathing == "Y") {
			echo "<li>Using accessory muscles for breathing</li>";
		}
		
		if($CentrallyCyanosed == "Y") {
			echo "<li>Patient centrally cyanosed</li>";
		}
		
		if($TracheaPos == "L" || $TracheaPos == "R") {
			echo "<li>Trachea position " . ($TracheaPos === "L" ? "deviated left" : "deviated right") . "</li>";
		}
		
		if($Percussion == "D") {
			echo "<li>Percussion dull (" . $PercussionDullWhere . ")</li>";
		}
		
		if($BreathSounds == "A" && $Crackles == "Y") {
			echo "<li>Breath sounds abnormal (crackles - " . $CracklesWhere .")</li>";
		}
		
		echo "</ul>point to other possible explanations and so progress in the computerised package has been halted. No changes have been made to " . $genderPossessivePronounLower . " clinical management.</p><p>Please ensure that either appropriate investigation of these abnormalities happens or that a doctor is happy to show they are unnecessary.</p><p>Please use your clinical judgment to manage the respiratory symptoms. As and when a doctor is able to authorise that it is appropriate to manage " . $genderPossessivePronounLower . " asthma in a standard manner, then please re-enter " . $genderObjectivePronounLower . " to the package.</p>";
	
	//Pre-Therapy Exit
	} elseif ($exitScreen == "PT") {
		
		echo "<p>Placeholder text</p>";	
	
	//Lung-Function Review Exit
	} elseif ($exitScreen == "LFR" /*|| $exitScreen == "OT"*/) {
		
		echo "<p>" . $genderSubjectivePronounUpper . " presented with symptoms thought to be asthma but on entering data into the program and reflecting on it, the diagnosis feels uncertain and " . $genderSubjectivePronounLower . " has been withdrawn from the package. An alternative plan was suggested with the details: <i>" . $NoDiagnosisAlternativeTreatmentDetails . ".</i></p><p>You may wish to amend " . $genderPossessivePronounLower . " status on the asthma register.</p><p>If in future asthma is confirmed, " . $genderSubjectivePronounLower . " may be restarted on the guided management package at a later date.</p>";
	//No Medication Exit (but medicated last time)
	} elseif ($exitScreen == "NM" && $MedicationLevelAtStartOfLastVisit != 0) {
		
		echo "<p>" . $genderSubjectivePronounUpper . " was on high levels of therapy last time and now appears to be on none. This situation is most unusual and beyond the computerised guidance algorithm. " . $genderSubjectivePronounUpper . " has been withdrawn from the computerised guidance package but " . $genderPossessivePronounLower . " data has been saved should it be appropriate to resume standard asthma therapy in future</p>";
	
	//No Medication Exit (two times no medication)
	} elseif ($exitScreen == "NM" && $MedicationLevelAtStartOfLastVisit == 0) {
		
		echo "<p>" . $genderSubjectivePronounUpper . " was on high levels of therapy last time and now appears to be on none. This situation is most unusual and beyond the computerised guidance algorithm. " . $genderSubjectivePronounUpper . " has been withdrawn from the computerised guidance package but " . $genderPossessivePronounLower . " data has been saved should it be appropriate to resume standard asthma therapy in future</p>";
	
	}
	} else {
		echo "<p>" . $genderSubjectivePronounUpper . " has been withdrawn from the computerised guidance package but " . $genderPossessivePronounLower . " data has been saved should it be appropriate to resume standard asthma therapy in future.</p>";
	}
	
	
	
                                            ?></p>
<?php
