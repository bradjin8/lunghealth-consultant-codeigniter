<?php

//Find last flow
preg_match_all('/{(.*?)}/', $objReview->agcsystem_arrFlows, $matches);

$matchesString = $matches[1][0];

if(substr($matchesString,-1) == ";"){
	$matchesString = substr($matchesString,0,-1);
}

$arrFlows = explode(";",$matchesString);

if($arrFlows){
	
	$lastFlow = end($arrFlows);
	if( explode(":",$lastFlow)[1] == '20000'){
		prev($arrFlows);
		$lastFlow = prev($arrFlows);
	}

} else {
	$lastFlow = null;
}

$lastFlow = explode(":",$lastFlow)[1];

//Report
if (true) {
	
	echo '<h3>QOF Codes Reported</h3>';
	
	//Table start
	$TableHTML = "<table class=\"qof_table\">";
	$TableHTML .= "<tr><th width=\"20%\"></th><th width=\"40%\"></th><th width=\"15%\">5-Byte v2</th><th width=\"15%\">CTV3</th><th width=\"10%\">Value</th></tr>";
	
	//Asthma Register - AST001
	if (true) {
		$AST001_rubric = "";
		$AST001_ctv2 = "";
		$AST001_ctv3 = "";
		
		//Currently assumed on register
		if (	(($lastFlow == "1110" || $lastFlow == "2013") && $objReview->Therapy_CheckOnNoTherapy != "NeverOnTherapy" && $objReview->Therapy_CheckOnNoTherapy != "JustFinishingTherapy")
			||	$objReview->QOF_RemainOnRegister == "Y"
			) {
			$AST001_rubric = "Asthma";
			$AST001_ctv2 = "H33";
			$AST001_ctv3 = "H33..";
		} elseif (	($objReview->Therapy_CheckOnNoTherapy == "NeverOnTherapy" || $objReview->Therapy_CheckOnNoTherapy == "JustFinishingTherapy")
				&&	$objReview->QOF_RemainOnRegister == "N"	
			) {
			$AST001_rubric = "Asthma resolved";
			$AST001_ctv2 = "212G";
			$AST001_ctv3 = "21262";
		}
		
		$TableHTML .= "<tr><td><b>Asthma Register</b></td><td>" . $AST001_rubric . "</td><td>" . $AST001_ctv2 . "</td><td>" . $AST001_ctv3 . "</td><td></td></tr>";
		
	}
	
	//Reversibility - AST002
	if (true) {
		$AST002_rubric = "";
		$AST002_ctv2 = "";
		$AST002_ctv3 = "";
		$AST002_value = "";
		
		if ($objReview->LungFunction_LungFunctionPerformed == "Spirometry performed") {
			if ($objReview->Spirometry_RecentBronchodilators == "Y") {
				$AST002_rubric .= "Peak flow rate after bronchodilation<br>";
				$AST002_ctv2 .= "339B<br>";
				$AST002_ctv3 .= "XaEGA<br>";
				$AST002_value .= $objReview->Spirometry_PostbronchodilatorFEV1 . "<br>";
			} elseif ($objReview->Spirometry_RecentBronchodilators == "N") {
				if ($objReview->Spirometry_PrebronchodilatorFEV1) {
					$AST002_rubric .= "Peak flow rate before bronchodilation<br>";
					$AST002_ctv2 .= "339A<br>";
					$AST002_ctv3 .= "XaEHe<br>";
					$AST002_value .= $objReview->Spirometry_PrebronchodilatorFEV1 . "<br>";
				}
				if ($objReview->Spirometry_PostbronchodilatorFEV1) {
					$AST002_rubric .= "Peak flow rate after bronchodilation<br>";
					$AST002_ctv2 .= "339B<br>";
					$AST002_ctv3 .= "XaEGA<br>";
					$AST002_value .= $objReview->Spirometry_PostbronchodilatorFEV1 . "<br>";
				}
			}
		} elseif (	$objReview->LungFunction_LungFunctionPerformed == "PEF Performed"
				|| (($objReview->AssessmentDetails_AssessmentType == "1A" || $objReview->AssessmentDetails_AssessmentType == "EX") && ($objReview->PEF_CurrentPEF))
				) {
			$AST002_rubric .= "Peak flow rate before bronchodilation<br>";
			$AST002_ctv2 .= "339A<br>";
			$AST002_ctv3 .= "XaEHe<br>";
			$AST002_value .= $objReview->PEF_CurrentPEF . "<br>";
		}
		
		$TableHTML .= "<tr><td><b>Reversibility</b></td><td>" . $AST002_rubric . "</td><td>" . $AST002_ctv2 . "</td><td>" . $AST002_ctv3 . "</td><td>" . $AST002_value . "</td></tr>";
		
	}
	
	//RCP 3 Questions - AST003
	if (true) {
		$AST003_rubric = "";
		$AST003_ctv2 = "";
		$AST003_ctv3 = "";
		
		//Q1
		if ($objReview->CurrentControl_DifficultySleeping == "Y") {
			$AST003_rubric .= "Asthma disturbing sleep<br>";
			$AST003_ctv2 .= "663N<br>";
			$AST003_ctv3 .= "663N.<br>";
		} elseif ($objReview->CurrentControl_DifficultySleeping == "N") {
			$AST003_rubric .= "Asthma not disturbing sleep<br>";
			$AST003_ctv2 .= "663O<br>";
			$AST003_ctv3 .= "663O.<br>";
		}
		
		//Q2
		if ($objReview->CurrentControl_UsualAsthmaSymptoms == "Y") {
			$AST003_rubric .= "Asthma daytime symptoms<br>";
			$AST003_ctv2 .= "663q<br>";
			$AST003_ctv3 .= "XaIIZ<br>";
		} elseif ($objReview->CurrentControl_UsualAsthmaSymptoms == "N") {
			$AST003_rubric .= "Asthma never causes daytime symptoms<br>";
			$AST003_ctv2 .= "663s<br>";
			$AST003_ctv3 .= "XaINa<br>";
		}
		
		//Q3
		if ($objReview->CurrentControl_InterferedWithUsualActivities == "Y") {
			$AST003_rubric .= "Asthma restricts exercise<br>";
			$AST003_ctv2 .= "663e<br>";
			$AST003_ctv3 .= "663e.<br>";
		} elseif ($objReview->CurrentControl_InterferedWithUsualActivities == "N") {
			$AST003_rubric .= "Asthma never restricts exercise<br>";
			$AST003_ctv2 .= "663f<br>";
			$AST003_ctv3 .= "663f.<br>";
		}

		$TableHTML .= "<tr><td><b>RCP 3 Questions</b></td><td>" . $AST003_rubric . "</td><td>" . $AST003_ctv2 . "</td><td>" . $AST003_ctv3 . "</td><td></td></tr>";
		
	}
	
	//Smoking - AST004
	if ($objReview->AssessmentDetails_AssessmentType != "EX") {
		$AST004_rubric = "";
		$AST004_ctv2 = "";
		$AST004_ctv3 = "";
		
		if ($objReview->Smoking_SmokingStatus == "Current") {
			$AST004_rubric .= "Current smoker<br>";
			$AST004_ctv2 .= "137R<br>";
			$AST004_ctv3 .= "137R.<br>";
		} elseif ($objReview->Smoking_SmokingStatus == "Never") {
			$AST004_rubric .= "Never smoked<br>";
			$AST004_ctv2 .= "1371<br>";
			$AST004_ctv3 .= "XE0oh<br>";
		} elseif ($objReview->Smoking_SmokingStatus == "Recent") {
			$AST004_rubric .= "Recent ex-smoker (stopped within last 6 months)<br>";
			$AST004_ctv2 .= "137K0<br>";
			$AST004_ctv3 .= "XaQzw<br>";
		} elseif ($objReview->Smoking_SmokingStatus == "Ex") {
			$AST004_rubric .= "Ex-smoker (stopped more than 6 months)<br>";
			$AST004_ctv2 .= "137S<br>";
			$AST004_ctv3 .= "Ub1na<br>";
		}
		
		$TableHTML .= "<tr><td><b>Smoking</b></td><td>" . $AST004_rubric . "</td><td>" . $AST004_ctv2 . "</td><td>" . $AST004_ctv3 . "</td><td></td></tr>";
		
	}
	
	//Table end
	$TableHTML .= "</table>";
	
	echo $TableHTML;
	
}

    