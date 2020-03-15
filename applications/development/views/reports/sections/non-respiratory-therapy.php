
<?php


//$other_drugs_model = $arrModels['Otherdrugs_model'];

if (	$objReview->CurrentMedication_CurrentOtherDrugsNS == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsNSAID == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsBB == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsA == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsD == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsBP == "Y"
	||	$objReview->CurrentMedication_CurrentOtherDrugsOther == "Y"
	){

	echo "<h3>Non-Respiratory Therapy</h3></p>";
	echo "<p>Patient is currently taking the following non-respiratory medication:</p><p><ul>";

	$arrOtherDrugs = $objReview->arrOtherDrugs;
		foreach ($arrOtherDrugs as $arrDrug) {
			echo "<li>".$arrDrug["OtherDrugsType"]." - ".$arrDrug["OtherDrugsName"]."</li>";
		}

	
	/*if ($objReview->CurrentMedication_CurrentOtherDrugsNS == "Y") {
		echo "<li>Nasal Steroid</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsNSAID == "Y") {
		echo "<li>NSAID</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsBB == "Y") {
		echo "<li>Beta-blocker</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsA == "Y") {
		echo "<li>Antibiotics</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsD == "Y") {
		echo "<li>Diuretic</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsBP == "Y") {
		echo "<li>Bone Prophylaxis</li>";
	}
	if ($objReview->CurrentMedication_CurrentOtherDrugsOther == "Y") {
		echo "<li>Other</li>";
	}*/
	
	echo "</ul></p>";
	
} 