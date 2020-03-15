<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends MY_Controller {
    
        public function pdf()
        {
            $this->load->library('Pdf');

            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            
            $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');
            $font_relative_path = realpath(dirname(__FILE__).'/../../../includes/fonts/');
			
            $strName = "";
            $strDate = "";
            $strFilename = "";
            
            
            if ($this->session->userdata('intReviewID'))
            {
                $this->load->model('review_model','review_model');
                
                if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieve()->isLoaded())
                {
                    $objReview = $this->review_model->getAsObject();
                    $this->review_model->clear();
                    
                    $strName = $objReview->InitialPatientDetails_FirstName." ".$objReview->InitialPatientDetails_Surname;
                    
                    $strDate = explode('T',$objReview->agcsystem_ReviewStartTime)[0];
                    
                    $strFilename = $objReview->InitialPatientDetails_FirstName."_"
                                    .$objReview->InitialPatientDetails_Surname."_"
                                    .explode('T',$objReview->agcsystem_ReviewStartTime)[0];
                    
                    $this->load->model('apidrugs_model','d_model');
                    $arrModels['drugs_model'] = $this->d_model;
                   
                }
                else
                {
                    $this->session->set_flashdata('strTitle','ERROR D/PDF/1 Review Not Found');
                    $this->session->set_flashdata('strMessage','The ReviewID in the Session did not match one stored in the database.  The following may be helpful.');
                    redirect('/error/', 'refresh');
                }
            }
            
            
            
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Lung Health');
            $pdf->SetTitle('Report of '.$strName);
            $pdf->SetSubject($strName);
            $pdf->SetKeywords('');

            // set default header data
            //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 058', PDF_HEADER_STRING);

            // set header and footer fonts
            //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(20, 10, 20);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            //$pdf->SetFont('helvetica', '', 10);
			$Calibri = $pdf->addTTFfont($file=$font_relative_path.'/Calibri.ttf', 'TrueTypeUnicode', '', 10);
			
			
            // add a page
            $pdf->AddPage();

            // NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
            //$pdf->setRasterizeVectorImages(true);

            $pdf->ImageSVG($file=$relative_path.'/logo4.svg', $x=10, $y=10, $w=70, $h='', '', $align='', $palign='R', $border=0, $fitonpage=false);
			
			$pdf->Image($file=$relative_path.'/impactlogo.jpg', $x=20, $y=10, $w=50, $h='', '', $align='', $palign='l', $border=0, $fitonpage=false);
			$pdf->setPageMark();
			
            //$pdf->ImageSVG($file='images/tux.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);

            //$pdf->SetFont('helvetica', '', 12);
            $pdf->SetY(25);
            $pdf->SetX(20);
           /* $txt = 'Name : '.$strName;
            $pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);

            // ---------------------------------------------------------
            $pdf->SetMargins(20, 10, 20);
            $pdf->AddPage();*/
            $html = $this->load->view('reports/report',array('objReview'=>$objReview,'arrModels'=>$arrModels),true); 
            
			$footer = "*<h2>Nurse Summary</h2>
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
								<td rowspan='3'><br><br><br><br><br><br><br></td>
								<td></td>
								<td></td>

							</tr>
							<tr>
								<td rowspan='3'>Nurse Name:</td>
								<td>Nurse Signature:</td>
								<td>Date:</td>
							</tr>

						</table>
						<h2>GP Recommendations and Requests</h2>
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
								<td rowspan='1'><br><br><br><br><br><br><br>I authorise the NSHI Nurse Advisor to implement the above medicinal/non-medicinal intervention(s) in line with the Practice Treatment Protocol on the practice computer system</td>
							</tr>
						</table>
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
								<td rowspan='3'>GP Name:</td>
								<td>GP Signature:</td>
								<td>Date:</td>
							</tr>
						</table>
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
								<td rowspan='2'>Review appointment required? Yes/No</td>
								<td>If Yes, give date:</td>
							</tr>
						</table>
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
							 <td  rowspan='2' align='left'>UK/IM/16/0000<br>Date of Preparation: May 2016 &copy; NSHI Ltd 2016</td>
							 <td align='right'>This service is sponsored by Teva UK Limited<br>Teva UK Limited, Field House, Station Approach, Harlow, Essex CM20 2FB</td>
							</tr>
						</table>
						
						" ;
/*<h2>Nurse Summary</h2>
						<table border='1'>
							<tr>
							 
							</tr>
							<tr>
							 <td rowspan='3'>Nurse Name:</td><td>Nurse Signature:</td><td>Date:</td>
							</tr>	
						</table>";
			
						<h2>GP Recommendations and Requests</h2>
						<table width='100%' border='1'>
							<tr height='100'>
							  <td colspan='100%' valign='bottom'>I authorise the NSHI Nurse Advisor to implement the above medicinal/non-medicinal intervention(s) in line with the Practice Treatment Protocol on the practice computer system</td>
							</tr>
							<tr>
							 <td width = '33%'>GP Name:</td><td width = '33%'>GP Signature:</td><td width = '33%'>Date:</td>
							</tr>	
						</table>

						<table width='100%'>
							<tr>
							 <td align='left'>UK/IM/16/0000<br>Date of Preparation: May 2016 &copy; NSHI Ltd 2016</td>
							 <td align='right'>This service is sponsored by Teva UK Limited<br>Teva UK Limited, Field House, Station Approach, Harlow, Essex CM20 2FB</td>
							</tr>
						</table>";
			*/
			
            $html = "<html><head><style> body {
                padding-left: 100px;
            } </style></head><body>".$html.$footer."</body></html>";
            
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $pdf->Output('Asthma_'.$strFilename.'.pdf', 'D');
            
            
            
            
        }
        
        public function previous_pdf()
        {
            $this->load->library('Pdf');

            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            
            $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');
            
            $strName = "";
            $strDate = "";
            $strFilename = "";
            
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
                        
                        $strDate = explode('T',$objReview->agcsystem_ReviewStartTime)[0];
                    
                        $strFilename = $objReview->InitialPatientDetails_FirstName."_"
                                    .$objReview->InitialPatientDetails_Surname."_"
                                    .explode('T',$objReview->agcsystem_ReviewStartTime)[0];
                    
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
            
            
            
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Lung Health');
            $pdf->SetTitle('Report of '.$strName);
            $pdf->SetSubject($strName);
            $pdf->SetKeywords('');

            // set default header data
            //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 058', PDF_HEADER_STRING);

            // set header and footer fonts
            //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(20, 10, 20);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            $pdf->SetFont('helvetica', '', 10);

            // add a page
            $pdf->AddPage();

            // NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
            //$pdf->setRasterizeVectorImages(true);


            




            $pdf->ImageSVG($file=$relative_path.'/logo4.svg', $x=10, $y=10, $w=70, $h='', '', $align='', $palign='R', $border=0, $fitonpage=false);

            //$pdf->ImageSVG($file='images/tux.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);

            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetY(25);
            $pdf->SetX(20);
           /* $txt = 'Name : '.$strName;
            $pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);

            // ---------------------------------------------------------
            $pdf->SetMargins(20, 10, 20);
            $pdf->AddPage();*/
            $html = $this->load->view('reports/report',array('objReview'=>$objPreviousConsultation,'arrModels'=>$arrModels),true); 
            
            $html = "<html><head><style> body {
                padding-left: 100px;
            } </style></head><body>".$html."</body></html>";
            
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $pdf->Output('Asthma_'.$strFilename.'.pdf', 'D');
            
            
            
        }
        
        public function auditPdf($intConsultationId = -1, $strConsultationType = '')
        {
            $this->load->library('Pdf');

            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            
            $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');
            
            $strName = "";
            $strDate = "";
            $strFilename = "";
            
            if ($intConsultationId > 0)
            {
                $this->load->model('auditconsultationvalues_model','acv_model');
                $this->load->model('apiconsultation_model','apiconsultation');
               
                $intAuditId = $this->apiconsultation->getConsultationForAudit($intConsultationId,$strConsultationType);
				
				
				
                if ($intAuditId > 0)
                {
                    
                    if ($this->acv_model->setAuditID($intAuditId)->retrieve()->isLoaded())
                    {

                        $objPreviousConsultation = $this->acv_model->getAsObject();


                        $strName = $objPreviousConsultation->InitialPatientDetails_FirstName." ".$objPreviousConsultation->InitialPatientDetails_Surname;


                        
                        $strDate = explode('T',$objPreviousConsultation->agcsystem_ReviewStartTime)[0];
                    
                        $strFilename = $objPreviousConsultation->InitialPatientDetails_FirstName."_"
                                    .$objPreviousConsultation->InitialPatientDetails_Surname."_"
                                    .explode('T',$objPreviousConsultation->agcsystem_ReviewStartTime)[0];
                    }
                    else
                    {
                        $this->session->set_flashdata('strTitle','ERROR D/AUDITPDF/2 Previous Consultation Not Found');
                        $this->session->set_flashdata('strMessage','The Previous Consultation requested ('.$intAuditId.') did not match one stored in the database.  The following may be helpful.');
                        redirect('/error/', 'refresh');
                    }
                }
                else
                {
                    $this->throwErrorRedirect('ERROR D/AUDITPDF/1 Failed to Get Previous Data'
                                                    , 'You requested an audit of ID: '.$intConsultationId.'. The following may be helpful.');
                }
                
                
                
                

                $this->load->model('apidrugs_model','d_model');
                $arrModels['drugs_model'] = $this->d_model;
               
            }
            
            
            
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Lung Health');
            $pdf->SetTitle('Report of '.$strName);
            $pdf->SetSubject($strName);
            $pdf->SetKeywords('');

            // set default header data
            //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 058', PDF_HEADER_STRING);

            // set header and footer fonts
            //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(20, 10, 20);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }

            // ---------------------------------------------------------

            // set font
            $pdf->SetFont('helvetica', '', 10);

            // add a page
            $pdf->AddPage();

            // NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
            //$pdf->setRasterizeVectorImages(true);


            




            $pdf->ImageSVG($file=$relative_path.'/logo4.svg', $x=10, $y=10, $w=70, $h='', '', $align='', $palign='R', $border=0, $fitonpage=false);

            //$pdf->ImageSVG($file='images/tux.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);

            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetY(25);
            $pdf->SetX(20);
           /* $txt = 'Name : '.$strName;
            $pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);

            // ---------------------------------------------------------
            $pdf->SetMargins(20, 10, 20);
            $pdf->AddPage();*/
            $html = $this->load->view('reports/report',array('objReview'=>$objPreviousConsultation,'arrModels'=>$arrModels),true); 
            
            $html = "<html><head><style> body {
                padding-left: 100px;
            } </style></head><body>".$html."</body></html>";
            
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $pdf->Output('Asthma_'.$strFilename.'.pdf', 'D');
            
            
            
            
        }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */