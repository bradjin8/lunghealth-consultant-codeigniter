<h3>Personal Features</h3>
<p><?php echo ($objReview->InitialPatientDetails_Sex === 'M'? 'He':'She');
switch($objReview->Smoking_SmokingStatus) 
{ 
    case "Current": 
            echo " is a person who continues to smoke.";
            break;
    case "Recent": 
            echo " has recently (within last 6 months) stopped smoking.";
            break;
    case "Ex": 
            echo " is an ex-smoker."; 
            break;
    case "Never": 
            echo " is a non-smoker.";
            break;
}
?></p>
<p><?php
if( ($objReview->AssessmentDetails_AssessmentType === "FU" || $objReview->AssessmentDetails_AssessmentType === "AR" ) && $objReview->Smoking_SmokingStatus === "Current" ) 
    { 
	switch( $objReview->FirstAssessment_SmokingCessationReferral ) 
        {
		case "Y":
			echo ($objReview->InitialPatientDetails_Sex === 'M'? 'He':'She')." was referred for anti smoking support in the last consultation.";
                        break;
		case "N":
			echo ($objReview->InitialPatientDetails_Sex === 'M'? 'He':'She')." declined referral for anti smoking support.";
                        break;
	}
}
if( ($objReview->AssessmentDetails_AssessmentType === "FU" || $objReview->AssessmentDetails_AssessmentType === "AR" ) && $objReview->Smoking_SmokingStatus === "Recent" ) { 
	echo ($objReview->InitialPatientDetails_Sex === 'M'? 'He':'She')." stopped less than 6 months ago so may need more support.";
} ?></p>
<?php

