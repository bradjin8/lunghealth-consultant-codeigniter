<?php

//Final Report

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


//Normal exit
if ($lastFlow == "1110" || $lastFlow == "2013") {

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
} elseif ($lastFlow == "3001" || $lastFlow == "4001" || $lastFlow == "4002" || $lastFlow == "4004" || $lastFlow == "4005") {
	
	$this->load->view('reports/exacerbation',array('objReview'=>$objReview,'arrModels'=>$arrModels));

//Early exit	
} else {
	
	$this->load->view('reports/summary',array('objReview'=>$objReview,'arrModels'=>$arrModels)); 

}
