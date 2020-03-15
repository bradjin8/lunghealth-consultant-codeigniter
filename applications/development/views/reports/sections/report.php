<?php

//Final Report

echo $objReview->agcsystem_arrScreenHistory;

//$CI =& get_instance();
//$arrScreenHistory = $CI->session->userdata("arrScreenHistory");

$arrScreenHistory = implode($objReview->agcsystem_arrScreenHistory);

if($arrScreenHistory){
	end($arrScreenHistory);
	$penultimateScreen = prev($arrScreenHistory);
} else {
	$penultimateScreen = null;
}
	
//end($arrScreenHistory);
//$penultimateScreen = prev($arrScreenHistory);
//echo "<script>console.log('".$penultimateScreen."');</script>";

//Normal exit
if ($penultimateScreen == "NPRX3") {

	switch ($objReview->AssessmentDetails_AssessmentType)
	{
		case "1A":
			$this->load->view('reports/first_assessment',array('objReview'=>$objReview,'arrModels'=>$arrModels)); 
			break;
		case "AR":
			$this->load->view('reports/follow_up',array('objReview'=>$objReview,'arrModels'=>$arrModels));
			break;
		case "FU":
			$this->load->view('reports/follow_up',array('objReview'=>$objReview,'arrModels'=>$arrModels));
			break;
		default:
			echo "<p><strong>Review ID</strong>:".$objReview->intReviewID."</p>";
			break;
	}

//Exacerbation exit	
} elseif ($penultimateScreen == "EX0" || $penultimateScreen == "EX1" || $penultimateScreen == "EX2" || $penultimateScreen == "EX3" || $penultimateScreen == "EX4") {
	
	$this->load->view('reports/exacerbation',array('objReview'=>$objReview,'arrModels'=>$arrModels));

//Early exit	
} else {
	
	$this->load->view('reports/summary',array('objReview'=>$objReview,'arrModels'=>$arrModels)); 

}
