<p><?php 
	
	$CurrentMedicationLevel = '';
	if($objReview->CurrentMedication_CurrentMedicationLevel != '') {
		$CurrentMedicationLevel = $objReview->CurrentMedication_CurrentMedicationLevel;
	} else {
		$CurrentMedicationLevel = $objReview->CurrentMedicationLastVisit_CurrentMedicationLevel;
	}	
	
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	    
	echo "<b>Consultation Completed: " . $objReview->AssessmentDetails_ConsultationEndDate . "</b></p><p>";
	
    echo $objReview->PatientDetails_PatientDetailsTitle; 
    
    ?> <?php echo $objReview->InitialPatientDetails_Surname; 
    ?> is <?php echo (substr($objReview->InitialPatientDetails_Age,0,1) === '8' ? 'an':'a' ); 
    ?> <?php echo $objReview->InitialPatientDetails_Age; 
	?> year old <?php echo ($objReview->InitialPatientDetails_Sex === 'M'? 'man':'woman');
	?> <?php 		
		
		if  ($objReview->FirstAssessment_SymptomDuration === "Since Childhood") { 
			echo " who has had asthma since childhood"; 				
		} elseif ( $objReview->FirstAssessment_AsthmaRegister2006 ==="Y") { 
			echo " who has had asthma since before 2006"; 				
		} elseif ($objReview->FirstAssessment_SymptomDuration ==="Last Few Years (>10)") { 
			echo " who has had asthma for less than ten years"; 				
		}
		
	?> and <?php 
	
		if ($CurrentMedicationLevel === '0') { 
			echo "has been on no asthma treatment.";
		} else {
            //TODO: For Step Title
            $StepTitle = "";
            switch ($CurrentMedication_CurrentMedicationLevel) {
                case "1":
                    $StepTitle = "As Required Reliever Therapy";
                    break;
                case "2":
                    $StepTitle = "Regular Preventer Therapy";
                    break;
                case "3":
                    $StepTitle = "Initial Addon-Therapy";
                    break;
                case "4":
                    $StepTitle = "Additional Controller Therapies";
                    break;
                case "5":
                case "6":
                    $StepTitle = "Specialist Therapies";
                    break;
                default:
                    $StepTitle = "Unknown Step";
                    break;
            }


            echo "has been taking treatment equivalent to <b> ".$StepTitle."</b> of the BTS / SIGN Asthma Guidelines.";
		}

	echo " This is " . ($objReview->InitialPatientDetails_Sex === 'M'? 'his ':'her ');
  
	if (($objReview->PatientDetails_ConsultationNumber %100) >= 11 && ($objReview->PatientDetails_ConsultationNumber%100) <= 13){
		echo "<b>" . $objReview->PatientDetails_ConsultationNumber . "<sup>th</sup></b>";
	} else {
		echo "<b>" .$objReview->PatientDetails_ConsultationNumber . "<sup>" . $ends[$objReview->PatientDetails_ConsultationNumber % 10] . "</sup></b>";
	}
                        
    echo " entry to the Lung Health software.";										
    ?>
</p> <?php
