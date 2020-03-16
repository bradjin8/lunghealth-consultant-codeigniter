<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller
{

    public function getPreviousControlScores($intPatientId)
    {
        $this->load->model('Apicontrol_model', 'api_control');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->api_control->getControl($intPatientId)));
    }

    public function getPreviousPEFScores($intPatientId)
    {
        $this->load->model('apispirometry_model', 'api_spirometry');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->api_spirometry->getSpirometry($intPatientId)));
    }


    public function getPreviousScores($intReviewID)
    {


        $this->output
            ->set_content_type('application/json')
            ->set_output($this->strDemoScores);

    }

    public function getPreviousNonZeroPEFScores($intReviewID)
    {
        $arrTableData = json_decode($this->strDemoScores);
        $arrOutputData = array();

        foreach ($arrTableData as $objRow) {
            if ($objRow->PEF != '') {
                $objNewRow = new stdClass();
                $objNewRow->Date = $objRow->Date;
                $objNewRow->PEF = $objRow->PEF;
                $arrOutputData[] = $objNewRow;
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($arrOutputData));
    }

    public function getDrugsList()
    {
        $this->load->model('apidrugs_model', 'd_model');

        $this->output
            ->set_content_type('application/json')
            ->set_output("var objDrugs = " . json_encode($this->d_model->getDrugsByTypeName()) . ";");
    }

    public function addSpirometry($intPatientId)
    {
        $this->load->model('apispirometry_model', 'api_spirometry');

        $arrSpirometry = $this->input->post('sSCP_payload');

        $arrResult = array('error' => true);

        if ($this->api_spirometry->sendSpirometryToApi($intPatientId, $arrSpirometry)) {
            $arrResult['error'] = false;
        }


        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($arrResult));

    }


}
