<?php

/*
Definites:
FirstAssessment_ReferralToENT
NonPharmaRx_ReferralToENTDetails

FirstAssessment_SmokingCessationReferral
NonPharmaRx_SmokingCessationReferralDetails

FirstAssessment_WorkRelatedReferral
FirstAssessment_WorkRelatedReferralDetails

FirstAssessment_SecondaryCareAsthmaReferral
FirstAssessment_SecondaryCareAsthmaReferralDetails


Do I need to do it for these?
FollowUp	ReferralToENT
FollowUp	ReferralToENTDetails

FollowUp	SecondaryCareAsthmaReferral

FollowUp	SmokingCessationReferral
FollowUp	SmokingCessationReferralDetails

FollowUp	WorkRelatedReferral
FollowUp	WorkRelatedReferralDetails

FirstAssessment_SputumSampleSentOff
FirstAssessment_SputumSampleSentOffDetails

What are these?:
FirstAssessment	ReasonForNoReferral
LungFunction	SpirometryReferOrContinue
FirstAssessment	ReasonForNoReferralTextOnly
ProgressReview	SecondaryCareAsthmaReferral
ProgressReview	ShowHospitalReferral

Tests:
TestsAndResults_AsthmaExerciseTest_OrderedThisTime
TestsAndResults_BronchialHyperReactivity_OrderedThisTime
TestsAndResults_CardiorespiratoryExerciseTest_OrderedThisTime
TestsAndResults_ChestRadiograph_OrderedThisTime
TestsAndResults_ExhaledNo_OrderedThisTime
TestsAndResults_FbcBloodEosinophila_OrderedThisTime
TestsAndResults_FlowVolumeLoop_OrderedThisTime
TestsAndResults_Hrct_OrderedThisTime
TestsAndResults_LungVolumesAndDlco_OrderedThisTime
TestsAndResults_PefCharts_OrderedThisTime
TestsAndResults_Petco2_OrderedThisTime
TestsAndResults_ReversibilityTest_OrderedThisTime
TestsAndResults_SerumIge_OrderedThisTime
TestsAndResults_SkinAllergyTesting_OrderedThisTime
TestsAndResults_SputumEosinophila_OrderedThisTime

ClinicalExam_CxrOrdered
ClinicalExam_CxrOrderedDetails
*/

if ($objReview->FirstAssessment_ReferralToENT == 'Y' || 
	$objReview->FirstAssessment_SmokingCessationReferral == 'Y' || 
	$objReview->FirstAssessment_WorkRelatedReferral == 'Y' || 
	$objReview->FirstAssessment_SecondaryCareAsthmaReferral == 'Y' ||
	
	$objReview->TestsAndResults_AsthmaExerciseTest_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_BronchialHyperReactivity_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_CardiorespiratoryExerciseTest_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_ChestRadiograph_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_ExhaledNo_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_FbcBloodEosinophila_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_FlowVolumeLoop_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_Hrct_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_LungVolumesAndDlco_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_PefCharts_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_Petco2_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_ReversibilityTest_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_SerumIge_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_SkinAllergyTesting_OrderedThisTime == 'Y' ||
	$objReview->TestsAndResults_SputumEosinophila_OrderedThisTime == 'Y' ||
	
	$objReview->ClinicalExam_CxrOrdered == 'Y' ||
	
	$objReview->FirstAssessment_SputumSampleSentOff == 'Y') {
	
	echo '<h3>Referrals, Tests and Labs</h3>';
	
	//Referrals
	if ($objReview->FirstAssessment_ReferralToENT == 'Y' || 
		$objReview->FirstAssessment_SmokingCessationReferral == 'Y' || 
		$objReview->FirstAssessment_WorkRelatedReferral == 'Y' || 
		$objReview->FirstAssessment_SecondaryCareAsthmaReferral == 'Y') {
		
		echo '<h4>Referrals</h4><p>The following referrals should have been made:</p><ul>';
		
		if ($objReview->FirstAssessment_SecondaryCareAsthmaReferral == 'Y') {
			echo '<li><b>Specialist Asthma Referral - </b><i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'</i></li>';
		}
		if ($objReview->FirstAssessment_SmokingCessationReferral == 'Y') {
			echo '<li><b>Smoking Cessation Referral - </b><i>'.$objReview->NonPharmaRx_SmokingCessationReferralDetails.'</i></li>';
		}
		if ($objReview->FirstAssessment_WorkRelatedReferral == 'Y') {
			echo '<li><b>Work-related Referral - </b><i>'.$objReview->FirstAssessment_WorkRelatedReferralDetails.'</i></li>';
		}
		if ($objReview->FirstAssessment_ReferralToENT == 'Y') {
			echo '<li><b>ENT Specialist Referral - </b><i>'.$objReview->NonPharmaRx_ReferralToENTDetails.'</i></li>';
		}
		
		echo '</ul>';
	
	}
	
	//Tests
	if ($objReview->TestsAndResults_AsthmaExerciseTest_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_BronchialHyperReactivity_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_CardiorespiratoryExerciseTest_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_ChestRadiograph_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_ExhaledNo_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_FbcBloodEosinophila_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_FlowVolumeLoop_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_Hrct_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_LungVolumesAndDlco_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_PefCharts_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_Petco2_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_ReversibilityTest_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_SerumIge_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_SkinAllergyTesting_OrderedThisTime == 'Y' ||
		$objReview->TestsAndResults_SputumEosinophila_OrderedThisTime == 'Y' ||
		$objReview->ClinicalExam_CxrOrdered == 'Y') {
		
		echo '<h4>Tests</h4><p>The following tests were requested in the package and should be arranged:</p><ul>';
		
		if ($objReview->TestsAndResults_AsthmaExerciseTest_OrderedThisTime == 'Y') {
			echo '<li><b>Asthma Exercise Test</b></li>';
		}
		if ($objReview->TestsAndResults_BronchialHyperReactivity_OrderedThisTime == 'Y') {
			echo '<li><b>Bronchial Hyperreactivity Test</b></li>';
		}
		if ($objReview->TestsAndResults_CardiorespiratoryExerciseTest_OrderedThisTime == 'Y') {
			echo '<li><b>Cardiorespiratory Exercise Test</b></li>';
		}
		if ($objReview->TestsAndResults_ChestRadiograph_OrderedThisTime == 'Y') {
			echo '<li><b>Chest Radiograph</b></li>';
		}		
		if ($objReview->TestsAndResults_ExhaledNo_OrderedThisTime == 'Y') {
			echo '<li><b>Exhaled NO</b></li>';
		}
		if ($objReview->TestsAndResults_FbcBloodEosinophila_OrderedThisTime == 'Y') {
			echo '<li><b>FBC / Blood Eosinophila</b></li>';
		}
		if ($objReview->TestsAndResults_FlowVolumeLoop_OrderedThisTime == 'Y') {
			echo '<li><b>Flow Volume Loop</b></li>';
		}
		if ($objReview->TestsAndResults_Hrct_OrderedThisTime == 'Y') {
			echo '<li><b>High-Resolution Computed Tomography (HRCT) Scan</b></li>';
		}
		if ($objReview->TestsAndResults_LungVolumesAndDlco_OrderedThisTime == 'Y') {
			echo '<li><b>Lung Volumes and DLCO</b></li>';
		}
		if ($objReview->TestsAndResults_PefCharts_OrderedThisTime == 'Y') {
			echo '<li><b>PEF Charts</b></li>';
		}
		if ($objReview->TestsAndResults_Petco2_OrderedThisTime == 'Y') {
			echo '<li><b>PetCO2</b></li>';
		}
		if ($objReview->TestsAndResults_ReversibilityTest_OrderedThisTime == 'Y') {
			echo '<li><b>Reversibility Test</b></li>';
		}
		if ($objReview->TestsAndResults_SerumIge_OrderedThisTime == 'Y') {
			echo '<li><b>Serum IGE</b></li>';
		}
		if ($objReview->TestsAndResults_SkinAllergyTesting_OrderedThisTime == 'Y') {
			echo '<li><b>Skin Allergy Test</b></li>';
		}
		if ($objReview->TestsAndResults_SputumEosinophila_OrderedThisTime == 'Y') {
			echo '<li><b>Sputum Eosinophila</b></li>';
		}
		
		if ($objReview->ClinicalExam_CxrOrdered == 'Y') {
			echo '<li><b>Chest X-ray </b><i>(' . $objReview->ClinicalExam_CxrOrderedDetails . ')</i></li>';
		}
		
		echo '</ul>';
	
	}
	
	//Labs
	if (($objReview->FirstAssessment_SputumClear == "Yellow" || $objReview->FirstAssessment_SputumClear == "Green") && $objReview->AssessmentDetails_AssessmentType == "1A") {
		
		echo "<h4>Labs</h4><p>They reported coloured sputum ";
		
		if ($objReview->FirstAssessment_SputumSampleSentOff == "Y") {
			echo "and a <b>Sputum Sample </b>has been sent (<i>" . $objReview->FirstAssessment_SputumSampleSentOffDetails . ")</i>";
		} elseif ($objReview->FirstAssessment_SputumSampleSentOff == "N") {
			echo "but no sample has been sent";
		}
		
		echo "</p>";
	
	}
	
}

    