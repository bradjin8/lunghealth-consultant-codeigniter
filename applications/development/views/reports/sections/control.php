<h3>Control</h3>

<?php

			$UpArrow = "&uArr";
            $DownArrow = "&dArr";

            $GoodColour  = "#00CC66";
            $PartialColour  = "#FFCC33";
            $PoorColour =  "#FF3333";

            if($FirstAssessment_FastOrStandardTrack == "Standard Track"){
                    $UsedControl = $FirstAssessment_CurrentControl45;
            } else {
                    $UsedControl = $FirstAssessment_CurrentControl13;
            }	
			
            $RCP = ((int)($CurrentControl_DifficultySleeping == "Y")+
                            (int)($CurrentControl_UsualAsthmaSymptoms == "Y") +
                            (int)($CurrentControl_InterferedWithUsualActivities == "Y"));

            switch ($RCP)
            {
                    case 0:
                            $RCP_GPP = $GoodColour;
                            break;
                    case 1:
                            $RCP_GPP = $PartialColour;
                            break;
                    case 2:
                            $RCP_GPP = $PoorColour;
                            break;
                    case 3:
                            $RCP_GPP = $PoorColour;
                            break;
            default:
                        $RCP_GPP = "NOT USED";
                break;
            }

            switch ($CurrentControl_FrequencyRelieverInhalerLastWeek) {
                    case "NA":
                            $RelieverInhalerGPP = "NA";
                            $RelieverInhalerTimesMessage = "NA";
                            break;
                    case "0":
                            $RelieverInhalerGPP = $GoodColour;
                            $RelieverInhalerTimesMessage = "no times";
                            break;
                    case "1-2":
                            $RelieverInhalerGPP = $PartialColour;
                            $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek." times";
                            break;
                    case "3-4":
                            $RelieverInhalerGPP = $PoorColour;
                            $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek." times";
                            break;
                    case "5+":
                            $RelieverInhalerGPP = $PoorColour;
                            $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek." times";
                            break;	
            default:
                            $RelieverInhalerGPP = "NOT ASKED";
                            $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek."NOT ASKED";
            break;
            }

            $ExacerbationsLast12Months = ($CurrentControl_Last12MonthsOralSteroidsYesNo == "Y" || 
                                                                            $CurrentControl_Last12MonthsAntibioticsYesNo == "Y" ||
                                                                            $CurrentControl_Last12MonthsCallOutYesNo == "Y" ||
                                                                            $CurrentControl_Last12MonthsAedOrHospYesNo == "Y");


            $ExacerbationsLast3Months =  ($CurrentControl_Last12MonthsOralSteroidsLast3Months == "Y" || 
                                                                            $CurrentControl_Last12MonthsAntibioticsLast3Months == "Y" ||
                                                                            $CurrentControl_Last12MonthsCallOutLast3Months == "Y" ||
                                                                            $CurrentControl_Last12MonthsAedOrHospLast3Months == "Y");

            /*switch ($LungFunction_LungFunctionPerformed) 
            {
                    case "Spirometry performed":
                            $LfPercent = $Spirometry_PercentPredictedFEV1 ;
                            $LfPerformed = "FEV1" ;
                            break;
                    case "PEF Performed":
                            $LfPercent = $PEF_CurrentPEFPctPredicted;
                            $LfPerformed = "PEF";
                            break;
                    default:
                            $LfPercent = "0";
                            $LfPerformed = "NONE";
            }*/
			
			if($FirstAssessment_FastOrStandardTrack == "Standard Track" || $AssessmentDetails_AssessmentType != "1A"){
				switch ($LungFunction_LungFunctionPerformed) 
				{
						case "Spirometry performed":
								$LfPercent = $Spirometry_PercentPredictedFEV1 ;
								$LfPerformed = "FEV1" ;
								break;
						case "PEF Performed":
								$LfPercent = $PEF_CurrentPEFPctPredicted;
								$LfPerformed = "PEF";
								break;
						default:
								$LfPercent = "0";
								$LfPerformed = "NONE";
				}
			} elseif ($PEF_CurrentPEFPctPredicted > 0) {
				$LfPercent = $PEF_CurrentPEFPctPredicted;
				$LfPerformed = "PEF";
			} else {
				$LfPercent = "0";
				$LfPerformed = "NONE";
			}	
			
			/*Is this bit still necessary or is it handled accurately above?*/
			/*CR 20160310 - Added "&& $PEF_CurrentPEFPctPredicted > 0" as this didn't work when on no medication or early exit*/
            if($LfPerformed == "NONE" && $FirstAssessment_FastOrStandardTrack == "Fast Track" && $PEF_CurrentPEFPctPredicted > 0){
                    $LfPercent = $PEF_CurrentPEFPctPredicted;
                    $LfPerformed = "PEF";
            }
			
            if($CurrentControl_AsthmaControlScore < 15){
                    $ACT_GPP = $PoorColour;
            } elseif ($CurrentControl_AsthmaControlScore < 20) {
                    $ACT_GPP = $PartialColour;
            } else {
                    $ACT_GPP = $GoodColour;
            }


            if($LfPercent > 80){
                    $Lf_GPP = $GoodColour;
            } elseif ($LfPercent > 65) {
                    $Lf_GPP = $PartialColour;
            } else {
                    $Lf_GPP = $PoorColour;
            }

			if($RCP > 0){
                    $RCP_LowHigh = "high is poor";
            } else {
                    $RCP_LowHigh = "low is good";
            }
			
			
            // RCP
            $RCP_Message = "The Royal College of Physicians Three Questions - ".$RCP."/3 (".$RCP_LowHigh.")";

            // ACT
            $ACT_Message = "Score of ".$CurrentControl_AsthmaControlScore."/25 (high is good) on the Asthma Control Test";

            // Exacerbations 
            if($ExacerbationsLast3Months == TRUE) {
                    $ExacMessage = "The patient has had an exacerbation in the last 3 months";
                    $ExacGPP = $PoorColour;
            } elseif ($ExacerbationsLast12Months == TRUE){
                    $ExacMessage = "The patient has had an exacerbation in the last 12 months, but not in the last 3 months";
                    $ExacGPP = $PartialColour;		
            } else {
                    $ExacMessage = "The patient has had no exacerbations in the last 12 months";
                    $ExacGPP = $GoodColour;		
            }
			
			$ExacDetailsMessage = "";
			
			if ($ExacerbationsLast3Months == TRUE || $ExacerbationsLast12Months == TRUE) {
				$ExacDetailsMessage = " and is described as <br><i>" .  nl2br($objReview->CurrentControl_Last12MonthsAedOrHospDetails) . "<i>";
			}

            // No reliever inhalations in previous week //$RelieverInhalerGPP; //$CurrentControl_FrequencyRelieverInhalerLastWeek;
            $RelieverInhalerMessage = "The patient used their reliever inhaler ".$RelieverInhalerTimesMessage." last week";

        // PEF or FEV1 <= 65% Predicted or Best
            $LungFuncMessage = "The patient's ".$LfPerformed." is ".$LfPercent."% of expected";

            $messageHTML = "<p>Overall control has been assessed as <b>".strtolower($UsedControl)."</b>. Their control is based on the following:</p>";

            /// Start of table
            $messageHTML = $messageHTML."<table class=\"control_table\">";

            // RCP
            $messageHTML = $messageHTML."<tr>";
            $messageHTML = $messageHTML."<td width=\"10%\">";
            $messageHTML = $messageHTML."<span style=\"color:".$RCP_GPP.";\">&bull;</span>";
            $messageHTML = $messageHTML."</td>";
            $messageHTML = $messageHTML."<td width=\"90%\">";
            $messageHTML = $messageHTML.$RCP_Message;
            $messageHTML = $messageHTML."</td>";
            $messageHTML = $messageHTML."</tr>";

            // ACT
            if($FirstAssessment_FastOrStandardTrack == "Standard Track"){
                    $messageHTML = $messageHTML."<tr>";
                    $messageHTML = $messageHTML."<td width=\"10%\">";
                    $messageHTML = $messageHTML."<span style=\"color:".$ACT_GPP.";\">&bull;</span>";
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."<td width=\"90%\">";
                    $messageHTML = $messageHTML.$ACT_Message;
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."</tr>";
            }

            // Exacerbations
            $messageHTML = $messageHTML."<tr>";
            $messageHTML = $messageHTML."<td width=\"10%\">";
            $messageHTML = $messageHTML."<span style=\"color:".$ExacGPP.";\">&bull;</span>";
            $messageHTML = $messageHTML."</td>";
            $messageHTML = $messageHTML."<td width=\"90%\">";
            $messageHTML = $messageHTML.$ExacMessage.$ExacDetailsMessage;
            $messageHTML = $messageHTML."</td>";
            $messageHTML = $messageHTML."</tr>";


            // Reliever inhaler
            if($RelieverInhalerGPP != "NOT ASKED" && $RelieverInhalerGPP != "NA"){	
                    $messageHTML = $messageHTML."<tr>";
                    $messageHTML = $messageHTML."<td width=\"10%\">";
                    $messageHTML = $messageHTML."<span style=\"color:".$RelieverInhalerGPP.";\">&bull;</span>";
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."<td width=\"90%\">";
                    $messageHTML = $messageHTML.$RelieverInhalerMessage;
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."</tr>";	
            }

            // Lung Function
            if($LfPerformed != "NONE"){	
                    $messageHTML = $messageHTML."<tr>";
                    $messageHTML = $messageHTML."<td width=\"10%\">";
                    $messageHTML = $messageHTML."<span style=\"color:".$Lf_GPP.";\">&bull;</span>";
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."<td width=\"90%\">";
                    $messageHTML = $messageHTML.$LungFuncMessage;
                    $messageHTML = $messageHTML."</td>";
                    $messageHTML = $messageHTML."</tr>";	
            }

            $messageHTML = $messageHTML."</table>";
			
			//Puts into blue alert box - experimental
			//$messageHTML = "<div class=\"alert alert-info\" role=\"alert\">" . $messageHTML . "</div>";
			
			echo $messageHTML;

