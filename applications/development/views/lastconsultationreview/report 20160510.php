<?php

//Previous Report

$strScreenHistory = $objReview->agcsystem_arrScreenHistory;
$strScreenHistorySubstring = strrev( $strScreenHistory );
$intFirstDoubleQuote = strpos ( $strScreenHistorySubstring , "\"" );
$strScreenHistorySubstring = substr( $strScreenHistorySubstring , $intFirstDoubleQuote+1);
$intFirstDoubleQuote = strpos ( $strScreenHistorySubstring , "\"" );
$strLastScreen = strrev (substr( $strScreenHistorySubstring , 0 ,$intFirstDoubleQuote) );
//echo "<script>console.log('LAST SCREEN: ".$strLastScreen."');</script>";

if ($strLastScreen == "NPRX3") {
	
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
	
} elseif ($strLastScreen == "EX0" || $strLastScreen == "EX1" || $strLastScreen == "EX2" || $strLastScreen == "EX3" || $strLastScreen == "EX4") {

	$this->load->view('reports/exacerbation',array('objReview'=>$objReview,'arrModels'=>$arrModels));
	
	//aaargh this needs to be the previous "standard" consultation!!!
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
	
} else {
	
	$this->load->view('reports/summary',array('objReview'=>$objReview,'arrModels'=>$arrModels)); 
	
	//and this!
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

}
