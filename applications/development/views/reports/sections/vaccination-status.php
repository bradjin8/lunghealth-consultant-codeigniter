<?php 

	$GreenColour  = "#00CC66";
	$AmberColour  = "#FFCC33";
	$RedColour =  "#FF3333";

	echo "<h3>Vaccination Status</h3><p>";  	

	//Flu Vaccination
	$fluNextAutumn = array("A", "D", "E");
	$fluNextFewDays = array("B", "C");
	
	if ($objReview->NonPharmaRx_BookFluVaccination == "Y"){ 
	
		switch ($objReview->NonPharmaRx_FluVaccineUpToDate) {
			 case "A":
				 echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Flu vaccination is up to date this season. ";
				 break;
			 case "B":
				 echo "<span style=\"color:".$AmberColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Flu vaccination is not up to date this season. ";
				 break;
			 case "C":
				 echo "<span style=\"color:".$AmberColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Flu vaccination is not up to date this season. ";
				 break;
			 case "D":
				 echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Flu vaccination is up to date. ";
				 break;
			 case "E":
				 echo "<span style=\"color:".$AmberColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Flu vaccination is not up to date. ";
				 break;
			 case "F":
				 echo "";
				 break;		 
			 default;
				echo "No data about flu vaccinations was collected";
		}
		
		if(in_array($objReview->NonPharmaRx_FluVaccineUpToDate, $fluNextAutumn )) {
			echo "<b>An appointment for a flu vaccination should be made for next autumn.</b>";
		} elseif(in_array($objReview->NonPharmaRx_FluVaccineUpToDate, $fluNextFewDays)) {
			echo "<b>An appointment for a flu vaccination should be made in the next few days / weeks.</b>";
		}
		
	} elseif ($objReview->NonPharmaRx_BookFluVaccination == "T") {
		echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient was given the flu vaccination today.";
	} elseif ($objReview->NonPharmaRx_BookFluVaccination == "N") {
		echo "<span style=\"color:".$RedColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient's flu vaccincation is not up to date but the patient declines to have it.";
	} elseif ($objReview->NonPharmaRx_BookFluVaccination == "X") {
		echo "<span style=\"color:".$RedColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient's flu vaccincation is not up to date but the vaccine is not currently available.";
	}
	
	?></p>
        <p><?php
		
	//Pneumococcal Vaccination
	if ($objReview->NonPharmaRx_BookPneumococcalVaccination == "T") {
		echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient was given the pneumococcal vaccincation today.";
	} elseif ($objReview->NonPharmaRx_BookPneumococcalVaccination == "N") {
		echo "<span style=\"color:".$RedColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient's pneumococcal vaccincation is not up to date but the patient declines to have it.";
	} elseif ($objReview->NonPharmaRx_BookPneumococcalVaccination == "X") {
		echo "<span style=\"color:".$RedColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>The patient's pneumococcal vaccincation is not up to date but the vaccine is not currently available.";
	} else {
		
		switch ($objReview->NonPharmaRx_PneumococcalVaccineUpToDate) {
			 case "A":
				 echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Pneumococcal vaccination is up to date and is not due for some time. ";
				 break;
			 case "B":
				 echo "<span style=\"color:".$AmberColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Pneumococcal vaccination is up to date, though the patient is due for a booster in the next 12 months and <b>this should be booked in for the next few days / weeks.</b> ";
				 break;
			 case "C":
				 echo "<span style=\"color:".$AmberColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Pneumococcal vaccination is not up to date, and <b>the patient should be booked in for a vaccination in the next few days / weeks.</b> ";
				 break;
			 case "D":
				 echo "<span style=\"color:".$GreenColour.";\">&bull;&nbsp;&nbsp;&nbsp;&nbsp;</span>Pneumococcal vaccination is up to date, and as they are aged over 65 they will not require it again.";
				 break;	 
			 default;
				echo "No data about pneumococcal vaccinations was collected";
		}
		
	}

	?></p>