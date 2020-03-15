<p><?php 

	$CI =& get_instance();
	$arrScreenHistory = $CI->session->userdata("arrScreenHistory");
	$lastScreen = end($arrScreenHistory);

	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	
	$consultationDate = $objReview->agcsystem_ReviewStartTime;
	$consultationDateExploded = explode('-',$consultationDate);
	$consultationDateString = date('jS F Y',strtotime($consultationDateExploded[1].'/'.substr($consultationDateExploded[2],0,2).'/'.$consultationDateExploded[0]));
	$todayDateString = date('jS F Y');
	
	$d1 = new DateTime($consultationDateString);
	$d2 = new DateTime($todayDateString);
	
	$CurrentMedicationLevel = '';
	if($objReview->CurrentMedication_CurrentMedicationLevel != '') {
		$CurrentMedicationLevel = $objReview->CurrentMedication_CurrentMedicationLevel;
	} else {
		$CurrentMedicationLevel = $objReview->CurrentMedicationLastVisit_CurrentMedicationLevel;
	}	
	
	/*echo "<script>console.log('CurrentMedication_CurrentMedicationLevel: ".$objReview->CurrentMedication_CurrentMedicationLevel."');</script>";
	echo "<script>console.log('CurrentMedicationLastVisit_CurrentMedicationLevel: ".$objReview->CurrentMedicationLastVisit_CurrentMedicationLevel."');</script>";
	echo "<script>console.log('CurrentMedicationLevel: ".$CurrentMedicationLevel."');</script>";
	
	echo "<script>console.log('Consultation Date: ".$consultationDate."');</script>";
	echo "<script>console.log('Consultation Date String: ".$consultationDateString."');</script>";
	echo "<script>console.log('Todays Date String: ".$todayDateString."');</script>";
	
	echo "<script>console.log('Current Screen: ".$lastScreen."');</script>";*/
	
	//If consultation begin date is earlier than today and this is the final report (Exit screen) then set consultation date as today
	if ($d1 < $d2 && $lastScreen == "EX") {
		echo "<b>Consultation Completed: " . $todayDateString . "</b></p><p>";
	} else {
		echo "<b>Consultation Completed: " . $consultationDateString . "</b></p><p>";
	}
	    
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
			echo "has been taking treatment equivalent to <b>Step ".$CurrentMedicationLevel."</b> of the BTS / SIGN Asthma Guidelines."; 
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
