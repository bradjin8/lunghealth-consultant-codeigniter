<?php

/*
ClinicalExam	WeightKg
ClinicalExam	HeightM
ClinicalExam	BMI
ClinicalExam	PulseBpm
InitialPatientDetails	Sex
InitialPatientDetails	Age
FirstAssessment	PredictedFEVValue
FirstAssessment	PredictedFVCValue
FirstAssessment	PredictedPEFValue
ClinicalExam	PulseConfirmedNormalByGP
ClinicalExam	PulseNormal

ClinicalExam	AccessoryMusclesForBreathing
ClinicalExam	CentrallyCyanosed
ClinicalExam	RespiratoryRate
ClinicalExam	TracheaPos
ClinicalExam	Percussion
ClinicalExam	PercussionDullWhere
ClinicalExam	BreathSounds
ClinicalExam	Wheeze
ClinicalExam	CxrOrdered
ClinicalExam	Inspiratory
ClinicalExam	Expiratory
ClinicalExam	Crackles
ClinicalExam	CracklesWhere
ClinicalExam	ChestOtherComments
ClinicalExam	ClinicalExamCompleted
ClinicalExam	ClinicalExamOutcome
ClinicalExam	CxrOrderedDetails
ClinicalExam	RRConfirmedNormalByGP
ClinicalExam	RRNormal
Exacerbation	ExacerbationReviewCompleted

AssessmentDetails	AssessmentType
FirstAssessment	GPAuthorisation
FirstAssessment	GPAuthorisationDetails
ClinicalExam	ClinicalExamOutcome
FirstAssessment	FastOrStandardTrack
ClinicalExam	PulseConfirmedNormalByGP
ClinicalExam	RRConfirmedNormalByGP
CurrentMedication	MedicationLevelAtStartOfLastVisit
*/

if (	$objReview->ClinicalExam_ClinicalExamCompleted == "Y"

	&&	($objReview->ClinicalExam_BMI >= 30
	||	$objReview->ClinicalExam_PulseBpm < 50
	||	$objReview->ClinicalExam_PulseBpm >= 110
	||	$objReview->ClinicalExam_AccessoryMusclesForBreathing == "Y"
	||	$objReview->ClinicalExam_CentrallyCyanosed == "Y"
	||	$objReview->ClinicalExam_RespiratoryRate < 8
	||	$objReview->ClinicalExam_RespiratoryRate >= 25
	||	$objReview->ClinicalExam_TracheaPos == "L"
	||	$objReview->ClinicalExam_TracheaPos == "R"
	||	$objReview->ClinicalExam_Percussion == "U"
	||	$objReview->ClinicalExam_Percussion == "D"
	||	$objReview->ClinicalExam_BreathSounds == "U"
	||	($objReview->ClinicalExam_BreathSounds == "A" && ($objReview->ClinicalExam_Inspiratory == "Y" || $objReview->ClinicalExam_Crackles == "Y"))
	||	strlen($objReview->ClinicalExam_ChestOtherComments) >0)
	) {
	
	echo "<h3>Clinical Examination</h3><p>The patient underwent a clinical examination today and the following abnormal findings were noted:</p><ul>";
	
	//General Examination
	if ($objReview->ClinicalExam_BMI >= 30) {		
		echo "<li>BMI of " . $objReview->ClinicalExam_BMI . " indicates the patient is obese</li>";
	}
	
	if ($objReview->ClinicalExam_PulseBpm < 50) {		
		echo "<li>Pulse of " . $objReview->ClinicalExam_PulseBpm . " (bpm) is unusually low";
		if ($objReview->ClinicalExam_PulseConfirmedNormalByGP == "Normal") {
			echo " but it has been confirmed normal for this patient by a GP</li>";
		} elseif ($objReview->ClinicalExam_PulseConfirmedNormalByGP == "Abnormal") {
			echo " and a GP has confirmed this is abnormal, so the assessment was suspended, pending further investigations</li>";
		}
	} elseif ($objReview->ClinicalExam_PulseBpm >= 110) {
		echo "<li>Pulse of " . $objReview->ClinicalExam_PulseBpm . " (bpm) is raised, and this took the patient into an exacerbation assessment</li>";
	} elseif ($objReview->ClinicalExam_PulseBpm >= 200) {
		echo "<li>Pulse of " . $objReview->ClinicalExam_PulseBpm . " (bpm) is unusually high, and this took the patient into an exacerbation assessment";
		if ($objReview->ClinicalExam_PulseConfirmedNormalByGP == "Normal") {
			echo " but it has been confirmed normal for this patient by a GP</li>";
		} elseif ($objReview->ClinicalExam_PulseConfirmedNormalByGP == "Abnormal") {
			echo " and a GP has confirmed this is abnormal, so the assessment was suspended, pending further investigations</li>";
		}
	}
	
	//Chest Examination
	if ($objReview->ClinicalExam_AccessoryMusclesForBreathing == "Y") {		
		echo "<li>Patient is using accessory muscles for breathing</li>";
	}
	
	if ($objReview->ClinicalExam_CentrallyCyanosed == "Y") {		
		echo "<li>Patient is centrally cyanosed</li>";
	}
	
	if ($objReview->ClinicalExam_RespiratoryRate < 8) {		
		echo "<li>Respiratory rate of " . $objReview->ClinicalExam_RespiratoryRate . " (breaths per minute) is unusually low";
		if ($objReview->ClinicalExam_RRConfirmedNormalByGP == "Normal") {
			echo " but it has been confirmed normal for this patient by a GP</li>";
		} elseif ($objReview->ClinicalExam_RRConfirmedNormalByGP == "Abnormal") {
			echo " and a GP has confirmed this is abnormal, so the assessment was suspended, pending further investigations</li>";
		}
	} elseif ($objReview->ClinicalExam_RespiratoryRate >= 25) {
		echo "<li>Respiratory rate of " . $objReview->ClinicalExam_RespiratoryRate . " (breaths per minute) is raised, and this took the patient into an exacerbation assessment</li>";
	} elseif ($objReview->ClinicalExam_RespiratoryRate >= 50) {
		echo "<li>Respiratory rate of " . $objReview->ClinicalExam_RespiratoryRate . " (breaths per minute) is unusually high, and this took the patient into an exacerbation assessment";
		if ($objReview->ClinicalExam_RRConfirmedNormalByGP == "Normal") {
			echo " but it has been confirmed normal for this patient by a GP</li>";
		} elseif ($objReview->ClinicalExam_RRConfirmedNormalByGP == "Abnormal") {
			echo " and a GP has confirmed this is abnormal, so the assessment was suspended, pending further investigations</li>";
		}
	}
	
	if ($objReview->ClinicalExam_TracheaPos == "L") {		
		echo "<li>Patient's trachea position is deviated left</li>";
	} elseif ($objReview->ClinicalExam_TracheaPos == "R") {
		echo "<li>Patient's trachea position is deviated right</li>";
	}
	
	if ($objReview->ClinicalExam_Percussion == "U") {		
		echo "<li>Patient's percussion was unknown</li>";
	} elseif ($objReview->ClinicalExam_Percussion == "D") {
		echo "<li>Patient's percussion was dull (<i>" . $objReview->ClinicalExam_PercussionDullWhere . "</i>)</li>";
	}
	
	if ($objReview->ClinicalExam_BreathSounds == "U") {		
		echo "<li>Patient's breath sounds were unknown</li>";
	} elseif ($objReview->ClinicalExam_BreathSounds == "A" && ($objReview->ClinicalExam_Inspiratory == "Y" || $objReview->ClinicalExam_Crackles == "Y")) {
		echo "<li>Patient's breath sounds were abnormal:</li><ul>";
		if ($objReview->ClinicalExam_Inspiratory == "Y") {
			echo "<li>An inspiratory wheeze was observed, which indicates a possibility of inspiratory stridor,";
			if ($objReview->ClinicalExam_CxrOrdered == "Y") {
				echo " and a chest x-ray has been requested (details below)</li>";
			} elseif ($objReview->ClinicalExam_CxrOrdered == "N") {
				echo " but a chest x-ray has not been requested</li>";
			}
		}
		if ($objReview->ClinicalExam_Crackles == "Y") {
			echo "<li>Crackles were observed (<i>" . $objReview->ClinicalExam_CracklesWhere . "</i>)</li>";
		}
		echo "</ul>";
	}
	
	echo "</ul>";
	
	if (strlen($objReview->ClinicalExam_ChestOtherComments) >0) {		
		echo "<p>Other comments were noted:</p><p>" . nl2br($objReview->ClinicalExam_ChestOtherComments) . "</p>";
	}
	
	//Outcome
	if ($objReview->FirstAssessment_GPAuthorisation == "Y") {
		echo "<p>These clinical findings were not entirely normal but a doctor authorised that it was appropriate to continue with standard asthma assessment:</p><p>" . nl2br($objReview->FirstAssessment_GPAuthorisationDetails) . "</p>";
	} elseif ($objReview->FirstAssessment_GPAuthorisation == "N") {
		echo "<p>These clinical findings were not entirely normal and as no doctor has authorised that it was appropriate to continue, the assessment was terminated.</p>";
	}
	
}
		
		

    