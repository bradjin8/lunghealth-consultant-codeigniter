<?php

if ($objReview->InitialPatientDetails_EvaluateData == 'Y' && $objReview->InitialPatientDetails_Research == 'Y') {

	echo '<hr><p align="center"><small>Thankyou for agreeing that your data may be used anonymously for the purposes of evaluating the asthma service and for research.</small></p>';

} elseif ($objReview->InitialPatientDetails_EvaluateData == 'Y' && $objReview->InitialPatientDetails_Research == 'N') {
	
	echo '<hr><p align="center"><small>Thankyou for agreeing that your data may be used anonymously for the purposes of evaluating the asthma service. You have chosen that your data should not be used for any research process and that wish will be respected.</small></p>';
	
} elseif ($objReview->InitialPatientDetails_EvaluateData == 'N' && $objReview->InitialPatientDetails_Research == 'Y') {
	
	echo '<hr><p align="center"><small>Thankyou for agreeing that your data may be used anonymously for research purposes. You have chosen that your data should not be used for the purposes of evaluating the asthma service, and that wish will be respected.</small></p>';
	
} elseif ($objReview->InitialPatientDetails_EvaluateData == 'N' && $objReview->InitialPatientDetails_Research == 'N'){

	echo '<hr><p align="center"><small>You have chosen that your data should not be used for any research or evaluation process and that wish will be respected.</small></p>';
	
}

/*
Research question - 2753
Evaluation question - 2752

“Thankyou for agreeing that your data may be used anonymously for the purposes of evaluating the asthma service and for research”  or “You have chosen that your data should not be used for any research or evaluation process and that wish will be respected”
*/