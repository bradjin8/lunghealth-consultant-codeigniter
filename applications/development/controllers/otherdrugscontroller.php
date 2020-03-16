<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class OtherdrugsController extends MY_Controller
{

    public function store($intReviewID)
    {
        if ($intReviewID > 0) {
            $this->load->model('otherdrugs_model', 'od_model');

        }
    }


}

/* End of file screen.php */
/* Location: ./application/controllers/screen.php */