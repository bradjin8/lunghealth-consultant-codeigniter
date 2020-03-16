<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Begin extends MY_Controller
{

    public function index()
    {
        $arrInputs = $this->getVariablesFromString($_SERVER['QUERY_STRING']);

        if (count($arrInputs) > 0) {
            foreach ($arrInputs as $strKey => $strValue) {
                $arrCookie = array(
                    'name' => 'agc_APIVars_' . $strKey,
                    'value' => $strValue,
                    'expire' => '86500'
                );
                $this->input->set_cookie($arrCookie);
            }

            $arrCookie = array(
                'name' => 'agc_APIPatient',
                'value' => false,
                'expire' => '86500'
            );
            $this->input->set_cookie($arrCookie);

            $arrCookie = array(
                'name' => 'agc_APIUser',
                'value' => false,
                'expire' => '86500'
            );
            $this->input->set_cookie($arrCookie);

            $arrCookie = array(
                'name' => 'agc_APIPreviousUser',
                'value' => false,
                'expire' => '86500'
            );
            $this->input->set_cookie($arrCookie);

            $this->session->sess_destroy();
            redirect('/begin/welcome', 'refresh');
        } else {
            $this->throwErrorRedirect('ERROR BEGIN/INDEX/1 No API Variables'
                , 'You arrived from the API without the correct variables. The following may be helpful.');
        }

    }

    public function auditcleanup($strToken = '')
    {
        if (strlen($strToken) > 0) {
            $objPatient = unserialize($this->input->cookie('agc_APIPatient'));

            $this->load->model('audit_model', 'audit_model');
            $this->audit_model->deleteAllRowsForPatientID($objPatient->PatientId);

            redirect('/begin/?token=' . $strToken, 'refresh');
        } else {
            $this->throwErrorRedirect('ERROR BEGIN/AUDITCLEANUP/1 No API Token'
                , 'You did not send an API Token. The following may be helpful.');
        }
    }

    public function audit($intConsultationId = -1, $strConsultationType = '1A')
    {
        if ($intConsultationId > 0) {
            $this->load->model('apiconsultation_model', 'apiconsultation');
            $intAuditId = $this->apiconsultation->getConsultationForAudit($intConsultationId, $strConsultationType);
            if ($intAuditId > 0) {
                $this->load->library('FlowLibrary');
                $this->load->library('FlowAlgorithmsLibrary', array(), 'flowalgorithmslibrary');
                $strScreenName = $this->flowalgorithmslibrary->setAuditIdAndLoad($intAuditId);

                $objReview = $this->flowalgorithmslibrary->getReviewObject();

                $this->load->model('apiuser_model', 'apiuser');
                $objPreviousApiUser = $this->apiuser->getUserObjectById($objReview->agcsystem_APIUserId);

                $arrCookie = array(
                    'name' => 'agc_APIPreviousUser',
                    'value' => serialize($objPreviousApiUser),
                    'expire' => '86500'
                );
                $this->input->set_cookie($arrCookie);


                //die($strScreenName);

                redirect('/flowcontroller/screen/' . $strScreenName, 'refresh');
            } else {
                $this->throwErrorRedirect('ERROR BEGIN/AUDIT/2 Failed to Get Previous Data'
                    , 'You requested an audit of ID: ' . $intConsultationId . '. The following may be helpful.');
            }
        } else {
            $this->throwErrorRedirect('ERROR BEGIN/AUDIT/1 No Consultation Value'
                , 'You requested an audit of ID: ' . $intConsultationId . '. The following may be helpful.');
        }
    }


    public function welcome()
    {
        $this->load->model('review_model', 'review_model');
        $this->load->model('outcomes_model', 'outcomes_model');
        $this->load->model('apiuser_model', 'apiuser');
        $this->load->model('apipatient_model', 'apipatient');
        $this->load->model('apiconsultation_model', 'apiconsultation');
        $this->load->model('auditconsultationvalues_model', 'acv_model');

        $objApiUser = $this->apiuser->getUserObject();
        $objPatient = $this->apipatient->getPatientObject();
        $arrConsultations = array();
        $booCompletedFA = false;

        $arrCookie = array(
            'name' => 'agc_APIPatient',
            'value' => serialize($objPatient),
            'expire' => '86500'
        );
        $this->input->set_cookie($arrCookie);

        $arrCookie = array(
            'name' => 'agc_APIUser',
            'value' => serialize($objApiUser),
            'expire' => '86500'
        );
        $this->input->set_cookie($arrCookie);


        foreach ($this->apiconsultation->getAllPreviousConsultationsFor($objPatient->PatientId) as $objConsultation) {

            if ($objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows) - 1] == "20000") {
                $objConsultation->Status =
                    $this->outcomes_model->getStatus(
                        $objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows) - 2]
                    );
            } else {
                $objConsultation->Status =
                    $this->outcomes_model->getStatus(
                        $objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows) - 1]
                    );
            }

            if (!$objConsultation->Status) {
                $this->throwErrorRedirect('ERROR BEGIN/WELCOME/1 Unknown Consultation Status Received'
                    , 'The remote API returned a Status ('
                    . $objConsultation->arrConsultationFlows[count($objConsultation->arrConsultationFlows) - 1]
                    . ') which the Outcomes Model has no record of. The following may be helpful.');
            }

            if (($objConsultation->ConsultationType === '1A')
                && ($objConsultation->Status->State === 'Complete')
                && ($objConsultation->ApiStatus !== 'CompleteNoMedication')) {
                $booCompletedFA = true;
            }

            $this->acv_model->deleteAllAuditRowsFor($objConsultation->ConsultationId);

            $arrConsultations[] = $objConsultation;

        }

        $intReviewId = $this->review_model->getReviewIdForPatientId($objPatient->PatientId);

        $this->session->set_userdata(array('objSetup_ApiUser' => $objApiUser
        , 'objSetup_ApiPatient' => $objPatient
        , 'objSetup_ApiConsultations' => $arrConsultations));

        $strCurrentPage = "begin/welcome";

        $this->document->setTitleString("Welcome");
        $this->document->setH1String("Welcome to the Guided Consultation");

        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
        $this->load->helper('form');
        $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplateOverride'] = 'header_text';
        $arrVariablesForTemplate['arrPageVariables']['intReviewId'] = $intReviewId;
        if ($intReviewId > 0) {
            $arrVariablesForTemplate['arrPageVariables']['strBeginConsultation_PreviousDate'] = date('l, jS F Y', strtotime($this->review_model->getStartDateFromReviewId($intReviewId)));

        }
        $arrVariablesForTemplate['arrPageVariables']['booCompletedFA'] = $booCompletedFA;
        $arrVariablesForTemplate['arrPageVariables']['arrConsultations'] = $arrConsultations;
        $arrVariablesForTemplate['arrPageVariables']['objApiPatient'] = $objPatient;
        $arrVariablesForTemplate['arrPageVariables']['objApiUser'] = $objApiUser;

        $this->load->view('template', $arrVariablesForTemplate);
    }


    public function create($strReviewType = '')
    {
        if (strlen($strReviewType) === 0) {
            $strReviewType = $this->input->post('agc-begin-AssessmentType');
        }


        $intFlow = -1;

        switch ($strReviewType) {
            case '1A':
                $intFlow = 1001;
                break;
            case 'AR':
            case 'FU':
            case 'EX':
                $intFlow = 19000;
                break;
            /*case 'EX':
                $intFlow = 3001;
                break;*/
            default:
                $intFlow = -1;
        }


        if ($intFlow < 0) {
            $this->throwErrorRedirect('ERROR BEGIN/CREATE/1 No Review Type Received'
                , 'You did not provide a Review Type to attempt to create. The following may be helpful.');
        }

        $this->load->model('previousconsultationvalues_model', 'pcv_model');
        $this->load->model('previousconsultationexacerbation_model', 'pce_model');
        $this->pcv_model->deleteAllRowsForPatientID($this->session->userdata('objSetup_ApiPatient')->PatientId);
        $this->pce_model->deleteAllRowsForPatientID($this->session->userdata('objSetup_ApiPatient')->PatientId);

        $this->load->library('FlowLibrary');
        $this->load->library('FlowAlgorithmsLibrary', array(), 'flowalgorithmslibrary');
        $this->flowalgorithmslibrary->createReview($strReviewType
            , $this->session->userdata('objSetup_ApiUser')
            , $this->session->userdata('objSetup_ApiPatient'));


        $arrCookie = array(
            'name' => 'agc_APIPreviousUser',
            'value' => false,
            'expire' => '86500'
        );
        $this->input->set_cookie($arrCookie);


        $this->flowalgorithmslibrary->pushFlowID($intFlow);


        redirect('/flowcontroller/screen/' . $this->flowalgorithmslibrary->getNextScreen($intFlow), 'refresh');
    }


    public function loadreview($intReviewId = -1)
    {
        $this->load->library('FlowLibrary');
        $this->load->library('FlowAlgorithmsLibrary', array(), 'flowalgorithmslibrary');

        if ($intReviewId > 0) {
            $strScreenName = $this->flowalgorithmslibrary->setReviewIdAndLoad($intReviewId);

            $objReview = $this->flowalgorithmslibrary->getReviewObject();
            $this->load->model('apiuser_model', 'apiuser');
            $objPreviousApiUser = $this->apiuser->getUserObjectById($objReview->agcsystem_APIUserId);

            $arrCookie = array(
                'name' => 'agc_APIPreviousUser',
                'value' => serialize($objPreviousApiUser),
                'expire' => '86500'
            );
            $this->input->set_cookie($arrCookie);

            redirect('/flowcontroller/screen/' . $strScreenName, 'refresh');
        } else {
            $this->throwErrorRedirect('ERROR BEGIN/LOADREVIEW/1 No Review ID'
                , 'You did not provide a Review ID to attempt load. The following may be helpful.');
        }
    }

    public function deletereview($intReviewId = -1)
    {
        $strReviewType = $this->input->post('agc-begin-AssessmentType');

        if ($intReviewId > 0) {
            $this->load->model('review_model', 'review_model');
            if ($this->review_model->setReviewID($intReviewId)->retrieveWithTimestamps()->isLoaded()) {
                $objReview = $this->review_model->getAsObject();
                $this->load->model('Apiconsultation_model', 'apicon_model');
                if ($this->apicon_model->sendConsultationToApi($objReview)) {
                    $this->load->model('previousconsultationvalues_model', 'pcv_model');

                    $this->pcv_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);
                    $this->review_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);

                    $this->review_model->clear();
                    $this->create($strReviewType);
                } else {
                    $this->session->set_flashdata('strTitle', 'ERROR BEGIN/DELETEREVIEW/3 Could not send Consultation to API');
                    $this->session->set_flashdata('strMessage', 'There was no API Error Code, but something went wrong somewhere.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }


            } else {
                $this->throwErrorRedirect('ERROR BEGIN/DELETEREVIEW/2 Unable to Load Review'
                    , 'The Review requested could not be loaded (ReviewID: ' . $intReviewId . '). The following may be helpful.');
            }
        } else {
            $this->throwErrorRedirect('ERROR BEGIN/DELETEREVIEW/1 No Review ID'
                , 'You did not provide a Review ID to attempt delete. The following may be helpful.');
        }
    }


    protected function throwErrorRedirect($strTitle = '', $strMessage = '')
    {
        $CI =& get_instance();
        $CI->session->set_flashdata('strTitle', $strTitle);
        $CI->session->set_flashdata('strMessage', $strMessage);
        redirect('/error/', 'refresh');
    }

    private function getVariablesFromString($strInput)
    {
        $arrInputVariables = array();
        if (strlen($strInput) > 0) {
            foreach (explode('&', $strInput) as $strVariable) {
                $arrInputVariables[explode('=', $strVariable)[0]] = explode('=', $strVariable)[1];
            }
        }
        return $arrInputVariables;
    }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */
