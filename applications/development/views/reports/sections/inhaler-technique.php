<?php

echo "<h3>Other Checks and Advice</h3><p>";
// Inhaler technique

    if ($objReview->NonPharmaRx_InhalerTechniqueChecked == 'Y' || $objReview->NonPharmaRx_CheckInhalerTechniqueNow == 'Y') {
		echo 'Inhaler technique with current medication has been checked and ';
		if ($objReview->NonPharmaRx_InhalerTechniqueAdequate == 'Y') {
			echo 'was found to be <b>adequate</b> for current devices';
		} else {
			echo 'was initially found to be <b>inadequate</b> for current devices. ';
			if ($objReview->NonPharmaRx_TechniqueCorrected == 'Y' || $objReview->NonPharmaRx_ChangeDevice == 'Y' || $objReview->NonPharmaRx_AddSpacer == 'Y' || $objReview->NonPharmaRx_Other == 'Y') {
				echo 'The following solutions were chosen:</p><ul>';
				if($objReview->NonPharmaRx_TechniqueCorrected == 'Y') {
				echo '<li>The inhaler technique has been corrected.</li>';
				}
				if($objReview->NonPharmaRx_ChangeDevice == 'Y') {
					echo '<li>The inhaler device has been changed.</li>';			
				}
				if($objReview->NonPharmaRx_AddSpacer == 'Y' && $objReview->NonPharmaRx_SpacerAdded == 'Y') {
					echo '<li>A spacer has been added.</li>';		
				}
				if($objReview->NonPharmaRx_Other == 'Y') {
					echo '<li>The solution stated is <i>'.$objReview->NonPharmaRx_Details.'.</i></li>';			
				}
				echo '</ul><p>';
			} else {
				echo '<b>No solutions were chosen.</b></p>';
			}
			if($objReview->NonPharmaRx_TechniqueSatisfactory == 'Y') {
				echo 'The technique is now considered <b>satisfactory.</b>';
			} else {
				echo 'The technique still <b>not</b> satisfactory and a referral has ';
				if ($objReview->FirstAssessment_SecondaryCareAsthmaReferral == 'Y') {
					echo 'been made';
					if ($objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails != '') {
						echo ' with the details: <i>'.$objReview->FirstAssessment_SecondaryCareAsthmaReferralDetails.'.</i></p>';
					} else {
						echo '.</p>';
					}
				} else {
					echo '<b>not</b> been made.</p>';
				}
			}
		}
	} else {		
		echo 'Inhaler technique with current medication has <b>not</b> been checked. Checking inhaler technique is a requirement for QOF. Please ensure that inhaler technique is checked at a future date as it has not been checked today.</p>';
	}
	



/*echo "<p>";
    if ($objReview->NonPharmaRx_InhalerTechniqueChecked === 'Y') 
    {

		if ($objReview->NonPharmaRx_InhalerTechniqueAdequate === 'Y') {
			echo 'Inhaler technique with current medication has been checked and was fine.';
		} else {
			
			echo 'Inhaler technique with current medication has been checked and was not adequate. ';
			
			if($objReview->NonPharmaRx_TechniqueCorrected === 'Y') {
				echo 'The inhaler technique has been corrected. ';
			}
			if($objReview->NonPharmaRx_ChangeDevice === 'Y') {
				echo 'The inhaler device has been changed. ';			
			}
			if($objReview->NonPharmaRx_AddSpacer=== 'Y') {
				echo 'A spacer has been added. ';			
			}
			if($objReview->NonPharmaRx_Other == 'Y') {
				echo 'The solution stated is '.$objReview->NonPharmaRx_Details.'.';			
			}

			if($objReview->NonPharmaRx_TechniqueSatisfactory === 'Y') {
				echo 'The technique is now satisfactory.';
			}
			if($objReview->NonPharmaRx_TechniqueSatisfactory === 'N') {
				echo 'The technique still not satisfactory.';
			}
			
		}

    }
echo "</p>";*/