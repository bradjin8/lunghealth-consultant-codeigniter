<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Information extends MY_Controller 
{
    
    public function Abandon()
    {
        $strTitle = $this->session->flashdata('strTitle');
        if (($strTitle == false) || (strlen($strTitle) === 0))
        {
            $strTitle = "Review Abandoned (Review ID not available)";
        }
        
        $strMessage = $this->session->flashdata('strMessage');
        if (($strMessage == false) || (strlen($strMessage) === 0))
        {
            $strMessage = "The Review was abandoned.  The following may be helpful.";
        }
        
        $strCurrentPage = "common/general_information";
        $this->document->setTitleString($strTitle);
        $this->document->setH1String($strTitle);
        $this->document->setErrorMessage($strMessage);
        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
        $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplateOverride']    = 'header_text';
        $this->load->view('template',$arrVariablesForTemplate);
    }
 
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */