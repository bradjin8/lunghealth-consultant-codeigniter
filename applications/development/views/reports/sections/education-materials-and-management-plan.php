<?php

echo "<h3>Education Materials and Management Plan</h3>";

//Management Plan
echo "<p>";
if ($objReview->NonPharmaRx_ManagementPlanUnderstandTreatment == "Y" || $objReview->NonPharmaRx_ManagementPlanCreated == "Y"){
	echo "A written management plan has been supplied and it has been confirmed that the patient understands it.";
} elseif ($objReview->NonPharmaRx_ManagementPlanCreated == "N"){
	echo "A written management plan has not been supplied or is not understood by the patient, with the reason: <i>" . $objReview->NonPharmaRx_ManagementPlanWhyNotProvided . "</i>";
}
echo "</p>";

//Education Materials
/*echo "<p>";
if ($objReview->NonPharmaRx_DiseaseEducationDownloadDate != ""){
	echo "Disease education materials have been downloaded.</p>";
} elseif ($objReview->FirstAssessment_DiseaseEducationOffline == "Y"){
	echo "Disease education materials have been received offline.</p>";
} else {
	echo "Disease education materials have <b>not</b> been supplied.</p>";
}

echo "<p>";
if ($objReview->NonPharmaRx_MedicationEducationDownloadDate != ""){
	echo "Medication education materials have been downloaded.</p>";
} elseif ($objReview->FirstAssessment_MedicationEducationOffline == "Y"){
	echo "Medication education materials have been received offline.</p>";
} else {
	echo "Medication education materials have <b>not</b> been supplied.</p>";
}

echo "<p>";
if ($objReview->NonPharmaRx_PersonalActionPlanDownloadDate != ""){
	echo "Personal Action Plan materials have been downloaded.</p>";
} elseif ($objReview->FirstAssessment_PersonalActionPlanOffline == "Y"){
	echo "Personal Action Plan materials have been received offline.</p>";
} else {
	echo "Personal Action Plan materials have <b>not</b> been supplied.</p>";
}*/

echo "<p>";
if ($objReview->NonPharmaRx_OnlineAdviceVisitDate != ""){
	echo "The Asthma UK online advice website has been shown to the patient in practice.</p>";
} elseif ($objReview->NonPharmaRx_OnlineAdviceOffline == "Y"){
	echo "The Asthma UK online advice website URL has been given to the patient as a resource for them to review at home.</p>";
} else {
	echo "Information about the Asthma UK online advice website has <b>not</b> yet been given to the patient.</p>";
}

echo "<p>";
if ($objReview->NonPharmaRx_OnlineResourcesVisitDate != ""){
	echo "The Asthma UK online resources website has been shown to the patient in practice. It is not known if any materials were downloaded.</p>";
} elseif ($objReview->NonPharmaRx_OnlineResourcesOffline == "Y"){
	echo "Asthma UK resources have been received offline.</p>";
} else {
	echo "Asthma UK online resouces have <b>not</b> yet been given to the patient, and the patient has not been made aware of them.</p>";
}

//Home Peak Flows
echo "<p>";
if ($objReview->AssessmentDetails_PEFMeterIssued == "Y"){
	echo 'A Peak Flow meter has been issued to the patient with the details:<i>'.$objReview->NonPharmaRx_PEFMeterIssuedDetails.'</i></p>';
} 

