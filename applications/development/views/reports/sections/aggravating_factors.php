<h3>Aggravating Factors</h3>
<?php

$strHeOrShe = ($objReview->InitialPatientDetails_Sex === 'M'? 'he':'she');
$strHisOrHer = ($objReview->InitialPatientDetails_Sex === 'M'? 'his':'her');

//Standard Track Version
if($objReview->FirstAssessment_FastOrStandardTrack === 'Standard Track') {

	//Chest Cold and exercise
	echo "<h4>Chest cold and exercise</h4>";
	
	if($objReview->FirstAssessment_ChestCold == "Always"){
		echo "<p>".ucfirst($strHeOrShe)." reports that ".$strHisOrHer." asthma is worse with a cold";
	} else {
		echo "<p>".ucfirst($strHeOrShe)." reports that ".$strHisOrHer." asthma is not worse with a cold";
	}
	
	if($objReview->FirstAssessment_ExertionWorseActiveAfter == "Active" || $objReview->FirstAssessment_ExertionWorseActiveAfter == "After" ){ // "Active","After","Neither"
		
		if($objReview->FirstAssessment_ExertionInhalerReponse == "Y"){
		 $doesDoesNotExertionInhalerReponse= "does";
		} else {
		 $doesDoesNotExertionInhalerReponse= "does not";
		}
		
		echo " and that its aggravated by exercise which ".$doesDoesNotExertionInhalerReponse." respond to an inhaler.</p>";
		
	} else {
		echo ".</p>";
	}
	
	//Atopy
	echo "<h4>Atopy</h4>";
	
	if($objReview->FirstAssessment_HayFever  == "Y"){
		if($objReview->FirstAssessment_HayFeverInterference == "Y"){
		 $doesDoesNotHayFeverInterference = "and thinks it interferes with ".$strHisOrHer." asthma.</p>";
		} else {
		 $doesDoesNotHayFeverInterference = "but does not think it interferes with ".$strHisOrHer." asthma.</p>" ;
		}
		echo "<p>".ucfirst($strHeOrShe)." has hayfever/rhinitis ".$doesDoesNotHayFeverInterference; // Nasal steroid needs adding
	} else {
		echo "<p>".ucfirst($strHeOrShe)." does not have hayfever/rhinitis.</p>";
	}
	
	if($objReview->FirstAssessment_FamilyHistory == "Y"){
		echo "<p>There is a family history of atopy: <i>" . nl2br($objReview->FirstAssessment_FamilyHistoryDetails) . "</i></p>";
	}

	//Employment
	echo "<h4>Employment</h4>";
	
	if($objReview->FirstAssessment_EmploymentStatus == "Employed") {//"Employed","Education","Unemployed","Illness","Retired","Carer"

		if($objReview->FirstAssessment_EmploymentAtRisk == null || $objReview->FirstAssessment_EmploymentAtRisk == "None"){
		 $jobName = $objReview->FirstAssessment_EmployedDetails;
		 echo "<p>".ucfirst($strHeOrShe)." is currently working as a ".strtolower($jobName);
		} else {
		 $jobName = $objReview->FirstAssessment_EmploymentAtRisk;
		 echo "<p>".ucfirst($strHeOrShe)." is currently working in the  ".strtolower($jobName)." industry, known to be high risk for asthma,";
		}
		
		if($objReview->FirstAssessment_EmployedChestAffect == "N"){
			echo " but does not think work has any effect on ".$strHisOrHer." asthma</p>";
		} elseif ($objReview->FirstAssessment_EmployedChestAffect == "Y"){
			echo " and there is concern that work may affect ".$strHisOrHer." asthma.";

			if($objReview->FirstAssessment_EmployedChestDetails != null){
				echo " The details given are: <i>".$objReview->FirstAssessment_EmployedChestDetails.".</i></p>";
			} else {
				echo "</p>";
			}
		} else {
			echo "ERROR EmployedChestAffect</p>";
		}
	
	} elseif($objReview->FirstAssessment_EmploymentStatus == "Education"){
		echo "<p>".ucfirst($strHeOrShe)." is currently in full time education.</p>";
	} elseif($objReview->FirstAssessment_EmploymentStatus == "Unemployed"){
		echo "<p>".ucfirst($strHeOrShe)." is not working but looking for work as a ".$objReview->FirstAssessment_UnemployedTypeOfWork.".</p>";
	} elseif($objReview->FirstAssessment_EmploymentStatus == "Illness"){

		if($objReview->FirstAssessment_IllnessBenefits == "Y"){
		 $IsIsNotIllnessBenefits= "is";
		} else {
		 $IsIsNotIllnessBenefits= "is not";
		}

		echo "<p>".ucfirst($strHeOrShe)." is not working because of illness and ".$IsIsNotIllnessBenefits." not dependent on benefits.</p>";
		
	} elseif($objReview->FirstAssessment_EmploymentStatus == "Retired"){
		echo "<p>".ucfirst($strHeOrShe)." is not looking for work or retired.</p>";
	} elseif($objReview->FirstAssessment_EmploymentStatus == "Carer"){
		echo "<p>".ucfirst($strHeOrShe)." is not currently working as ".$strHeOrShe." is a parent or carer.</p>";
	}
	
	//Work related factors
	if (strlen($objReview->FirstAssessment_WorkRelatedFactors) > 0) {
		echo "<p>";
		if($objReview->FirstAssessment_WorkRelatedFactors === "N"){
				echo "No work related factors have been found.";
		} elseif($objReview->FirstAssessment_WorkRelatedFactors === "Y"){
			echo "A possible work related factor has been noted";
 
			if($objReview->FirstAssessment_WorkRelatedReferral === "Y"){
				echo " and advice is being sought from a specialist clinic. (<i>" . $objReview->FirstAssessment_WorkRelatedReferralDetails . "</i>).";
			} else {
				echo ".";
			}
		}
		echo "</p>";
	}

	//Other Medical Conditions
	echo "<h4>Other Medical Conditions</h4>";

	if ($objReview->FirstAssessment_OtherMedicalConditions == "Y") {
		echo "<p>Other medical conditions have been noted:<ul>";
		if ($objReview->FirstAssessment_Bronchiectasis == "Y") {echo "<li>Bronchiectasis</li>";}
		if ($objReview->FirstAssessment_COPD == "Y") {echo "<li>COPD</li>";}
		if ($objReview->FirstAssessment_Hyperventilation == "Y") {echo "<li>Hyperventilation</li>";}
		if ($objReview->FirstAssessment_VocalCordDysfunction == "Y") {echo "<li>Vocal Cord Dysfunction</li>";}
		if ($objReview->FirstAssessment_Tumour == "Y") {echo "<li>Tumour</li>";}
		if ($objReview->FirstAssessment_ForeignBodyInAirway == "Y") {echo "<li>Foreign Body In Airway</li>";}
		if ($objReview->FirstAssessment_InterstitialLungDisease == "Y") {echo "<li>Interstitial Lung Disease</li>";}
		if ($objReview->FirstAssessment_PulmonaryEmbolism == "Y") {echo "<li>Pulmonary Embolism</li>";}
		if ($objReview->FirstAssessment_Aspiration == "Y") {echo "<li>Aspiration</li>";}
		if ($objReview->FirstAssessment_Hypertension == "Y") {echo "<li>Hypertension</li>";}
		if ($objReview->FirstAssessment_HeartDisease == "Y") {echo "<li>Heart Disease</li>";}
		if ($objReview->FirstAssessment_HeartFailure == "Y") {echo "<li>Heart Failure</li>";}
		if ($objReview->FirstAssessment_Diabetes == "Y") {echo "<li>Diabetes</li>";}
		if ($objReview->FirstAssessment_MusculoskeletalDisease == "Y") {echo "<li>Musculoskeletal Disease</li>";}
		if ($objReview->FirstAssessment_GastricReflux == "Y") {echo "<li>Gastric Reflux</li>";}
		echo "</ul><p>";
		
		echo "<p>Further details: <i>" . nl2br($objReview->FirstAssessment_OtherMedicalConditionsDetails) . "</i></p>";
		
	} elseif ($objReview->FirstAssessment_OtherMedicalConditions == "N") {
	
		echo "<p>No other medical conditions have been reported</p>";
	
	}
		
//Standard Track Version	
} elseif ($objReview->FirstAssessment_FastOrStandardTrack === 'Fast Track') {

	//Work related factors
	echo "<h4>Employment</h4>";
	
	if (strlen($objReview->FirstAssessment_WorkRelatedFactors) > 0) {
		echo "<p>";
		if($objReview->FirstAssessment_WorkRelatedFactors === "N"){
				echo "No work related factors have been found.";
		} elseif($objReview->FirstAssessment_WorkRelatedFactors === "Y"){
			echo "A possible work related factor has been noted";
 
			if($objReview->FirstAssessment_WorkRelatedReferral === "Y"){
				echo " and advice is being sought from a specialist clinic. (<i>" . $objReview->FirstAssessment_WorkRelatedReferralDetails . "</i>).";
			} else {
				echo ".";
			}
		}
		echo "</p>";
	}
	 
	
}

//Shared features

//Triggers
echo "<h4>Triggers</h4>";

if ($objReview->FirstAssessment_TriggersFumesPerfumes === "Y" ||
	$objReview->FirstAssessment_TriggersPets === "Y" ||
	$objReview->FirstAssessment_TriggersPassiveSmoking === "Y" ||
	$objReview->FirstAssessment_TriggersSeasons === "Y" ||
	$objReview->FirstAssessment_TriggersPollen === "Y" ||
	$objReview->FirstAssessment_TriggersDustMites === "Y" ||
	$objReview->FirstAssessment_TriggersMould === "Y" ||
	$objReview->FirstAssessment_TriggersOther === "Y"){

	echo "<p>".ucfirst($strHeOrShe)." describes a number of specific  triggers to ".$strHisOrHer." asthma:</p><ul>";
	
	if ($objReview->FirstAssessment_TriggersFumesPerfumes === "Y"){echo "<li>Fumes / Perfumes</li>";}
	if ($objReview->FirstAssessment_TriggersPets === "Y"){echo "<li>Pets / animals (<i>" . $objReview->FirstAssessment_TriggersPetsDetails . "</i>)</li>";}
	if ($objReview->FirstAssessment_TriggersPassiveSmoking === "Y"){echo "<li>Passive smoking</li>";}
	if ($objReview->FirstAssessment_TriggersSeasons === "Y"){echo "<li>Certain seasons (<i>" . $objReview->FirstAssessment_TriggersSeasonsDetails . "</i>)</li>";}
	if ($objReview->FirstAssessment_TriggersPollen === "Y"){echo "<li>Pollen</li>";}
	if ($objReview->FirstAssessment_TriggersDustMites === "Y"){echo "<li>Dust mites</li>";}
	if ($objReview->FirstAssessment_TriggersMould === "Y"){echo "<li>Mould</li>";}
	if ($objReview->FirstAssessment_TriggersOther === "Y"){echo "<li>Other (<i>" . $objReview->FirstAssessment_TriggersOtherDetails . "</i>)</li>";}
		
	echo "</ul>";
	
	} else {
		echo "<p>No triggers have been identified.</p>";    
	}
	




