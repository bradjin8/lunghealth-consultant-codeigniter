<?php

	/*$ends = array("th","st","nd","rd","th","th","th","th","th","th");	

	$DiagnosisBasisText = "<h3>Diagnosis</h3><p>This is " . ($objReview->InitialPatientDetails_Sex === "M"? "his ":"her ");
  
	if (($objReview->PatientDetails_ConsultationNumber %100) >= 11 && ($objReview->PatientDetails_ConsultationNumber%100) <= 13){
		$DiagnosisBasisText .=$objReview->PatientDetails_ConsultationNumber . "<sup>th</sup>";
	} else {
		$DiagnosisBasisText .=$objReview->PatientDetails_ConsultationNumber . "<sup>" . $ends[$objReview->PatientDetails_ConsultationNumber % 10] . "</sup>";
	}
                        
    $DiagnosisBasisText .=" entry to the Lung Health software." . "</p>";*/
	
	$DiagnosisBasisText = "<h3>Diagnosis</h3>";

	//Group 1 - Symptom and Treatment Duration
	if(	   $objReview->DiagnosisBasis_BeenOnInhalersSinceChildhood == "Y" 
		|| $objReview->DiagnosisBasis_BeenOnInhalersWithBenefit == "Y" 
		|| $objReview->DiagnosisBasis_SymptomsPresentSinceChildhood == "Y" 
		|| $objReview->DiagnosisBasis_SymptomsPresentLastFewYears == "Y" 
		|| $objReview->FirstAssessment_AsthmaRegister2006 == "Y"
		|| $objReview->DiagnosisBasis_HospitalReport == "Y"
		){
	
		$DiagnosisBasisText .="<p>Main pointers to an asthma diagnosis include:</p><p><ul>";
	
		if($objReview->FirstAssessment_AsthmaRegister2006 == "Y"){
			$DiagnosisBasisText .="<li>Patient has been on the Asthma Register for nine or more years (since before 2006)</li>";
		}
		
		if($objReview->DiagnosisBasis_SymptomsPresentSinceChildhood == "Y"){
			$DiagnosisBasisText .="<li>Patient has had asthma symptoms since childhood or for more than ten years</li>";
		}
		
		if($objReview->DiagnosisBasis_SymptomsPresentLastFewYears == "Y"){
			$DiagnosisBasisText .="<li>Patient has had asthma symptoms for the last few years</li>";
		}
		
		if($objReview->DiagnosisBasis_BeenOnInhalersSinceChildhood == "Y"){
			$DiagnosisBasisText .="<li>Patient has been on inhalers since childhood or for more than ten years</li>";
		}
		
		if($objReview->DiagnosisBasis_BeenOnInhalersWithBenefit == "Y"){
			$DiagnosisBasisText .="<li>Reports symptomatic benefit from inhaled therapy</li>";
		}
		
		if($objReview->DiagnosisBasis_HospitalReport == "Y"){
			$DiagnosisBasisText .="<li>There is a record of a hospital report for this patient, confirming a diagnosis of asthma</li>";	
		}
		
		$DiagnosisBasisText .="</ul></p>";
	
	}
	
	//Group 2 - Chest Colds / Chest on Holiday / Wheeze on Exertion / Triggers (Standard Track)
	if(	   $objReview->DiagnosisBasis_ChestCold == "Y" 
		|| $objReview->DiagnosisBasis_ChestOnHoliday == "Y" 
		|| $objReview->DiagnosisBasis_ChestOnExertion == "Y" 
		|| $objReview->DiagnosisBasis_TriggersAny == "Y"
		|| $objReview->FirstAssessment_Eczema == "Now"
		|| $objReview->FirstAssessment_HayFever == "Y"
		|| $objReview->DiagnosisBasis_RCP3PoorControl == "Y"
		){
	
		$DiagnosisBasisText .="<p>Other Supporting Information:</p><p><ul>";
	
		if($objReview->DiagnosisBasis_ChestCold == "Y"){
			$DiagnosisBasisText .="<li>When this patient gets a cold, it ".strtolower ($objReview->FirstAssessment_ChestCold)." goes to his / her chest</li>";
		}
		
		if($objReview->DiagnosisBasis_ChestOnHoliday == "Y"){
			$DiagnosisBasisText .="<li>When this patient is on holiday, their chest is improved</li>";
		}
		
		if($objReview->DiagnosisBasis_ChestOnExertion == "Y" && $objReview->FirstAssessment_ExertionInhalerReponse == "Y"){
			$DiagnosisBasisText .="<li>After exertion, this patient experiences worsening chest symptoms (and it responds to inhalers)</li>";
		}
		
		if($objReview->DiagnosisBasis_ChestOnExertion == "Y" && $objReview->FirstAssessment_ExertionInhalerReponse != "Y"){
			$DiagnosisBasisText .="<li>After exertion, this patient experiences worsening chest symptoms</li>";
		}
					
		//Triggers
		if($objReview->DiagnosisBasis_TriggersAny == "Y"){
		
			$DiagnosisBasisText .="<li>Triggers:</li><ul>";
			
			if($objReview->DiagnosisBasis_TriggersFumesPerfumes == "Y"){
				$DiagnosisBasisText .="<li>Perfume fumes</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersPets == "Y"){
				$DiagnosisBasisText .="<li>Pets</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersPassiveSmoking == "Y"){
				$DiagnosisBasisText .="<li>Passive Smoking</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersSeasons == "Y"){
				$DiagnosisBasisText .="<li>Seasons</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersPollen == "Y"){
				$DiagnosisBasisText .="<li>Pollen</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersDustMites == "Y"){
				$DiagnosisBasisText .="<li>Dust Mites</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersOther == "Y"){
				$DiagnosisBasisText .="<li>Other</li>";
			}
			
			if($objReview->DiagnosisBasis_TriggersMould == "Y"){
				$DiagnosisBasisText .="<li>Mould</li>";
			}
			
			$DiagnosisBasisText .="</ul>";
			
		}
		
		if($objReview->DiagnosisBasis_RCP3PoorControl == "Y"){	
			$DiagnosisBasisText .="<li>Patient has previously scored 2 or 3 on Royal College of Physicians (RCP) 3 Questions, indicating poor asthma control</li>";	
		}
		
		if($objReview->FirstAssessment_Eczema == "Now" || $objReview->FirstAssessment_HayFever == "Y"){
			$DiagnosisBasisText .="<li>Patient has concomitant eczema or hay fever</li>";
		}
		
		$DiagnosisBasisText .="</ul></p>";
	
	}
	
	//Group 2 - Background Questions (Fast Track)
	if(	   $objReview->DiagnosisBasis_ExacerbationInPractice == "Y" 
		|| $objReview->DiagnosisBasis_HospitalOPD == "Y" 
		|| $objReview->DiagnosisBasis_HospitalAdmission == "Y" 
		|| $objReview->DiagnosisBasis_ObservedTherapyResponse == "Y"
		){
	
		$DiagnosisBasisText .="<p>Other Supporting Information:</p><p><ul>";
	
		if($objReview->DiagnosisBasis_ExacerbationInPractice == "Y"){
			$DiagnosisBasisText .="<li>Management of an exacerbation in the practice</li>";
		}
		
		if($objReview->DiagnosisBasis_HospitalOPD == "Y"){
			$DiagnosisBasisText .="<li>A hospital OPD assessment for asthma</li>";
		}
		
		if($objReview->DiagnosisBasis_HospitalAdmission == "Y"){
			$DiagnosisBasisText .="<li>Information following a hospital admission / emergency attendance</li>";
		}
		
		if($objReview->DiagnosisBasis_ObservedTherapyResponse == "Y"){
			$DiagnosisBasisText .="<li>An observed response to therapy in the practice</li>";
		}
					
		$DiagnosisBasisText .="</ul></p>";
	
	}
	
	//PEF
	/*if($objReview->LungFunction_LungFunctionPerformed == "PEF Performed"){
	
		$DiagnosisBasisText .="<p>Today's Peak Flow Results:</p><p>";
	
		$DiagnosisBasisText .="Today's PEF was recorded as <b>" 
                                        . number_format($objReview->PEF_CurrentPEF,0)
                                        . "</b> (L/min) which is <b>" 
                                        . number_format($objReview->PEF_CurrentPEFPctPredicted,0) . "%</b> of the patient's " 
                                        . strtolower($objReview->PEF_BestOrPredicted) . " value of <b>";
		
		if($objReview->PEF_BestOrPredicted == "Best"){
			$DiagnosisBasisText .=number_format($objReview->FirstAssessment_BestPEF,0) . "</b> (L/min)";
		} else {
			$DiagnosisBasisText .=number_format($objReview->FirstAssessment_PredictedPEFValue,0) . "</b> (L/min)";
		}
					
		$DiagnosisBasisText .="</p>";
	
	}
	
	//FEV1
	if($objReview->LungFunction_LungFunctionPerformed == "Spirometry performed"){
	
		$DiagnosisBasisText .="<p>Today's Spirometry Results:</p><p>";
	
		$DiagnosisBasisText .="Today's FEV1 was recorded as <b>" . number_format($objReview->Spirometry_PostbronchodilatorFEV1,1) . "</b> litres which is <b>" . number_format($objReview->Spirometry_PercentPredictedFEV1,0) . "%</b> of the patient's predicted value of <b>" . number_format($objReview->FirstAssessment_PredictedFEVValue,1) . "</b> litres";
						
		$DiagnosisBasisText .="</p>";
	
	}*/
	
	//Tests
	if(		$objReview->DiagnosisBasis_TestsAsthmaExerciseTest == "Y" 
		|| 	$objReview->DiagnosisBasis_TestsBronchialHyperReactivity == "Y" 
		|| 	$objReview->DiagnosisBasis_TestsBronchodilatorReversibility == "Y" 
		|| 	$objReview->DiagnosisBasis_TestsCardiorespiratoryExerciseTest == "Y"
		//|| 	$objReview->DiagnosisBasis_TestsChestRadiograph == "Y"
		|| 	$objReview->DiagnosisBasis_TestsExhaledNo == "Y"
		|| 	$objReview->DiagnosisBasis_TestsFbcBloodEosinophila == "Y"
		|| 	$objReview->DiagnosisBasis_TestsSputumEosinophila == "Y"
		|| 	$objReview->DiagnosisBasis_TestsFlowVolumeLoop == "Y"
		|| 	$objReview->DiagnosisBasis_SpirometryFEV1OverFVC == "Y"
		//|| 	$objReview->DiagnosisBasis_TestsHrct_Category == "Y"
		//|| 	$objReview->DiagnosisBasis_TestsLungVolumesAndDlco_Dlco == "Y"
		|| 	$objReview->DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumes == "Y"
		|| 	$objReview->DiagnosisBasis_TestsPEFCharts == "Y"
		//|| 	$objReview->DiagnosisBasis_TestsPetco2 == "Y"
		|| 	$objReview->DiagnosisBasis_TestsSerumIge == "Y"
		|| 	$objReview->DiagnosisBasis_TestsSkinAllergy == "Y"
		|| 	$objReview->DiagnosisBasis_SpirometricResponseToOralSteroids == "Y"
		){
	
		$DiagnosisBasisText .="<p>Other Supportive Test Results:</p><p><ul>";
	
		if($objReview->DiagnosisBasis_TestsAsthmaExerciseTest == "Y"){
			$DiagnosisBasisText .="<li>Asthma Exercise Test</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsBronchialHyperReactivity == "Y"){
			$DiagnosisBasisText .="<li>Bronchial Hyper Reactivity (< 4.0 mg/L)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsBronchodilatorReversibility == "Y"){
			$DiagnosisBasisText .="<li>Bronchodilator Reversibility (> 12% and 200 ml change in FEV1 or FVC)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsCardiorespiratoryExerciseTest == "Y"){
			$DiagnosisBasisText .="<li>Cardiorespiratory Exercise Test</li>";
		}
		
		/*if($objReview->DiagnosisBasis_TestsChestRadiograph == "Y"){
			$DiagnosisBasisText .="<li>Chest Radiograph</li>";
		}*/
		
		if($objReview->DiagnosisBasis_TestsExhaledNo == "Y"){
			$DiagnosisBasisText .="<li>Exhaled NO (> 50 ppm)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsFbcBloodEosinophila == "Y"){
			$DiagnosisBasisText .="<li>FBC Blood Eosinophila (Eosinophils of > 3% or > 300)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsSputumEosinophila == "Y"){
			$DiagnosisBasisText .="<li>Sputum Eosinophila (Present or Eosinophils of > 2%)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsFlowVolumeLoop == "Y"){
			$DiagnosisBasisText .="<li>Flow Volume Loop (FEV1/FVC ratio < 70%)</li>";
		}
		
		if($objReview->DiagnosisBasis_SpirometryFEV1OverFVC == "Y"){
			$DiagnosisBasisText .="<li>FEV1/FVC ratio (< 70% reported in Spirometry)</li>";
		}
		
		/*if($objReview->DiagnosisBasis_TestsHrct_Category == "Y"){
			$DiagnosisBasisText .="<li>High-resolution computed tomography confirming no other abnormality</li>";
		}*/
		
		/*if($objReview->DiagnosisBasis_TestsLungVolumesAndDlco_Dlco == "Y"){
			$DiagnosisBasisText .="<li>Spirometry Diffusion Capacity (DLCO)</li>";
		}*/
		
		if($objReview->DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumes == "Y"){
			$DiagnosisBasisText .="<li>Lung Volumes (FEV1 or FVC < 80% of predicted)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsPEFCharts == "Y"){
			$DiagnosisBasisText .="<li>PEF Chart Variability</li>";
		}
		
		/*if($objReview->DiagnosisBasis_TestsPetco2 == "Y"){
			$DiagnosisBasisText .="<li>PETCO2 (< 4.0 kPa)</li>";
		}*/
		
		if($objReview->DiagnosisBasis_TestsSerumIge == "Y"){
			$DiagnosisBasisText .="<li>Serum IGE (> 60 u/l)</li>";
		}
		
		if($objReview->DiagnosisBasis_TestsSkinAllergy == "Y"){
			$DiagnosisBasisText .="<li>Skin Allergy Test</li>";
		}
		
		if($objReview->DiagnosisBasis_SpirometricResponseToOralSteroids == "Y"){
			$DiagnosisBasisText .="<li>Spirometric Response to Oral Steroids (best vs least FEV1 > 400 ml)</li>";
		}		
		
		$DiagnosisBasisText .="</ul>";
	
	}
	
	echo $DiagnosisBasisText;

