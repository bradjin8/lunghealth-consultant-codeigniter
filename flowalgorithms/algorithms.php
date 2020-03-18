<?php

class FlowAlgorithmsLibrary extends FlowLibrary
{

    function __construct()
    {
        parent::__construct();
    }

    /******************************************************************
     *
     * FLOW ALGORITHMS
     ******************************************************************/

    //Flow 1001 - Initiate First Assessment
    //fn_InititateFirstAssessmentFlow("CurrentMedication,CurrentMedicationLevel",1101,1003) - FieldID: 78
    function fn_InititateFirstAssessmentFlow($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];

        $TrackTypeFlow = $arrInputs[1];
        $HistoryAndSymptomsFlow = $arrInputs[2];

        if ($CurrentMedicationLevel == 1 || $CurrentMedicationLevel == 2 || $CurrentMedicationLevel == 3) {
            return $TrackTypeFlow;
        } elseif ($CurrentMedicationLevel == 0 || $CurrentMedicationLevel == 4 || $CurrentMedicationLevel == 5) {
            return $HistoryAndSymptomsFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_InititateFirstAssessmentFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1101 - Track Type
    //fn_TrackTypeFlow("FirstAssessment,NewOrKnown","Exacerbation,PEFNotAvailable",1003,1002,20000) - FieldID: 2251
    function fn_TrackTypeFlow($arrInputs = array())
    {

        $NewOrKnown = $arrInputs[0];
        $Exacerbation_PEFNotAvailable = $arrInputs[1];

        $HistoryAndSymptomsFlow = $arrInputs[2];
        $DiagnosisFTFlow = $arrInputs[3];
        $ExitFlow = $arrInputs[4];

        if ($Exacerbation_PEFNotAvailable != "Y") {
            if ($NewOrKnown == "New") {
                return $HistoryAndSymptomsFlow;
            } elseif ($NewOrKnown == "Known") {
                return $DiagnosisFTFlow;
            } else {
                $CI = &get_instance();
                $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_TrackTypeFlow/1 Untitled');
                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                redirect('/error/', 'refresh');
            }
        } elseif ($Exacerbation_PEFNotAvailable == "Y") {
            return $ExitFlow;
        }
    }

    //Flow 1002 - Diagnosis (FT)
    //fn_SufficientEvidenceFlow("FirstAssessment,ConfirmDiagnosis13",1004,1103) - FieldID: 2084
    public function fn_SufficientEvidenceFlow($arrInputs = array())
    {

        $ConfirmDiagnosis13 = $arrInputs[0];

        $AssessControlFTFlow = $arrInputs[1];
        $HistoryAndSymptomsAlternativeFlow = $arrInputs[2];

        if ($ConfirmDiagnosis13 == "Definite" || $ConfirmDiagnosis13 == "Probable" || $ConfirmDiagnosis13 == "Possible") {
            return $AssessControlFTFlow;
        } elseif ($ConfirmDiagnosis13 == "NoEvidence") {
            return $HistoryAndSymptomsAlternativeFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_SufficientEvidenceFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1003 AND Flow 1103 - History and Symptoms
    //fn_ClinicalExamRequired45Flow("FirstAssessment,ClinicalExamRequired45",1007,5001) - FieldID: 2085
    public function fn_ClinicalExamRequired45Flow($arrInputs = array())
    {

        $ClinicalExamRequired45 = $arrInputs[0];

        $ClinicalExamFlow = $arrInputs[1];
        $LungFunctionFlow = $arrInputs[2];

        if ($ClinicalExamRequired45 == "Required" || $ClinicalExamRequired45 == "Requested") {
            return $ClinicalExamFlow;
        } elseif ($ClinicalExamRequired45 == "Not Required") {
            return $LungFunctionFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_ClinicalExamRequired45Flow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1004 - Assess Control (FT)
    //fn_ClinicalExamRequiredFlow("FirstAssessment,CurrentControl13","FirstAssessment,PatientComfort",1005,1109) - FieldID: 2086
    public function fn_ClinicalExamRequiredFlow($arrInputs = array())
    {

        $CurrentControl13 = $arrInputs[0];
        $PatientComfort = $arrInputs[1];

        $ClinicalExamFlow = $arrInputs[2];
        $Education1Flow = $arrInputs[3];

        if ($CurrentControl13 == "Poor" || $PatientComfort == "Not Comfortable") {
            return $ClinicalExamFlow;
        } elseif (($CurrentControl13 == "Good" || $CurrentControl13 == "Partial") && $PatientComfort != "Not Comfortable") {
            return $Education1Flow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_ClinicalExamRequiredFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1007 AND Flow 1005 - Clinical Examination / Clinical Examination (FT)
    //Standard Track: fn_ClinicalExamFlow("ClinicalExam,ClinicalExamOutcome","FirstAssessment,GPAuthorisation","ClinicalExam,PulseConfirmedNormalByGP","ClinicalExam,RRConfirmedNormalByGP","",4001,5001,5001,20000) - FieldID: 2089
    //Fast Track: fn_ClinicalExamFlow("ClinicalExam,ClinicalExamOutcome","FirstAssessment,GPAuthorisation","ClinicalExam,PulseConfirmedNormalByGP","ClinicalExam,RRConfirmedNormalByGP","CurrentMedication,CurrentMedicationLevel",4001,1109,5004,20000) - FieldID: 2087
    public function fn_ClinicalExamFlow($arrInputs = array())
    {

        $CI =& get_instance();
        $arrFlowHistory = $CI->session->userdata("arrFlowHistory");
        $intPosition = array_search("4001", $arrFlowHistory);

        if (is_numeric($intPosition)) {
            $exacerbationReview = "Y";
        } else {
            $exacerbationReview = "N";
        }

        $ClinicalExamOutcome = $arrInputs[0];
        $GPAuthorisation = $arrInputs[1];
        $PulseConfirmedNormalByGP = $arrInputs[2];
        $RRConfirmedNormalByGP = $arrInputs[3];
        $CurrentMedicationLevel = $arrInputs[4];

        $Exacerbation1Flow = $arrInputs[5];
        $NormalFlow = $arrInputs[6];
        $AlternativeFlow = $arrInputs[7];
        $ExitFlow = $arrInputs[8];

        // If RR or HR not confirmed normal by GP, then ExitFlow (This takes precedence over Exacerbation Review?)
        if ($PulseConfirmedNormalByGP == "Abnormal" || $RRConfirmedNormalByGP == "Abnormal") {
            return $ExitFlow;
        } else {
            // If Raised HR or RR and not previously had exacerbationReview in session, go to Exacerbation (4001)
            if ($ClinicalExamOutcome == "Raised HR or RR" && $exacerbationReview == "N") {
                return $Exacerbation1Flow;
                // If No Alarm or Other Abnormality with GP Authorisation or previously had exacerbationReview in session, go to Therapy (1009)
            } elseif ($ClinicalExamOutcome == "No Alarm" || ($ClinicalExamOutcome == "Other Abnormality" && $GPAuthorisation != "N") || $exacerbationReview == "Y") {
                if ($CurrentMedicationLevel != 0) {
                    return $NormalFlow;
                } else {
                    return $AlternativeFlow;
                }
            } elseif ($ClinicalExamOutcome == "Other Abnormality" && $GPAuthorisation == "N") {
                return $ExitFlow;
            } else {
                $CI = &get_instance();
                $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_ClinicalExamFlow/1 Untitled');
                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                redirect('/error/', 'refresh');
            }
        }
    }

    //Flow 5001 - Lung Function
    //fn_LungFunctionPefOrFev1("Spirometry,SurgeryLast2weeksAbdominal","Spirometry,SurgeryLast2weeksThoracic", "Spirometry,SurgeryLast2weeksEye","Spirometry,PneumothoraxLast2Weeks","Spirometry,SpirometryNotPerformedAnyOtherReason","LungFunction,IsSpirometryAvailable",5002,5003,20000) - FieldID: 2245
    function fn_LungFunctionPefOrFev1($arrInputs = array())
    {

        $SurgeryLast2weeksAbdominal = $arrInputs[0];
        $SurgeryLast2weeksThoracic = $arrInputs[1];
        $SurgeryLast2weeksEye = $arrInputs[2];
        $PneumothoraxLast2Weeks = $arrInputs[3];
        $SpirometryNotPerformedAnyOtherReason = $arrInputs[4];
        $IsSpirometryAvailable = $arrInputs[5];

        $PEFFlow = $arrInputs[6];
        $SpirometryFlow = $arrInputs[7];
        $ExitFlow = $arrInputs[8];

        if ($SurgeryLast2weeksAbdominal == "Y" || $SurgeryLast2weeksThoracic == "Y" || $SurgeryLast2weeksEye == "Y" || $PneumothoraxLast2Weeks == "Y" || $SpirometryNotPerformedAnyOtherReason == "Y") {
            return $LfFlowType = $PEFFlow;
        } elseif ($IsSpirometryAvailable == "Y") {
            return $LfFlowType = $SpirometryFlow;
        } elseif ($IsSpirometryAvailable == "N") {
            return $LfFlowType = $PEFFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_LungFunctionPefOrFev1/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }

    }

    //Flow 5002 - PEF AND 5003 Spirometry
    //fn_SpiroOrPEFFlow("AssessmentDetails,AssessmentType",1008,5004) - FieldID: 2246
    //fn_SpiroOrPEFFlow("AssessmentDetails,AssessmentType","LungFunction,LungFunctionOutcome","Spirometry,MaximumEffort","LungFunction,LungFunctionPerformed","Exacerbation,PEFNotAvailable","Spirometry,ReferSpiroRestrictiveReversable",1008,5004,20000) - FieldID: 2246
    public function fn_SpiroOrPEFFlow($arrInputs = array())
    {

        $AssessmentType = $arrInputs[0];
        $LungFunctionOutcome = $arrInputs[1];
        $MaximumEffort = $arrInputs[2];
        $LungFunctionPerformed = $arrInputs[3];
        $Exacerbation_PEFNotAvailable = $arrInputs[4];
        $ReferSpiroRestrictiveReversable = $arrInputs[5];

        $AssessControlFlow = $arrInputs[6];
        $SummarySoFarFlow = $arrInputs[7];
        $ExitFlow = $arrInputs[8];

        if ((($LungFunctionOutcome == "Spirometry performed - Restrictive" && $ReferSpiroRestrictiveReversable == "Continue") || $LungFunctionOutcome != "Spirometry performed - Restrictive")
            && substr($LungFunctionOutcome, 0, 5) != "Error"
            && (($LungFunctionPerformed == "Spirometry performed" && $MaximumEffort == "Y") || $LungFunctionPerformed == "PEF Performed")
            && $Exacerbation_PEFNotAvailable != "Y"
        ) {
            if ($AssessmentType == "1A") {
                return $AssessControlFlow;
            } elseif ($AssessmentType == "AR" || $AssessmentType == "FU") {
                return $SummarySoFarFlow;
            } else {
                $CI = &get_instance();
                $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SpiroOrPEFFlow/1 Untitled');
                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                redirect('/error/', 'refresh');
            }
            // Restrictive pattern or not maximum effort
        } elseif (($LungFunctionOutcome == "Spirometry performed - Restrictive" && $ReferSpiroRestrictiveReversable != "Continue") || $MaximumEffort == "N" || substr($LungFunctionOutcome, 0, 5) == "Error" || $Exacerbation_PEFNotAvailable == "Y") {
            return $ExitFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SpiroOrPEFFlow/2 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1008 - Assess Control
    //fn_CurrentControl45Flow("CurrentMedication,CurrentMedicationLevel",1109,5004) - FieldID: 2090
    public function fn_CurrentControl45Flow($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];

        $Education1Flow = $arrInputs[1];
        $SummarySoFarFlow = $arrInputs[2];

        if ($CurrentMedicationLevel != 0) {
            return $Education1Flow;
        } elseif ($CurrentMedicationLevel == 0) {
            return $SummarySoFarFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_CurrentControl45Flow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 1109 - Education 1 (NOTE: POTENTIALLY REDUNDANT ALGORITHM NOW AS ALWAYS GOES TO SUMMARY SO FAR FLOW!)
    //fn_StepAlgorithm1("CurrentMedication,CurrentMedicationLevel",5004,5004) - FieldID: 2363
    function fn_StepAlgorithm1($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];

        $SummarySoFarFlow = $arrInputs[1];
        $SummarySoFarAlternativeFlow = $arrInputs[2];

        if ($CurrentMedicationLevel >= 4 && $CurrentMedicationLevel <= 5) {
            return $SummarySoFarFlow;
        } elseif ($CurrentMedicationLevel >= 1 && $CurrentMedicationLevel <= 3) {
            return $SummarySoFarAlternativeFlow;
        } else {
            $CI =& get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_StepAlgorithm1/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 5004 - Summary So Far	//fn_SummarySoFarFlow("AssessmentDetails,AssessmentType","FirstAssessment,FastOrStandardTrack","CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","Spirometry,SummarySoFarOptions","FollowUp,PossibleAction","FollowUp,ProbableAction","CurrentMedication,CurrentDrugILABANewDrug",20000,5005,1009,1010,2009,2010) - FieldID: 2311
    public function fn_SummarySoFarFlow($arrInputs = array())
    {

        $AssessmentType = $arrInputs[0];
        $FastOrStandardTrack = $arrInputs[1];
        $CurrentMedicationLevel = $arrInputs[2];
        $MedicationLevelAtStartOfLastVisit = $arrInputs[3];
        $NewSummaryOptions = $arrInputs[4];
        $PossibleAction = $arrInputs[5];
        $ProbableAction = $arrInputs[6];
        $CurrentDrugILABANewDrug = $arrInputs[7];

        $ExitConsultationFlow = $arrInputs[8];
        $TestsFlow = $arrInputs[9];
        $TherapyFastTrackFlow1A = $arrInputs[10];
        $TherapyStandardTrackFlow1A = $arrInputs[11];
        $TherapyFastTrackFlow = $arrInputs[12];
        $TherapyStandardTrackFlow = $arrInputs[13];

        //If CurrentMedicationLevel = 0 and not on LABA alone then use MedicationLevelAtStartOfLastVisit
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            $FlowMedicationLevel = $MedicationLevelAtStartOfLastVisit;
        } else {
            $FlowMedicationLevel = $CurrentMedicationLevel;
        }

        //If voluntarily chooses not to continue: exit flow
        if ($NewSummaryOptions == "End") {
            return $ExitConsultationFlow;

            //If user chooses to book tests: book tests flow
        } elseif ($NewSummaryOptions == "BookTestsContinue" || $PossibleAction == "More Tests" || $ProbableAction == "More Tests") {
            return $TestsFlow;

            //If user chooses to continue, with or without referral -
        } elseif ($NewSummaryOptions == "Continue" || $NewSummaryOptions == "ReferralContinue" || $NewSummaryOptions == "") {
            //First Assessment
            if ($AssessmentType == "1A") {
                if ($FastOrStandardTrack == "Standard Track") {
                    return $TherapyFastTrackFlow1A;
                } elseif ($FastOrStandardTrack == "Fast Track") {
                    return $TherapyStandardTrackFlow1A;
                } else {
                    $CI = &get_instance();
                    $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SummarySoFarFlow/4 Untitled');
                    $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                    redirect('/error/', 'refresh');
                }
                //Follow Ups
            } elseif ($AssessmentType == "FU" || $AssessmentType == "AR") {
                //LABA alone
                if ($CurrentMedicationLevel == 0 && !empty($CurrentDrugILABANewDrug)) {
                    return $TherapyFastTrackFlow;
                } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3) {
                    return $TherapyFastTrackFlow;
                } elseif ($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5) {
                    return $TherapyStandardTrackFlow;
                } else {
                    $CI = &get_instance();
                    $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SummarySoFarFlow/3 Untitled');
                    $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                    redirect('/error/', 'refresh');
                }
            } else {
                $CI = &get_instance();
                $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SummarySoFarFlow/2 Untitled');
                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                redirect('/error/', 'refresh');
            }
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_SummarySoFarFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }
    }

    //Flow 5005 - Order Tests	//fn_LungFunctionBookTests("AssessmentDetails,AssessmentType","FirstAssessment,FastOrStandardTrack","CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","Spirometry,SummarySoFarOptions","FollowUp,PossibleAction","FollowUp,ProbableAction","CurrentMedication,CurrentDrugILABANewDrug",20000,5005,1009,1010,2009,2010) - FieldID: 2312

    public function fn_LungFunctionBookTests($arrInputs = array())
    {

        //$arrInputs[5] = "Continue";

        return $this->fn_SummarySoFarFlow(array($arrInputs[0], $arrInputs[1], $arrInputs[2], $arrInputs[3], "Continue", "Continue", "Continue", $arrInputs[7], $arrInputs[8], $arrInputs[9], $arrInputs[10], $arrInputs[11], $arrInputs[12], $arrInputs[13]));

    }

    //Flow 1009 AND 1010 AND 2009 AND 2010 - Therapy (Step 1-3) 1A / Therapy (Step 4-5) 1A / Therapy (Step 1-3) FU/AR / Therapy (Step 4-5) FU/AR

    //fn_TherapyOrDeviceChanged("Therapy,CheckOnNoTherapy",1112,1110) - FieldID: 2121
    //fn_TherapyOrDeviceChanged("Therapy,CheckOnNoTherapy",1112,1110) - FieldID: 2122
    //fn_TherapyOrDeviceChanged("Therapy,CheckOnNoTherapy",2012,2013) - FieldID: 2124
    //fn_TherapyOrDeviceChanged("Therapy,CheckOnNoTherapy",2012,2013) - FieldID: 2125

    function fn_TherapyOrDeviceChanged($arrInputs = array())
    {

        /*
        Current Drugs List 11/03/2016:

            ISABA							Inhaled short-acting beta agonist
            ICS								Inhaled corticosteroid
            ILABA							Inhaled long-acting beta agonist
            Comb							Combination therapy (ICS + LABA)
            ILAA							Inhaled long-acting anticholinergic
            LTRA							Oral antileukotriene antagonists
            Theophylline					Theophylline
            PDE4Inhibitor					Phosphodiesterase 4 inhibitor
            Mucolytic						Mucolytic
            ISAA							Inhaled short-acting anticholinergic
            OBA								Oral beta agonists
            NebSABA							Nebulised short-acting beta agonist
            NebSAA							Nebulised short-acting anticholinergic
            Cromolyns						Cromolyns
            OralSteroids					Oral steroids
            InjectableBetaAgonist			Injectable beta agonist
            LABALAMA						LABA/LAMA
            NebulisedSABASAMA						SABA/SAMA
            LongActingInjectableSteroids	Long acting injectable steroids
        */

        $Therapy_CheckOnNoTherapy = $arrInputs[0];

        $TherapyChangedFlow = $arrInputs[1];
        $TherapyMaintainedFlow = $arrInputs[2];
        $JustStartingTherapyFlow = $arrInputs[2];
        $JustFinishingTherapyFlow = $arrInputs[2];
        $NeverOnTherapyFlow = $arrInputs[2];

        switch ($Therapy_CheckOnNoTherapy) {
            case "TherapyChanged":
                return $TherapyChangedFlow;
                break;
            case "MaintainedTherapy":
                return $TherapyMaintainedFlow;
                break;
            case "JustFinishingTherapy":
                return $JustFinishingTherapyFlow;
                break;
            case "JustStartingTherapy":
                return $JustStartingTherapyFlow;
                break;
            case "NeverOnTherapy":
                return $NeverOnTherapyFlow = "O2";
                break;
            default:
                $CI =& get_instance();
                $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_TherapyOrDeviceChanged/1 Untitled');
                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

                redirect('/error/', 'refresh');
        }

    }


    //Flow 19000 - Previous Report
    //fn_PreviousReport("AssessmentDetails,AssessmentType",2001,3001) - FieldID: 2365
    public function fn_PreviousReport($arrInputs = array())
    {

        $AssessmentType = $arrInputs[0];

        $InitiateFollowUpFlow = $arrInputs[1];
        $ExacerbationFlow = $arrInputs[2];

        if ($AssessmentType == "FU" || $AssessmentType == "AR") {
            return $InitiateFollowUpFlow;
        } elseif ($AssessmentType == "EX") {
            return $ExacerbationFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/fn_PreviousReport/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
            redirect('/error/', 'refresh');
        }
    }


    //Flow 2001 - Initiate Follow Up (No Medication)
    //fn_InitiateFollowUpStepFlowNM("CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","AssessmentDetails,AssessmentType","FirstAssessment,ConfirmDiagnosis13","CurrentMedication,CurrentDrugILABANewDrug",2002,2003,2101,2201,2004) - FieldID: 2066
    public function fn_InitiateFollowUpStepFlowNM($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];
        $MedicationLevelAtStartOfLastVisit = $arrInputs[1];
        $AssessmentType = $arrInputs[2];
        $ConfirmDiagnosis13 = $arrInputs[3];
        $CurrentDrugILABANewDrug = $arrInputs[4];

        $AssessControlFTFlow = $arrInputs[5];
        $AssessControlFlow = $arrInputs[6];
        $ConfirmDiagnosisFTFlow = $arrInputs[7];
        $ConfirmDiagnosisFlow = $arrInputs[8];
        $NoMedicationFlow = $arrInputs[9];

        //If CurrentMedicationLevel = 0 and not on LABA alone then use MedicationLevelAtStartOfLastVisit
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            $FlowMedicationLevel = $MedicationLevelAtStartOfLastVisit;
        } else {
            $FlowMedicationLevel = $CurrentMedicationLevel;
        }

        //No Medication and not on LABA alone
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            return $NoMedicationFlow;
            //LABA alone
        } elseif ($CurrentMedicationLevel == 0 && !empty($CurrentDrugILABANewDrug)) {
            return $AssessControlFTFlow;
            //Recently stepped up from 1-3 to 4-5
        } elseif ($CurrentMedicationLevel >= 4 && $CurrentMedicationLevel <= 5 && $MedicationLevelAtStartOfLastVisit >= 1 && $MedicationLevelAtStartOfLastVisit <= 3) {
            return $ConfirmDiagnosisFlow;
            //Current Step 1-3 (or no medication and previously Step 1-3) on Annual Review with weak basis for diagnosis
        } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && $AssessmentType == "AR" && ($ConfirmDiagnosis13 == "Possible" || $ConfirmDiagnosis13 == "NoEvidence")) {
            return $ConfirmDiagnosisFTFlow;
            //Current Step 1-3 (or no medication and previously Step 1-3)
        } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3) {
            return $AssessControlFTFlow;
            //Current Step 4-5
        } elseif ($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5) {
            return $AssessControlFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_InitiateFollowUpStepFlowNM/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
            redirect('/error/', 'refresh');
        }
    }

    //Flow 2004 - Initiate Follow Up
    //fn_InitiateFollowUpStepFlow("CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","AssessmentDetails,AssessmentType","FirstAssessment,ConfirmDiagnosis13","CurrentMedication,CurrentDrugILABANewDrug",2002,2101,20000) - FieldID: 2802
    public function fn_InitiateFollowUpStepFlow($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];
        $MedicationLevelAtStartOfLastVisit = $arrInputs[1];
        $AssessmentType = $arrInputs[2];
        $ConfirmDiagnosis13 = $arrInputs[3];
        $CurrentDrugILABANewDrug = $arrInputs[4];

        $AssessControlFTFlow = $arrInputs[5];
        $ConfirmDiagnosisFTFlow = $arrInputs[6];
        $ExitFlow = $arrInputs[7];

        //If CurrentMedicationLevel = 0 and not on LABA alone then use MedicationLevelAtStartOfLastVisit
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            $FlowMedicationLevel = $MedicationLevelAtStartOfLastVisit;
        } else {
            $FlowMedicationLevel = $CurrentMedicationLevel;
        }

        //Previously Step 4-5
        if ($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5) {
            return $ExitFlow;
            //No medication for two visits
        } elseif ($FlowMedicationLevel == 0) {
            return $ExitFlow;
            //Previously Step 1-3 on Annual Review with weak basis for diagnosis
        } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && $AssessmentType == "AR" && ($ConfirmDiagnosis13 == "Possible" || $ConfirmDiagnosis13 == "NoEvidence")) {
            return $ConfirmDiagnosisFTFlow;
            //Previously Step 1-3 and on Follow-up or strong basis for diagnosis
        } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && ($AssessmentType == "FU" || ($ConfirmDiagnosis13 != "Possible" && $ConfirmDiagnosis13 != "NoEvidence"))) {
            return $AssessControlFTFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_InitiateFollowUpStepFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
            redirect('/error/', 'refresh');
        }
    }

    //Flow 2002 AND 2003 - Assess Control (FT) / Assess Control
    //fn_FUAssessControlFlow("CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","FirstAssessment,CurrentControl13","AssessmentDetails,AssessmentType","ProgressReview,AdherenceToTherapyAdequateLastTime","NonPharmaRx,InhalerTechniqueAdequateLastTime","CurrentMedication,CurrentDrugILABANewDrug",5004,5001,2011) - FieldID: 2067
    //fn_FUAssessControlFlow("CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","FirstAssessment,CurrentControl45","AssessmentDetails,AssessmentType","ProgressReview,AdherenceToTherapyAdequateLastTime","NonPharmaRx,InhalerTechniqueAdequateLastTime","CurrentMedication,CurrentDrugILABANewDrug",5004,5001,2011) - FieldID: 2068
    function fn_FUAssessControlFlow($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];
        $MedicationLevelAtStartOfLastVisit = $arrInputs[1];
        $ControlScore = $arrInputs[2];
        $AssessmentType = $arrInputs[3];
        $AdherenceToTherapyAdequateLastTime = $arrInputs[4];
        $InhalerTechniqueAdequateLastTime = $arrInputs[5];
        $CurrentDrugILABANewDrug = $arrInputs[6];

        $SummarySoFarFlow = $arrInputs[7];
        $LungFunctionFlow = $arrInputs[8];
        $EducationFlow = $arrInputs[9];

        //If CurrentMedicationLevel = 0 and not on LABA alone then use MedicationLevelAtStartOfLastVisit
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            $FlowMedicationLevel = $MedicationLevelAtStartOfLastVisit;
        } else {
            $FlowMedicationLevel = $CurrentMedicationLevel;
        }

        if ($AssessmentType == "AR"
            || $AdherenceToTherapyAdequateLastTime == "N"
            || $InhalerTechniqueAdequateLastTime == "N"
            || ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && ($ControlScore == "Poor" || $ControlScore == "Partial"))
            || ($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5 && $ControlScore == "Poor")
        ) {
            return $EducationFlow;
        } elseif ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && $ControlScore == "Good" && $AssessmentType == "FU" && $AdherenceToTherapyAdequateLastTime != "N" && $InhalerTechniqueAdequateLastTime != "N") {
            return $SummarySoFarFlow;
        } elseif ($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5 && $AssessmentType == "FU" && ($ControlScore == "Good" || $ControlScore == "Partial") && $AdherenceToTherapyAdequateLastTime != "N" && $InhalerTechniqueAdequateLastTime != "N") {
            return $LungFunctionFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_FUAssessControlFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }

    }

    //Flow 2011 - Follow-Up Education 1
    //fn_FUEducationFlow("CurrentMedication,CurrentMedicationLevel","CurrentMedication,MedicationLevelAtStartOfLastVisit","FirstAssessment,CurrentControl13","FirstAssessment,CurrentControl45","AssessmentDetails,AssessmentType","CurrentMedication,CurrentDrugILABANewDrug",5004,5001) - FieldID: 2594

    //SOME ISSUES HERE; NEED TO TEST! SEE HIGH LEVEL OVERVIEW AND COMPARE WITH ORIGINAL FLOW CHART

    function fn_FUEducationFlow($arrInputs = array())
    {

        $CurrentMedicationLevel = $arrInputs[0];
        $MedicationLevelAtStartOfLastVisit = $arrInputs[1];
        $CurrentControl13 = $arrInputs[2];
        $CurrentControl45 = $arrInputs[3];
        $AssessmentType = $arrInputs[4];
        $CurrentDrugILABANewDrug = $arrInputs[5];

        $SummarySoFarFlow = $arrInputs[6];
        $LungFunctionFlow = $arrInputs[7];

        //Set appropriate control score
        $ControlScore = $CurrentControl45 ?: $CurrentControl13;

        //If CurrentMedicationLevel = 0 and not on LABA alone then use MedicationLevelAtStartOfLastVisit
        if ($CurrentMedicationLevel == 0 && (!$CurrentDrugILABANewDrug || empty($CurrentDrugILABANewDrug))) {
            $FlowMedicationLevel = $MedicationLevelAtStartOfLastVisit;
        } else {
            $FlowMedicationLevel = $CurrentMedicationLevel;
        }

        //LABA Alone
        if ($CurrentMedicationLevel == 0 && !empty($CurrentDrugILABANewDrug)) {
            return $SummarySoFarFlow;
            //Step 1-3 (or no medication and Step 1-3 last time); good control; follow-up
        } else if ($FlowMedicationLevel >= 1 && $FlowMedicationLevel <= 3 && $ControlScore == "Good" && $AssessmentType == "FU") {
            return $SummarySoFarFlow;
            //Step 4-5 OR good or partial control OR annual review
        } elseif (($FlowMedicationLevel >= 4 && $FlowMedicationLevel <= 5)
            || ($ControlScore == "Partial" || $ControlScore == "Poor")
            || $AssessmentType == "AR") {
            return $LungFunctionFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_fn_FUEducationFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }

    }

    //Flow 3001 - Exacerbation 0 (Exacerbation Review)
    //fn_Exacerbation1EFFlow("Exacerbation,ProfessionalJudgement",20000,4001) - FieldID: 2137
    public function fn_Exacerbation1EFFlow($arrInputs = array())
    {

        $ProfessionalJudgement = $arrInputs[0];

        $ExitFlow = $arrInputs[1];
        $Exacerbation1Flow = $arrInputs[2];

        /*No change in asthma symptoms returns to exit flow*/
        if ($ProfessionalJudgement == "A") {
            return $ExitFlow;
            /*Mild or signifiant change in asthma symptoms returns next Exacerbation flow*/
        } elseif ($ProfessionalJudgement == "B" || $ProfessionalJudgement == "C") {
            return $Exacerbation1Flow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_Exacerbation1EFFlow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
            redirect('/error/', 'refresh');
        }
    }

    //Flow 4001 - Exacerbation 1
    //fn_Exacerbation1Flow("Exacerbation,ProfessionalJudgement","Exacerbation,ShowPlanBExitQuestions",20000,4002) - FieldID: 2123
    public function fn_Exacerbation1Flow($arrInputs = array())
    {

        $ProfessionalJudgement = $arrInputs[0];
        $ShowPlanBExitQuestions = $arrInputs[1];

        $ExitFlow = $arrInputs[2];
        $Exacerbation2Flow = $arrInputs[3];

        $CI =& get_instance();
        $arrFlowHistory = $CI->session->userdata("arrFlowHistory");
        $intPosition = array_search("4001", $arrFlowHistory);
        $previousFlow = '';
        if ($intPosition >= 2) {
            $previousFlow = $arrFlowHistory[$intPosition - 2];
        }

        //Two or more Yes OR Plan C Exacerbation flow
        if ($ProfessionalJudgement == "C" || $ShowPlanBExitQuestions == 0) {
            return $Exacerbation2Flow;
            //One and only one Yes OR Plan B (mild) from Exacerbation Review returns Exit Flow
        } elseif (($ProfessionalJudgement == "B" || $ShowPlanBExitQuestions == 1) && $previousFlow == '') {
            return $ExitFlow;
            //One and only one Yes OR Plan B (mild) from a standard Review returns Previous Flow
        } elseif (($ProfessionalJudgement == "B" || $ShowPlanBExitQuestions == 1) && $previousFlow != '') {
            return $this->exitFlow($previousFlow);
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_Exacerbation1Flow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }

    }

    //Flow 4004 - Exacerbation 3
    //fn_Exacerbation3Flow("Exacerbation,ManageAtHome","Exacerbation,SymptomImproved50","Exacerbation,SymptomImproved75","Exacerbation,SeverityMessages","Exacerbation,AlternativePathway4",20000,4005) - FieldID: 2733
    public function fn_Exacerbation3Flow($arrInputs = array())
    {

        $ManageAtHome = $arrInputs[0];
        $SymptomImproved50 = $arrInputs[1];
        $SymptomImproved75 = $arrInputs[2];
        $SeverityMessages = $arrInputs[3];
        $AlternativePathway4 = $arrInputs[4];

        $ExitFlow = $arrInputs[5];
        $SupplementaryQuestionsFlow = $arrInputs[6];

        if ($ManageAtHome == "Continue" || $SymptomImproved50 == "Y" || $SymptomImproved75 == "Y" || $SeverityMessages == "E7" || $AlternativePathway4 == "A") {
            return $SupplementaryQuestionsFlow;
        } elseif ($ManageAtHome != "Continue" && $SymptomImproved50 != "Y" && $SymptomImproved75 != "Y" && $SeverityMessages != "E7" && $AlternativePathway4 != "A") {
            return $ExitFlow;
        } else {
            $CI = &get_instance();
            $CI->session->set_flashdata('strTitle', 'ERROR FL/USERFUNC_Exacerbation3Flow/1 Untitled');
            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');

            redirect('/error/', 'refresh');
        }

    }

    //Placeholder Flow
    public function fn_PlaceholderExitFlow($arrInputs = array())
    {
        return $arrInputs[0];
    }

    /******************************************************************
     *
     * SCREEN FUNCTIONS
     ******************************************************************/


    //fn_CheckOnNoTherapy("CurrentMedication,CurrentDrugCombNewDrug","CurrentMedication,CurrentDrugCromolynsNewDrug","CurrentMedication,CurrentDrugICSNewDrug","CurrentMedication,CurrentDrugILAANewDrug","CurrentMedication,CurrentDrugILABANewDrug","CurrentMedication,CurrentDrugISAANewDrug","CurrentMedication,CurrentDrugISABANewDrug","CurrentMedication,CurrentDrugLTRANewDrug","CurrentMedication,CurrentDrugMucolyticNewDrug","CurrentMedication,CurrentDrugNebSAANewDrug","CurrentMedication,CurrentDrugNebSABANewDrug","CurrentMedication,CurrentDrugOBANewDrug","CurrentMedication,CurrentDrugOralSteroidsNewDrug","CurrentMedication,CurrentDrugPDE4InhibitorNewDrug","CurrentMedication,CurrentDrugTheophyllineNewDrug","CurrentMedication,CurrentDrugLongActingInjectableSteroidsNewDrug","CurrentMedication,CurrentDrugNebulisedSABASAMANewDrug","CurrentMedication,CurrentDrugLABALAMANewDrug","CurrentMedication,CurrentDrugInjectableBetaAgonistNewDrug","MedicationReview,ReviewDrugCombNewDrug","MedicationReview,ReviewDrugCromolynsNewDrug","MedicationReview,ReviewDrugICSNewDrug","MedicationReview,ReviewDrugILAANewDrug","MedicationReview,ReviewDrugILABANewDrug","MedicationReview,ReviewDrugISAANewDrug","MedicationReview,ReviewDrugISABANewDrug","MedicationReview,ReviewDrugLTRANewDrug","MedicationReview,ReviewDrugMucolyticNewDrug","MedicationReview,ReviewDrugNebSAANewDrug","MedicationReview,ReviewDrugNebSABANewDrug","MedicationReview,ReviewDrugOBANewDrug","MedicationReview,ReviewDrugOralSteroidsNewDrug","MedicationReview,ReviewDrugPDE4InhibitorNewDrug","MedicationReview,ReviewDrugTheophyllineNewDrug","MedicationReview,ReviewDrugLongActingInjectableSteroidsNewDrug","MedicationReview,ReviewDrugNebulisedSABASAMANewDrug","MedicationReview,ReviewDrugLABALAMANewDrug","MedicationReview,ReviewDrugInjectableBetaAgonistNewDrug","MedicationReview,ReviewDrugCombChangeType","MedicationReview,ReviewDrugCromolynsChangeType","MedicationReview,ReviewDrugICSChangeType","MedicationReview,ReviewDrugILAAChangeType","MedicationReview,ReviewDrugILABAChangeType","MedicationReview,ReviewDrugISAAChangeType","MedicationReview,ReviewDrugISABAChangeType","MedicationReview,ReviewDrugLTRAChangeType","MedicationReview,ReviewDrugMucolyticChangeType","MedicationReview,ReviewDrugNebSAAChangeType","MedicationReview,ReviewDrugNebSABAChangeType","MedicationReview,ReviewDrugOBAChangeType","MedicationReview,ReviewDrugOralSteroidsChangeType","MedicationReview,ReviewDrugPDE4InhibitorChangeType","MedicationReview,ReviewDrugTheophyllineChangeType","MedicationReview,ReviewDrugLongActingInjectableSteroidsChangeType","MedicationReview,ReviewDrugNebulisedSABASAMAChangeType","MedicationReview,ReviewDrugLABALAMAChangeType","MedicationReview,ReviewDrugInjectableBetaAgonistChangeType") - FieldID: 2813

    function fn_CheckOnNoTherapy($arrInputs = array())
    {

        /*
        Current Drugs List 11/03/2016:

            ISABA							Inhaled short-acting beta agonist
            ICS								Inhaled corticosteroid
            ILABA							Inhaled long-acting beta agonist
            Comb							Combination therapy (ICS + LABA)
            ILAA							Inhaled long-acting anticholinergic
            LTRA							Oral antileukotriene antagonists
            Theophylline					Theophylline
            PDE4Inhibitor					Phosphodiesterase 4 inhibitor
            Mucolytic						Mucolytic
            ISAA							Inhaled short-acting anticholinergic
            OBA								Oral beta agonists
            NebSABA							Nebulised short-acting beta agonist
            NebSAA							Nebulised short-acting anticholinergic
            Cromolyns						Cromolyns
            OralSteroids					Oral steroids
            InjectableBetaAgonist			Injectable beta agonist
            LABALAMA						LABA/LAMA
            NebulisedSABASAMA						SABA/SAMA
            LongActingInjectableSteroids	Long acting injectable steroids
        */

        $CurrentMedication_CurrentDrugCombNewDrug = $arrInputs[0];
        $CurrentMedication_CurrentDrugCromolynsNewDrug = $arrInputs[1];
        $CurrentMedication_CurrentDrugICSNewDrug = $arrInputs[2];
        $CurrentMedication_CurrentDrugILAANewDrug = $arrInputs[3];
        $CurrentMedication_CurrentDrugILABANewDrug = $arrInputs[4];
        $CurrentMedication_CurrentDrugISAANewDrug = $arrInputs[5];
        $CurrentMedication_CurrentDrugISABANewDrug = $arrInputs[6];
        $CurrentMedication_CurrentDrugLTRANewDrug = $arrInputs[7];
        $CurrentMedication_CurrentDrugMucolyticNewDrug = $arrInputs[8];
        $CurrentMedication_CurrentDrugNebSAANewDrug = $arrInputs[9];
        $CurrentMedication_CurrentDrugNebSABANewDrug = $arrInputs[10];
        $CurrentMedication_CurrentDrugOBANewDrug = $arrInputs[11];
        $CurrentMedication_CurrentDrugOralSteroidsNewDrug = $arrInputs[12];
        $CurrentMedication_CurrentDrugPDE4InhibitorNewDrug = $arrInputs[13];
        $CurrentMedication_CurrentDrugTheophyllineNewDrug = $arrInputs[14];
        $CurrentMedication_CurrentDrugLongActingInjectableSteroidsNewDrug = $arrInputs[15];
        $CurrentMedication_CurrentDrugNebulisedSABASAMANewDrug = $arrInputs[16];
        $CurrentMedication_CurrentDrugLABALAMANewDrug = $arrInputs[17];
        $CurrentMedication_CurrentDrugInjectableBetaAgonistNewDrug = $arrInputs[18];

        $MedicationReview_ReviewDrugCombNewDrug = $arrInputs[19];
        $MedicationReview_ReviewDrugCromolynsNewDrug = $arrInputs[20];
        $MedicationReview_ReviewDrugICSNewDrug = $arrInputs[21];
        $MedicationReview_ReviewDrugILAANewDrug = $arrInputs[22];
        $MedicationReview_ReviewDrugILABANewDrug = $arrInputs[23];
        $MedicationReview_ReviewDrugISAANewDrug = $arrInputs[24];
        $MedicationReview_ReviewDrugISABANewDrug = $arrInputs[25];
        $MedicationReview_ReviewDrugLTRANewDrug = $arrInputs[26];
        $MedicationReview_ReviewDrugMucolyticNewDrug = $arrInputs[27];
        $MedicationReview_ReviewDrugNebSAANewDrug = $arrInputs[28];
        $MedicationReview_ReviewDrugNebSABANewDrug = $arrInputs[29];
        $MedicationReview_ReviewDrugOBANewDrug = $arrInputs[30];
        $MedicationReview_ReviewDrugOralSteroidsNewDrug = $arrInputs[31];
        $MedicationReview_ReviewDrugPDE4InhibitorNewDrug = $arrInputs[32];
        $MedicationReview_ReviewDrugTheophyllineNewDrug = $arrInputs[33];
        $MedicationReview_ReviewDrugLongActingInjectableSteroidsNewDrug = $arrInputs[34];
        $MedicationReview_ReviewDrugNebulisedSABASAMANewDrug = $arrInputs[35];
        $MedicationReview_ReviewDrugLABALAMANewDrug = $arrInputs[36];
        $MedicationReview_ReviewDrugInjectableBetaAgonistNewDrug = $arrInputs[37];

        $MedicationReview_ReviewDrugCombChangeType = $arrInputs[38];
        $MedicationReview_ReviewDrugCromolynsChangeType = $arrInputs[39];
        $MedicationReview_ReviewDrugICSChangeType = $arrInputs[40];
        $MedicationReview_ReviewDrugILAAChangeType = $arrInputs[41];
        $MedicationReview_ReviewDrugILABAChangeType = $arrInputs[42];
        $MedicationReview_ReviewDrugISAAChangeType = $arrInputs[43];
        $MedicationReview_ReviewDrugISABAChangeType = $arrInputs[44];
        $MedicationReview_ReviewDrugLTRAChangeType = $arrInputs[45];
        $MedicationReview_ReviewDrugMucolyticChangeType = $arrInputs[46];
        $MedicationReview_ReviewDrugNebSAAChangeType = $arrInputs[47];
        $MedicationReview_ReviewDrugNebSABAChangeType = $arrInputs[48];
        $MedicationReview_ReviewDrugOBAChangeType = $arrInputs[49];
        $MedicationReview_ReviewDrugOralSteroidsChangeType = $arrInputs[50];
        $MedicationReview_ReviewDrugPDE4InhibitorChangeType = $arrInputs[51];
        $MedicationReview_ReviewDrugTheophyllineChangeType = $arrInputs[52];
        $MedicationReview_ReviewDrugLongActingInjectableSteroidsChangeType = $arrInputs[53];
        $MedicationReview_ReviewDrugNebulisedSABASAMAChangeType = $arrInputs[54];
        $MedicationReview_ReviewDrugLABALAMAChangeType = $arrInputs[55];
        $MedicationReview_ReviewDrugInjectableBetaAgonistChangeType = $arrInputs[56];


        //Check if ALL review empty
        if (
            empty($MedicationReview_ReviewDrugCombNewDrug)
            && empty($MedicationReview_ReviewDrugCromolynsNewDrug)
            && empty($MedicationReview_ReviewDrugICSNewDrug)
            && empty($MedicationReview_ReviewDrugILAANewDrug)
            && empty($MedicationReview_ReviewDrugILABANewDrug)
            && empty($MedicationReview_ReviewDrugISAANewDrug)
            && empty($MedicationReview_ReviewDrugISABANewDrug)
            && empty($MedicationReview_ReviewDrugLTRANewDrug)
            && empty($MedicationReview_ReviewDrugMucolyticNewDrug)
            && empty($MedicationReview_ReviewDrugNebSAANewDrug)
            && empty($MedicationReview_ReviewDrugNebSABANewDrug)
            && empty($MedicationReview_ReviewDrugOBANewDrug)
            && empty($MedicationReview_ReviewDrugOralSteroidsNewDrug)
            && empty($MedicationReview_ReviewDrugPDE4InhibitorNewDrug)
            && empty($MedicationReview_ReviewDrugTheophyllineNewDrug)
            && empty($MedicationReview_ReviewDrugLongActingInjectableSteroidsNewDrug)
            && empty($MedicationReview_ReviewDrugNebulisedSABASAMANewDrug)
            && empty($MedicationReview_ReviewDrugLABALAMANewDrug)
            && empty($MedicationReview_ReviewDrugInjectableBetaAgonistNewDrug)
        ) {

            //Either maintained, finished or never on therapy

            //Never On Therapy
            if (
                empty($CurrentMedication_CurrentDrugCombNewDrug)
                && empty($CurrentMedication_CurrentDrugCromolynsNewDrug)
                && empty($CurrentMedication_CurrentDrugICSNewDrug)
                && empty($CurrentMedication_CurrentDrugILAANewDrug)
                && empty($CurrentMedication_CurrentDrugILABANewDrug)
                && empty($CurrentMedication_CurrentDrugISAANewDrug)
                && empty($CurrentMedication_CurrentDrugISABANewDrug)
                && empty($CurrentMedication_CurrentDrugLTRANewDrug)
                && empty($CurrentMedication_CurrentDrugMucolyticNewDrug)
                && empty($CurrentMedication_CurrentDrugNebSAANewDrug)
                && empty($CurrentMedication_CurrentDrugNebSABANewDrug)
                && empty($CurrentMedication_CurrentDrugOBANewDrug)
                && empty($CurrentMedication_CurrentDrugOralSteroidsNewDrug)
                && empty($CurrentMedication_CurrentDrugPDE4InhibitorNewDrug)
                && empty($CurrentMedication_CurrentDrugTheophyllineNewDrug)
                && empty($CurrentMedication_CurrentDrugLongActingInjectableSteroidsNewDrug)
                && empty($CurrentMedication_CurrentDrugNebulisedSABASAMANewDrug)
                && empty($CurrentMedication_CurrentDrugLABALAMANewDrug)
                && empty($CurrentMedication_CurrentDrugInjectableBetaAgonistNewDrug)
            ) {

                echo "<script>console.log( 'NeverOnTherapy' );</script>";
                return "NeverOnTherapy";

                //Maintained or partially maintained (at least one therapy maintained though some may have been stopped, but not changed)
            } elseif (
                (!empty($CurrentMedication_CurrentDrugCombNewDrug) && $MedicationReview_ReviewDrugCombChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugCromolynsNewDrug) && $MedicationReview_ReviewDrugCromolynsChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugICSNewDrug) && $MedicationReview_ReviewDrugICSChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugILAANewDrug) && $MedicationReview_ReviewDrugILAAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugILABANewDrug) && $MedicationReview_ReviewDrugILABAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugISAANewDrug) && $MedicationReview_ReviewDrugISAAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugISABANewDrug) && $MedicationReview_ReviewDrugISABAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugLTRANewDrug) && $MedicationReview_ReviewDrugLTRAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugMucolyticNewDrug) && $MedicationReview_ReviewDrugMucolyticChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugNebSAANewDrug) && $MedicationReview_ReviewDrugNebSAAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugNebSABANewDrug) && $MedicationReview_ReviewDrugNebSABAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugOBANewDrug) && $MedicationReview_ReviewDrugOBAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugOralSteroidsNewDrug) && $MedicationReview_ReviewDrugOralSteroidsChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugPDE4InhibitorNewDrug) && $MedicationReview_ReviewDrugPDE4InhibitorChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugTheophyllineNewDrug) && $MedicationReview_ReviewDrugTheophyllineChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugLongActingInjectableSteroidsNewDrug) && $MedicationReview_ReviewDrugLongActingInjectableSteroidsChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugNebulisedSABASAMANewDrug) && $MedicationReview_ReviewDrugNebulisedSABASAMAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugLABALAMANewDrug) && $MedicationReview_ReviewDrugLABALAMAChangeType != "stop")
                || (!empty($CurrentMedication_CurrentDrugInjectableBetaAgonistNewDrug) && $MedicationReview_ReviewDrugInjectableBetaAgonistChangeType != "stop")
            ) {

                echo "<script>console.log( 'MaintainedTherapy' );</script>";
                return "MaintainedTherapy";

                //Finished all therapy
            } else {

                echo "<script>console.log( 'JustFinishingTherapy' );</script>";
                return "JustFinishingTherapy";

            }

            //Either changed or just started therapy for the first time
        } else {

            //Just starting therapy for the first time
            if (
                empty($CurrentMedication_CurrentDrugCombNewDrug)
                && empty($CurrentMedication_CurrentDrugCromolynsNewDrug)
                && empty($CurrentMedication_CurrentDrugICSNewDrug)
                && empty($CurrentMedication_CurrentDrugILAANewDrug)
                && empty($CurrentMedication_CurrentDrugILABANewDrug)
                && empty($CurrentMedication_CurrentDrugISAANewDrug)
                && empty($CurrentMedication_CurrentDrugISABANewDrug)
                && empty($CurrentMedication_CurrentDrugLTRANewDrug)
                && empty($CurrentMedication_CurrentDrugMucolyticNewDrug)
                && empty($CurrentMedication_CurrentDrugNebSAANewDrug)
                && empty($CurrentMedication_CurrentDrugNebSABANewDrug)
                && empty($CurrentMedication_CurrentDrugOBANewDrug)
                && empty($CurrentMedication_CurrentDrugOralSteroidsNewDrug)
                && empty($CurrentMedication_CurrentDrugPDE4InhibitorNewDrug)
                && empty($CurrentMedication_CurrentDrugTheophyllineNewDrug)
                && empty($CurrentMedication_CurrentDrugLongActingInjectableSteroidsNewDrug)
                && empty($CurrentMedication_CurrentDrugNebulisedSABASAMANewDrug)
                && empty($CurrentMedication_CurrentDrugLABALAMANewDrug)
                && empty($CurrentMedication_CurrentDrugInjectableBetaAgonistNewDrug)
            ) {

                echo "<script>console.log( 'JustStartingTherapy' );</script>";
                return "JustStartingTherapy";

                //Therapy Changed
            } else {

                echo "<script>console.log( 'TherapyChanged' );</script>";
                return "TherapyChanged";

            }

        }

    }

    //fn_CurrentControl13("CurrentControl,DifficultySleeping","CurrentControl,UsualAsthmaSymptoms","CurrentControl,InterferedWithUsualActivities","CurrentControl,FrequencyRelieverInhalerLastWeek","CurrentControl,Last12MonthsOralSteroidsYesNo","CurrentControl,Last12MonthsOralSteroidsLast3Months","CurrentControl,Last12MonthsAntibioticsYesNo","CurrentControl,Last12MonthsAntibioticsLast3Months","CurrentControl,Last12MonthsCallOutYesNo","CurrentControl,Last12MonthsCallOutLast3Months","CurrentControl,Last12MonthsAedOrHospYesNo","CurrentControl,Last12MonthsAedOrHospLast3Months","PEF,CurrentPEFPctPredicted") - FieldID: 2109

    public function fn_CurrentControl13($arrInputs = array())
    {

        $DifficultySleeping = $arrInputs[0];
        $UsualAsthmaSymptoms = $arrInputs[1];
        $InterferedWithUsualActivities = $arrInputs[2];
        $FrequencyRelieverInhalerLastWeek = $arrInputs[3];
        $Last12MonthsOralSteroidsYesNo = $arrInputs[4];
        $Last12MonthsOralSteroidsLast3Months = $arrInputs[5];
        $Last12MonthsAntibioticsYesNo = $arrInputs[6];
        $Last12MonthsAntibioticsLast3Months = $arrInputs[7];
        $Last12MonthsCallOutYesNo = $arrInputs[8];
        $Last12MonthsCallOutLast3Months = $arrInputs[9];
        $Last12MonthsAedOrHospYesNo = $arrInputs[10];
        $Last12MonthsAedOrHospLast3Months = $arrInputs[11];
        $CurrentPEFPctPredicted = $arrInputs[12];

        if (
            // RCP 3 questions all negative
            $DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N"
            // No reliever inhalations in previous week
            && ($FrequencyRelieverInhalerLastWeek == "0" || $FrequencyRelieverInhalerLastWeek == "NA")
            // No exacerbations (last 12 months)
            && $Last12MonthsOralSteroidsYesNo == "N" && $Last12MonthsAntibioticsYesNo == "N" && $Last12MonthsCallOutYesNo == "N" && $Last12MonthsAedOrHospYesNo == "N"
            // PEF > 80% Predicted or Best
            && ($CurrentPEFPctPredicted > 80 || $CurrentPEFPctPredicted == 0)
        ) {
            return "Good";
        } elseif (
            // Two or three RCP 3 questions positive
            (
                ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y") || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
            )
            // 3-4 or more use of reliever inhalations most days in previous week
            || $FrequencyRelieverInhalerLastWeek == "3-4" || $FrequencyRelieverInhalerLastWeek == "5+"
            // Exacerbations (last 3 months)
            || $Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y"
            // PEF <= 65% Predicted or Best
            || ($CurrentPEFPctPredicted <= 65 && $CurrentPEFPctPredicted != 0)
        ) {
            return "Poor";
        } elseif (
            // One of RCP 3 questions positive
            (
                ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
            )
            // <= 2 Reliever inhalations in previous week
            || $FrequencyRelieverInhalerLastWeek == "1-2"
            // One Exacerbations (last 12 months) but none in last 3 months
            || (
                ($Last12MonthsOralSteroidsYesNo == "Y" || $Last12MonthsAntibioticsYesNo == "Y" || $Last12MonthsCallOutYesNo == "Y" || $Last12MonthsAedOrHospYesNo == "Y") && $Last12MonthsOralSteroidsLast3Months != "Y" && $Last12MonthsAntibioticsLast3Months != "Y" && $Last12MonthsCallOutLast3Months != "Y" && $Last12MonthsAedOrHospLast3Months != "Y"
            )
            // PEF > 65% and <= 80% Predicted or Best
            || ($CurrentPEFPctPredicted > 65 && $CurrentPEFPctPredicted <= 80 && $CurrentPEFPctPredicted != 0)
        ) {
            return "Partial";
        } else {
            return "Undetermined";
        }

    }

    //fn_CurrentControl45("CurrentControl,DifficultySleeping","CurrentControl,UsualAsthmaSymptoms","CurrentControl,InterferedWithUsualActivities","CurrentControl,Last12MonthsOralSteroidsYesNo","CurrentControl,Last12MonthsOralSteroidsLast3Months","CurrentControl,Last12MonthsAntibioticsYesNo","CurrentControl,Last12MonthsAntibioticsLast3Months","CurrentControl,Last12MonthsCallOutYesNo","CurrentControl,Last12MonthsCallOutLast3Months","CurrentControl,Last12MonthsAedOrHospYesNo","CurrentControl,Last12MonthsAedOrHospLast3Months","CurrentControl,AsthmaControlScore","PEF,CurrentPEFPctPredicted","Spirometry,PercentPredictedFEV1","LungFunction,LungFunctionPerformed") - FieldID: 2118

    public function fn_CurrentControl45($arrInputs = array())
    {

        $DifficultySleeping = $arrInputs[0];
        $UsualAsthmaSymptoms = $arrInputs[1];
        $InterferedWithUsualActivities = $arrInputs[2];
        $Last12MonthsOralSteroidsYesNo = $arrInputs[3];
        $Last12MonthsOralSteroidsLast3Months = $arrInputs[4];
        $Last12MonthsAntibioticsYesNo = $arrInputs[5];
        $Last12MonthsAntibioticsLast3Months = $arrInputs[6];
        $Last12MonthsCallOutYesNo = $arrInputs[7];
        $Last12MonthsCallOutLast3Months = $arrInputs[8];
        $Last12MonthsAedOrHospYesNo = $arrInputs[9];
        $Last12MonthsAedOrHospLast3Months = $arrInputs[10];
        $AsthmaControlScore = $arrInputs[11];
        $CurrentPEFPctPredicted = $arrInputs[12];
        $PercentPredictedFEV1 = $arrInputs[13];
        $LungFunctionPerformed = $arrInputs[14];

        if (
            // RCP 3 questions all negative and ACT >= 20
            $DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20
            // No exacerbations (last 12 months)
            && $Last12MonthsOralSteroidsYesNo == "N" && $Last12MonthsAntibioticsYesNo == "N" && $Last12MonthsCallOutYesNo == "N" && $Last12MonthsAedOrHospYesNo == "N"
            // PEF or FEV1 > 80% Predicted or Best
            && (($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
            )
        ) {
            return "Good";
        } elseif (
            // Two or 3 RCP 3 questions positive
            (
                ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N")
                || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
                || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
                || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
            )
            // ACT < 15
            || $AsthmaControlScore < 15
            // PEF or FEV1 <= 65% Predicted or Best
            || ($CurrentPEFPctPredicted <= 65 && $LungFunctionPerformed == "PEF Performed")
            || ($PercentPredictedFEV1 <= 65 && $LungFunctionPerformed == "Spirometry performed")
            // Exacerbations (last 3 months) but not otherwise good control
            || (
                ($Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y")
                && !($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20)
                && !(($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                    || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                    || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
                )
            )
        ) {
            return "Poor";
        } elseif (
            // One of RCP 3 questions positive
            (
                ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N")
                || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N")
                || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
            )
            // ACT >= 15 AND ACT < 20
            || ($AsthmaControlScore >= 15 && $AsthmaControlScore < 20)
            // One Exacerbations (last 12 months) but none in last 3 months
            || (
                ($Last12MonthsOralSteroidsYesNo == "Y" || $Last12MonthsAntibioticsYesNo == "Y" || $Last12MonthsCallOutYesNo == "Y" || $Last12MonthsAedOrHospYesNo == "Y")
                && ($Last12MonthsOralSteroidsLast3Months != "Y" && $Last12MonthsAntibioticsLast3Months != "Y" && $Last12MonthsCallOutLast3Months != "Y" && $Last12MonthsAedOrHospLast3Months != "Y")
            )
            // Exacerbations (last 3 months) but otherwise good control
            || (
                ($Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y")
                && ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20)
                && (($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                    || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                    || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
                )
            )
            // PEF or FEV1 Between 65% and 80% of Predicted or Best
            || ($CurrentPEFPctPredicted > 65 && $CurrentPEFPctPredicted <= 80 && $LungFunctionPerformed == "PEF Performed")
            || ($PercentPredictedFEV1 > 65 && $PercentPredictedFEV1 <= 80 && $LungFunctionPerformed == "Spirometry performed")
        ) {
            return "Partial";
        } else {
            return "Undetermined";

            $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
        }
    }

    //NEW CONTROL LEVEL FUNCTION (temporarily being run at the end of NPRX1 screen for testing; need to replace all uses of Field 2109 and 2118 eventually)
    //fn_CurrentControl("CurrentControl,DifficultySleeping","CurrentControl,UsualAsthmaSymptoms","CurrentControl,InterferedWithUsualActivities","CurrentControl,Last12MonthsOralSteroidsYesNo","CurrentControl,Last12MonthsOralSteroidsLast3Months","CurrentControl,Last12MonthsAntibioticsYesNo","CurrentControl,Last12MonthsAntibioticsLast3Months","CurrentControl,Last12MonthsCallOutYesNo","CurrentControl,Last12MonthsCallOutLast3Months","CurrentControl,Last12MonthsAedOrHospYesNo","CurrentControl,Last12MonthsAedOrHospLast3Months","CurrentControl,AsthmaControlScore","PEF,CurrentPEFPctPredicted","Spirometry,PercentPredictedFEV1","LungFunction,LungFunctionPerformed","CurrentControl,FrequencyRelieverInhalerLastWeek","FirstAssessment,FastOrStandardTrack") - FieldID: 2819

    public function fn_CurrentControl($arrInputs = array())
    {

        $DifficultySleeping = $arrInputs[0];
        $UsualAsthmaSymptoms = $arrInputs[1];
        $InterferedWithUsualActivities = $arrInputs[2];
        $Last12MonthsOralSteroidsYesNo = $arrInputs[3];
        $Last12MonthsOralSteroidsLast3Months = $arrInputs[4];
        $Last12MonthsAntibioticsYesNo = $arrInputs[5];
        $Last12MonthsAntibioticsLast3Months = $arrInputs[6];
        $Last12MonthsCallOutYesNo = $arrInputs[7];
        $Last12MonthsCallOutLast3Months = $arrInputs[8];
        $Last12MonthsAedOrHospYesNo = $arrInputs[9];
        $Last12MonthsAedOrHospLast3Months = $arrInputs[10];
        $AsthmaControlScore = $arrInputs[11];
        $CurrentPEFPctPredicted = $arrInputs[12];
        $PercentPredictedFEV1 = $arrInputs[13];
        $LungFunctionPerformed = $arrInputs[14];

        $FrequencyRelieverInhalerLastWeek = $arrInputs[15];
        $FastOrStandardTrack = $arrInputs[16];

        //Standard Track Control Algorithm
        if ($FastOrStandardTrack == "Standard Track") {

            if (
                // RCP 3 questions all negative and ACT >= 20
                $DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20
                // No exacerbations (last 12 months)
                && $Last12MonthsOralSteroidsYesNo == "N" && $Last12MonthsAntibioticsYesNo == "N" && $Last12MonthsCallOutYesNo == "N" && $Last12MonthsAedOrHospYesNo == "N"
                // PEF or FEV1 > 80% Predicted or Best
                && (($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                    || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                    || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
                )
            ) {
                return "Good";
            } elseif (
                // Two or 3 RCP 3 questions positive
                (
                    ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N")
                    || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
                    || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
                    || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
                )
                // ACT < 15
                || $AsthmaControlScore < 15
                // PEF or FEV1 <= 65% Predicted or Best
                || ($CurrentPEFPctPredicted <= 65 && $LungFunctionPerformed == "PEF Performed")
                || ($PercentPredictedFEV1 <= 65 && $LungFunctionPerformed == "Spirometry performed")
                // Exacerbations (last 3 months) but not otherwise good control
                || (
                    ($Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y")
                    && !($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20)
                    && !(($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                        || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                        || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
                    )
                )
            ) {
                return "Poor";
            } elseif (
                // One of RCP 3 questions positive
                (
                    ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N")
                    || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N")
                    || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
                )
                // ACT >= 15 AND ACT < 20
                || ($AsthmaControlScore >= 15 && $AsthmaControlScore < 20)
                // One Exacerbations (last 12 months) but none in last 3 months
                || (
                    ($Last12MonthsOralSteroidsYesNo == "Y" || $Last12MonthsAntibioticsYesNo == "Y" || $Last12MonthsCallOutYesNo == "Y" || $Last12MonthsAedOrHospYesNo == "Y")
                    && ($Last12MonthsOralSteroidsLast3Months != "Y" && $Last12MonthsAntibioticsLast3Months != "Y" && $Last12MonthsCallOutLast3Months != "Y" && $Last12MonthsAedOrHospLast3Months != "Y")
                )
                // Exacerbations (last 3 months) but otherwise good control
                || (
                    ($Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y")
                    && ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N" && $AsthmaControlScore >= 20)
                    && (($CurrentPEFPctPredicted > 80 && $LungFunctionPerformed == "PEF Performed")
                        || ($PercentPredictedFEV1 > 80 && $LungFunctionPerformed == "Spirometry performed")
                        || (empty($LungFunctionPerformed) || !$LungFunctionPerformed)
                    )
                )
                // PEF or FEV1 Between 65% and 80% of Predicted or Best
                || ($CurrentPEFPctPredicted > 65 && $CurrentPEFPctPredicted <= 80 && $LungFunctionPerformed == "PEF Performed")
                || ($PercentPredictedFEV1 > 65 && $PercentPredictedFEV1 <= 80 && $LungFunctionPerformed == "Spirometry performed")
            ) {
                return "Partial";
            } else {
                return "Undetermined (Standard Track)";

                $CI->session->set_flashdata('strMessage', 'You should not see this message.  The following may be helpful.<br /><br />The array contents were: [' . implode("|", $arrInputs) . ']');
            }

            //Fast Track Control Algorithm
        } elseif ($FastOrStandardTrack == "Fast Track") {

            if (
                // RCP 3 questions all negative
                $DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N"
                // No reliever inhalations in previous week
                && ($FrequencyRelieverInhalerLastWeek == "0" || $FrequencyRelieverInhalerLastWeek == "NA")
                // No exacerbations (last 12 months)
                && $Last12MonthsOralSteroidsYesNo == "N" && $Last12MonthsAntibioticsYesNo == "N" && $Last12MonthsCallOutYesNo == "N" && $Last12MonthsAedOrHospYesNo == "N"
                // PEF > 80% Predicted or Best
                && ($CurrentPEFPctPredicted > 80 || $CurrentPEFPctPredicted == 0)
            ) {
                return "Good";
            } elseif (
                // Two or three RCP 3 questions positive
                (
                    ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y") || ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "Y")
                )
                // 3-4 or more use of reliever inhalations most days in previous week
                || $FrequencyRelieverInhalerLastWeek == "3-4" || $FrequencyRelieverInhalerLastWeek == "5+"
                // Exacerbations (last 3 months)
                || $Last12MonthsOralSteroidsLast3Months == "Y" || $Last12MonthsAntibioticsLast3Months == "Y" || $Last12MonthsCallOutLast3Months == "Y" || $Last12MonthsAedOrHospLast3Months == "Y"
                // PEF <= 65% Predicted or Best
                || ($CurrentPEFPctPredicted <= 65 && $CurrentPEFPctPredicted != 0)
            ) {
                return "Poor";
            } elseif (
                // One of RCP 3 questions positive
                (
                    ($DifficultySleeping == "Y" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "Y" && $InterferedWithUsualActivities == "N") || ($DifficultySleeping == "N" && $UsualAsthmaSymptoms == "N" && $InterferedWithUsualActivities == "Y")
                )
                // <= 2 Reliever inhalations in previous week
                || $FrequencyRelieverInhalerLastWeek == "1-2"
                // One Exacerbations (last 12 months) but none in last 3 months
                || (
                    ($Last12MonthsOralSteroidsYesNo == "Y" || $Last12MonthsAntibioticsYesNo == "Y" || $Last12MonthsCallOutYesNo == "Y" || $Last12MonthsAedOrHospYesNo == "Y") && $Last12MonthsOralSteroidsLast3Months != "Y" && $Last12MonthsAntibioticsLast3Months != "Y" && $Last12MonthsCallOutLast3Months != "Y" && $Last12MonthsAedOrHospLast3Months != "Y"
                )
                // PEF > 65% and <= 80% Predicted or Best
                || ($CurrentPEFPctPredicted > 65 && $CurrentPEFPctPredicted <= 80 && $CurrentPEFPctPredicted != 0)
            ) {
                return "Partial";
            } else {
                return "Undetermined (Fast Track)";
            }

        } else {
            return "Undetermined (No track information)";
        }

    }

    //fn_CurrentMedicationLevelStep(
    //"CurrentMedication,IcsBdpEquivIncUse",                        640
    //"CurrentMedication,ComboBdpEquivIncUse",                      1000
    //"CurrentMedication,CurrentDrugISABANewDrug",                  2
    //"CurrentMedication,CurrentDrugICSNewDrug",                    54
    //"CurrentMedication,CurrentDrugILABANewDrug",                  24
    //"CurrentMedication,CurrentDrugCombNewDrug",                   149
    //"CurrentMedication,CurrentDrugILAANewDrug",                   47
    //"CurrentMedication,CurrentDrugLTRANewDrug",                   339
    //"CurrentMedication,CurrentDrugTheophyllineNewDrug",           277
    //"CurrentMedication,CurrentDrugPDE4InhibitorNewDrug",          337
    //"CurrentMedication,CurrentDrugMucolyticNewDrug",              178
    //"CurrentMedication,CurrentDrugISAANewDrug",                   22
    //"CurrentMedication,CurrentDrugOBANewDrug",                    302
    //"CurrentMedication,CurrentDrugNebSABANewDrug",                291
    //"CurrentMedication,CurrentDrugNebSAANewDrug",                 300
    //"CurrentMedication,CurrentDrugNebICSNewDrug",                 null
    //"CurrentMedication,CurrentDrugCromolynsNewDrug",              312
    //"CurrentMedication,CurrentDrugOralSteroidsNewDrug",           209
    //"CurrentMedication,HowLongOralSteroids",                      null
    //"CurrentMedication,CurrentDrugInjectableBetaAgonist",         null
    //"CurrentMedication,CurrentDrugLABALAMA",                      null
    //"CurrentMedication,CurrentDrugNebulisedSABASAMA",             null
    //"CurrentMedication,CurrentDrugLongActingInjectableSteroids"   null
    //) - FieldID: 400

    public function fn_CurrentMedicationLevelStep($arrInputs = array())
    {

        $BdpIcs = $arrInputs[0]; // Need to change to equiv
        $BdpCombo = $arrInputs[1];
        $CurrentISABA = $arrInputs[2];
        $CurrentICS = $arrInputs[3];
        $CurrentILABA = $arrInputs[4];
        $CurrentComb = $arrInputs[5];
        $CurrentILAA = $arrInputs[6];
        $CurrentLTRA = $arrInputs[7];
        $CurrentTheophylline = $arrInputs[8];
        $CurrentPDE4Inhibitor = $arrInputs[9];
        $CurrentMucolytic = $arrInputs[10];
        $CurrentISAA = $arrInputs[11];
        $CurrentOBA = $arrInputs[12];
        $CurrentNebSABA = $arrInputs[13];
        $CurrentNebSAA = $arrInputs[14];
        $CurrentNebICS = $arrInputs[15];
        $CurrentCromone = $arrInputs[16];
        $CurrentOralSteroids = $arrInputs[17];
        $HowLongOralSteroids = $arrInputs[18];

        $CurrentDrugInjectableBetaAgonist = $arrInputs[19];
        $CurrentDrugLABALAMA = $arrInputs[20];
        $CurrentDrugNebulisedSABASAMA = $arrInputs[21];
        $CurrentDrugLongActingInjectableSteroids = $arrInputs[22];

        //var_dump($totalSteroidDose);die();

        // 03/18/2020
        // for updated step algorithm
        // step 0: Step O
        // step A: As required reliever therapy
        // step B: Regular preventer therapy
        // step C: Initial "add on therapy"
        // step D: Additional Controller therapies
        // step E1: Specialist therapies            // A patient who is using nebulised therapies enters this step
        // step E2: Hospital Specialist therapies   // A patient who is using nebulised therapies enters this step
//        echo json_encode($CurrentICS);
//	    die();

        // Check if SABA
        if ($CurrentISABA != NULL) {
            return $MedicationLevel = "A";
        } else {
            // Check if ICS
            if ($CurrentICS != NULL) {
                // Check if Low dose ICS
                if (intval($CurrentICS) < 400) {
                    // If LABA + MART, then Step C
                    if ($CurrentILABA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART") {
                        return $MedicationLevel = "C";
                    }

                    // If low dose ICS, then Step B
                    return $MedicationLevel = "B";
                } // If medium does ICS
                else if (intval($CurrentICS) < 800) {
                    // If medium does ICS + (LABA, LABA + LRTA, or LRTA) , then Step D
                    if ($CurrentILABA != NULL || $CurrentLTRA != NULL) {
                        return $MedicationLevel = "D";
                    }
                } else {
                    // If high dose ICS + (, LAVA + MART, LAVA + MART + LRTA, LAVA + MART + LAMA, LATA + MART + Theophyline, LAMA/LABA,) , then Step E1
                    if (
                        ($CurrentILABA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART") ||
                        ($CurrentILABA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART" && $CurrentLTRA) ||
                        ($CurrentILABA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART" && $CurrentDrugLABALAMA != NULL) ||
                        ($CurrentLTRA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART" && $CurrentTheophylline) ||
                        ($CurrentDrugLABALAMA != NULL)
                    ) {
                        return $MedicationLevel = "E1";
                    }

                    return $MedicationLevel = "E1";
                }

                // If low dose ICS + LAVA fixed dose or MART + LRTA, then Step D
                if ((intval($CurrentICS) < 400 && $CurrentILABA != NULL) || ($CurrentILABA != NULL && $CurrentComb != NULL && $BdpCombo == "SMART")) {
                    return $MedicationLevel = "D";
                }

                // If NebSABA or NebSAA or NebICS or InjectableSteroid or OralSteroid
                if ($CurrentNebSABA != NULL || $CurrentNebSAA != NULL || $CurrentNebICS != NULL || $CurrentDrugLongActingInjectableSteroids != NULL || $CurrentOralSteroids != NULL) {
                    return $MedicationLevel = "E1";
                }
            }

            // Non-standard use of drugs LAMA, LABA, Theophyline, LRTA, alone
            return $MedicationLevel = "0";
        }


        //If Oral Steroids >= 4 weeks then Step 5
//		if ($CurrentOralSteroids != NULL && $HowLongOralSteroids != "<4weeks") {
//            return $MedicationLevel = "5";
//        } else {
//			//If Nebulised SABA, Nebulised SAA, Nebulised ICS, or Injectable Steroid then Step 4
//			if ($CurrentNebSABA != NULL || $CurrentNebSAA != NULL || $CurrentNebICS  != NULL || $CurrentDrugLongActingInjectableSteroids  != NULL) {
//				 return $MedicationLevel = "4";
//				 //echo "<h1>41</h1>";
//			} else {
//				//Check if on ICS or Combo
//				if ($CurrentICS != NULL || $CurrentComb != NULL){
//					//If ICS AND Combo together, OR Combo with any of ILABA, LTRA, Theophylline,ILAA, or LABA/LAMA then Step 4
//					if (($CurrentICS != NULL && $CurrentComb != NULL) || ( $CurrentComb != NULL & ($CurrentILABA != NULL || $CurrentLTRA != NULL || $CurrentTheophylline != NULL || $CurrentILAA != NULL || $CurrentDrugLABALAMA != NULL) ) ){
//						//echo "<h1>42</h1>";
//						return $MedicationLevel = "4";
//					} else {
//						//If Combo with SMART dosing then Step 3
//						if($CurrentComb != NULL && $BdpCombo == "SMART"){
//							return $MedicationLevel = "3";
//						} else {
//							if ($totalSteroidDose > 800){
//								return $MedicationLevel = "4";
//								//echo "<h1>43</h1>";
//							} else {
//
//								$noOthers = 0;
//								//if ($totalSteroidDose > 400) {$noOthers = $noOthers+1;}
//								if ($totalSteroidDose > 400 && $CurrentICS != NULL) {$noOthers = $noOthers+1;} //Probably this
//								if ($CurrentComb != NULL) {$noOthers = $noOthers+1;}
//								if ($CurrentILABA != NULL) {$noOthers = $noOthers+1;}
//								if ($CurrentLTRA != NULL) {$noOthers = $noOthers+1;}
//								if ($CurrentTheophylline != NULL) {$noOthers = $noOthers+1;}
//								if ($CurrentILAA != NULL) {$noOthers = $noOthers+1;}
//								if ($CurrentDrugLABALAMA != NULL) {$noOthers = $noOthers+2;}
//
//								//echo "<h1>NO OTHERS ".$noOthers."</h1>";
//
//								if($noOthers < 1){
//									return $MedicationLevel = "2";
//								} elseif ($noOthers == 1) {
//									return $MedicationLevel = "3";
//								} else {
//									//echo "<h1>44</h1>";
//									return $MedicationLevel = "4";
//								}
//
//							}
//						}
//
//					}
//
//
//				} else {
//					//If LTRA, Cromone, or Theophylline then Step 2
//					if($CurrentLTRA != NULL || $CurrentCromone !== NULL || $CurrentTheophylline != NULL  /*|| $CurrentILABA !== NULL|| $CurrentLTRA !== NULL|| $CurrentILAA !== NULL || $CurrentDrugLABALAMA !== NULL*/){
//						return $MedicationLevel = "2";
//					//If ISAMA, ISAA, or OBA then Step 1
//					} elseif ($CurrentISABA  != NULL || $CurrentISAA != NULL || $CurrentOBA != NULL ){
//						return $MedicationLevel = "1";
//					} else {
//					//Otherwise Step 0 - no recognised asthma therapy
//						return $MedicationLevel = "0";
//					}
//
//				}
//			}
//
//        }


        /*

        if ($CurrentOralSteroids != NULL && ($HowLongOralSteroids == "1-2 Months" || $HowLongOralSteroids == "3-12 Months" || $HowLongOralSteroids == "> 1 year")) { // Do we need to add 2 weeks?
            return $MedicationLevel = "5";
        } else {
            if ($CurrentNebSABA != NULL || $CurrentNebSAA != NULL || $CurrentNebICS  != NULL) {
                 return $MedicationLevel = "4";
            } else {
                if ($CurrentICS != NULL){
                    if ($BdpIcs <= 400){
                        if ($CurrentILABA != NULL || $CurrentComb != NULL || $CurrentLTRA  != NULL || $CurrentTheophylline  != NULL || $CurrentILAA  != NULL)  {
                             return $MedicationLevel = "3";
                        } else {
                             return $MedicationLevel = "2";
                        }
                    } elseif ($BdpIcs <= 1000) {
                        if ($CurrentILABA != NULL || $CurrentComb != NULL || $CurrentLTRA  != NULL || $CurrentTheophylline  != NULL || $CurrentILAA  != NULL)  {
                             return $MedicationLevel = "4";
                        } else {
                             return $MedicationLevel = "3";
                        }
                    } elseif ($BdpIcs > 1000){
                        return $MedicationLevel = "4";
                    } else {
                        return $MedicationLevel = "ICS_ERROR";
                    }

                } else {
                    if ($CurrentComb != NULL){
                        if ($CurrentLTRA != NULL || $CurrentILAA != NULL || $CurrentTheophylline != NULL){
                             return $MedicationLevel = "4";
                        } else {
                             return $MedicationLevel = "3";
                        }
                    } else {
                        if ($CurrentLTRA != NULL || $CurrentCromone != NULL){
                             return $MedicationLevel = "2";
                        } else {
                            if ($CurrentISABA  != NULL || $CurrentISAA != NULL || $CurrentOBA != NULL || $CurrentTheophylline != NULL ){
                                 return $MedicationLevel = "1";
                            } else {
                                 return $MedicationLevel = "0";
                            }
                        }
                    }

                }
            }

        }

        */
    }

    //fn_LungFunctionPerformed - FieldID: 2249
    function fn_LungFunctionPerformed($arrInputs = array())
    {

        $SurgeryLast2weeksAbdominal = $arrInputs[0];
        $SurgeryLast2weeksThoracic = $arrInputs[1];
        $SurgeryLast2weeksEye = $arrInputs[2];
        $PneumothoraxLast2Weeks = $arrInputs[3];
        $SpirometryNotPerformedAnyOtherReason = $arrInputs[4];
        $IsSpirometryAvailable = $arrInputs[5];

        if ($SurgeryLast2weeksAbdominal == "Y" || $SurgeryLast2weeksThoracic == "Y" || $SurgeryLast2weeksEye == "Y" || $PneumothoraxLast2Weeks == "Y" || $SpirometryNotPerformedAnyOtherReason == "Y") {
            return $LfFlowType = "PEF Performed";
        } elseif ($IsSpirometryAvailable == "Y") {
            return $LfFlowType = "Spirometry performed";
        } elseif ($IsSpirometryAvailable == "N") {
            return $LfFlowType = "PEF Performed";
        } else {
            return $LfFlowType = "Error";
        }

    }

    //fn_Therapy - FieldID: 213
    function fn_Therapy($arrInputs = array())
    {

        echo json_encode($arrInputs);
        die();

        $FirstAssessmentFastOrStandardTrack = $arrInputs[0];
        $CurrentMedicationLevel = $arrInputs[1];
        $CurrentControl13 = $arrInputs[2];
        $CurrentControl45 = $arrInputs[3];
        $PresentControl6Months = $arrInputs[4]; // Added Exception
        $FirstTimeGoodControl = $arrInputs[5]; // Added Exception
        $AdherenceToTherapyAdequate = $arrInputs[6];
        $PresentControl3Months = $arrInputs[7];     // "Y","N"
        $UnderHospitalCare = $arrInputs[8];
        $ReferFormalAssessment = $arrInputs[9];
        $StepDownTherapyNow = $arrInputs[10];
        $StepUpTherapyNow = $arrInputs[11];
        $InhalerTechniqueAdequate = $arrInputs[12];
        $NewPatientFirstVisit = $arrInputs[13];
        $HowLongOralSteroids = $arrInputs[14];

        $InhaledCorticosteroids = $arrInputs[15];

        $Laba = $arrInputs[16];
        $Lama = $arrInputs[17];
        $Theo = $arrInputs[18];
        $Ltra = $arrInputs[19];

        $ControlLastTime = $arrInputs[20];

        #### Fields to add 27/10/2015
        $Saba = $arrInputs[21]; # 	CurrentMedication	CurrentDrugISABA
        $StepDownFailedBefore = $arrInputs[22]; #ProgressReview	StepDownFailed
        $ControlOptions = $arrInputs[23];#ProgressReview	ControlOptions "AwaitAdvice","IncreaseTherapy","OralSteroid"
        $InhalerTechniqueSatisfactory = $arrInputs[24];

        ### Fields to add 24/11/2015

        $TherapyOptionsN2 = $arrInputs[25];    #MedicationReview	TherapyOptionsN2	"Maintain", "StepUpWithin3", "SMART", "StepUpTo4"
        $TherapyOptionsO1 = $arrInputs[26];        #MedicationReview	TherapyOptionsO1	"Maintain", "SMART", "StepUpTo4"
        $TherapyOptionsQ = $arrInputs[27];        #MedicationReview	TherapyOptionsQ		"SMART", "StepUpTo4"
        $TherapyOptionsD9 = $arrInputs[28];        #MedicationReview	TherapyOptionsD9	"StepUpWithin4", "StepUpTo5"
        $TherapyOptionsD11 = $arrInputs[29];    #MedicationReview	TherapyOptionsD11	"Same", "Increase"
        $AcuteReason = $arrInputs[30];            #FollowUp	AcuteReason "Y","N"

        $FollowUpTestsOrReferral = $arrInputs[31];   #	FollowUp	TestsOrReferral	Tests,"Referral"
        /*$FollowUpPossibleAction =  $arrInputs[32];   #	FollowUp	PossibleAction	Step 4 Option,"More Tests","Referral"
        $FollowUpProbableAction =  $arrInputs[33];   #	FollowUp	ProbableAction	Step 4 Option,"More Tests","Referral"
        $FollowUpDefiniteAction =  $arrInputs[34];   #	FollowUp	DefiniteAction	Step 4 Option,"Referral"*/
        $FirstAssessmentSecondaryCareAsthmaReferral = $arrInputs[32];   #	FirstAssessment	SecondaryCareAsthmaReferral	Y,"N"
        $FollowUpSecondaryCareAsthmaReferral = $arrInputs[33];   #	FollowUp	SecondaryCareAsthmaReferral	Y,"N"
        $SpirometrySummarySoFarOptions = $arrInputs[34];   #	Spirometry	SummarySoFarOptions	Continue,"BookTestsContinue","ReferralContinue","End"
        $LungFunctionSpirometryReferOrContinue = $arrInputs[35];   #	LungFunction	SpirometryReferOrContinue	refer,"continue"
        $ProgressReviewSecondaryCareAsthmaReferral = $arrInputs[36];   #	ProgressReview	SecondaryCareAsthmaReferral	Y,"N"
        $ExacerbationManageAtHome = $arrInputs[37];   #	Exacerbation	ManageAtHome	Continue,"Refer"
        $AssessmentType = $arrInputs[38]; #AssessmentDetails	AssessmentType	"FU","AR","EX","1A"

        ### Fields to add 03/12/2015

        $DiagnosticConfirmation45 = $arrInputs[39]; # FirstAssessment	DiagnosticConfirmation45

        $PossibleAction = $arrInputs[40];    #FollowUp	PossibleAction	Varchar(50)		"Step 4 Option","More Tests","Referral"
        $ProbableAction = $arrInputs[41];    #FollowUp	ProbableAction	Varchar(50)		"Step 4 Option","More Tests","Referral"
        $DefiniteAction = $arrInputs[42];    #FollowUp	DefiniteAction	Varchar(50)		"Step 4 Option","Referral"

        $CurrentMedicationLevelLastVisit = $arrInputs[43];


        ### Fields to added 03/19/2020
        $TherapyOptionsN2N2 = $arrInputs[43]; # "Maintain", "MART", "StepUp"
        $TherapyOptionsOO = $arrInputs[44]; # "Maintain", "Increase", "StepUp"
        $TherapyOptionsQQ = $arrInputs[45]; # "Increase", "Specialist"

        $TherapyOptionsN2N2N2 = $arrInputs[46]; # "StepUp", "Refer", "ModifyRx"
        $TherapyOptionsOOO = $arrInputs[47]; # "Y", "N"
        $TherapyOptionsQQQ = $arrInputs[48]; # "Y", "N"

        ### Find control to use
        if ($FirstAssessmentFastOrStandardTrack == "Fast Track") {
            $UsedControl = $CurrentControl13;
        } elseif ($FirstAssessmentFastOrStandardTrack == "Standard Track") {
            $UsedControl = $CurrentControl45;
        } else {
            $UsedControl = $CurrentControl45 ?: $CurrentControl13;
        }

        ### Define acute reason / poor adherence / inhaler technique
        $OtherReason = ($AcuteReason == "N" || $AdherenceToTherapyAdequate == "Y" || $InhalerTechniqueAdequate == "Y");# || $InhalerTechniqueSatisfactory == "Y");

        ### Define previous referral
        $PreviousReferral = ($FollowUpTestsOrReferral == "Referral" ||
            $PossibleAction == "Referral" ||
            $ProbableAction == "Referral" ||
            $DefiniteAction == "Referral" ||
            $FirstAssessmentSecondaryCareAsthmaReferral = "Y" ||
                $FollowUpSecondaryCareAsthmaReferral == "Y" ||
                $SpirometrySummarySoFarOptions == "ReferralContinue" ||
                $LungFunctionSpirometryReferOrContinue == "refer" ||
                $ProgressReviewSecondaryCareAsthmaReferral == "Y" ||
                $ExacerbationManageAtHome == "Refer");

        ###### STEP 0 and Annual Review or Follow-Up #######
        if (($AssessmentType == "FU" OR $AssessmentType == "AR") AND $CurrentMedicationLevel == 0 AND (!$Laba || empty($Laba))) {
            $CurrentMedicationLevel = $CurrentMedicationLevelLastVisit;
        }


        // 03/18/2020        Step 0-5 => Step 0, A-E

        ###CR - Added $AssessmentType
        if ($CurrentMedicationLevel == "0" && !empty($Laba)) {
            ###### On LABA alone #######
            return $TherapyStream = "NS";
        } elseif ($CurrentMedicationLevel == "0" && $AssessmentType == "1A") {
            ###### STEP 0 and First Assessment #######
            return $TherapyStream = "STA";
        } else {
            if ($CurrentMedicationLevel == "A") {
                ###### STEP A #######
                if ($UsedControl == "Good") {
                    if ($PresentControl6Months == "Y") {
                        return $TherapyStream = "A";
                    } else {
                        return $TherapyStream = "B";
                    }
                } else {
                    return $TherapyStream = "C";
                }
            } elseif ($CurrentMedicationLevel == "B") {
                ###### STEP B #######
                if ($UsedControl == "Good") {
                    ########## drug types, SABA + ICS ########
                    if ($InhaledCorticosteroids != null || $Saba != null) {
                        ######### has this level of control been present for 3 months #########
                        if ($PresentControl3Months == "Y") {
                            return $TherapyStream = "D";
                        } else {
                            return $TherapyStream = "E";
                        }
                    } ######### drug types, all others #########
                    else {
                        ######### has this level of control been present for 3 months #########
                        if ($PresentControl3Months == "Y") {
                            return $TherapyStream = "F";
                        } else {
                            return $TherapyStream = "G";
                        }
                    }
                } elseif ($UsedControl == "Partial") {
                    // TODO: CHECK THIS TREE...
                    if ($ControlLastTime == "Partial" || $ControlLastTime == "Poor") {
                        if ($InhaledCorticosteroids == null || $Saba == null) {
                            return $TherapyStream = "I1";
                        } else {
                            return $TherapyStream = "H1";
                        }
                    } else {
                        if ($InhaledCorticosteroids != null || $Saba != null) {
                            return $TherapyStream = "H2";
                        } else {
                            return $TherapyStream = "I2";
                        }
                    }
                } elseif ($UsedControl == "Poor") {
                    ## Control poor last time
                    // TODO: Check this trees also
                    if ($ControlLastTime == "Poor") {
                        ## is there an acute reason for deterioration, Or poor adherence or poor technique
                        if ($OtherReason == false) {
                        } else {
                        }

                        if ($Saba != null || $InhaledCorticosteroids != null) {
                            return $TherapyStream = "J";
                        } else {
                            return $TherapyStream = "J1";
                        }
                    } else {
                        if ($OtherReason == false) {
                            if ($Saba != null || $InhaledCorticosteroids != null) {
                                return $TherapyStream = "K";
                            } else {
                                return $TherapyStream = "K1";
                            }
                        } else {
                            return $TherapyStream = "K2";
                        }
                    }
                } else {
                    return $TherapyStream = "ERROR - UsedControl";
                }
            } elseif ($CurrentMedicationLevel == "C ") {
                ###### STEP C #######
                $temp = false;
                if ($UsedControl == "Good") {
                    ######### has this level of control been present for 3 months #########
                    if ($PresentControl3Months == "Y") {
                        return $TherapyStream = "M";
                    } else {
                        return $TherapyStream = "L";
                    }
                } elseif ($UsedControl == "Partial") {
                    #$TherapyOptionsO1 = $arrInputs[26];		#MedicationReview	TherapyOptionsO1	"Maintain", "SMART", "StepUpTo4"
                    #$TherapyOptionsQ = $arrInputs[27];		#MedicationReview	TherapyOptionsQ		"SMART", "StepUpTo4"
                    #$TherapyOptionsN2 = $arrInputs[25]; 	#MedicationReview	TherapyOptionsN2	"Maintain", "StepUpWithin3", "SMART", "StepUpTo4"

                    ### not (Control good or unknown last time)
                    //if($ControlLastTime != "Poor" && $ControlLastTime != "Partial"){
                    if ($ControlLastTime == "Poor" || $ControlLastTime == "Partial") {
                        ### Any of (Is there an acute reason for deterioration, poor adherence, poor technique)
                        if ($OtherReason == TRUE) {
                            // N2 question (N2a - N2d)
                            switch ($TherapyOptionsO1) {
                                case "Maintain":
                                    return $TherapyStream = "O1";
                                    break;
                                case "SMART":
                                    return $TherapyStream = "O2";
                                    break;

                                ## TODO: increase ICS? As above would you like to refer
                                case "StepUpTo4":
                                    return $TherapyStream = "O3";
                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsO1 - Error";
                            }
                        } else {
                            switch ($TherapyOptionsQ) {
                                case "SMART":
                                    return $TherapyStream = "Q1";
                                    break;
                                case "StepUpTo4":
                                    return $TherapyStream = "Q2";
                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsQ - Error";
                            }
                        }

                    } else {
                        if ($OtherReason == TRUE) {
                            return $TherapyStream = "N1";
                        } else {
                            switch ($TherapyOptionsN2) {
                                case "Maintain":
                                    return $TherapyStream = "N2a";
                                    break;
                                case "StepUpWithin3":
                                    return $TherapyStream = "N2b";
                                    break;
                                case "SMART":
                                    return $TherapyStream = "N2c";
                                    break;

                                ## TODO: increase ICS?
                                case "StepUpTo4":
                                    return $TherapyStream = "N2d";
                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsN2 - Error";
                            }
                        }
                    }


                } elseif ($UsedControl == "Poor") {
                    ### Same as the previous tree, ("Step C -> "Partial" -> "Poor"/"Partial")
                    ### Any of (Is there an acute reason for deterioration, poor adherence, poor technique)
                    if ($OtherReason == TRUE) {
                        // N2 question (N2a - N2d)
                        switch ($TherapyOptionsO1) {
                            case "Maintain":
                                return $TherapyStream = "O1";
                                break;
                            case "SMART":
                                return $TherapyStream = "O2";
                                break;

                            ## TODO: increase ICS? As above would you like to refer
                            case "StepUpTo4":
                                return $TherapyStream = "O3";
                                break;
                            default:
                                return $TherapyStream = "TherapyOptionsO1 - Error";
                        }
                    } else {
                        switch ($TherapyOptionsQ) {
                            case "SMART":
                                return $TherapyStream = "Q1";
                                break;
                            case "StepUpTo4":
                                return $TherapyStream = "Q2";
                                break;
                            default:
                                return $TherapyStream = "TherapyOptionsQ - Error";
                        }
                    }
                } else {
                    return $TherapyStream = "ERROR - UsedControl";
                }
            } elseif ($CurrentMedicationLevel == "D") {
                ###### STEP D #######

                if ($UsedControl == "Good") {
                    if ($PresentControl3Months == "Y") {
                        return $TherapyStream = "DGY";
                    } else {
                        return $TherapyStream = "DGN";
                    }
                } elseif ($UsedControl == "Partial") {

                    # Was control "good" or "unknown" last time
                    if ($ControlLastTime != "Poor" && $ControlLastTime != "Partial") {
                        if ($OtherReason == TRUE) {
                            return $TherapyStream = "DPGT";
                        } else {
                            switch ($TherapyOptionsN2N2) {
                                case "Maintain":
                                    return $TherapyStream = "DPGF1";
                                    break;
                                case "MART":
                                    return $TherapyStream = "DPGF2";
                                    break;
                                case "StepUp":
                                    // TODO: divide to 3 options again
                                    //return $TherapyStream = "DPGF3";
                                    if ($TherapyOptionsN2N2N2 == "StepUp") {
                                        $TherapyStream = "DPGF31";
                                    } elseif ($TherapyOptionsN2N2N2 == "Refer") {
                                        $TherapyStream = "DPGF32";
                                    } elseif ($TherapyOptionsN2N2N2 == "ModifyRx") {
                                        $TherapyStream = "DPGF33";
                                    } else {
                                        $TherapyStream = "TherapyOptionN2N2N2 - Error";
                                    }
                                    return $TherapyStream;
                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsN2N2 - Error";
                            }
                        }

                    } else {
                        ### Partial => Last time, Good or Unknown,       DPP
                        if ($OtherReason == TRUE) {
                            switch ($TherapyOptionsOO) {
                                case "Maintain":
                                    return $TherapyStream = "DPPT1";
                                    break;
                                case "Increase":
                                    return $TherapyStream = "DPPT2";
                                    break;
                                case "StepUp":
                                    # return $TherapyStream = "DPPT3";
                                    if ($TherapyOptionsOOO == "Y") {
                                        return $TherapyStream = "DPPT3Y";
                                    } else {
                                        return $TherapyStream = "DPPT3N";
                                    }
                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsOO - Error";
                            }
                        } else {
                            switch ($TherapyOptionsQQ) {
                                case "Increase":
                                    return $TherapyStream = "DPPF1";
                                    break;
                                case "Specialist":
                                    # return $TherapyStream = "DPPF2";
                                    if ($TherapyOptionsQQQ == "Y") {
                                        return $TherapyStream = "DPPF2Y";
                                    } else {
                                        return $TherapyStream = "DPPF2N";
                                    }

                                    break;
                                default:
                                    return $TherapyStream = "TherapyOptionsQQ - Error";
                            }
                        }
                    }
                } elseif ($UsedControl == "Poor") {
                    ### Same as DPP
                    if ($OtherReason == TRUE) {
                        switch ($TherapyOptionsOO) {
                            case "Maintain":
                                return $TherapyStream = "DPPT1";
                                break;
                            case "Increase":
                                return $TherapyStream = "DPPT2";
                                break;
                            case "StepUp":
                                # return $TherapyStream = "DPPT3";
                                if ($TherapyOptionsOOO == "Y") {
                                    return $TherapyStream = "DPPT3Y";
                                } else {
                                    return $TherapyStream = "DPPT3N";
                                }
                                break;
                            default:
                                return $TherapyStream = "TherapyOptionsOO - Error";
                        }
                    } else {
                        switch ($TherapyOptionsQQ) {
                            case "Increase":
                                return $TherapyStream = "DPPF1";
                                break;
                            case "Specialist":
                                # return $TherapyStream = "DPPF2";
                                if ($TherapyOptionsQQQ == "Y") {
                                    return $TherapyStream = "DPPF2Y";
                                } else {
                                    return $TherapyStream = "DPPF2N";
                                }

                                break;
                            default:
                                return $TherapyStream = "TherapyOptionsQQ - Error";
                        }
                    }

                } else {
                    return $TherapyStream = "ERROR - UsedControl";
                }
            } elseif ($CurrentMedicationLevel == "E1" || $CurrentMedicationLevel == "E2") {
                ### TODO: Step E1/E2

            } else {
                return $TherapyStream = "ERROR - CurrentMedicationLevel2";
            }

        }

    }

    /*
    fn_TherapyMessage("Therapy,TherapyStream",
    "CurrentMedication,CurrentDrugILABANewDrug",
    "CurrentMedication,CurrentDrugILAANewDrug",
    "CurrentMedication,CurrentDrugLTRANewDrug",
    "CurrentMedication,CurrentDrugOBANewDrug",
    "CurrentMedication,CurrentDrugISAANewDrug",
    "CurrentMedication,CurrentDrugTheophyllineNewDrug",
    "CurrentMedication,CurrentDrugCromolynsNewDrug",
    "CurrentMedication,CurrentDrugISABANewDrug",
    "CurrentMedication,CurrentDrugICSNewDrug",
    "CurrentMedication,CurrentMedicationLevel",
    "NonPharmaRx,InhalerTechniqueAdequate",
    "ProgressReview,AdherenceToTherapyAdequate",
    "NonPharmaRx,ChangeDevice",
    "NonPharmaRx,Other",
    "NonPharmaRx,Details",
    "NonPharmaRx,AddSpacer",
    "FirstAssessment,CurrentControl13",
    "FirstAssessment,CurrentControl45",
    "FirstAssessment,FastOrStandardTrack",
    "FirstAssessment,UnderHospitalCare",
    "FirstAssessment,SecondaryCareAsthmaReferral",
    "ProgressReview,StepDownTherapyNow",
    "ProgressReview,StepUpTherapyNow",
    "CurrentControl,ControlLastTime",
    "FollowUp,AcuteReason",
    "FollowUp,TestsOrReferral",
    "FollowUp,PossibleAction",
    "FollowUp,ProbableAction",
    "FollowUp,DefiniteAction",
    "FirstAssessment,SecondaryCareAsthmaReferral",
    "FollowUp,SecondaryCareAsthmaReferral",
    "Spirometry,SummarySoFarOptions",
    "LungFunction,SpirometryReferOrContinue",
    "ProgressReview,SecondaryCareAsthmaReferral",
    "Exacerbation,ManageAtHome",
    "AssessmentDetails,AssessmentType",
    "FirstAssessment,SecondaryCareAsthmaReferralDetails",
    "CurrentMedication,MedicationLevelAtStartOfLastVisit",
    "NoMedication,WhyStopped",
    "NoMedication,WhyStoppedOtherDetails",
    "NonPharmaRx,InhalerTechniqueChecked") - FieldID: 2307

    */

    function fn_TherapyMessage($arrInputs = array())
    {
        $CI =& get_instance();
        $CI->load->model('apidrugs_model', 'd_model');

        /*
        Current Drugs List 11/03/2016:

            ISABA							Inhaled short-acting beta agonist
            ICS								Inhaled corticosteroid
            ILABA							Inhaled long-acting beta agonist
            Comb							Combination therapy (ICS + LABA)
            ILAA							Inhaled long-acting anticholinergic
            LTRA							Oral antileukotriene antagonists
            Theophylline					Theophylline
            PDE4Inhibitor					Phosphodiesterase 4 inhibitor
            Mucolytic						Mucolytic
            ISAA							Inhaled short-acting anticholinergic
            OBA								Oral beta agonists
            NebSABA							Nebulised short-acting beta agonist
            NebSAA							Nebulised short-acting anticholinergic
            Cromolyns						Cromolyns
            OralSteroids					Oral steroids
            InjectableBetaAgonist			Injectable beta agonist
            LABALAMA						LABA/LAMA
            NebulisedSABASAMA				SABA/SAMA
            LongActingInjectableSteroids	Long acting injectable steroids
        */

        $msgCode = $arrInputs[0];
        $Laba = $arrInputs[1];
        $Lama = $arrInputs[2];
        $Ltra = $arrInputs[3];
        $SabaTabs = $arrInputs[4];
        $Sama = $arrInputs[5];
        $Theo = $arrInputs[6];
        $Cromone = $arrInputs[7];
        $Saba = $arrInputs[8];
        $Ics = $arrInputs[9];
        $CurrentMedicationLevel = $arrInputs[10];
        $InhalerTechnique = $arrInputs[11];
        $AdherenceToTherapyAdequate = $arrInputs[12];
        $InhalerChangeDevice = $arrInputs[13];
        $InhalerOther = $arrInputs[14];
        $InhalerOtherDetails = $arrInputs[15];
        $InhalerAddSpacer = $arrInputs[16];

        $CurrentControl13 = $arrInputs[17]; # FirstAssessment	CurrentControl13
        $CurrentControl45 = $arrInputs[18]; # FirstAssessment	CurrentControl45
        $FirstAssessmentFastOrStandardTrack = $arrInputs[19]; # FirstAssessment	FastOrStandardTrack

        $UnderHospitalCare = $arrInputs[20];
        $ReferFormalAssessment = $arrInputs[21];
        $InhalerTechniqueAdequate = $arrInputs[22];
        $NewPatientFirstVisit = $arrInputs[23];
        $ControlLastTime = $arrInputs[24];
        $AcuteReason = $arrInputs[25];            #FollowUp	AcuteReason "Y","N"

        $FollowUpTestsOrReferral = $arrInputs[26];   #	FollowUp	TestsOrReferral	Tests,"Referral"
        $FollowUpPossibleAction = $arrInputs[27];   #	FollowUp	PossibleAction	Step 4 Option,"More Tests","Referral"
        $FollowUpProbableAction = $arrInputs[28];   #	FollowUp	ProbableAction	Step 4 Option,"More Tests","Referral"
        $FollowUpDefiniteAction = $arrInputs[29];   #	FollowUp	DefiniteAction	Step 4 Option,"Referral"
        $FirstAssessmentSecondaryCareAsthmaReferral = $arrInputs[30];   #	FirstAssessment	SecondaryCareAsthmaReferral	Y,"N"
        $FollowUpSecondaryCareAsthmaReferral = $arrInputs[31];   #	FollowUp	SecondaryCareAsthmaReferral	Y,"N"
        $SpirometrySummarySoFarOptions = $arrInputs[32];   #	Spirometry	SummarySoFarOptions	Continue,"BookTestsContinue","ReferralContinue","End"
        $LungFunctionSpirometryReferOrContinue = $arrInputs[33];   #	LungFunction	SpirometryReferOrContinue	refer,"continue"
        $ProgressReviewSecondaryCareAsthmaReferral = $arrInputs[34];   #	ProgressReview	SecondaryCareAsthmaReferral	Y,"N"
        $ExacerbationManageAtHome = $arrInputs[35];   #	Exacerbation	ManageAtHome	Continue,"Refer"
        $AssessmentType = $arrInputs[36]; #AssessmentDetails	AssessmentType	"FU","AR","EX","1A"

        $ReferalDetails = $arrInputs[37]; # FirstAssessment	SecondaryCareAsthmaReferralDetails

        $MedicationLevelAtStartOfLastVisit = $arrInputs[38];    #CurrentMedication MedicationLevelAtStartOfLastVisit
        $NoMedicationWhyStopped = $arrInputs[39];
        $NoMedicationWhyStoppedOtherDetails = $arrInputs[40];

        $NonPharmaRxInhalerTechniqueChecked = $arrInputs[41];

        ### Find control to use
        if ($FirstAssessmentFastOrStandardTrack == "Fast Track") {
            $UsedControl = $CurrentControl13;
        } elseif ($FirstAssessmentFastOrStandardTrack == "Standard Track") {
            $UsedControl = $CurrentControl45;
        } else {
            $UsedControl = $CurrentControl45 ?: $CurrentControl13;
        }

        ##CR EDIT 2016/05/19
        if (!$ControlLastTime) {
            $ControlLastTime = "unknown";
        }

        ### Define acute reason / poor adherence / inhaler technique
        $OtherReason = !($AcuteReason == "N" && $AdherenceToTherapyAdequate == "Y" && $InhalerTechniqueAdequate == "Y");//($InhalerTechniqueAdequate == "Y" || $InhalerTechniqueSatisfactory == "Y");

        ### Define previous referral
        $PreviousReferral = ($FollowUpTestsOrReferral == "Referral" ||
            $FollowUpPossibleAction == "Referral" ||
            $FollowUpProbableAction == "Referral" ||
            $FollowUpDefiniteAction == "Referral" ||
            $FirstAssessmentSecondaryCareAsthmaReferral = "Y" ||
                $FollowUpSecondaryCareAsthmaReferral == "Y" ||
                $SpirometrySummarySoFarOptions == "ReferralContinue" ||
                $LungFunctionSpirometryReferOrContinue == "refer" ||
                $ProgressReviewSecondaryCareAsthmaReferral == "Y" ||
                $ExacerbationManageAtHome == "Refer");

        if ($CurrentMedicationLevel == 0) {
            $CurrentStep = $MedicationLevelAtStartOfLastVisit;
            if ($NoMedicationWhyStopped == "Other") {
                $NoMedicationReason = $NoMedicationWhyStoppedOtherDetails;
            } else {
                $NoMedicationReason = $NoMedicationWhyStopped;
            }
        } else {
            $CurrentStep = $CurrentMedicationLevel;
        }

        if ($CurrentStep == "1" || $CurrentStep == "2" || $CurrentStep == "3") {
            $CurrentStep1Drug = $Saba ?: $Sama ?: $SabaTabs ?: $Theo;
            $CurrentStep1Drug = $CI->d_model->getLabelFor($CurrentStep1Drug);
            if (!$CurrentStep1Drug || empty($CurrentStep1Drug)) {
                $CurrentStep1Drug = "no current therapy";
            }
        }

        if ($CurrentStep == "2") {
            $CurrentStep2Drug = $Ics;
            $CurrentStep2Drug = $CI->d_model->getLabelFor($CurrentStep2Drug);
            if (!$CurrentStep2Drug || empty($CurrentStep2Drug)) {
                $CurrentStep2Drug = "no current therapy";
            }

            $CurrentStep2NonIcsDrug = $Ltra ?: $Cromone ?: $Theo;
            $CurrentStep2NonIcsDrug = $CI->d_model->getLabelFor($CurrentStep2NonIcsDrug);
            if (!$CurrentStep2NonIcsDrug || empty($CurrentStep2NonIcsDrug)) {
                $CurrentStep2NonIcsDrug = "no current therapy";
            }
        }

        if ($CurrentStep == "3") {
            $CurrentStep3NonIcsDrug = $Ltra ?: $Cromone ?: $Theo;
            $CurrentStep3NonIcsDrug = $CI->d_model->getLabelFor($CurrentStep3NonIcsDrug);
            if (!$CurrentStep3NonIcsDrug || empty($CurrentStep3NonIcsDrug)) {
                $CurrentStep3NonIcsDrug = "no current therapy";
            }
        }

        $referReRefer = "refer";
        $specialistAdvice = "specialist advice";
        if ($UnderHospitalCare == "Y") {
            $referReRefer = "re-refer";
            $specialistAdvice = "further specialist advice";
        }

        //DEFAULT MESSAGE:
        $msgText = "[DEV] Please note review ID - Therapy recommendation failed";


        // EDIT: 2020/03/17


        //LABA Alone
        if ($msgCode == "NS") {
            $msgText = "<p>This patient was on a non-standard regime and the algorithm cannot be adjusted for this. Please take extra care as you select your therapeutic plan for this person.</p>";
        }

        //No medication on First Assesment
        if ($msgCode == "STA") {
            $msgText = "<p>This patient is new to the system and has been on no previous asthma therapy.</p><p>Starting therapy depends on severity of symptoms, level of current control and is at the discretion of the treating doctor. Usually the choice will rest between Step 2 and Step 3 of the guidelines i.e. a preventer ICS inhaler at a dose between 200 and 800 mcg per day via a dry powder device with additional long acting beta agonists if current control is poor. The patient should be followed up after 4 weeks to assess response. Response should be assessed both from the change in reported symptoms <b>and</b> by the change in PEF and/or spirometric measurement.</p>";
        }

        //Step A; good control present for 6 months
        if ($msgCode == "A") {
            $msgText = "<p>This patient is on \"As required Reliever therapy\" using " . $CurrentStep1Drug . ". They report good control that has been present for 6 months or more. It is acceptable for them to use the drug less but appropriate for them to retain some therapy since even well controlled asthma may vary over time. Plan to continue annual asthma reviews until and unless they have been shown not to need any therapy over several years.</p>";
        }

        //Step A; good control but NOT present for 6 months
        if ($msgCode == "B") {
            $msgText = "<p>This patient is on \"As required Reliever therapy\" using " . $CurrentStep1Drug . ". They report good control and the plan is for them to continue with current therapy unchanged. Keep under annual review.</p>";
        }

        //Step A; poor or partial control
        if ($msgCode == "C") {
            if ($Saba != null) {
                $msgText = "<p>This patient is reporting symptoms that should be controllable. Consider moving up to Step 2 by adding a low dose inhaled steroid.</p>";
            } else {
                $msgText = "<p>This patient is reporting symptoms that should be controllable. Consider moving up to Step 2 by adding a low dose inhaled steroid and switching the reliever drug " . $CurrentStep1Drug . " to a short acting beta-agonist bronchodilator. Plan to review the effect of this change sooner than otherwise when you plan next appointment later in the program.</p>";
            }
        }

        //Step 2; good control present for 6 months; SABA+ICS
        if ($msgCode == "D") {
            $msgText = "<p>This patient is reporting good control on a low dose of inhaled steroid (Step 2). Control was also good 6 months ago, and if it has been stable over the whole period then consider if an even lower dose or step down may be appropriate. Plan to review in one year but make it clear that if stepping down causes symptoms to return the patient may resume their previous dosage.</p>";
        }

        //Step 2; good control but NOT present for 6 months; SABA+ICS
        if ($msgCode == "E") {
            $msgText = "<p>This patient is reporting good control on a low dose of inhaled steroid (Step 2). Continue same therapy. Plan to review in one year.</p>";
        }

        //Step 2; good control present for 6 months; NOT SABA+ICS
        if ($msgCode == "F") {
            $msgText = "<p>This patient is reporting good control on " . $CurrentStep2NonIcsDrug . " (Step 2). As control was also good 6 months ago, consider step down. i.e. whether " . $CurrentStep2NonIcsDrug . " is still required. Plan to review in one year but make it clear that if the reduction causes symptoms to return the patient may resume their previous dosage.</p>";
        }

        //Step 2; good control but NOT present for 6 months; NOT SABA+ICS
        if ($msgCode == "G") {
            $msgText = "<p>This patient is reporting good control on " . $CurrentStep2NonIcsDrug . " (Step 2). Continue same regimen and plan to review in one year.</p>";
        }

        //Step 2; partial control and poor or partial last time; SABA+ICS
        if ($msgCode == "H1") {
            $msgText = "<p>This patient reports partial control on a low dose of inhaled steroid (Step 2). As control was less than good last time, consider increasing the dose of inhaler steroid or changing to an ICS/LABA combination inhaler and plan to review earlier than one year to assess whether this has achieved control.</p>";
        }

        //Step 2; partial control and poor or partial last time; NOT SABA+ICS
        if ($msgCode == "I1") {
            $msgText = "<p>This patient reports partial control on " . $CurrentStep2NonIcsDrug . " (Step 2). As control was less than good last time, consider changing the " . $CurrentStep2NonIcsDrug . " to an inhaled steroid or adding an inhaled steroid to the current regime and plan to review earlier than one year to assess whether this has achieved control.</p>";
        }

        //Step 2; partial control and good or unknown last time; SABA+ICS
        if ($msgCode == "H2") {
            $msgText = "<p>This patient reports partial control on a low dose of inhaled steroid (Step 2). As this is the first time reporting less than good control, consider keeping treatment the same now but to review earlier than one year to assess whether a step up of therapy may be needed.</p>";
        }

        //Step 2; partial control and good or unknown last time; NOT SABA+ICS
        if ($msgCode == "I2") {
            $msgText = "<p>This patient reports partial control on " . $CurrentStep2NonIcsDrug . " (Step 2). Consider switching to the more conventional Step 2 option i.e. a low dose of inhaled steroid (Step 2) <b>with or without a LABA.</b> In view of symptoms, plan to review earlier than one year to assess whether control has been regained.</p>";
        }

        //Step 2; poor control and poor last time; SABA+ICS
        if ($msgCode == "J") {
            if ($AcuteReason == "Y") {
                $msgText = "<p><b>This patient has an acute reason for being worse that may benefit from a temporary increase in current inhaled drugs.</b></p>";
            } else {
                $msgText = "";
            }
            $msgText = $msgText . "<p>Because this is not the first time limited control has been noted, consider increasing the dose of inhaler steroid or switching to an ICS/LABA combination inhaler and plan to review earlier than one year to assess whether control has been regained.</p>";
        }

        //Step 2; poor control and poor last time; NOT SABA+ICS
        if ($msgCode == "J1") {
            if ($AcuteReason == "Y") {
                $msgText = "<p><b>This patient has an acute reason for being worse that may benefit from a temporary increase in current inhaled drugs.</b></p>";
            } else {
                $msgText = "";
            }
            $msgText = $msgText . "<p>Because this is not the first time limited control has been noted, consider changing the " . $CurrentStep2NonIcsDrug . " to an ICS <b>with or without a LABA</b> and plan to review earlier than one year to assess whether a step up of therapy may be needed.</p>";
        }

        //Step 2; poor control and good or partial last time; SABA+ICS
        if ($msgCode == "K") {

            if ($ControlLastTime == "unknown") {
                $secSentance = "As this is the patient's first entry into the package";
            } else {
                $secSentance = "As control was " . strtolower($ControlLastTime) . " last time";
            }

            $msgText = "<p>This patient reports poor control on a low dose of inhaled steroid " . $CurrentStep2Drug . " (Step 2). " . $secSentance . ", consider increasing the dose of inhaler steroid or switching to an ICS/LABA combination inhaler and plan to review earlier than one year to assess whether this step up of therapy should be maintained permanently.</p>";
        }

        //Step 2; poor control and good or partial last time; NOT SABA+ICS
        if ($msgCode == "K1") {
            $msgText = "<p>This patient reports poor control on " . $CurrentStep2NonIcsDrug . " prophylaxis (Step 2). Consider adding a low dose of inhaled steroid or switching to an ICS/LABA combination inhaler and plan to review earlier than one year to assess whether control has been regained.</p>";
        }

        //Step 2; poor control and good or partial last time; acute reason
        if ($msgCode == "K2") {
            $msgText = "<p>This patient has an acute reason for being worse that may benefit from a temporary increase in current inhaled drugs. Because this is the first time limited control has been noted, it is reasonable to maintain existing therapy unchanged but to review earlier to make sure that the acute problems have subsided.</p>";
        }

        //Step 3; good control present for 6 months
        if ($msgCode == "M") {
            $msgText = "<p>This patient reports good control on Step 3 treatment. Control was also good 6 months ago, and if it has been stable over the whole period, then consider if step down may be appropriate. Plan to review in one year but make it clear that if stepping down causes symptoms to return the patient may resume their previous dosage.</p>";
        }

        //Step 3; good control not NOT present for 6 months
        if ($msgCode == "L") {
            $msgText = "<p>This patient reports good control on Step 3 treatment. Continue with the same regimen and plan to review in one year.</p>";
        }

        //Step 3; partial control and good or unknown last time; acute reason
        if ($msgCode == "N1") {

            $msgText = "<p>This patients control is not as good as it should be.</p>";

            if ($AcuteReason == "Y") {
                "<p>This patient has an acute reason for being worse that may benefit from a temporary increase in current inhaled drugs.</p>";
            }

            if ($InhalerTechnique == "N") {
                "<p>Inhaler technique has not been optimal. Correct and plan to reassess control in the near future.</p>";
            }

            if ($AdherenceToTherapyAdequate == "N") {
                "<p>Adherence to therapy seems to be a problem. Advise and and plan to reassess control in the near future.</p>";
            }

            $msgText = $msgText . "<p>Because this is the first time limited control has been noted, it is reasonable to maintain existing therapy unchanged but to review in three months to make sure that control has been regained.</p>";

        }

        //Step 3; partial control and good or unknown last time; no acute reason; maintain therapy
        if ($msgCode == "N2a") {
            $msgText = "<p>This patient is only partially controlled on Step 3 therapy but it has been good (or was unknown) at the previous visit. Inhaler technique and medicine adherence are good and you have chosen to  maintain current level of therapy with an earlier review to assess if more therapy may be needed. Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 3; partial control and good or unknown last time; no acute reason; step up within Step 3
        if ($msgCode == "N2b") {
            $msgText = "<p>This patient is only partially controlled on Step 3 therapy but it has been good (or was unknown) at the previous visit. Inhaler technique and medicine adherence are good and you have chosen to step up the therapy within the Step 3 range. In general this means using Inhaled steroids supported by a LABA. Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 3; partial control and good or unknown last time; no acute reason; SMART therapy
        if ($msgCode == "N2c") {
            $msgText = "<p>This patient is only partially controlled on Step 3 therapy but it has been good (or was unknown) at the previous visit. Inhaler technique and medicine adherence are good and you have chosen to step up the therapy within the Step 3 range by switching to(S)MART therapy. Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 3; partial control and good or unknown last time; no acute reason; step up to Step 4
        if ($msgCode == "N2d") {
            $msgText = "<p>This patient is only partially controlled on Step 3 therapy but it has been good (or was unknown) at the previous visit. Inhaler technique and medicine adherence are good and you have chosen to step up to Step 4 levels of therapy";

            if ($ReferFormalAssessment == "Y") {
                $msgText = $msgText . " and have referred for a further specialist assessment to <i>" . $ReferalDetails . ".</i></p>";
            } else {
                $msgText = $msgText . ".</p>";
            }

            $msgText = $msgText . "<p>Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 3; poor control this time, or partial this time and partial or poor last time; acute reason; maintain therapy
        if ($msgCode == "O1") {

            $eduProblem = "";

            if ($InhalerTechnique == "N" && $AdherenceToTherapyAdequate == "N") {
                $eduProblem = "inhaler technique and concordance with therapy";
            } elseif ($AdherenceToTherapyAdequate == "N") {
                $eduProblem = "concordance with therapy";
            } else {
                $eduProblem = "inhaler technique";
            }

            if ($AcuteReason == "Y") {
                if ($eduProblem == "") {
                    $eduProblem = "acute reason";
                } else {
                    $eduProblem = $eduProblem . " and acute reason";
                }
            }

            if ($UsedControl == "Partial") {
                $msgText = "<p>This patient is only partially controlled on Step 3 therapy and it was not good at the previous visit. ";
            } else {
                $msgText = "<p>This patient is poorly controlled on Step 3 therapy. ";
            }

            $msgText = $msgText . "You have chosen to maintain current level of therapy and review earlier to assess if improvements in " . $eduProblem . " with or without educational support can restore control.</p>";

            #if($ReferFormalAssessment == "Y"){
            #	$msgText = $msgText."<p>You have chosen to refer for specialist advice to ".$ReferalDetails.".</p>" ;
            #}
        }

        //Step 3; poor control this time, or partial this time and partial or poor last time; acute reason; SMART therapy
        if ($msgCode == "O2") {

            if ($UsedControl == "Partial") {
                $msgText = "<p>This patient is only partially controlled on Step 3 therapy and it was not good at the previous visit. ";
            } else {
                $msgText = "<p>This patient is poorly controlled on Step 3 therapy. ";
            }

            $msgText = $msgText . " You have chosen to  switch to a trial of a (S)MART regime</p>";

            #if($ReferFormalAssessment == "Y"){
            #	$msgText = $msgText."<p>You have chosen to refer for specialist advice to ".$ReferalDetails.".</p>" ;
            #}
        }

        //Step 3; poor control this time, or partial this time and partial or poor last time; acute reason; step up to Step 4
        if ($msgCode == "O3") {

            if ($UsedControl == "Partial") {
                $msgText = "<p>This patient is only partially controlled on Step 3 therapy and it was not good at the previous visit. ";
            } else {
                $msgText = "<p>This patient is poorly controlled on Step 3 therapy. ";
            }

            $msgText = $msgText . " You have chosen to  step up to Step 4 levels of therapy</p>";

            if ($ReferFormalAssessment == "Y") {
                $msgText = $msgText . "<p>You have chosen to refer for specialist advice to " . $ReferalDetails . ".</p>";
            } else {
                $msgText = $msgText . "<p>You have chosen not to refer for specialist advice.</p>";
            }

            $msgText = $msgText . "Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 3; poor control this time, or partial this time and partial or poor last time; no acute reason; SMART therapy
        if ($msgCode == "Q1") {

            if ($UsedControl == "Partial") {
                $msgText = "<p>This patient is only partially controlled on Step 3 therapy and it was not good at the previous visit. ";
            } else {
                $msgText = "<p>This patient is poorly controlled on Step 3 therapy. ";
            }

            $msgText = $msgText . "Both inhaler technique and adherence to medicines are reported to be good and there is no acute reason so consider stepping up therapy to regain control.  You have chosen to switch to (S)MART therapy.</p>";

            #if($ReferFormalAssessment == "Y"){
            #	$msgText = $msgText."<p>You have chosen to refer for specialist advice to ".$ReferalDetails.".</p>" ;
            #}
        }

        //Step 3; poor control this time, or partial this time and partial or poor last time; no acute reason; step up to Step 4
        if ($msgCode == "Q2") {

            if ($UsedControl == "Partial") {
                $msgText = "<p>This patient is only partially controlled on Step 3 therapy and it was not good at the previous visit. ";
            } else {
                $msgText = "<p>This patient is poorly controlled on Step 3 therapy. ";
            }

            $msgText = $msgText . "Both inhaler technique and adherence to medicines are reported to be good and there is no acute reason so consider stepping up therapy to regain control. You have chosen to step up to Step 4 therapy.</p>";

            if ($ReferFormalAssessment == "Y") {
                $msgText = $msgText . "<p>You have chosen to refer for specialist advice to " . $ReferalDetails . ".</p>";
            } else {
                $msgText = $msgText . "<p>You have chosen not to refer for specialist advice.</p>";
            }

            $msgText = $msgText . "Plan to review in 3 – 6 months to check that control is re-established.</p>";
        }

        //Step 4-5; good or partial control for at least 3 months; step down therapy; step down has NOT failed before
        if ($msgCode == "D1") {
            $msgText = "<p>This patient's asthma control has been adequate for more than 3 months and so it is recommended to try stepping down therapy. Plan to review in one year.</p>";
        }

        //Step 4-5; good or partial control for at least 3 months; step down therapy; step down has failed before
        if ($msgCode == "D2") {
            $msgText = "<p>This patient's asthma control has been adequate for more than 3 months and it's known that previous attempts to step down were not successful. Maintain same level of therapy. Plan to review in one year.</p>";
        }

        //Step 4-5; good or partial control for at least 3 months; do not wish to step down therapy
        if ($msgCode == "D3") {
            $msgText = "<p>This patient's asthma control has been adequate for more than 3 months; step down might be possible but it has been decided to maintain the same level of therapy. Plan to review in one year unless there is a need to review changes.</p>";
        }

        //Step 4-5; good or partial control but not present for at least 3 months
        if ($msgCode == "D4") {
            $msgText = "<p>This patient's asthma control has been adequate. Maintain the same level of therapy. Plan to review in one year.</p>";
        }

        //Step 4-5; poor control; acute reason
        if ($msgCode == "D6") {

            $msgText = "<p>This patients asthma control has not been good despite Step 4 / 5 asthma guidelines therapy.</p>";

            If ($AdherenceToTherapyAdequate != "Y") {
                $msgText = $msgText . "<p>Looking at the prescription record it seems that this person has not collected the no of scripts necessary for full dosage. Give advice on the importance of regular daily treatment doses.</p>";
            }

            if ($NonPharmaRxInhalerTechniqueChecked == "N") {
                $msgText = $msgText . "<p>Inhaler technique has not been checked.</p>";
            } elseif ($InhalerTechniqueAdequate != "Y") {
                $msgText = $msgText . "<p>A check of inhaler technique suggests that not all the inhaled drugs have been reaching the airways. Advice has been given.</p>";
            }

            If ($AcuteReason != "N") {
                $msgText = $msgText . "<p>You have identified some acute reasons for deterioration today – it is recommended to manage those.</p>";
            }

            If ($UnderHospitalCare == "Y" && $ReferFormalAssessment == "Y") {
                $msgText = $msgText . "<p>This patient is under specialist care and has been re referred for more advice.</p>";
            } ElseIf ($UnderHospitalCare == "Y") {
                $msgText = $msgText . "<p>This patient is under specialist care.</p>";
            } ElseIf ($ReferFormalAssessment == "Y") {
                $msgText = $msgText . "<p>This patient has been referred for a specialist assessment to " . $ReferalDetails . ".</p>";
            }

        }

        /*if ($msgCode == "D7"){
            $msgText = "<p>This patients asthma control has not been good despite step 4/5 asthma guidelines therapy. A check of inhaler technique suggests that not all the inhaled drugs have been reaching the airways. Advice has been given and it is recommended to continue the current prescription level. Reassess earlier than usual to check this has worked.</p>";
        }*/

        //Step 4-5; poor control; NO acute reason; First Assesment or routine Follow Up; under the care of specialist service; awaiting advice
        if ($msgCode == "D5A") {
            $msgText = "<p>This patient is known to the asthma specialist service but still reports poor control despite good inhaler technique and concordance. You have chosen to continue present therapy pending further asthma specialist advice.</p>";
        }

        //Step 4-5; poor control; NO acute reason; First Assesment or routine Follow Up; under the care of specialist service; increase therapy
        if ($msgCode == "D5B") {
            $msgText = "<p>This patient is known to the asthma specialist service but still reports poor control despite good inhaler technique and concordance. You have chosen to increase therapy today to achieve that.</p>";
        }

        //Step 4-5; poor control; NO acute reason; First Assesment or routine Follow Up; under the care of specialist service; short course of oral steroid
        if ($msgCode == "D5C") {
            $msgText = "<p>This patient is known to the asthma specialist service but still reports poor control despite good inhaler technique and concordance. You have decided that the loss of control may be a temporary exacerbation, so prescribe a short course of oral steroid to regain control.</p>";
        }

        //Step 4-5; poor control; NO acute reason; First Assesment or routine Follow Up; NOT under the care of specialist service; referred for formal assessment
        if ($msgCode == "D8") {
            $msgText = "<p>This patient's asthma control has not been good despite Step 4/5 asthma guidelines therapy, and checks on concordance and inhaler technique suggest it's being used properly. It's appropriate to consider stepping up therapy but first they are being referred for a specialist asthma clinic assessment.</p>";
        }

        //Step 4; poor control; NO acute reason; First Assesment or routine Follow Up; NOT under the care of specialist service; NOT referred for formal assessment; step up within Step 4
        if ($msgCode == "D9a") {
            $msgText = "<p>This patient's asthma control has not been good despite Step 4 asthma guidelines therapy, and checks on concordance and inhaler technique suggest it's being used properly. You have chosen to step up therapy within Step 4. Plan to review within 3-6 months to assess the effect of this.</p>";
        }

        //Step 4; poor control; NO acute reason; First Assesment or routine Follow Up; NOT under the care of specialist service; NOT referred for formal assessment; step up to Step 5
        if ($msgCode == "D9b") {
            $msgText = "<p>This patient's asthma control has not been good despite Step 4 asthma guidelines therapy, and checks on concordance and inhaler technique suggest it's being used properly. You have chosen to increase the dose of oral steroids. Plan to reassess early e.g. 1-3 months, to assess the effect of this.</p>";
        }

        //Step 5; poor control; NO acute reason; First Assesment or routine Follow Up; NOT under the care of specialist service; NOT referred for formal assessment; maintain therapy
        if ($msgCode == "D11a") {
            $msgText = "<p>This patient's asthma control has not been good despite Step 5 asthma guidelines therapy, and checks on concordance and inhaler technique suggest it's being used properly. You have elected to keep the patient on the same dose of oral steroid despite symptoms. Are you sure that specialist advice would not help? Plan to reassess in 1-3 months.</p>";
        }

        //Step 5; poor control; NO acute reason; First Assesment or routine Follow Up; NOT under the care of specialist service; NOT referred for formal assessment; increase therapy
        if ($msgCode == "D11b") {
            $msgText = "<p>This patient's asthma control has not been good despite Step 5 asthma guidelines therapy, and checks on concordance and inhaler technique suggest it's being used properly. You have elected to increase the dose of oral steroid. Are you sure that specialist advice would not help? Plan to reassess in 1-3 months.</p>";
        }

        //Step 4-5; poor control; NO acute reason; Annual Review; NOT referred for formal assessment; try another Step 4/5 therapy
        if ($msgCode == "ARa") {
            $msgText = "<p>This patient has not responded as well as expected to Step 4/5 therapy and is reporting poor control. In the absence of obvious remediable factors the choices open to you are to reconsider the diagnosis, and then either to refer for " . $specialistAdvice . " or perform more investigations yourself. You have chosen to continue management at the practice and to increase Step 4/5 therapy and to reassess in 3-6 month to assess the effect.</p>";
        }

        //Step 4-5; poor control; NO acute reason; Annual Review; NOT referred for formal assessment; arrange more tests (NOT 'definite' diagnosis)
        if ($msgCode == "ARb") {
            $msgText = "<p>This patient has not responded as well as expected to Step 4/5 therapy and is reporting poor control. In the absence of obvious remediable factors the choices open to you are to reconsider the diagnosis with more tests, and then either to refer for " . $specialistAdvice . " or perform more investigations yourself. You have chosen to perform more tests to achieve a positive confirmation of diagnosis and to maintain long term therapy pending the results. Some short term adjustments are allowed.</p>";
        }

        //Step 4-5; poor control; NO acute reason; Annual Review; NOT referred for formal assessment; hospital referral
        if ($msgCode == "ARc") {
            $msgText = "<p>This patient has not responded as well as expected to Step 4/5 therapy and is reporting poor control. In the absence of obvious remediable factors the choices open to you are to reconsider the diagnosis, and then either to refer for " . $specialistAdvice . " or perform more investigations yourself. You have chosen to " . $referReRefer . " for specialist advice to " . $ReferalDetails . " asking for more positive confirmation of diagnosis while maintaining long term therapy pending the outcome. Some short term adjustments are allowed.</p>";
        }

        //Step 4-5; poor control; NO acute reason; Annual Review; referred for formal assessment
        if ($msgCode == "ARd") {
            $msgText = "<p>This patient has not responded as well as expected to Step 4/5 therapy and is reporting poor control. In the absence of obvious remediable factors the choices open to you are to reconsider the diagnosis, and then either to refer for (further) specialist advice or perform more investigations yourself. You have chosen to " . $referReRefer . " for specialist advice to " . $ReferalDetails . " earlier in the consultation. Some short term adjustments are allowed.</p>";
        }

        /*
        N2 Question ################################
        "a) Maintain current level of therapy with an earlier review to assess if more therapy may be needed",
        "b) Step up the therapy within Step 3 range",
        "c) Switch to (S)MART  therapy",
        "d) Step up to Step 4 levels of therapy by adding another agent or increasing to high dose inhaled steroids"

        "Maintain", "StepUpWithin3", "SMART", "StepUpTo4"

        O1 Question ################################
        "a) Maintain current level of therapy and review earlier to assess if improvements in technique and/or adherence with or without educational support can restore control",
        "b) Switch to a trial of a (S)MART regime",
        "c) Step up to Step 4 therapy by adding one of LABA/LAMA/LTRA/Theo or increasing to high dose inhaled steroids"

        "Maintain", "SMART", "StepUpTo4"

        Q Question ################################
        "a) switch to (S)MART therapy",
        "b) step up to Step 4 therapy by adding one of LABA/LAMA/LTRA/Theo or increasing to high dose inhaled steroids"

         "SMART", "StepUpTo4"

        D9 Question ################################
        "a) Other options within step 4 that have not been tried before",
        "b) Moving to step 5 and long term oral steroids."

        "StepUpWithin4", "StepUpTo5"

        With text: If the latter then It may be wise to organise a further specialist assessment before that long term commitment and they may be able to also assess if there are other options eg anti IgE therapy.

        D11 Question ################################
        a) Stay with the same dose of oral steroid
        b) Increase the dose of oral steroid

        "Same", "Increase"

        */

        //No Medication sub-section
        if ($CurrentMedicationLevel == 0 && $msgCode != "NS" && $msgCode != "STA") {
            $msgText = "<p><b>This patient has stopped the Step " . $MedicationLevelAtStartOfLastVisit . " treatment suggested last time because <i>" . $NoMedicationReason . ".</i> Please take extra care as you select your therapeutic plan for this person; it may need to be individualised rather than following guidelines rigidly.</b></p><hr>" . $msgText;
            //LABA Alone
        } elseif ($CurrentMedicationLevel == 0 && $msgCode == "NS") {
            $msgText = "<p><b>This patient was on a non-standard regime and the algorithm cannot be adjusted for this. Please take extra care as you select your therapeutic plan for this person.</b></p>";
        }

        //Inhaler Technique sub-section
        /*if ($InhalerChangeDevice == "Y" || $InhalerOther == "Y" || $InhalerAddSpacer == "Y"){
            $msgText .= "<hr><p><b>You have noted that inhaler technique was inadequate and you ";
            if (	($InhalerChangeDevice == "Y" && $InhalerOther != "Y" && $InhalerAddSpacer != "Y")
                ||	($InhalerOther == "Y" && $InhalerChangeDevice != "Y" && $InhalerAddSpacer != "Y")
                ||	($InhalerAddSpacer == "Y" && $InhalerChangeDevice != "Y" && $InhalerOther != "Y")
                ) {
                    if ($InhalerChangeDevice == "Y"){
                        $msgText .= "intend to change the patient's inhaler device. Please make that change now.";
                    }
                    if ($InhalerOther == "Y"){
                        $msgText .= "have chosen a solution with the details: <i>" . $InhalerOtherDetails . ".</i> If any changes to medication are needed please make them now.";
                    }
                    if ($InhalerAddSpacer == "Y"){
                        $msgText .= "have chosen to add a spacer. Please do this now and confirm you have done so below.";
                    }
                } else {
                $msgText .= "chose the following multiple solutions:<ul>";
                    if($InhalerChangeDevice == "Y"){
                        $msgText .= "<li>Change the patient's inhaler device</li>";
                    }
                    if($InhalerOther == "Y"){
                        $msgText .= "<li>A solution with the details: <i>" . $InhalerOtherDetails . ".</i></li>";
                    }
                    if($InhalerAddSpacer == "Y"){
                        $msgText .= "<li>Add a spacer</li>";
                    }
                    $msgText .= "</ul></br>Please make the appropriate changes on this screen.";
                }
            $msgText .= "</b></p>";
        }*/

        return $msgText;

    }

    //fn_TherapyInhalerTechnique("NonPharmaRx,ChangeDevice","NonPharmaRx,Other","NonPharmaRx,Details","NonPharmaRx,AddSpacer") - FieldID: 2807
    /*function fn_TherapyInhalerTechnique($arrInputs = array()) {

        //Inhaler Technique sub-section
        $InhalerChangeDevice = $arrInputs[0];
        $InhalerOther = $arrInputs[1];
        $InhalerOtherDetails = $arrInputs[2];
        $InhalerAddSpacer = $arrInputs[3];


        $msgText = "<hr>";

        $msgText .= $InhalerChangeDevice . " " . $InhalerOther . " " . $InhalerAddSpacer . " " . $InhalerOtherDetails;

        if ($InhalerChangeDevice == "Y" || $InhalerOther == "Y" || $InhalerAddSpacer == "Y"){
            $msgText .= "<hr><p><b>You have noted that inhaler technique was inadequate and you ";
            if (	($InhalerChangeDevice == "Y" && $InhalerOther != "Y" && $InhalerAddSpacer != "Y")
                ||	($InhalerOther == "Y" && $InhalerChangeDevice != "Y" && $InhalerAddSpacer != "Y")
                ||	($InhalerAddSpacer == "Y" && $InhalerChangeDevice != "Y" && $InhalerOther != "Y")
                ) {
                    if ($InhalerChangeDevice == "Y"){
                        $msgText .= "intend to change the patient's inhaler device. Please make that change now.";
                    }
                    if ($InhalerOther == "Y"){
                        $msgText .= "have chosen a solution with the details: <i>" . $InhalerOtherDetails . ".</i> If any changes to medication are needed please make them now.";
                    }
                    if ($InhalerAddSpacer == "Y"){
                        $msgText .= "have chosen to add a spacer. Please do this now and confirm you have done so below.";
                    }
                } else {
                $msgText .= "chose the following multiple solutions:<ul>";
                    if($InhalerChangeDevice == "Y"){
                        $msgText .= "<li>Change the patient's inhaler device</li>";
                    }
                    if($InhalerOther == "Y"){
                        $msgText .= "<li>A solution with the details: <i>" . $InhalerOtherDetails . ".</i></li>";
                    }
                    if($InhalerAddSpacer == "Y"){
                        $msgText .= "<li>Add a spacer</li>";
                    }
                    $msgText .= "</ul></br>Please make the appropriate changes on this screen.";
                }
            $msgText .= "</b></p>";
        }

        return $msgText;

    }*/
    //fn_CurrentControlMessage("CurrentControl,DifficultySleeping","CurrentControl,UsualAsthmaSymptoms","CurrentControl,InterferedWithUsualActivities","CurrentControl,FrequencyRelieverInhalerLastWeek","CurrentControl,Last12MonthsOralSteroidsYesNo","CurrentControl,Last12MonthsOralSteroidsNowMany","CurrentControl,Last12MonthsOralSteroidsLast3Months","CurrentControl,Last12MonthsAntibioticsYesNo","CurrentControl,Last12MonthsAntibioticsNowMany","CurrentControl,Last12MonthsAntibioticsLast3Months","CurrentControl,Last12MonthsCallOutYesNo","CurrentControl,Last12MonthsCallOutNowMany","CurrentControl,Last12MonthsCallOutLast3Months","CurrentControl,Last12MonthsAedOrHospYesNo","CurrentControl,Last12MonthsAedOrHospNowMany","CurrentControl,Last12MonthsAedOrHospLast3Months","CurrentControl,Last12MonthsAedOrHospMonth","CurrentControl,Last12MonthsAedOrHospYear","CurrentControl,Last12MonthsAedOrHospDetails","PEF,CurrentPEFPctPredicted","CurrentControl,AsthmaControlScore","Spirometry,PercentPredictedFEV1","LungFunction,LungFunctionPerformed","CurrentMedication,CurrentMedicationLevel","FirstAssessment,CurrentControl45","FirstAssessment,CurrentControl13","FirstAssessment,FastOrStandardTrack","AssessmentDetails,AssessmentType") - FieldID: 2558

    function fn_CurrentControlMessage($arrInputs = array())
    {
        $CurrentControl_DifficultySleeping = $arrInputs[0];
        $CurrentControl_UsualAsthmaSymptoms = $arrInputs[1];
        $CurrentControl_InterferedWithUsualActivities = $arrInputs[2];
        $CurrentControl_FrequencyRelieverInhalerLastWeek = $arrInputs[3];
        $CurrentControl_Last12MonthsOralSteroidsYesNo = $arrInputs[4];
        $CurrentControl_Last12MonthsOralSteroidsNowMany = $arrInputs[5];
        $CurrentControl_Last12MonthsOralSteroidsLast3Months = $arrInputs[6];
        $CurrentControl_Last12MonthsAntibioticsYesNo = $arrInputs[7];
        $CurrentControl_Last12MonthsAntibioticsNowMany = $arrInputs[8];
        $CurrentControl_Last12MonthsAntibioticsLast3Months = $arrInputs[9];
        $CurrentControl_Last12MonthsCallOutYesNo = $arrInputs[10];
        $CurrentControl_Last12MonthsCallOutNowMany = $arrInputs[11];
        $CurrentControl_Last12MonthsCallOutLast3Months = $arrInputs[12];
        $CurrentControl_Last12MonthsAedOrHospYesNo = $arrInputs[13];
        $CurrentControl_Last12MonthsAedOrHospNowMany = $arrInputs[14];
        $CurrentControl_Last12MonthsAedOrHospLast3Months = $arrInputs[15];
        $CurrentControl_Last12MonthsAedOrHospMonth = $arrInputs[16];
        $CurrentControl_Last12MonthsAedOrHospYear = $arrInputs[17];
        $CurrentControl_Last12MonthsAedOrHospDetails = $arrInputs[18];
        $PEF_CurrentPEFPctPredicted = $arrInputs[19];
        $CurrentControl_AsthmaControlScore = $arrInputs[20];
        $Spirometry_PercentPredictedFEV1 = $arrInputs[21];
        $LungFunction_LungFunctionPerformed = $arrInputs[22];

        $CurrentMedication_CurrentMedicationLevel = $arrInputs[23];
        $FirstAssessment_CurrentControl45 = $arrInputs[24];
        $FirstAssessment_CurrentControl13 = $arrInputs[25];
        $FirstAssessment_FastOrStandardTrack = $arrInputs[26];
        $AssessmentDetails_AssessmentType = $arrInputs[27];

        $UpArrow = "&uArr";
        $DownArrow = "&dArr";

        $GoodColour = "#00CC66";
        $PartialColour = "#FFCC33";
        $PoorColour = "#FF3333";

        if ($FirstAssessment_FastOrStandardTrack == "Standard Track") {
            $UsedControl = $FirstAssessment_CurrentControl45;
        } else {
            $UsedControl = $FirstAssessment_CurrentControl13;
        }

        $RCP = ((int)($CurrentControl_DifficultySleeping == "Y") +
            (int)($CurrentControl_UsualAsthmaSymptoms == "Y") +
            (int)($CurrentControl_InterferedWithUsualActivities == "Y"));

        switch ($RCP) {
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
                $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek . " times";
                break;
            case "3-4":
                $RelieverInhalerGPP = $PoorColour;
                $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek . " times";
                break;
            case "5+":
                $RelieverInhalerGPP = $PoorColour;
                $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek . " times";
                break;
            default:
                $RelieverInhalerGPP = "NOT ASKED";
                $RelieverInhalerTimesMessage = $CurrentControl_FrequencyRelieverInhalerLastWeek . "NOT ASKED";
                break;
        }

        $ExacerbationsLast12Months = ($CurrentControl_Last12MonthsOralSteroidsYesNo == "Y" ||
            $CurrentControl_Last12MonthsAntibioticsYesNo == "Y" ||
            $CurrentControl_Last12MonthsCallOutYesNo == "Y" ||
            $CurrentControl_Last12MonthsAedOrHospYesNo == "Y");


        $ExacerbationsLast3Months = ($CurrentControl_Last12MonthsOralSteroidsLast3Months == "Y" ||
            $CurrentControl_Last12MonthsAntibioticsLast3Months == "Y" ||
            $CurrentControl_Last12MonthsCallOutLast3Months == "Y" ||
            $CurrentControl_Last12MonthsAedOrHospLast3Months == "Y");

        if ($FirstAssessment_FastOrStandardTrack == "Standard Track" || $AssessmentDetails_AssessmentType != "1A") {
            switch ($LungFunction_LungFunctionPerformed) {
                case "Spirometry performed":
                    $LfPercent = $Spirometry_PercentPredictedFEV1;
                    $LfPerformed = "FEV1";
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


        if ($CurrentControl_AsthmaControlScore < 15) {
            $ACT_GPP = $PoorColour;
        } elseif ($CurrentControl_AsthmaControlScore < 20) {
            $ACT_GPP = $PartialColour;
        } else {
            $ACT_GPP = $GoodColour;
        }


        if ($LfPercent > 80) {
            $Lf_GPP = $GoodColour;
        } elseif ($LfPercent > 65) {
            $Lf_GPP = $PartialColour;
        } else {
            $Lf_GPP = $PoorColour;
        }

        // RCP
        $RCP_Message = "The Royal College of Physicians Three Questions - " . $RCP . "/3 (low is good)";

        // ACT
        $ACT_Message = "Score of " . $CurrentControl_AsthmaControlScore . "/25 (high is good) on the Asthma Control Test";

        // Exacerbations
        if ($ExacerbationsLast3Months == TRUE) {
            $ExacMessage = "The patient has had an exacerbation in the last 3 months";
            $ExacGPP = $PoorColour;
        } elseif ($ExacerbationsLast12Months == TRUE) {
            $ExacMessage = "The patient has had an exacerbation in the last 12 months, but not in the last 3 months";
            $ExacGPP = $PartialColour;
        } else {
            $ExacMessage = "The patient has had no exacerbations in the last 12 months";
            $ExacGPP = $GoodColour;
        }

        // No reliever inhalations in previous week //$RelieverInhalerGPP; //$CurrentControl_FrequencyRelieverInhalerLastWeek;
        $RelieverInhalerMessage = "The patient used their reliever inhaler " . $RelieverInhalerTimesMessage . " last week";

        // PEF or FEV1 <= 65% Predicted or Best
        $LungFuncMessage = "The patient's " . $LfPerformed . " is " . $LfPercent . "% of expected";

        $messageHTML = "<h3>Control Assessment</h3>";
        $messageHTML = $messageHTML . "<p>The patient is on treatment <b>Step " . $CurrentMedication_CurrentMedicationLevel . "</b> (2014 BTS/SIGN Asthma Guidelines), and their overall control has been assessed as <b>" . strtolower($UsedControl) . "</b>. Their control is based on the following:</p>";

        /// Start of table
        $messageHTML = $messageHTML . "<table class=\"control_table\">";

        // RCP
        $messageHTML = $messageHTML . "<tr>";
        $messageHTML = $messageHTML . "<td>";
        $messageHTML = $messageHTML . "<span style='color:" . $RCP_GPP . "'>O</span>";
        $messageHTML = $messageHTML . "</td>";
        $messageHTML = $messageHTML . "<td>";
        $messageHTML = $messageHTML . $RCP_Message;
        $messageHTML = $messageHTML . "</td>";
        $messageHTML = $messageHTML . "</tr>";

        // ACT
        if ($FirstAssessment_FastOrStandardTrack == "Standard Track") {
            $messageHTML = $messageHTML . "<tr>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . "<span style='color:" . $ACT_GPP . "'>O</span>";
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . $ACT_Message;
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "</tr>";
        }

        // Exacerbations
        $messageHTML = $messageHTML . "<tr>";
        $messageHTML = $messageHTML . "<td>";
        $messageHTML = $messageHTML . "<span style='color:" . $ExacGPP . "'>O</span>";
        $messageHTML = $messageHTML . "</td>";
        $messageHTML = $messageHTML . "<td>";
        $messageHTML = $messageHTML . $ExacMessage;
        $messageHTML = $messageHTML . "</td>";
        $messageHTML = $messageHTML . "</tr>";


        // Reliever inhaler
        if ($RelieverInhalerGPP != "NOT ASKED" && $RelieverInhalerGPP != "NA") {
            $messageHTML = $messageHTML . "<tr>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . "<span style='color:" . $RelieverInhalerGPP . "'>O</span>";
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . $RelieverInhalerMessage;
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "</tr>";
        }

        // Lung Function
        if ($LfPerformed != "NONE") {
            $messageHTML = $messageHTML . "<tr>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . "<span style='color:" . $Lf_GPP . "'>O</span>";
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "<td>";
            $messageHTML = $messageHTML . $LungFuncMessage;
            $messageHTML = $messageHTML . "</td>";
            $messageHTML = $messageHTML . "</tr>";
        }

        $messageHTML = $messageHTML . "</table>";
        return $messageHTML;
    }

    //fn_ExacerbationSeverityMessages("Exacerbation,PEFDeclined","PEF,CurrentPEFPctPredicted","Exacerbation,Sa02Declined","Exacerbation,CurrentSaO2","Exacerbation,HR_RR_SS_Abnormal") - FieldID: 2780
    function fn_ExacerbationSeverityMessages($arrInputs = array())
    {

        $PEFDeclined = $arrInputs[0];
        $CurrentPEFPctPredicted = $arrInputs[1];
        $Sa02Declined = $arrInputs[2];
        $CurrentSaO2 = $arrInputs[3];
        $HR_RR_SS_Abnormal = $arrInputs[4];

        $messageCode = "";

        if ($CurrentPEFPctPredicted == 0 || $PEFDeclined == "Y") {
            $messageCode = "E1";
        } elseif ($CurrentSaO2 < 92 && $Sa02Declined != "Y") {
            if ($CurrentPEFPctPredicted < 50) {
                $messageCode = "E2";
            } else {
                $messageCode = "E3";
            }
        } elseif ($CurrentSaO2 >= 92 || $Sa02Declined == "Y") {
            if ($CurrentPEFPctPredicted < 50) {
                $messageCode = "E4";
            } elseif ($CurrentPEFPctPredicted < 75) {
                $messageCode = "E5";
            } else {
                if ($HR_RR_SS_Abnormal == "Y") {
                    $messageCode = "E6";
                } else {
                    $messageCode = "E7";
                }
            }
        }

        if ($messageCode != "") {
            return $messageCode;
        } else {
            return "error";
        }

    }


    function fn_QOFCodes($arrInputs = array())
    {
        $Therapy_CheckOnNoTherapy = $arrInputs[0];
        $QOF_RemainOnRegister = $arrInputs[1];
        $LungFunction_LungFunctionPerformed = $arrInputs[2];
        $Spirometry_RecentBronchodilators = $arrInputs[3];
        $Spirometry_PrebronchodilatorFEV1 = $arrInputs[4];
        $Spirometry_PostbronchodilatorFEV1 = $arrInputs[5];
        $AssessmentDetails_AssessmentType = $arrInputs[6];
        $PEF_CurrentPEF = $arrInputs[7];
        $CurrentControl_DifficultySleeping = $arrInputs[8];
        $CurrentControl_UsualAsthmaSymptoms = $arrInputs[9];
        $CurrentControl_InterferedWithUsualActivities = $arrInputs[10];
        $Smoking_SmokingStatus = $arrInputs[11];

        $CI =& get_instance();
        $arrFlowHistory = $CI->session->userdata("arrFlowHistory");
        $lastFlow = $arrFlowHistory[count($arrFlowHistory) - 2];

        $strQOFArray = "";

        //{value, displayterm, code, scheme};
        /*
        {,,,CTV3};{,,,READ2};{700,XaEHe,Peak flow rate before bronchodilation,CTV3};{700,339A.,Peak flow rate before bronchodilation,READ2};{,6630.,Asthma not disturbing sleep,CTV3};{,6630.,Asthma not disturbing sleep,READ2};{,6630.XalNa,Asthma not disturbing sleepAsthma never causes daytime symptoms,CTV3};{,6630.663s.,Asthma not disturbing sleepAsthma never causes daytime symptoms,READ2};{,6630.XalNa663f.,Asthma not disturbing sleepAsthma never causes daytime symptomsAsthma never restricts exercise,CTV3};{,6630.663s.663f.,Asthma not disturbing sleepAsthma never causes daytime symptomsAsthma never restricts exercise,READ2};{,UB0oo,Current smoker,CTV3};{,137R.,Current smoker,READ2};
        */

        //Asthma Register - AST001
        if (true) {
            $AST001_displayterm = "";
            $AST001_ctv2 = "";
            $AST001_ctv3 = "";

            //Currently assumed on register
            if ((($lastFlow == "1110" || $lastFlow == "2013") && $Therapy_CheckOnNoTherapy != "NeverOnTherapy" && $Therapy_CheckOnNoTherapy != "JustFinishingTherapy")
                || $QOF_RemainOnRegister == "Y"
            ) {
                $AST001_displayterm = "Asthma";
                $AST001_ctv2 = "H33";
                $AST001_ctv3 = "H33..";
            } elseif (($Therapy_CheckOnNoTherapy == "NeverOnTherapy" || $Therapy_CheckOnNoTherapy == "JustFinishingTherapy")
                && $QOF_RemainOnRegister == "N"
            ) {
                $AST001_displayterm = "Asthma resolved";
                $AST001_ctv2 = "212G";
                $AST001_ctv3 = "21262";
            }

            //$strQOFArray .= $lastFlow;

            $strQOFArray .= "{," . $AST001_ctv3 . "," . $AST001_displayterm . "," . "CTV3};";
            $strQOFArray .= "{," . $AST001_ctv2 . "," . $AST001_displayterm . "," . "READ2};";

        }

        //Reversibility - AST002
        if (true) {
            $AST002_displayterm = "";
            $AST002_ctv2 = "";
            $AST002_ctv3 = "";
            $AST002_value = "";

            if ($LungFunction_LungFunctionPerformed == "Spirometry performed") {
                if ($Spirometry_RecentBronchodilators == "Y") {
                    $AST002_displayterm = "Peak flow rate after bronchodilation";
                    $AST002_ctv2 = "339B";
                    $AST002_ctv3 = "XaEGA";
                    $AST002_value = $Spirometry_PostbronchodilatorFEV1;

                    $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv3 . "," . $AST002_displayterm . "," . "CTV3};";
                    $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv2 . "," . $AST002_displayterm . "," . "READ2};";

                } elseif ($Spirometry_RecentBronchodilators == "N") {
                    if ($Spirometry_PrebronchodilatorFEV1) {
                        $AST002_displayterm = "Peak flow rate before bronchodilation";
                        $AST002_ctv2 = "339A";
                        $AST002_ctv3 = "XaEHe";
                        $AST002_value = $Spirometry_PrebronchodilatorFEV1;

                        $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv3 . "," . $AST002_displayterm . "," . "CTV3};";
                        $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv2 . "," . $AST002_displayterm . "," . "READ2};";

                    }
                    if ($Spirometry_PostbronchodilatorFEV1) {
                        $AST002_displayterm = "Peak flow rate after bronchodilation";
                        $AST002_ctv2 = "339B";
                        $AST002_ctv3 = "XaEGA";
                        $AST002_value = $Spirometry_PostbronchodilatorFEV1;

                        $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv3 . "," . $AST002_displayterm . "," . "CTV3};";
                        $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv2 . "," . $AST002_displayterm . "," . "READ2};";
                    }
                }
            } elseif ($LungFunction_LungFunctionPerformed == "PEF Performed"
                || (($AssessmentDetails_AssessmentType == "1A" || $AssessmentDetails_AssessmentType == "EX") && ($PEF_CurrentPEF))
            ) {
                $AST002_displayterm = "Peak flow rate before bronchodilation";
                $AST002_ctv2 = "339A";
                $AST002_ctv3 = "XaEHe";
                $AST002_value = $PEF_CurrentPEF;

                $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv3 . "," . $AST002_displayterm . "," . "CTV3};";
                $strQOFArray .= "{" . $AST002_value . "," . $AST002_ctv2 . "," . $AST002_displayterm . "," . "READ2};";
            }

        }

        //RCP 3 Questions - AST003
        if (true) {
            $AST003_displayterm = "";
            $AST003_ctv2 = "";
            $AST003_ctv3 = "";

            //Q1
            if ($CurrentControl_DifficultySleeping == "Y") {
                $AST003_displayterm = "Asthma disturbing sleep";
                $AST003_ctv2 = "663N";
                $AST003_ctv3 = "663N.";
            } elseif ($CurrentControl_DifficultySleeping == "N") {
                $AST003_displayterm = "Asthma not disturbing sleep";
                $AST003_ctv2 = "663O";
                $AST003_ctv3 = "663O.";
            }

            $strQOFArray .= "{," . $AST003_ctv3 . "," . $AST003_displayterm . "," . "CTV3};";
            $strQOFArray .= "{," . $AST003_ctv2 . "," . $AST003_displayterm . "," . "READ2};";

            //Q2
            if ($CurrentControl_UsualAsthmaSymptoms == "Y") {
                $AST003_displayterm = "Asthma daytime symptoms";
                $AST003_ctv2 = "663q";
                $AST003_ctv3 = "XaIIZ";
            } elseif ($CurrentControl_UsualAsthmaSymptoms == "N") {
                $AST003_displayterm = "Asthma never causes daytime symptoms";
                $AST003_ctv2 = "663s";
                $AST003_ctv3 = "XaINa";
            }

            $strQOFArray .= "{," . $AST003_ctv3 . "," . $AST003_displayterm . "," . "CTV3};";
            $strQOFArray .= "{," . $AST003_ctv2 . "," . $AST003_displayterm . "," . "READ2};";

            //Q3
            if ($CurrentControl_InterferedWithUsualActivities == "Y") {
                $AST003_displayterm = "Asthma restricts exercise";
                $AST003_ctv2 = "663e";
                $AST003_ctv3 = "663e.";
            } elseif ($CurrentControl_InterferedWithUsualActivities == "N") {
                $AST003_displayterm = "Asthma never restricts exercise";
                $AST003_ctv2 = "663f";
                $AST003_ctv3 = "663f.";
            }

            $strQOFArray .= "{," . $AST003_ctv3 . "," . $AST003_displayterm . "," . "CTV3};";
            $strQOFArray .= "{," . $AST003_ctv2 . "," . $AST003_displayterm . "," . "READ2};";

        }

        //Smoking - AST004
        if ($AssessmentDetails_AssessmentType != "EX") {
            $AST004_displayterm = "";
            $AST004_ctv2 = "";
            $AST004_ctv3 = "";

            if ($Smoking_SmokingStatus == "Current") {
                $AST004_displayterm = "Current smoker";
                $AST004_ctv2 = "137R";
                $AST004_ctv3 = "137R.";
            } elseif ($Smoking_SmokingStatus == "Never") {
                $AST004_displayterm = "Never smoked";
                $AST004_ctv2 = "1371";
                $AST004_ctv3 = "XE0oh";
            } elseif ($Smoking_SmokingStatus == "Recent") {
                $AST004_displayterm = "Recent ex-smoker (stopped within last 6 months)";
                $AST004_ctv2 = "137K0";
                $AST004_ctv3 = "XaQzw";
            } elseif ($Smoking_SmokingStatus == "Ex") {
                $AST004_displayterm = "Ex-smoker (stopped more than 6 months)";
                $AST004_ctv2 = "137S";
                $AST004_ctv3 = "Ub1na";
            }

            $strQOFArray .= "{," . $AST004_ctv3 . "," . $AST004_displayterm . "," . "CTV3};";
            $strQOFArray .= "{," . $AST004_ctv2 . "," . $AST004_displayterm . "," . "READ2};";

        }

        return $strQOFArray;

    }


}