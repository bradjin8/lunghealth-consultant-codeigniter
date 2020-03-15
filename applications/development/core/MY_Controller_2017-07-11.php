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
    
    
    
    
}
