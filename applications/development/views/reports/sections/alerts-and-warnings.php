<?php 

	$GreenColour  = "#00CC66";
	$AmberColour  = "#FFCC33";
	$RedColour =  "#FF3333";

	if ($objReview->TestsAndResults_FbcBloodEosinophila_Hb <= 9 && $objReview->TestsAndResults_FbcBloodEosinophila_Hb != null){
	
		echo "<h3>Other Alerts and Warnings</h3><p>"; 

		//Tests with abnormal results
		if ($objReview->TestsAndResults_FbcBloodEosinophila_Hb <= 9){
			echo "<span style=\"color:".$RedColour.";\">A haemaglobin test result from <b>". $objReview->TestsAndResults_FbcBloodEosinophilaDate ."</b> was recorded as <b>". $objReview->TestsAndResults_FbcBloodEosinophila_Hb ." g/dL</b>. This indicates a significant anaemia is present. Please arrange to investigate the anaemia appropriately.</span>";
		}
		
	}
	
	?></p>