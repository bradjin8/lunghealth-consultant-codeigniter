<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class QuestionGroupPage extends MY_Controller
{

    public function index()
    {
        redirect('questiongrouppage/view', 'refresh');
    }

    public function view($intQGPId = 1)
    {
        $strCurrentPage = "questiongrouppage/view";

        $this->load->model('questiongrouppage_model', 'qgp_model');


        $objQgp = $this->qgp_model->setID($intQGPId)->retrieveAndGetQuestionGroups()->getAsObject();


        $this->load->helper('form');

        /*$this->document->addScriptToScriptArray("
                $(\".slider-selector\").slider({
                                    tooltip: 'always'
                                    });
                ", true);*/

        $this->document->setTitleString($objQgp->ScreenDisplayText);

        $this->document->setH1String($objQgp->ScreenDisplayText);

        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);

        $arrVariablesForTemplate['arrPageVariables']['objQgp'] = $objQgp;

        $this->load->view('template', $arrVariablesForTemplate);


    }


    public function view_jonti($intQGPId = 1)
    {
        $strCurrentPage = "questiongrouppage/view_jonti";

        $this->load->model('questiongrouppage_model', 'qgp_model');


        $objQgp = $this->qgp_model->setID($intQGPId)->retrieveAndGetQuestionGroups()->getAsObject();


        $this->load->helper('form');

        /*$this->document->addScriptToScriptArray("
                $(\".slider-selector\").slider({
                                    tooltip: 'always'
                                    });
                ", true);*/

        $this->document->setTitleString($objQgp->ScreenName);

        $this->document->setH1String($objQgp->ScreenName);

        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);

        $arrVariablesForTemplate['arrPageVariables']['objQgp'] = $objQgp;

        $this->load->view('template', $arrVariablesForTemplate);


    }

    public function view_wip($intQGPId = 1)
    {
        $strCurrentPage = "questiongrouppage/view_wip";

        $this->load->model('questiongrouppage_model', 'qgp_model');


        $objQgp = $this->qgp_model->setID($intQGPId)->retrieveAndGetQuestionGroups()->getAsObject();


        $this->load->helper('form');

        /*$this->document->addScriptToScriptArray("
                $(\".slider-selector\").slider({
                                    tooltip: 'always'
                                    });
                ", true);*/

        $this->document->setTitleString($objQgp->ScreenDisplayText);

        $this->document->setH1String($objQgp->ScreenDisplayText);

        $arrVariablesForTemplate = $this->getDefaultViewVariables($strCurrentPage);

        $arrVariablesForTemplate['arrPageVariables']['objQgp'] = $objQgp;

        $this->load->view('template', $arrVariablesForTemplate);


    }
}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */