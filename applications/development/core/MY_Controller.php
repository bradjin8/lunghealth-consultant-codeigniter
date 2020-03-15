<?php

class MY_Controller extends CI_Controller {
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getDefaultViewVariables($strCurrentPage)
    {
        return array('strCurrentPage'=> $strCurrentPage, 'arrHeaderVariables' => array('strSidebarTemplate'=>'menu'),'arrSidebarVariables'=>array(), 'arrPageVariables'=>array(), 'arrFooterVariables' => array());
    }
    
    
    private function saveAndServePdf($pdf, $patientName, $reviewId, $downloadPdf = true, $forceOverwrite = false) {
        $pdf_file_name = 'Asthma_' . $patientName. "_" . $reviewId . '.pdf';
        $pdf_file_path = $_SERVER['DOCUMENT_ROOT'] . "//pdfs//" . $pdf_file_name;

        if ($forceOverwrite || !file_exists($pdf_file_path)) {
            error_log("Saving PDF " . $pdf_file_name);
            $pdf->Output($pdf_file_path, 'F');
        }

        if ($downloadPdf && file_exists($pdf_file_path)) {
            // We'll be outputting a PDF
            header('Content-type: application/pdf');
            // It will be called downloaded.pdf
            header('Content-Disposition: attachment; filename="'.$pdf_file_name.'"');
            // The PDF source is in original.pdf
            readfile($pdf_file_path);
            return true;
        }
        return false;
    }

    // this is public as it is called when the review is completed...
    public function auditPdf($intConsultationId = -1, $strConsultationType = '', $downloadDocument = true)
        {
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
                        $this->generatePdf($objPreviousConsultation, $strName, $downloadDocument);
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
            }
        }

    /**
     * @param object $objReview Review object
     * @param string $strName Patient name
     * @param bool $downloadPdf downloads generated PDF via browser
     * @param bool $forceOverwrite forces overwriting of existing consultation report, if it exists
     * @return Pdf
     */
    protected function generatePdf($objReview, $strName, $downloadPdf = true, $forceOverwrite = false) {

        $this->load->library('Pdf');

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        $relative_path = realpath(dirname(__FILE__).'/../../../includes/images/');
        $font_relative_path = realpath(dirname(__FILE__).'/../../../includes/fonts/');

        $strDate = "";
        $strFilename = "";
        $footer = "";


        $this->load->model('apidrugs_model','d_model');
        $arrModels['drugs_model'] = $this->d_model;

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
            //$pdf->setLanguageArray($l);
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
				font-size:8pt;
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
				font-size:8pt;
			}	
			
			td {
			   border: 1px solid black;
			}
			
						
			</style></head><body>".$sigs."</body></html>";


        $footerComplete = "<html><head><style> body {
                padding-left: 100px;
            } 
			#bottomTable { 
				font-size:8pt;
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

        $this->saveAndServePdf($pdf, $strName, $objReview->intReviewID, $downloadPdf, $forceOverwrite);

        /*
        //Testing string
        $string = $pdf->Output('Asthma_'.$strFilename.'.pdf', 'S');

        $file = '/var/www/html/applications/development/controllers/test_pdf.txt';
        // Open the file to get existing content
        $current = file_get_contents($file);
        // Append a new person to the file
        $current .= $string;
        // Write the contents back to the file
        file_put_contents($file, $current, FILE_APPEND);
        print("|$current|");
        */

        return $pdf;
    }
    
}
