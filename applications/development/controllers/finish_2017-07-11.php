<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finish extends MY_Controller {
		
		public function pdfString()
        {
            $this->load->library('Pdf');

            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            
            $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');
			$pdf_path = realpath(dirname(__FILE__).'/../../../pdfs/');
			
            $font_relative_path = realpath(dirname(__FILE__).'/../../../includes/fonts/');
			
            $strName = "";
            $strDate = "";
            $strFilename = "";
            $footer = "";
            
            if ($this->session->userdata('intReviewID'))
            {
                $this->load->model('review_model','review_model');
                
                if ($this->review_model->setReviewID($this->session->userdata('intReviewID'))->retrieve()->isLoaded())
                {
                    $objReview = $this->review_model->getAsObject();
                    $this->review_model->clear();
                    
                    $strName = $objReview->InitialPatientDetails_FirstName." ".$objReview->InitialPatientDetails_Surname;
                    
                    $strDate = explode('T',$objReview->agcsystem_ReviewStartTime)[0];
                    
					
					/*
                    $strFilename = $objReview->InitialPatientDetails_FirstName."_"
                                    .$objReview->InitialPatientDetails_Surname."_"
                                    .explode('T',$objReview->agcsystem_ReviewStartTime)[0];
									
					*/				
					$strFilename = $objReview->PatientDetails_PatientId;				
                    
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
			//$Calibri = $pdf->addTTFfont($file=$font_relative_path.'/Calibri.ttf', 'TrueTypeUnicode', '', 10);
			
			
            // add a page
            $pdf->AddPage();

            //NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
            //$pdf->setRasterizeVectorImages(true);

            $pdf->ImageSVG($file=$relative_path.'/logo4.svg', $x=10, $y=10, $w=70, $h='', '', $align='', $palign='R', $border=0, $fitonpage=false);
			
			$pdf->Image($file=$relative_path.'/impactlogo.jpg', $x=20, $y=10, $w=50, $h='', '', $align='', $palign='l', $border=0, $fitonpage=false);
			
			$pdf->setPageMark();
			
            //$pdf->ImageSVG($file='images/tux.svg', $x=30, $y=100, $w='', $h=100, $link='', $align='', $palign='', $border=0, $fitonpage=false);

            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetY(25);
            $pdf->SetX(20);
           /* $txt = 'Name : '.$strName;
            $pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);
			
			
			
            // ---------------------------------------------------------
            $pdf->SetMargins(20, 10, 20);
            $pdf->AddPage();*/
			
			$pdf->setPageMark();
			
            $html = $this->load->view('reports/report',array('objReview'=>$objReview,'arrModels'=>$arrModels),true); 
            
			$sigs = "<h2>Nurse Summary</h2>
			
						<table cellspacing='0' cellpadding='1' border='5'>
							<tr>
								<td rowspan='1'><br><br><br><br><br><br><br><br></td>
							</tr>
						</table>
			
						<table border='5' cellspacing='0' cellpadding='1'>
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
						";

			$footer = "			<table border='0' cellspacing='0' cellpadding='1'>
									<tr>
									 
									 <td border='0' rowspan='2' align='left'><small>UK/IM/16/0000<br>Date of Preparation: May 2016 &copy; NSHI Ltd 2016</small></td>
									 <td border='0' style='text-align: right;' align='right'><small>This service is sponsored by Teva UK Limited<br>Teva UK Limited, Field House, Station Approach, Harlow, Essex CM20 2FB</small></td>
									 
									</tr>
								</table>
							";						
						
						
			
            $html = "<html><head><style> body {
                padding-left: 100px;
            } 
			#bottomTable { 
				font-size:8;
			}	
			
			/*
			td {
			   border: 1px solid black;
			}	
			*/
			.foot {
				 border: 10px solid black;
				  color: red;
				  font-family: helvetica;
				  font-size: 12pt;
			}
			
			footer {
				  border: 10px solid black;
				  color: red;
				  font-family: helvetica;
				  font-size: 12pt;
			}
		
			
			</style></head><body>".$html."</body></html>";
 			
			$html = str_replace("</h3>", "</h3><hr>", $html);
			$html = str_replace("</h1>", "</h1><hr>", $html);
			
			$sigsComplete = "<html><head><style> body {
                padding-left: 100px;
            } 
			#bottomTable { 
				font-size:8;
			}	
			
			td {
			   border: 1px solid black;
			}
			
						
			</style></head><body>".$sigs."</body></html>";

			
			$footerComplete = "<html><head><style> body {
                padding-left: 100px;
            } 
			#bottomTable { 
				font-size:8;
			}	
			
			/*
			td {
			   border: 1px solid black;
			}
			*/
						
			</style></head><body>".$footer."</body></html>";
			
			
			
            //var_dump($html);die();
			
			
            $pdf->writeHTML($html, true, false, true, false, '');
			$pdf->AddPage();
			$pdf->writeHTML($sigsComplete, true, false, true, false, '');
			$pdf->writeHTML($footerComplete, true, false, true, false, '');
            
            //Close and output PDF document
            //$pdf->Output('Asthma_'.$strFilename.'.pdf', 'D');

		
			//Testing string
			
			//var_dump($pdf_path.'Asthma_'.$strFilename.'.pdf', 'F');die;
			
			//$pdfAsString = $pdf->Output($pdf_path.'Asthma_'.$strFilename.'.pdf', 'F');
		
			$pdf->Output('/var/www/html/pdfs/'.'Asthma_'.$strFilename.'.pdf', 'F');
			$pdfAsString = true;
			
			//var_dump("TEST");die;
			
			
			//return $pdfAsString;
			
			/*
			//$file = '/var/www/html/applications/development/controllers/test_pdf.txt';
			// Open the file to get existing content
			$current = file_get_contents($file);
			// Append a new person to the file
			$current .= $string;
			// Write the contents back to the file
			file_put_contents($file, $current, FILE_APPEND);
			print("|$current|");
			*/

            return($pdfAsString );
            
        }
		
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
					
					
					
					$pdfAsString = $this->pdfString();
					
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