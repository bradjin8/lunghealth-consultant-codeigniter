<?php
	
	$SpirometryText = "";
	
	//PEF
	if($objReview->LungFunction_LungFunctionPerformed == "PEF Performed"){
	
		$SpirometryText .="<h3>Today's Peak Flow</h3><p>";
	
		$SpirometryText .="Today's PEF was recorded as <b>" 
                                        . number_format($objReview->PEF_CurrentPEF,0)
                                        . "</b> (L/min) which is <b>" 
                                        . number_format($objReview->PEF_CurrentPEFPctPredicted,0) . "%</b> of the patient's " 
                                        . strtolower($objReview->PEF_BestOrPredicted) . " value of <b>";
		
		if($objReview->PEF_BestOrPredicted == "Best"){
			$SpirometryText .=number_format($objReview->FirstAssessment_BestPEF,0) . "</b> (L/min)";
		} else {
			$SpirometryText .=number_format($objReview->FirstAssessment_PredictedPEFValue,0) . "</b> (L/min)";
		}
					
		$SpirometryText .="</p>";
	
	}
	
	//FEV1
	if($objReview->LungFunction_LungFunctionPerformed == "Spirometry performed"){
	
		$SpirometryText .="<h3>Today's Spirometry</h3><p>";
	
		$SpirometryText .="Today's FEV1 was recorded as <b>" . number_format($objReview->Spirometry_PostbronchodilatorFEV1,1) . "</b> litres which is <b>" . number_format($objReview->Spirometry_PercentPredictedFEV1,0) . "%</b> of the patient's predicted value of <b>" . number_format($objReview->FirstAssessment_PredictedFEVValue,1) . "</b> litres";
						
		$SpirometryText .="</p>";
	
	}
	
	echo $SpirometryText;
