<?php
    $arrHeaderVariables['strCurrentPage'] = $strCurrentPage;
    $arrHeaderVariables['arrSidebarVariables'] = $arrSidebarVariables;
    if ($strCurrentPage === 'begin/welcome')
    {
        $arrHeaderVariables['arrApiVariables'] = array('objApiPatient'=>$arrPageVariables['objApiPatient']
                                                        , 'objApiUser'=>$arrPageVariables['objApiUser']);
    }
        
    $this->load->view('common/header',$arrHeaderVariables);

    $this->load->view($strCurrentPage, $arrPageVariables);

    $this->load->view('common/footer',$arrFooterVariables); 