<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*

require_once(dirname(__FILE__).'/../../../libraries/tcpdf/'.'tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');

    //Page header
    public function Header() {
        // Logo
        $image_file = $relative_path.'/impactlogo.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

*/

class Download extends MY_Controller {
    
    

        public function pdf()
        {
            if ($this->session->userdata('intReviewID'))
            {
                $this->load->model('review_model','review_model');
                
                if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieve()->isLoaded())
                {
                    $objReview = $this->review_model->getAsObject();
                    $this->review_model->clear();

                    $strName = $objReview->InitialPatientDetails_FirstName." ".$objReview->InitialPatientDetails_Surname;

                    $this->generatePdf($objReview, $strName, true, true);
                }
                else
                {
                    $this->session->set_flashdata('strTitle','ERROR D/PDF/1 Review Not Found');
                    $this->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }
            }
        }
        
        public function previous_pdf()
        {
            if ($this->session->userdata('intReviewID'))
            {
                $this->load->model('review_model','review_model');
                $this->load->model('previousconsultationvalues_model','pcv_model');
                
                if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieve()->isLoaded())
                {
                    $objReview = $this->review_model->getAsObject();
                    $this->review_model->clear();
                    
                    if ($this->pcv_model->setConsultationID($objReview->agcsystem_PreviousConsultationId)->retrieve()->isLoaded())
                    {
                        $objPreviousConsultation = $this->pcv_model->getAsObject();

                        $strName = $objPreviousConsultation->InitialPatientDetails_FirstName." ".$objPreviousConsultation->InitialPatientDetails_Surname;

                        $this->generatePdf($objPreviousConsultation, $strName);
                    }
                    else
                    {
                        $this->session->set_flashdata('strTitle','ERROR D/PREVIOUS_PDF/2 Previous Consultation Not Found');
                        $this->session->set_flashdata('strMessage','The Previous Consultation, in the Review, in the Session did not match one stored in the database.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                    
                    $this->load->model('apidrugs_model','d_model');
                    $arrModels['drugs_model'] = $this->d_model;
                }
                else
                {
                    $this->session->set_flashdata('strTitle','ERROR D/PREVIOUS_PDF/1 Review Not Found');
                    $this->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }
            }
        }
        
        // public function auditPdf($intConsultationId = -1, $strConsultationType = '')
        // {
        //     if ($intConsultationId > 0)
        //     {
        //         $this->load->model('auditconsultationvalues_model','acv_model');
        //         $this->load->model('apiconsultation_model','apiconsultation');
               
        //         $intAuditId = $this->apiconsultation->getConsultationForAudit($intConsultationId,$strConsultationType);

        //         if ($intAuditId > 0)
        //         {
        //             if ($this->acv_model->setAuditID($intAuditId)->retrieve()->isLoaded())
        //             {
        //                 $objPreviousConsultation = $this->acv_model->getAsObject();
        //                 $strName = $objPreviousConsultation->InitialPatientDetails_FirstName." ".$objPreviousConsultation->InitialPatientDetails_Surname;
        //                 $this->generatePdf($objPreviousConsultation, $strName, "audit");
        //             }
        //             else
        //             {
        //                 $this->session->set_flashdata('strTitle','ERROR D/AUDITPDF/2 Previous Consultation Not Found');
        //                 $this->session->set_flashdata('strMessage','The Previous Consultation requested ('.$intAuditId.') did not match one stored in the database.  The following may be helpful.');
        //                 redirect('/error/', 'refresh');
        //             }
        //         }
        //         else
        //         {
        //             $this->throwErrorRedirect('ERROR D/AUDITPDF/1 Failed to Get Previous Data'
        //                                             , 'You requested an audit of ID: '.$intConsultationId.'. The following may be helpful.');
        //         }
        //     }
        // }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */