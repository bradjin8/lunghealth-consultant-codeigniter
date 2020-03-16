<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Finish extends MY_Controller
{

    public function pdfString()
    {
        if ($this->session->userdata('intReviewID')) {
            $this->load->model('review_model', 'review_model');

            if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieve()->isLoaded()) {
                $objReview = $this->review_model->getAsObject();
                $this->review_model->clear();

                $strName = $objReview->InitialPatientDetails_FirstName . " " . $objReview->InitialPatientDetails_Surname;


                // TODO: confirm this works...
                error_log("pdf generation at end");
                $this->generatePdf($objReview, $strName);

                return (true);

//                    $strDate = explode('T',$objReview->agcsystem_ReviewStartTime)[0];
//
//
//					/*
//                    $strFilename = $objReview->InitialPatientDetails_FirstName."_"
//                                    .$objReview->InitialPatientDetails_Surname."_"
//                                    .explode('T',$objReview->agcsystem_ReviewStartTime)[0];
//
//					*/
//					$strFilename = $objReview->PatientDetails_PatientId;
//
//                    $this->load->model('apidrugs_model','d_model');
//                    $arrModels['drugs_model'] = $this->d_model;

            } else {
                $this->session->set_flashdata('strTitle', 'ERROR D/PDF/1 Review Not Found');
                $this->session->set_flashdata('strMessage', 'The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                redirect('/error/', 'refresh');
            }
        }

        return (true);

    }

    public function pushToApi()
    {
        if ($this->session->userdata('intReviewID')) {
            $this->load->model('review_model', 'review_model');

            if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieveWithTimestamps()->isLoaded()) {
                $objReview = $this->review_model->getAsObject();
                $arrReview = $this->review_model->getAsArrayForApiPush();
                $this->review_model->clear();

                $this->load->model('Apiconsultation_model', 'apicon_model');

                if (($objReview->CurrentMedication_CurrentMedicationLevel['FieldValue'] !== '')
                    && ($objReview->CurrentMedication_CurrentMedicationLevel['FieldValue'] !== '0')) {

                    $objReview->agcsystem_ReviewStatus['FieldValue'] = 'Complete';

                } else {
                    $objReview->agcsystem_ReviewStatus['FieldValue'] = 'CompleteNoMedication';

                }

                /*
                $pdfAsString = utf8_encode($pdfAsString);
                $this->load->helper('xml');
                $pdfAsString = xml_convert($pdfAsString);

                */
                /*

                $pdfAsString = str_replace('"', '&quot;', $pdfAsString);
                $pdfAsString = str_replace('&', '&amp;', $pdfAsString);
                $pdfAsString = str_replace("'", "&#039;", $pdfAsString);
                $pdfAsString = str_replace('<', '&lt;', $pdfAsString);
                $pdfAsString = str_replace('>', '&gt;', $pdfAsString);

                */

                //$objReview->AssessmentDetails_PDF['FieldValue'] = $pdfAsString;

                //var_dump($pdfAsString);die;

                if ($this->apicon_model->sendConsultationToApi($objReview)) {
                    $this->load->model('previousconsultationvalues_model', 'pcv_model');
                    $this->pcv_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);
                    $this->review_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);
                    $strCurrentPage = "finish/pushtoapi";
                    $this->document->setTitleString("Complete!");
                    $this->document->setH1String("Complete!");
                    $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
                    $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplateOverride'] = 'header_text';
                    $this->load->view('template', $arrVariablesForTemplate);


                    $consultationId = $this->apicon_model->apiConsultationId;
                    $this->auditPdf($consultationId, "", false);
                    // $CI->session->set_userdata('intReviewID',$consultationId);

                    // $this->load->model('review_model','review_model_api');
                    // if ($this->review_model_api->setReviewID($consultationId)->retrieve()->isLoaded())
                    // {
                    //     error_log("pdf generation at end");
                    //     $finalReview = $this->review_model_api->getAsObject();
                    //     $this->review_model_api->clear();
                    //     $strName = $finalReview->InitialPatientDetails_FirstName." ".$finalReview->InitialPatientDetails_Surname;
                    //     $this->generatePdf($finalReview, $strName, "report");
                    // }
//
//                        //$pdfAsString = $this->pdfString($objReview);
//                        $strName = $objReview->InitialPatientDetails_FirstName." ".$objReview->InitialPatientDetails_Surname;
//                        // TODO: confirm this works...
//                        error_log("pdf generation at end");
//                        $this->generatePdf($objReview, $strName, "finish", false);
                } else {
                    $this->session->set_flashdata('strTitle', 'ERROR FINISH/PUSHTOAPI/2 Could not send Consultation to API');
                    $this->session->set_flashdata('strMessage', 'There was no API Error Code, but something went wrong somewhere.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }

                //$this->apicon_model->sendConsultation($arrReview);


            } else {
                $this->session->set_flashdata('strTitle', 'ERROR FINISH/PUSHTOAPI/1 Review Not Found');
                $this->session->set_flashdata('strMessage', 'The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                redirect('/error/', 'refresh');
            }
        }
    }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */