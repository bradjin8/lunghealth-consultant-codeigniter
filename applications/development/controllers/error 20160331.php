<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends MY_Controller 
{
    
    private function email_error($strTitle,$strError,$arrSession)
    {
        $this->load->library('email');
        
        
        
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "ssl://smtp.googlemail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "error.robot.shi@gmail.com";//also valid for Google Apps Accounts
        $config['smtp_pass'] = "Thefire69";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $this->email->initialize($config);
        
        $this->email->from('error.robot.shi@gmail.com', 'Error Robot');
        $this->email->to('errors@smarthealthinsight.org.uk'); 
        $this->email->bcc('barry@lunus.co.uk'); 

        $this->email->subject('LUNGHEALTH-AGC_ERROR:'.$strTitle);
        $this->email->message("<html><body><h1>".$strTitle."</h1><h2>".$strError."</h2><pre>".var_export($arrSession,true)."</pre></body></html>");	

        $this->email->send();
    }
    
    public function index()
    {
        $strTitle = $this->session->flashdata('strTitle');
        if (($strTitle == false) || (strlen($strTitle) === 0))
        {
            $strTitle = "ERROR 101";
        }
        
        $strMessage = $this->session->flashdata('strMessage');
        if (($strMessage == false) || (strlen($strMessage) === 0))
        {
            $strMessage = "There was an error.  The following may be helpful.";
        }
        
        $strCurrentPage = "common/general_error";
        $this->document->setTitleString("There was an Error");
        $this->document->setH1String("There was an Error");
        $this->document->setErrorMessage("The development team have been informed of the error.  You must now begin the consultation again.");
        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);
        $arrVariablesForTemplate['arrHeaderVariables']['strSidebarTemplateOverride']    = 'header_text';
        $arrVariablesForTemplate['arrPageVariables']['strLinkToWelcome']                = '/begin/?token='.$this->input->cookie('agc_APIVars_token');
        
        $this->email_error($strTitle, $strMessage, $this->session->all_userdata());
        
        $this->load->view('template',$arrVariablesForTemplate);
    }
 
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */