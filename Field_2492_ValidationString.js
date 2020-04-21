
function agc_calc_DiagnosisAlgorithm()
{
    //Diagnosis Fields
    var FT_ConfirmDiagnosis13 = $('input[name=FirstAssessment_ConfirmDiagnosis13]'); //2108
    var FT_Diagnosis13Score = $('input[name=DiagnosisBasis_DiagnosisFTScore]'); //2377

    var ST_ConfirmDiagnosis45 = $('input[name=FirstAssessment_DiagnosticConfirmation45]'); //2240
    var ST_Diagnosis45Score = $('input[name=DiagnosisBasis_DiagnosisSTScore]'); //2493

    //Spirometry Fields
    var S_FEV1OverFVC = $('input[name=Spirometry_FEV1OverFVC]'); //338
    var S_PreBroncFev1 = $('input[name=Spirometry_PrebronchodilatorFEV1]'); //172
    var	S_PostBroncFev1 = $('input[name=Spirometry_PostbronchodilatorFEV1]'); //175

    //Shared (FT and ST) Fields
    var BeenOnInhalers = $('input[name=FirstAssessment_BeenOnInhalers]:checked'); //2098

    //Confirm Asthma Diagnosis (Fast Track) fields
    var FT_ExacerbationInPractice = $('input[name=FirstAssessment_DiagnosisBasisExacerbation]:checked'); //2099
    var FT_HospitalOPD = $('input[name=FirstAssessment_DiagnosisBasisHospitalOPD]:checked'); //2100
    var FT_HospitalAdmission = $('input[name=FirstAssessment_DiagnosisBasisHospitalAdmission]:checked'); //2101
    var FT_ObservedTherapyResponse = $('input[name=FirstAssessment_DiagnosisBasisTherapyResponse]:checked'); //2102
    var FT_FEV1ReversibilityOrVariability = $('input[name=FirstAssessment_DiagnosisLungFunction1]:checked'); //2104
    var FT_PEFDiurnalVariation = $('input[name=FirstAssessment_DiagnosisLungFunction2]:checked'); //2105
    var FT_FEV1DecreaseAfterExcercise = $('input[name=FirstAssessment_DiagnosisLungFunction3]:checked'); //2106
    var FT_SpirometricResponseToOralSteroids = $('input[name=FirstAssessment_DiagnosisLungFunction4]:checked'); //2107

    //Confirm Asthma Diagnosis (Standard Track) fields
    var ST_DifficultySleeping = $('input[name=CurrentControl_DifficultySleeping]:checked'); //50
    var ST_UsualAsthmaSymptoms = $('input[name=CurrentControl_UsualAsthmaSymptoms]:checked'); //51
    var ST_InterferedWithUsualActivities = $('input[name=CurrentControl_InterferedWithUsualActivities]:checked'); //52
    var ST_SymptomDuration = $('input[name=FirstAssessment_SymptomDuration]:checked'); //2001
    var ST_HospitalReport = $('input[name=FirstAssessment_HospitalReport]:checked'); //2002
    var ST_ChestCold = $('input[name=FirstAssessment_ChestCold]:checked'); //2013
    var ST_ChestHoliday = $('input[name=FirstAssessment_ChestHoliday]:checked'); //2015
    var ST_TriggersFumesPerfumes = $('input[name=FirstAssessment_TriggersFumesPerfumes]:checked'); //2028
    var ST_TriggersPets = $('input[name=FirstAssessment_TriggersPets]:checked'); //2029
    var ST_TriggersPassiveSmoking = $('input[name=FirstAssessment_TriggersPassiveSmoking]:checked'); //2031
    var ST_TriggersSeasons = $('input[name=FirstAssessment_TriggersSeasons]:checked'); //2032
    var ST_TriggersPollen = $('input[name=FirstAssessment_TriggersPollen]:checked'); //2034
    var ST_TriggersDustMites = $('input[name=FirstAssessment_TriggersDustMites]:checked'); //2035
    var ST_TriggersOther = $('input[name=FirstAssessment_TriggersOther]:checked'); //2036
    var ST_TriggersMould = $('input[name=FirstAssessment_TriggersMould]:checked'); //2167
    var ST_ExertionWorseActiveAfterYN = $('input[name=FirstAssessment_ExertionWorseActiveAfterYN]:checked'); //2314

    //Tests
    var T_FbcBloodEosinophila_EosinophilsPercent = $('input[name=TestsAndResults_FbcBloodEosinophila_EosinophilsPercent]'); //403
    var T_FbcBloodEosinophila_EosinophilsCellsL = $('input[name=TestsAndResults_FbcBloodEosinophila_EosinophilsCellsL]'); //404
    var T_SputumEosinophila_Present = $('input[name=TestsAndResults_SputumEosinophila_Present]:checked'); //2709
    var T_SputumEosinophila_EosinophilsPercent = $('input[name=TestsAndResults_SputumEosinophila_EosinophilsPercent]'); //2710
    var T_SerumIge = $('input[name=TestsAndResults_SerumIge]'); //405
    var T_PreBroncFev1 = $('input[name=TestsAndResults_ReversibilityTest_Fev1Pre]'); //408
    var	T_PostBroncFev1 = $('input[name=TestsAndResults_ReversibilityTest_Fev1Post]'); //409
    var T_FlowVolumeLoop_Category = $('select[name=TestsAndResults_FlowVolumeLoop_Category] option:selected'); //414
    var T_PefCharts_Interpretation = $('select[name=TestsAndResults_PefCharts_Interpretation] option:selected'); //419
    var T_SkinAllergyTesting = $('select[name=TestsAndResults_SkinAllergyTesting] option:selected'); //420
    var T_LungVolumesAndDlco_LungVolumes = $('select[name=TestsAndResults_LungVolumesAndDlco_LungVolumes] option:selected'); //421
    var T_BronchialHyperReactivity = $('input[name=TestsAndResults_BronchialHyperReactivity]'); //423
    var T_AsthmaExerciseTest = $('select[name=TestsAndResults_AsthmaExerciseTest] option:selected'); //424
    var T_CardiorespiratoryExerciseTest = $('select[name=TestsAndResults_CardiorespiratoryExerciseTest] option:selected'); //425
    var T_ExhaledNo = $('input[name=TestsAndResults_ExhaledNo]'); //427

    //Review Last Visit
    var RLV_PEFMeterSupportiveOfAsthma = $('input[name=FollowUp_PEFMeterSupportiveOfAsthma]:checked'); //2355

    //Diagnosis Basis fields
    var D_BeenOnInhalersSinceChildhood = $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]'); //2367
    var D_BeenOnInhalersWithBenefit = $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]'); //2368
    var D_ExacerbationInPractice = $('input[name=DiagnosisBasis_ExacerbationInPractice]'); //2369
    var D_HospitalOPD = $('input[name=DiagnosisBasis_HospitalOPD]'); //2370
    var D_HospitalAdmission = $('input[name=DiagnosisBasis_HospitalAdmission]'); //2371
    var D_ObservedTherapyResponse = $('input[name=DiagnosisBasis_ObservedTherapyResponse]'); //2372

    var D_RCP3PoorControl = $('input[name=DiagnosisBasis_RCP3PoorControl]'); //2477
    var D_HospitalReport = $('input[name=DiagnosisBasis_HospitalReport]'); //2478
    var D_SymptomsPresentSinceChildhood = $('input[name=DiagnosisBasis_SymptomsPresentSinceChildhood]'); //2479
    var D_SymptomsPresentLastFewYears = $('input[name=DiagnosisBasis_SymptomsPresentLastFewYears]'); //2480
    var D_ChestCold = $('input[name=DiagnosisBasis_ChestCold]'); //2481
    var D_ChestOnHoliday = $('input[name=DiagnosisBasis_ChestOnHoliday]'); //2482
    var D_ChestOnExertion = $('input[name=DiagnosisBasis_ChestOnExertion]'); //2483
    var D_TriggersFumesPerfumes = $('input[name=DiagnosisBasis_TriggersFumesPerfumes]'); //2484
    var D_TriggersPets = $('input[name=DiagnosisBasis_TriggersPets]'); //2485
    var D_TriggersPassiveSmoking = $('input[name=DiagnosisBasis_TriggersPassiveSmoking]'); //2486
    var D_TriggersSeasons = $('input[name=DiagnosisBasis_TriggersSeasons]'); //2487
    var D_TriggersPollen = $('input[name=DiagnosisBasis_TriggersPollen]'); //2488
    var D_TriggersDustMites = $('input[name=DiagnosisBasis_TriggersDustMites]'); //2489
    var D_TriggersOther = $('input[name=DiagnosisBasis_TriggersOther]'); //2490
    var D_TriggersMould = $('input[name=DiagnosisBasis_TriggersMould]'); //2491
    var D_TriggersAny = $('input[name=DiagnosisBasis_TriggersAny]'); //2494

    var D_SpirometricResponseToOralSteroids = $('input[name=DiagnosisBasis_SpirometricResponseToOralSteroids]'); //2376
    var D_TestsFbcBloodEosinophila = $('input[name=DiagnosisBasis_TestsFbcBloodEosinophila]'); //2495
    var D_TestsSputumEosinophila = $('input[name=DiagnosisBasis_TestsSputumEosinophila]'); //2713
    var D_SpirometryFEV1OverFVC = $('input[name=DiagnosisBasis_SpirometryFEV1OverFVC]'); //2715
    var D_TestsSerumIge = $('input[name=DiagnosisBasis_TestsSerumIge]'); //2496
    var D_TestsBronchodilatorReversibility = $('input[name=DiagnosisBasis_TestsBronchodilatorReversibility]'); //2498
    var D_TestsFlowVolumeLoop = $('input[name=DiagnosisBasis_TestsFlowVolumeLoop]'); //2499
    var D_TestsPEFCharts = $('input[name=DiagnosisBasis_TestsPEFCharts]'); //2500
    var D_TestsSkinAllergy = $('input[name=DiagnosisBasis_TestsSkinAllergy]'); //2501
    var D_TestsLungVolumesAndDlco_LungVolumes = $('input[name=DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumes]'); //2502
    var D_TestsBronchialHyperReactivity = $('input[name=DiagnosisBasis_TestsBronchialHyperReactivity]'); //2504
    var D_TestsAsthmaExerciseTest = $('input[name=DiagnosisBasis_TestsAsthmaExerciseTest]'); //2505
    var D_TestsCardiorespiratoryExerciseTest = $('input[name=DiagnosisBasis_TestsCardiorespiratoryExerciseTest]'); //2506
    var D_TestsExhaledNo = $('input[name=DiagnosisBasis_TestsExhaledNo]'); //2508

    //Diagnosis Basis fields (Last Time)
    var D_BeenOnInhalersSinceChildhood_LASTTIME = $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhoodLastTime]'); //2510
    var D_BeenOnInhalersWithBenefit_LASTTIME = $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefitLastTime]'); //2511
    var D_ExacerbationInPractice_LASTTIME = $('input[name=DiagnosisBasis_ExacerbationInPracticeLastTime]'); //2512
    var D_HospitalOPD_LASTTIME = $('input[name=DiagnosisBasis_HospitalOPDLastTime]'); //2513
    var D_HospitalAdmission_LASTTIME = $('input[name=DiagnosisBasis_HospitalAdmissionLastTime]'); //2514
    var D_ObservedTherapyResponse_LASTTIME = $('input[name=DiagnosisBasis_ObservedTherapyResponseLastTime]'); //2515

    var D_RCP3PoorControl_LASTTIME = $('input[name=DiagnosisBasis_RCP3PoorControlLastTime]'); //2516
    var D_HospitalReport_LASTTIME = $('input[name=DiagnosisBasis_HospitalReportLastTime]'); //2517
    var D_SymptomsPresentSinceChildhood_LASTTIME = $('input[name=DiagnosisBasis_SymptomsPresentSinceChildhoodLastTime]'); //2518
    var D_SymptomsPresentLastFewYears_LASTTIME = $('input[name=DiagnosisBasis_SymptomsPresentLastFewYearsLastTime]'); //2519
    var D_ChestCold_LASTTIME = $('input[name=DiagnosisBasis_ChestColdLastTime]'); //2520
    var D_ChestOnHoliday_LASTTIME = $('input[name=DiagnosisBasis_ChestOnHolidayLastTime]'); //2521
    var D_ChestOnExertion_LASTTIME = $('input[name=DiagnosisBasis_ChestOnExertionLastTime]'); //2522
    var D_TriggersFumesPerfumes_LASTTIME = $('input[name=DiagnosisBasis_TriggersFumesPerfumesLastTime]'); //2523
    var D_TriggersPets_LASTTIME = $('input[name=DiagnosisBasis_TriggersPetsLastTime]'); //2524
    var D_TriggersPassiveSmoking_LASTTIME = $('input[name=DiagnosisBasis_TriggersPassiveSmokingLastTime]'); //2525
    var D_TriggersSeasons_LASTTIME = $('input[name=DiagnosisBasis_TriggersSeasonsLastTime]'); //2526
    var D_TriggersPollen_LASTTIME = $('input[name=DiagnosisBasis_TriggersPollenLastTime]'); //2527
    var D_TriggersDustMites_LASTTIME = $('input[name=DiagnosisBasis_TriggersDustMitesLastTime]'); //2528
    var D_TriggersOther_LASTTIME = $('input[name=DiagnosisBasis_TriggersOtherLastTime]'); //2529
    var D_TriggersMould_LASTTIME = $('input[name=DiagnosisBasis_TriggersMouldLastTime]'); //2530
    var D_TriggersAny_LASTTIME = $('input[name=DiagnosisBasis_TriggersAnyLastTime]'); //2531

    var D_SpirometricResponseToOralSteroids_LASTTIME = $('input[name=DiagnosisBasis_SpirometricResponseToOralSteroidsLastTime]'); //2532
    var D_TestsFbcBloodEosinophila_LASTTIME = $('input[name=DiagnosisBasis_TestsFbcBloodEosinophilaLastTime]');	//2533
    var D_TestsSputumEosinophila_LASTTIME = $('input[name=DiagnosisBasis_TestsSputumEosinophilaLastTime]'); //2714
    var D_SpirometryFEV1OverFVC_LASTTIME = $('input[name=DiagnosisBasis_SpirometryFEV1OverFVCLastTime]'); //2716
    var D_TestsSerumIge_LASTTIME = $('input[name=DiagnosisBasis_TestsSerumIgeLastTime]'); //2534
    var D_TestsBronchodilatorReversibility_LASTTIME = $('input[name=DiagnosisBasis_TestsBronchodilatorReversibilityLastTime]'); //2536
    var D_TestsFlowVolumeLoop_LASTTIME = $('input[name=DiagnosisBasis_TestsFlowVolumeLoopLastTime]'); //2537
    var D_TestsPEFCharts_LASTTIME = $('input[name=DiagnosisBasis_TestsPEFChartsLastTime]'); //2538
    var D_TestsSkinAllergy_LASTTIME = $('input[name=DiagnosisBasis_TestsSkinAllergyLastTime]'); //2539
    var D_TestsLungVolumesAndDlco_LungVolumes_LASTTIME = $('input[name=DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumesLastTime]'); //2540
    var D_TestsBronchialHyperReactivity_LASTTIME = $('input[name=DiagnosisBasis_TestsBronchialHyperReactivityLastTime]'); //2542
    var D_TestsAsthmaExerciseTest_LASTTIME = $('input[name=DiagnosisBasis_TestsAsthmaExerciseTestLastTime]'); //2543
    var D_TestsCardiorespiratoryExerciseTest_LASTTIME = $('input[name=DiagnosisBasis_TestsCardiorespiratoryExerciseTestLastTime]'); //2544
    var D_TestsExhaledNo_LASTTIME = $('input[name=DiagnosisBasis_TestsExhaledNoLastTime]'); //2546

    //Text Fields (including those not required for diagnosis algorithm
    var DiagnosisBasisText = $('input[name=DiagnosisBasis_DiagnosisBasisText]'); //2550
    var PatientSexField = $('select[name=InitialPatientDetails_Sex] option:selected'); //194
    var PatientSex = '';
    var PatientAge = $('input[name=InitialPatientDetails_Age]'); //214
    var StepNumber = '';
    var PatientSmoker = '';
    var PatientSmokerField = $('input[name=Smoking_SmokingStatus]:checked'); //1
    var AsthmaRegister2006 = $('input[name=FirstAssessment_AsthmaRegister2006]:checked'); //2094
    var Eczema = $('input[name=FirstAssessment_Eczema]:checked'); //2010
    var HayFever = $('input[name=FirstAssessment_HayFever]:checked'); //2004
    var ExertionInhalerReponse = $('input[name=FirstAssessment_ExertionInhalerReponse]:checked'); //2026
    var LungFunctionPerformed = $('input[name=LungFunction_LungFunctionPerformed]'); //2249 (not 471 as previously annotated!)
    var PredictedFEVValue = $('input[name=FirstAssessment_PredictedFEVValue]'); //216
    var PercentPredictedFEV1 = $('input[name=Spirometry_PercentPredictedFEV1]'); //336
    var BestPEF = $('input[name=FirstAssessment_BestPEF]'); //2095
    var PredictedPEFValue = $('input[name=FirstAssessment_PredictedPEFValue]'); //2096
    var CurrentPEF = $('input[name=PEF_CurrentPEF]'); //2117
    var CurrentPEFPctPredicted = $('input[name=PEF_CurrentPEFPctPredicted]'); //2119
    var BestOrPredicted = $('input[name=PEF_BestOrPredicted]:checked'); //2120
    var TriggersOtherDetails = $('input[name=FirstAssessment_TriggersOtherDetails]'); //2037
    var S_MaxFEV1 = 0;
    var S_TempPreFEV1 = 0;
    var S_TempPostFEV1 = 0;

    if(S_PostBroncFev1.val() != ''){
        S_TempPostFEV1 = S_PostBroncFev1.val();
    }

    if(S_PreBroncFev1.val() != ''){
        S_TempPreFEV1 = S_PreBroncFev1.val();
    }

    S_MaxFEV1 = Math.max(S_TempPostFEV1,S_TempPreFEV1);

    switch(PatientSexField.val()){
        case 'M':
            PatientSex = 'male';
            break;
        case 'F':
            PatientSex = 'female';
            break;
        default:
            PatientSex = '';
    }

    switch(PatientSmokerField.val()){
        case 'Current':
            PatientSmoker = 'smoker';
            break;
        case 'Recent':
            PatientSmoker = 'recent ex-smoker';
            break;
        case 'Ex':
            PatientSmoker = 'ex-smoker';
            break;
        case 'Never':
            PatientSmoker = '';
            break;
        default:
            PatientSmoker = '';
    }

    //Other fields
    var medicationStepLevel = $('input[name=CurrentMedication_CurrentMedicationLevel]'); //400
    var medicationLevelAtStartOfLastVisit = $('input[name=CurrentMedication_MedicationLevelAtStartOfLastVisit]'); //2548

    if (medicationStepLevel.val() == 0) {
        StepNumber = 'no current';
    }
    else if (medicationStepLevel.val() == 1) {
        StepNumber = 'Intermittent reliever therapy';
    }
    else if (medicationStepLevel.val() == 2) {
        StepNumber = 'Regular preventer therapy';
    }
    else if (medicationStepLevel.val() == 3) {
        StepNumber = 'Initial add-on therapy';
    }
    else if (medicationStepLevel.val() == 4) {
        StepNumber = 'Additional Controller Therapies';
    }
    else if (medicationStepLevel.val() == 5) {
        StepNumber = 'Specialist therapies';
    }
    else if (medicationStepLevel.val() == 6) {
        StepNumber = 'Hospital specialist therapies';
    }
    else {
        StepNumber = 'no current';
    }

    var S_BronchodilatorReversibility = '';
    var T_BronchodilatorReversibility = '';

    var FTGroup1Score = 0;
    var FTGroup2Score = 0;
    var FTGroup3Score = 0;
    var DiagnosisFTScore = 0;
    var FTDescription = '';

    var STGroup1Score = 0;
    var STGroup2Score = 0;
    var STGroup3Score = 0;
    var STGroup4Score = 0;
    var STGroup5Score = 0;
    var DiagnosisSTScore = 0;
    var STDescription = '';

    var combinedDiagnosisStrength = '';
    var combinedDiagnosisScore = 0;

    var Test_List = [
        D_SpirometricResponseToOralSteroids,
        D_TestsFbcBloodEosinophila,
        D_TestsSputumEosinophila,
        D_SpirometryFEV1OverFVC,
        D_TestsSerumIge,
        D_TestsBronchodilatorReversibility,
        D_TestsFlowVolumeLoop,
        D_TestsPEFCharts,
        D_TestsSkinAllergy,
        D_TestsLungVolumesAndDlco_LungVolumes,
        D_TestsBronchialHyperReactivity,
        D_TestsAsthmaExerciseTest,
        D_TestsCardiorespiratoryExerciseTest,
        D_TestsExhaledNo
    ];

    //Set Test Fields
    if(strScreenName == 'ETR'){

        if(T_FbcBloodEosinophila_EosinophilsPercent.val() > 3 || T_FbcBloodEosinophila_EosinophilsCellsL.val() > 300){
            $('input[name=DiagnosisBasis_TestsFbcBloodEosinophila]').val('Y');
        } else if(D_TestsFbcBloodEosinophila_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsFbcBloodEosinophila]').val('N');
        }

        if(T_SputumEosinophila_Present.val() == 'Y' || T_SputumEosinophila_EosinophilsPercent.val() > 2){
            $('input[name=DiagnosisBasis_TestsSputumEosinophila]').val('Y');
        } else if(D_TestsSputumEosinophila_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsSputumEosinophila]').val('N');
        }

        if(T_SerumIge.val() > 60){
            $('input[name=DiagnosisBasis_TestsSerumIge]').val('Y');
        } else if(D_TestsSerumIge_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsSerumIge]').val('N');
        }

        if(T_FlowVolumeLoop_Category.val() == 'Consistent with asthma'){
            $('input[name=DiagnosisBasis_TestsFlowVolumeLoop]').val('Y');
        } else if(D_TestsFlowVolumeLoop_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsFlowVolumeLoop]').val('N');
        }

        if(T_SkinAllergyTesting.val() == 'Positive'){
            $('input[name=DiagnosisBasis_TestsSkinAllergy]').val('Y');
        } else if(D_TestsSkinAllergy_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsSkinAllergy]').val('N');
        }

        if(T_LungVolumesAndDlco_LungVolumes.val() == 'Consistent with Asthma'){
            $('input[name=DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumes]').val('Y');
        } else if(D_TestsLungVolumesAndDlco_LungVolumes_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsLungVolumesAndDlco_LungVolumes]').val('N');
        }

        if(T_BronchialHyperReactivity.val() < 4 && T_BronchialHyperReactivity.val() != ''){
            $('input[name=DiagnosisBasis_TestsBronchialHyperReactivity]').val('Y');
        } else if(D_TestsBronchialHyperReactivity_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsBronchialHyperReactivity]').val('N');
        }

        if(T_CardiorespiratoryExerciseTest.val() == 'Compatible with asthma'){
            $('input[name=DiagnosisBasis_TestsCardiorespiratoryExerciseTest]').val('Y');
        } else if(D_TestsCardiorespiratoryExerciseTest_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsCardiorespiratoryExerciseTest]').val('N');
        }

        if(T_ExhaledNo.val() > 50){
            $('input[name=DiagnosisBasis_TestsExhaledNo]').val('Y');
        } else if(D_TestsExhaledNo_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsExhaledNo]').val('N');
        }

    }

    //TESTS AND CDFT
    if(strScreenName == 'CDFT' || strScreenName == 'ETR'){

        if(T_AsthmaExerciseTest.val() == 'Positive' || FT_FEV1DecreaseAfterExcercise.val() == 'Y'){
            $('input[name=DiagnosisBasis_TestsAsthmaExerciseTest]').val('Y');
        } else if(D_TestsAsthmaExerciseTest_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsAsthmaExerciseTest]').val('N');
        }

    }

    //TESTS AND SPIROMETRY AND CDFT
    if(strScreenName == 'CDFT' || strScreenName == 'ETR' || strScreenName == 'Spiro'){

        //Set Spirometry (Today) Bronchodilator Reversibility Variable
        if (S_PreBroncFev1.val() != '' && S_PostBroncFev1.val() != ''){
            changeInFev1 = S_PostBroncFev1.val() - S_PreBroncFev1.val();
            changeInFev1Percent = (S_PostBroncFev1.val() - S_PreBroncFev1.val()) / S_PreBroncFev1.val() * 100;

            if(changeInFev1 >= 0.4 && changeInFev1Percent >= 12.0){
                S_BronchodilatorReversibility = 'Y';
            }

        }

        //Set Tests Bronchodilator Reversibility Variable
        if (T_PreBroncFev1.val() != '' && T_PostBroncFev1.val() != ''){
            changeInFev1 = T_PostBroncFev1.val() - T_PreBroncFev1.val();
            changeInFev1Percent = (T_PostBroncFev1.val() - T_PreBroncFev1.val()) / T_PreBroncFev1.val() * 100;

            if(changeInFev1 >= 0.4 && changeInFev1Percent >= 12.0){
                T_BronchodilatorReversibility = 'Y';
            }

        }

        if(S_BronchodilatorReversibility == 'Y' || T_BronchodilatorReversibility == 'Y' || FT_FEV1ReversibilityOrVariability.val() == 'Y'){
            $('input[name=DiagnosisBasis_TestsBronchodilatorReversibility]').val('Y');
        } else if(D_TestsBronchodilatorReversibility_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsBronchodilatorReversibility]').val('N');
        }

    }

    //SPIROMETRY
    if(strScreenName == 'Spiro'){

        if(S_FEV1OverFVC.val() < 70 && S_FEV1OverFVC.val() != ''){
            $('input[name=DiagnosisBasis_SpirometryFEV1OverFVC]').val('Y');
        } else if(D_SpirometryFEV1OverFVC_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_SpirometryFEV1OverFVC]').val('N');
        }

    }

    //TESTS AND CDFT AND REVIEW LAST VISIT
    if(strScreenName == 'CDFT' || strScreenName == 'ETR' || strScreenName == 'RLV'){

        if(T_PefCharts_Interpretation.val() == 'Typical of asthma' || T_PefCharts_Interpretation.val() == 'Supportive of asthma' || FT_PEFDiurnalVariation.val() == 'Y' || RLV_PEFMeterSupportiveOfAsthma.val() == 'Y'){
            $('input[name=DiagnosisBasis_TestsPEFCharts]').val('Y');
        } else if(D_TestsPEFCharts_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TestsPEFCharts]').val('N');
        }

    }

    //Set Confirm Asthma Diagnosis Fields (Fast Track)
    if(strScreenName == 'CDFT'){

        //Group 1 - Treatment History
        if(BeenOnInhalers.val() == 'Since Childhood' && D_BeenOnInhalersWithBenefit_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('Y');
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('N');
        } else if(BeenOnInhalers.val() == 'With Benefit' && D_BeenOnInhalersSinceChildhood_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('Y');
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('N');
        } else if(D_BeenOnInhalersSinceChildhood_LASTTIME.val() != 'Y' && D_BeenOnInhalersWithBenefit_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('N');
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('N');
        }

        //Group 2 - Background Questions
        if(FT_ExacerbationInPractice.val() == 'Y'){
            $('input[name=DiagnosisBasis_ExacerbationInPractice]').val('Y');
        } else if(D_ExacerbationInPractice_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_ExacerbationInPractice]').val('N');
        }

        if(FT_HospitalOPD.val() == 'Y'){
            $('input[name=DiagnosisBasis_HospitalOPD]').val('Y');
        } else if(D_HospitalOPD_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_HospitalOPD]').val('N');
        }

        if(FT_HospitalAdmission.val() == 'Y'){
            $('input[name=DiagnosisBasis_HospitalAdmission]').val('Y');
        } else if(D_HospitalAdmission_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_HospitalAdmission]').val('N');
        }

        if(FT_ObservedTherapyResponse.val() == 'Y'){
            $('input[name=DiagnosisBasis_ObservedTherapyResponse]').val('Y');
        } else if(D_ObservedTherapyResponse_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_ObservedTherapyResponse]').val('N');
        }

        //Group 3 - Tests
        if(FT_SpirometricResponseToOralSteroids.val() == 'Y'){
            $('input[name=DiagnosisBasis_SpirometricResponseToOralSteroids]').val('Y');
        } else if(D_SpirometricResponseToOralSteroids_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_SpirometricResponseToOralSteroids]').val('N');
        }

    }

    //Set Diagnosis Score (Fast Track)
    if(strScreenName == 'CDFT' || strScreenName == 'ETR' || strScreenName == 'LFR' || strScreenName == 'FUQFT'){

        //Set diagnosis score
        if(D_BeenOnInhalersSinceChildhood.val() == 'Y' || D_BeenOnInhalersWithBenefit.val() == 'Y'){
            FTGroup1Score = 2;
        }

        if(D_ExacerbationInPractice.val() == 'Y' || D_HospitalOPD.val() == 'Y' || D_HospitalAdmission.val() == 'Y' || D_ObservedTherapyResponse.val() == 'Y'){
            FTGroup2Score = 2;
        }

        if(D_TestsBronchodilatorReversibility.val() == 'Y' && D_TestsAsthmaExerciseTest.val() == 'Y' && D_TestsPEFCharts.val() == 'Y' && D_SpirometricResponseToOralSteroids.val() == 'Y'){
            FTGroup3Score = 4;
        } else if(
            (D_TestsBronchodilatorReversibility.val() == 'Y' && D_TestsAsthmaExerciseTest.val() == 'Y' && D_TestsPEFCharts.val() == 'Y' && D_SpirometricResponseToOralSteroids.val() == 'N')
            ||	(D_TestsBronchodilatorReversibility.val() == 'Y' && D_TestsAsthmaExerciseTest.val() == 'Y' && D_TestsPEFCharts.val() == 'N' && D_SpirometricResponseToOralSteroids.val() == 'Y')
            ||	(D_TestsBronchodilatorReversibility.val() == 'Y' && D_TestsAsthmaExerciseTest.val() == 'N' && D_TestsPEFCharts.val() == 'Y' && D_SpirometricResponseToOralSteroids.val() == 'Y')
            ||	(D_TestsBronchodilatorReversibility.val() == 'N' && D_TestsAsthmaExerciseTest.val() == 'Y' && D_TestsPEFCharts.val() == 'Y' && D_SpirometricResponseToOralSteroids.val() == 'Y')
        ){
            FTGroup3Score = 3;
        } else if(D_TestsBronchodilatorReversibility.val() == 'Y' || D_TestsAsthmaExerciseTest.val() == 'Y' || D_TestsPEFCharts.val() == 'Y' || D_SpirometricResponseToOralSteroids.val() == 'Y'){
            FTGroup3Score = 2;
        }

        if(FTGroup3Score >= 2){
            DiagnosisFTScore = 4 + FTGroup3Score;
        } else if (FTGroup2Score == 2){
            DiagnosisFTScore = 2 + FTGroup2Score;
        } else if (FTGroup1Score == 2){
            DiagnosisFTScore = 2;
        }

        $('input[name=DiagnosisBasis_DiagnosisFTScore]').val(DiagnosisFTScore);

        //Set old text based field until / unless replaced
        if(DiagnosisFTScore >= 6){
            FTDescription = 'Definite';
        } else if (DiagnosisFTScore >= 4){
            FTDescription = 'Probable';
        } else if (DiagnosisFTScore >= 2){
            FTDescription = 'Possible';
        } else {
            FTDescription = 'NoEvidence';
        }

        $('input[name=FirstAssessment_ConfirmDiagnosis13]').val(FTDescription);

    }

    //Set Diagnostic Status Fields (Standard Track)
    //HS1
    if(strScreenName == 'HS1' || strScreenName == 'HSFU'){

        //Group 1 - Symptom and Treatment Duration
        if(BeenOnInhalers.val() == 'Since Childhood' && D_BeenOnInhalersWithBenefit_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('Y');
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('N');
        } else if(BeenOnInhalers.val() == 'With Benefit' && D_BeenOnInhalersSinceChildhood_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('Y');
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('N');
        } else if(D_BeenOnInhalersSinceChildhood_LASTTIME.val() != 'Y' && D_BeenOnInhalersWithBenefit_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_BeenOnInhalersWithBenefit]').val('N');
            $('input[name=DiagnosisBasis_BeenOnInhalersSinceChildhood]').val('N');
        }

        if(ST_SymptomDuration.val() == 'Since Childhood' && D_SymptomsPresentLastFewYears_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_SymptomsPresentSinceChildhood]').val('Y');
            $('input[name=DiagnosisBasis_SymptomsPresentLastFewYears]').val('N');
        } else if(ST_SymptomDuration.val() == 'Last Few Years (<10)' && D_SymptomsPresentSinceChildhood_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_SymptomsPresentLastFewYears]').val('Y');
            $('input[name=DiagnosisBasis_SymptomsPresentSinceChildhood]').val('N');
        } else if(D_SymptomsPresentLastFewYears_LASTTIME.val() != 'Y' && D_SymptomsPresentSinceChildhood_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_SymptomsPresentLastFewYears]').val('N');
            $('input[name=DiagnosisBasis_SymptomsPresentSinceChildhood]').val('N');
        }

        //Group 2 - Chest Colds / Chest on Holiday / Wheeze on Exertion
        if(ST_ChestCold.val() == 'Always' || ST_ChestCold.val() == 'Sometimes'){
            $('input[name=DiagnosisBasis_ChestCold]').val('Y');
        } else if(D_ChestCold_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_ChestCold]').val('N');
        }

        if(ST_ChestHoliday.val() == 'Much Better'){
            $('input[name=DiagnosisBasis_ChestOnHoliday]').val('Y');
        } else if(D_ChestOnHoliday_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_ChestOnHoliday]').val('N');
        }

        if(ST_ExertionWorseActiveAfterYN.val() == 'Y'){
            $('input[name=DiagnosisBasis_ChestOnExertion]').val('Y');
        } else if(D_ChestOnExertion_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_ChestOnExertion]').val('N');
        }

    }

    //HS2 AND NPRX3
    if(strScreenName == 'HS2' || strScreenName == 'NPRX3'){

        //Group 2 - Triggers
        if(ST_TriggersFumesPerfumes.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersFumesPerfumes]').val('Y');
        } else if(D_TriggersFumesPerfumes_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersFumesPerfumes]').val('N');
        }

        if(ST_TriggersPets.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersPets]').val('Y');
        } else if(D_TriggersPets_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersPets]').val('N');
        }

        if(ST_TriggersPassiveSmoking.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersPassiveSmoking]').val('Y');
        } else if(D_TriggersPassiveSmoking_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersPassiveSmoking]').val('N');
        }

        if(ST_TriggersSeasons.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersSeasons]').val('Y');
        } else if(D_TriggersSeasons_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersSeasons]').val('N');
        }

        if(ST_TriggersPollen.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersPollen]').val('Y');
        } else if(D_TriggersPollen_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersPollen]').val('N');
        }

        if(ST_TriggersDustMites.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersDustMites]').val('Y');
        } else if(D_TriggersDustMites_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersDustMites]').val('N');
        }

        if(ST_TriggersOther.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersOther]').val('Y');
        } else if(D_TriggersOther_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersOther]').val('N');
        }

        if(ST_TriggersMould.val() == 'Y'){
            $('input[name=DiagnosisBasis_TriggersMould]').val('Y');
        } else if(D_TriggersMould_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_TriggersMould]').val('N');
        }

        if(		ST_TriggersFumesPerfumes.val() == 'Y'
            ||	ST_TriggersPets.val() == 'Y'
            ||	ST_TriggersPassiveSmoking.val() == 'Y'
            ||	ST_TriggersSeasons.val() == 'Y'
            ||	ST_TriggersPollen.val() == 'Y'
            ||	ST_TriggersDustMites.val() == 'Y'
            ||	ST_TriggersOther.val() == 'Y'
            ||	ST_TriggersMould.val() == 'Y'
        ){
            $('input[name=DiagnosisBasis_TriggersAny]').val('Y');
        } else if(		D_TriggersFumesPerfumes_LASTTIME.val() != 'Y'
            &&	D_TriggersPets_LASTTIME.val() != 'Y'
            &&	D_TriggersPassiveSmoking_LASTTIME.val() != 'Y'
            &&	D_TriggersSeasons_LASTTIME.val() != 'Y'
            &&	D_TriggersPollen_LASTTIME.val() != 'Y'
            &&	D_TriggersDustMites_LASTTIME.val() != 'Y'
            &&	D_TriggersOther_LASTTIME.val() != 'Y'
            &&	D_TriggersMould_LASTTIME.val() != 'Y'
        ){
            $('input[name=DiagnosisBasis_TriggersAny]').val('N');
        }

    }

    //C1 AND C1FT AND HS3
    if(strScreenName == 'C1FT' || strScreenName == 'C1' || strScreenName == 'HS3'){

        //Group 3 - RCP3
        if(
            (ST_DifficultySleeping.val() == 'Y' && ST_UsualAsthmaSymptoms.val() == 'Y' && ST_InterferedWithUsualActivities.val() == 'N')
            ||	(ST_DifficultySleeping.val() == 'Y' && ST_UsualAsthmaSymptoms.val() == 'N' && ST_InterferedWithUsualActivities.val() == 'Y')
            ||	(ST_DifficultySleeping.val() == 'N' && ST_UsualAsthmaSymptoms.val() == 'Y' && ST_InterferedWithUsualActivities.val() == 'Y')
            ||	(ST_DifficultySleeping.val() == 'Y' && ST_UsualAsthmaSymptoms.val() == 'Y' && ST_InterferedWithUsualActivities.val() == 'Y')
        ){
            $('input[name=DiagnosisBasis_RCP3PoorControl]').val('Y');
        } else if(D_RCP3PoorControl_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_RCP3PoorControl]').val('N');
        }

    }

    //HS1 AND RLV AND HSFU (AND CSR?)
    //CR - ADDED LFR AFTER NEW SUMMARY SO FAR MERGER
    if(strScreenName == 'HS1' || strScreenName == 'RLV' || strScreenName == 'HSFU' || strScreenName == 'LFR'){

        //Group 4 - Hospital Report
        if(ST_HospitalReport.val() == 'Y'){
            $('input[name=DiagnosisBasis_HospitalReport]').val('Y');
        } else if(D_HospitalReport_LASTTIME.val() != 'Y'){
            $('input[name=DiagnosisBasis_HospitalReport]').val('N');
        }

    }

    //Set Diagnosis Score (Standard Track)
    if(strScreenName == 'HS3' || strScreenName == 'LFR' || strScreenName == 'FUQFT' || strScreenName == 'FUQ' || strScreenName == 'FUDS2'){

        if(D_BeenOnInhalersSinceChildhood.val() == 'Y' || D_BeenOnInhalersWithBenefit.val() == 'Y' || D_SymptomsPresentSinceChildhood.val() == 'Y' || D_SymptomsPresentLastFewYears.val() == 'Y'){
            STGroup1Score = 1;
        }

        if(
            (D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'N' && D_TriggersAny.val() == 'N') /*2 from 4*/
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'N' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'N')
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'N' && D_ChestOnExertion.val() == 'N' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'N' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'N')
            ||	(D_ChestCold.val() == 'N' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'N' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'N' && D_ChestOnHoliday.val() == 'N' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'N') /*3 from 4*/
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'N' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'N' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'N' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'Y')
            ||	(D_ChestCold.val() == 'Y' && D_ChestOnHoliday.val() == 'Y' && D_ChestOnExertion.val() == 'Y' && D_TriggersAny.val() == 'Y') /*4 from 4*/
        ){
            STGroup2Score = 1;
        }

        if(D_RCP3PoorControl.val() == 'Y'){
            STGroup3Score = 1;
        }

        if(D_HospitalReport.val() == 'Y'){
            STGroup4Score = 1;
        }

        //Add Tests
        for(var i = 0; i < Test_List.length; ++i){
            if(Test_List[i].val() == 'Y')
                STGroup5Score++;
        }

        DiagnosisSTScore = STGroup1Score + STGroup2Score + STGroup3Score + STGroup4Score + STGroup5Score;
        $('input[name=DiagnosisBasis_DiagnosisSTScore]').val(DiagnosisSTScore);

        //Set old text based field until / unless replaced
        if(DiagnosisSTScore >= 6){
            STDescription = 'Definite';
        } else if (DiagnosisSTScore >= 4){
            STDescription = 'Probable';
        } else if (DiagnosisSTScore >= 2){
            STDescription = 'Possible';
        } else {
            STDescription = 'NoEvidence';
        }

        $('input[name=FirstAssessment_DiagnosticConfirmation45]').val(STDescription);

    }

    //Set text ('LFR' / 'FUQFT' / 'FUQ' / 'FUDS2')
    if(strScreenName == 'LFR' || strScreenName == 'FUQFT' || strScreenName == 'FUQ' || strScreenName == 'FUDS2'){

        //Choose Standard or Fast Track Diagnosis Basis
        if(medicationStepLevel.val() == 0 || medicationStepLevel.val() == 4 || medicationStepLevel.val() == 5 || ((medicationLevelAtStartOfLastVisit.val() == 0 || medicationLevelAtStartOfLastVisit.val() == 4 || medicationLevelAtStartOfLastVisit.val() == 5) && strScreenName == 'ETR')
        ){
            combinedDiagnosisScore = DiagnosisSTScore;
            combinedDiagnosisStrength = STDescription;
        } else if (		medicationStepLevel.val() >= 1 && medicationStepLevel.val() <= 3
            || (medicationLevelAtStartOfLastVisit.val() >= 1 && medicationLevelAtStartOfLastVisit.val() <= 3 && strScreenName == 'ETR')
        ){
            combinedDiagnosisScore = DiagnosisFTScore;
            combinedDiagnosisStrength = FTDescription;
        }

        DiagnosisBasisText = '<p>This patient is a ' + PatientAge.val() + ' year old ' + PatientSex + ' ' + PatientSmoker + ' on <b>' + StepNumber + '</b> therapy</p>'

        //Group 1 - Symptom and Treatment Duration
        if(	   D_BeenOnInhalersSinceChildhood.val() == 'Y'
            || D_BeenOnInhalersWithBenefit.val() == 'Y'
            || D_SymptomsPresentSinceChildhood.val() == 'Y'
            || D_SymptomsPresentLastFewYears.val() == 'Y'
            || AsthmaRegister2006.val() == 'Y'
            || D_HospitalReport.val() == 'Y'
        ){

            DiagnosisBasisText += '<h3>Pointers to an asthma diagnosis so far include:</h3><p><ul>';

            if(AsthmaRegister2006.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has been on the Asthma Register for nine or more years (since before 2006)</li>';
            }

            if(D_SymptomsPresentSinceChildhood.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has had asthma symptoms since childhood or for more than ten years</li>';
            }

            if(D_SymptomsPresentLastFewYears.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has had asthma symptoms for the last few years</li>';
            }

            if(D_BeenOnInhalersSinceChildhood.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has been on inhalers since childhood or for more than ten years</li>';
            }

            if(D_BeenOnInhalersWithBenefit.val() == 'Y'){
                DiagnosisBasisText += '<li>Reports symptomatic benefit from inhaled therapy</li>';
            }

            if(D_HospitalReport.val() == 'Y'){
                DiagnosisBasisText += '<li>There is a record of a hospital report for this patient, confirming a diagnosis of asthma</li>';
            }

            DiagnosisBasisText += '</ul></p>';

        }

        //Group 2 - Chest Colds / Chest on Holiday / Wheeze on Exertion / Triggers (Standard Track)
        if(	   D_ChestCold.val() == 'Y'
            || D_ChestOnHoliday.val() == 'Y'
            || D_ChestOnExertion.val() == 'Y'
            || D_TriggersAny.val() == 'Y'
            || Eczema.val() == 'Now'
            || HayFever.val() == 'Y'
            || D_RCP3PoorControl.val() == 'Y'
        ){

            DiagnosisBasisText += '<h3>Other Supporting Information:</h3><p><ul>';

            if(D_ChestCold.val() == 'Y'){
                DiagnosisBasisText += '<li>When this patient gets a cold, it ' + ST_ChestCold.val().toLowerCase() + ' goes to his / her chest</li>';
            }

            if(D_ChestOnHoliday.val() == 'Y'){
                DiagnosisBasisText += '<li>When this patient is on holiday, their chest is improved</li>';
            }

            if(D_ChestOnExertion.val() == 'Y' && ExertionInhalerReponse.val() == 'Y'){
                DiagnosisBasisText += '<li>After exertion, this patient experiences worsening chest symptoms (and it responds to inhalers)</li>';
            }

            if(D_ChestOnExertion.val() == 'Y' && ExertionInhalerReponse.val() != 'Y'){
                DiagnosisBasisText += '<li>After exertion, this patient experiences worsening chest symptoms</li>';
            }

            //Triggers
            if(D_TriggersAny.val() == 'Y'){

                DiagnosisBasisText += '<li>Triggers:</li><ul>';

                if(D_TriggersFumesPerfumes.val() == 'Y'){
                    DiagnosisBasisText += '<li>Perfume fumes</li>';
                }

                if(D_TriggersPets.val() == 'Y'){
                    DiagnosisBasisText += '<li>Pets</li>';
                }

                if(D_TriggersPassiveSmoking.val() == 'Y'){
                    DiagnosisBasisText += '<li>Passive Smoking</li>';
                }

                if(D_TriggersSeasons.val() == 'Y'){
                    DiagnosisBasisText += '<li>Seasons</li>';
                }

                if(D_TriggersPollen.val() == 'Y'){
                    DiagnosisBasisText += '<li>Pollen</li>';
                }

                if(D_TriggersDustMites.val() == 'Y'){
                    DiagnosisBasisText += '<li>Dust Mites</li>';
                }

                if(D_TriggersOther.val() == 'Y'){
                    DiagnosisBasisText += '<li>Other: <i>'+ TriggersOtherDetails.val() +'</i></li>';
                }

                if(D_TriggersMould.val() == 'Y'){
                    DiagnosisBasisText += '<li>Mould</li>';
                }

                DiagnosisBasisText += '</ul>';

            }

            if(D_RCP3PoorControl.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has previously scored 2 or 3 on Royal College of Physicians (RCP) 3 Questions, indicating poor asthma control</li>';
            }

            if(Eczema.val() == 'Now' || HayFever.val() == 'Y'){
                DiagnosisBasisText += '<li>Patient has concomitant eczema or hay fever</li>';
            }

            DiagnosisBasisText += '</ul></p>';

        }

        //Group 2 - Background Questions (Fast Track)
        if(D_ExacerbationInPractice.val() == 'Y'
            || D_HospitalOPD.val() == 'Y'
            || D_HospitalAdmission.val() == 'Y'
            || D_ObservedTherapyResponse.val() == 'Y'
        ){

            DiagnosisBasisText += '<h3>Other Supporting Information:</h3><p><ul>';

            if(D_ExacerbationInPractice.val() == 'Y'){
                DiagnosisBasisText += '<li>Management of an exacerbation in the practice</li>';
            }

            if(D_HospitalOPD.val() == 'Y'){
                DiagnosisBasisText += '<li>A hospital OPD assessment for asthma</li>';
            }

            if(D_HospitalAdmission.val() == 'Y'){
                DiagnosisBasisText += '<li>Information following a hospital admission / emergency attendance</li>';
            }

            if(D_ObservedTherapyResponse.val() == 'Y'){
                DiagnosisBasisText += '<li>An observed response to therapy in the practice</li>';
            }

            DiagnosisBasisText += '</ul></p>';

        }

        //PEF
        if(LungFunctionPerformed.val() == 'PEF Performed'){

            DiagnosisBasisText += '<h3>Today\'s Peak Flow Results:</h3><p>';

            DiagnosisBasisText += 'Today\'s PEF was recorded as ' + CurrentPEF.val() + ' (L/min) which is ' + CurrentPEFPctPredicted.val() + ' percent of the patient\'s ' + BestOrPredicted.val().toLowerCase() + ' value of ';

            if(BestOrPredicted.val() == 'Best'){
                DiagnosisBasisText += BestPEF.val() + ' (L/min)';
            } else {
                DiagnosisBasisText += PredictedPEFValue.val() + ' (L/min)';
            }

            DiagnosisBasisText += '</p>';

        }

        //FEV1
        if(LungFunctionPerformed.val() == 'Spirometry performed'){

            DiagnosisBasisText += '<h3>Today\'s Spirometry Results:</h3><p>';

            DiagnosisBasisText += 'Today\'s FEV1 was recorded as ' + S_MaxFEV1 + ' litres which is ' + PercentPredictedFEV1.val() + ' percent of the patient\'s predicted value of ' + PredictedFEVValue.val() + ' litres';

            DiagnosisBasisText += '</p>';

        }

        //Tests
        if(		D_TestsAsthmaExerciseTest.val() == 'Y'
            || 	D_TestsBronchialHyperReactivity.val() == 'Y'
            || 	D_TestsBronchodilatorReversibility.val() == 'Y'
            || 	D_TestsCardiorespiratoryExerciseTest.val() == 'Y'
            || 	D_TestsExhaledNo.val() == 'Y'
            || 	D_TestsFbcBloodEosinophila.val() == 'Y'
            || 	D_TestsSputumEosinophila.val() == 'Y'
            || 	D_TestsFlowVolumeLoop.val() == 'Y'
            || 	D_SpirometryFEV1OverFVC.val() == 'Y'
            || 	D_TestsLungVolumesAndDlco_LungVolumes.val() == 'Y'
            || 	D_TestsPEFCharts.val() == 'Y'
            || 	D_TestsSerumIge.val() == 'Y'
            || 	D_TestsSkinAllergy.val() == 'Y'
            || 	D_SpirometricResponseToOralSteroids.val() == 'Y'
        ){

            DiagnosisBasisText += '<h3>Other Supportive Test Results:</h3><p><ul>';

            if(D_TestsAsthmaExerciseTest.val() == 'Y'){
                DiagnosisBasisText += '<li>Asthma Exercise Test</li>';
            }

            if(D_TestsBronchialHyperReactivity.val() == 'Y'){
                DiagnosisBasisText += '<li>Bronchial Hyper Reactivity (< 4.0 mg/L)</li>';
            }

            if(D_TestsBronchodilatorReversibility.val() == 'Y'){
                DiagnosisBasisText += '<li>Bronchodilator Reversibility (> 12% and 200 ml change in FEV1 or FVC)</li>';
            }

            if(D_TestsCardiorespiratoryExerciseTest.val() == 'Y'){
                DiagnosisBasisText += '<li>Cardiorespiratory Exercise Test</li>';
            }

            if(D_TestsExhaledNo.val() == 'Y'){
                DiagnosisBasisText += '<li>Exhaled NO (> 50 ppm)</li>';
            }

            if(D_TestsFbcBloodEosinophila.val() == 'Y'){
                DiagnosisBasisText += '<li>FBC Blood Eosinophila (Eosinophils of > 3% or > 300)</li>';
            }

            if(D_TestsSputumEosinophila.val() == 'Y'){
                DiagnosisBasisText += '<li>Sputum Eosinophila (Present or Eosinophils of > 2%)</li>';
            }

            if(D_TestsFlowVolumeLoop.val() == 'Y'){
                DiagnosisBasisText += '<li>Flow Volume Loop (FEV1/FVC ratio < 70%)</li>';
            }

            if(D_SpirometryFEV1OverFVC.val() == 'Y'){
                DiagnosisBasisText += '<li>FEV1/FVC ratio (< 70% reported in Spirometry)</li>';
            }

            if(D_TestsLungVolumesAndDlco_LungVolumes.val() == 'Y'){
                DiagnosisBasisText += '<li>Lung Volumes / Dlco Consistent with Asthma</li>';
            }

            if(D_TestsPEFCharts.val() == 'Y'){
                DiagnosisBasisText += '<li>PEF Chart Variability</li>';
            }

            if(D_TestsSerumIge.val() == 'Y'){
                DiagnosisBasisText += '<li>Serum IGE (> 60 u/l)</li>';
            }

            if(D_TestsSkinAllergy.val() == 'Y'){
                DiagnosisBasisText += '<li>Skin Allergy Test</li>';
            }

            if(D_SpirometricResponseToOralSteroids.val() == 'Y'){
                DiagnosisBasisText += '<li>Spirometric Response to Oral Steroids (best vs least FEV1 > 400 ml)</li>';
            }

            DiagnosisBasisText += '</ul></p>';

        }

        $('#text-2172').html(DiagnosisBasisText);
        $('input[name=DiagnosisBasis_DiagnosisBasisText]').val(DiagnosisBasisText);

    }

}

$( document ).ready(function(){agc_calc_DiagnosisAlgorithm();});

//Fast Track Fields
$('input[name=FirstAssessment_BeenOnInhalers]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisBasisExacerbation]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisBasisHospitalOPD]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisBasisHospitalAdmission]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisBasisTherapyResponse]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisLungFunction1]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisLungFunction2]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisLungFunction3]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_DiagnosisLungFunction4]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});

//Standard Track Fields
$('input[name=FirstAssessment_SymptomDuration]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=CurrentControl_DifficultySleeping]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=CurrentControl_UsualAsthmaSymptoms]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=CurrentControl_InterferedWithUsualActivities]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_HospitalReport]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_ChestCold]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_ChestHoliday]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_ExertionWorseActiveAfterYN]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersFumesPerfumes]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersPets]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersPassiveSmoking]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersSeasons]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersPollen]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersDustMites]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersOther]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=FirstAssessment_TriggersMould]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});

//Tests Fields
$('input[name=TestsAndResults_FbcBloodEosinophila_EosinophilsPercent]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_FbcBloodEosinophila_EosinophilsCellsL]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_SputumEosinophila_Present]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_SputumEosinophila_EosinophilsPercent]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_SerumIge]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_FlowVolumeLoop_Category]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_PefCharts_Interpretation]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_SkinAllergyTesting]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_LungVolumesAndDlco_LungVolumes]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_BronchialHyperReactivity]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_AsthmaExerciseTest]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('select[name=TestsAndResults_CardiorespiratoryExerciseTest]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_ExhaledNo]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_ReversibilityTest_Fev1Pre]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=TestsAndResults_ReversibilityTest_Fev1Post]').change(function(){agc_calc_DiagnosisAlgorithm();});

//Spirometry
$('input[name=Spirometry_PrebronchodilatorFEV1]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=Spirometry_PostbronchodilatorFEV1]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=Spirometry_PrebronchodilatorFVC]').change(function(){agc_calc_DiagnosisAlgorithm();});
$('input[name=Spirometry_PostbronchodilatorFVC]').change(function(){agc_calc_DiagnosisAlgorithm();});

//Review Last Visit Fields
$('input[name=FollowUp_PEFMeterSupportiveOfAsthma]').on('ifChanged',function(){agc_calc_DiagnosisAlgorithm();});
