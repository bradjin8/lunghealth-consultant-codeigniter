<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finish extends MY_Controller {
    
        public function pushToApi()
        {
            if ($this->session->userdata('intReviewID'))
            {
                $this->load->model('review_model','review_model');
                
                if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieveWithTimestamps()->isLoaded())
                {
                    $objReview = $this->review_model->getAsObject();
                    $arrReview = $this->review_model->getAsArrayForApiPush();
                    $this->review_model->clear();
                    
                    $this->load->model('Apiconsultation_model','apicon_model');
                    
                    if (($objReview->CurrentMedication_CurrentMedicationLevel['FieldValue'] !== '') 
                            && ($objReview->CurrentMedication_CurrentMedicationLevel['FieldValue'] !== '0'))
                    {
                        
                        $objReview->agcsystem_ReviewStatus['FieldValue'] = 'Complete';
                        
                    }
                    else
                    {
                        $objReview->agcsystem_ReviewStatus['FieldValue'] = 'CompleteNoMedication';
                        
                        
                    }
                    
                    if ($this->apicon_model->sendConsultationToApi($objReview))
                    {
                        $this->load->model('previousconsultationvalues_model','pcv_model');
                        $this->pcv_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);
                        $this->review_model->deleteAllRowsForPatientID($objReview->PatientDetails_PatientId['FieldValue']);
                        $strCurrentPage          = "finish/pushtoapi";
                        $this->document->setTitleString("Complete!");
                        $this->document->setH1String("Complete!");
                        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
                        $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplateOverride']    = 'header_text';
                        $this->load->view('template',$arrVariablesForTemplate);
                    }
                    else 
                    {
                        $this->session->set_flashdata('strTitle','ERROR FINISH/PUSHTOAPI/2 Could not send Consultation to API');
                        $this->session->set_flashdata('strMessage','There was no API Error Code, but something went wrong somewhere.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                    
                    //$this->apicon_model->sendConsultation($arrReview);
                    
                    
                    
                }
                else
                {
                    $this->session->set_flashdata('strTitle','ERROR FINISH/PUSHTOAPI/1 Review Not Found');
                    $this->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }
            }
        }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */