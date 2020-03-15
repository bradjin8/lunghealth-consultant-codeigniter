<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" type="image/png" href="<?php echo $this->document->getFaviconIncludeString(); ?>">
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $this->document->getTitleString(); ?></title>
        <script type="text/javascript" src="<?php echo $this->document->getJqueryScriptIncludeString(); ?>"></script>
        
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapScriptIncludeString(); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapPluginScriptIncludeString('bootstrapValidator'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapPluginScriptIncludeString('bootstrap-slider'); ?>"></script>
        
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapPluginScriptIncludeString('bootstrap-datepicker'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapPluginScriptIncludeString('chosen'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->document->getBootstrapPluginScriptIncludeString('icheck'); ?>"></script>
        <?php
        
        foreach ($this->document->getScriptsArray() as $arrScript) 
        {
            if($arrScript['string'])
            {
                echo "<script type=\"text/javascript\">\n\t".$arrScript['src']."\n</script>";
            } 
            else 
            { 
                if ($arrScript['external'])
                {
                ?><script type="text/javascript" src="<?php 
                    echo $arrScript['src']; 
                    ?>"></script><?php
                }
                else
                {
                ?><script type="text/javascript" src="<?php 
                    echo $this->document->getJavascriptIncludeUrlString().$arrScript['src']; 
                    ?>"></script><?php
                }
            } 
        } 
        echo "\n";
        ?>
        
        <!-- Bootstrap -->
        
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:200,400,600,900">
        
        <link href="<?php 
                    echo $this->document->getBootstrapStyleIncludeString(); 
                    ?>" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <link href="<?php 
                    echo $this->document->getBootstrapPluginStyleIncludeString('bootstrapValidator'); 
                    ?>" rel="stylesheet">
        <link href="<?php 
                    echo $this->document->getBootstrapPluginStyleIncludeString('bootstrap-slider'); 
                    ?>" rel="stylesheet">
        <link href="<?php 
                    echo $this->document->getBootstrapPluginStyleIncludeString('bootstrap-datepicker'); 
                    ?>" rel="stylesheet">
        <link href="<?php 
                    echo $this->document->getBootstrapPluginStyleIncludeString('chosen'); 
                    ?>" rel="stylesheet">
        <link href="<?php 
                    echo $this->document->getBootstrapPluginStyleIncludeString('icheck'); 
                    ?>" rel="stylesheet">
        <link href="<?php 
                    echo $this->document->getStylesheetIncludeUrlString()."global.css"; 
                    ?>" rel="stylesheet">
        <?php
        foreach ($this->document->getStylesheetArray() as $arrStyle) 
        {
            if($arrStyle['string'])
            {
                echo "<style>\n\t".$arrStyle['src']."\n</style>";
            } 
            else 
            { 
                echo "\n\t"; 
                ?><link rel="stylesheet" type="text/css" href="<?php
                    if (substr($arrStyle['src'], 0, 1) != "/")
                    {
                        echo $this->document->getStylesheetIncludeUrlString();
                    }
                    echo $arrStyle['src'];
                    ?>" /><?php 
            } 
        } 
        echo "\n";
        
        $CI =& get_instance();
        if ($CI->session->userdata('booAudit') === true)
        {
            ?><link rel="stylesheet" type="text/css" href="/includes/css/audit_amendments.css"/><?php
        } 
        
        if (
                $this->input->cookie('agc_BeginIndex_Cookieset')== true)
        {
        ?>   
                <style type="text/css">
                    body
                    {
                        font-family: <?php echo $this->input->cookie('agc_BeginIndex_Typeface'); ?>;
                        font-size: <?php switch($this->input->cookie('agc_BeginIndex_TypefaceSize'))
                                        {
                                            case 'extraextralarge':
                                                echo "20px";
                                                break;
                                            case 'extralarge':
                                                echo "18px";
                                                break;
                                            case 'large':
                                                echo "17px";
                                                break;
                                            case 'normal':
                                                echo "16px";
                                                break;
                                            case 'small':
                                                echo "14px";
                                                break;
                                            case 'extrasmall':
                                                echo "12px";
                                                break;
                                        } ?>;
                        font-weight: <?php switch($this->input->cookie('agc_BeginIndex_TypefaceWeight'))
                                        {
                                            case 'heavy':
                                                echo "900";
                                                break;
                                            case 'medium':
                                                echo "600";
                                                break;
                                            case 'normal':
                                                echo "400";
                                                break;
                                            case 'light':
                                                echo "200";
                                                break;
                                        } ?> !important;
                    }
                    
                </style>
        <?php
        }
        ?>
</head>
<body>
    <div class="container">
        <div class="row" style="background-image: url('<?php echo $this->document->getImagesFolderString()."logo.png"; ?>'); background-repeat: no-repeat; height: 60px;">
            
            <div class="col-md-6 col-md-offset-4 text-right" style="margin-top: 18px;">
                <?php
                $CI =& get_instance();
                if (isset($objReview) && is_object($objReview) && ($CI->session->userdata('booAudit') === true))
                {
                ?>
                <p>
                    <span class="h4"><span class="small">Consultation Type:</span>&nbsp; <?php
                $strReviewType = $objReview->AssessmentDetails_AssessmentType;
                switch ($strReviewType)
                {
                    case '1A':
                        echo "First Assessment";
                        break;
                    case 'AR':
                        echo "Annual Review";
                        break;
                    case 'FU':
                        echo "Follow Up";
                        break;
                    case 'EX':
                        echo "Exacerbation Review";
                        break;
                    default:
                        echo "UNKNOWN";
                        break;
                }

                ?>
                <span class="h4" style="margin-left: 15px;"><span class="small">Consultation Date:</span>&nbsp; <?php
                
                $arrDateTimeParts = explode('T',$objReview->agcsystem_ReviewStartTime);
                $arrDateParts = explode('-',$arrDateTimeParts[0]);
                $arrTimeParts = explode(':',explode('.',$arrDateTimeParts[1])[0]);

                echo date('d/m/Y',mktime((int) $arrTimeParts[0]
                                        ,(int) $arrTimeParts[1]
                                        ,(int) $arrTimeParts[2]
                                        ,(int) $arrDateParts[1]
                                        ,(int) $arrDateParts[2]
                                        ,(int) $arrDateParts[0]));
                ?></span>
                </p>
                <?php
                }
                ?>
            </div>
            
            <div class="col-md-2 text-right" style="margin-top: 15px;">
                <?php 
                    if (isset($objReview) && is_object($objReview))
                    {
                        if ($CI->session->userdata('booAudit') === true)
                        {
                            ?><span class="h3"><span class="label label-primary label-agc">Asthma Audit</span></span> <?php
                        }
                        else 
                        {
                            ?><span class="h3"><span class="label label-primary label-agc">Asthma Consultation</span></span> <?php
                        }
                    }
                    ?>
            </div>
        </div>
        <?php 
        $objApiPatient = false;
        $objApiUser    = false;
        $objApiPreviousUser = false;
        
        if ($strCurrentPage !== "begin/welcome")
        {
            if (!isset($objApiPatient) || !is_object($objApiPatient))
            {
                $objApiPatient      = $this->document->getAPIPatientObjectFromCookie();
            }
            if (!isset($objApiUser) || !is_object($objApiUser))
            {
                $objApiUser         = $this->document->getAPIUserObjectFromCookie();
            }    
            if (!isset($objApiPreviousUser) || !is_object($objApiPreviousUser))
            {    
                $objApiPreviousUser = $this->document->getAPIPreviousUserObjectFromCookie();
            }
        }
        else
        {
            $objApiPatient = $arrApiVariables['objApiPatient'];
            $objApiUser    = $arrApiVariables['objApiUser'];
        }
        
        ?>
        <div class="row">
            <div class="col-md-8">
                <p class="h4">
                     <?php 
                                                if (isset($objApiPatient) && is_object($objApiPatient)
                                                        &&
                                                        (    
                                                            ($objApiPatient->FirstName != '') 
                                                                && ($objApiPatient->LastName != '')
                                                        )
                                                   ) 
                                                { 
                                                    ?>
                    <span class="small">Patient Name:</span>
                                                    <?php
                                                    echo $objApiPatient->FirstName." ".$objApiPatient->LastName; 
                                                } 
                                                /*
                                                else
                                                { 
                                                    echo "UNKNOWN"; 

                                                }*/ 
                                                if (isset($objApiPatient) && is_object($objApiPatient)
                                                        &&
                                                        (    
                                                            $objApiPatient->NhsNumber != ''
                                                        )
                                                   ) 
                                                { ?>
                   <span class="small" style="margin-left: 15px;">NHS Number:</span> <?php
                                                    echo $objApiPatient->NhsNumber;
                                                } /*
                                                else
                                                {
                                                    echo "UNKNOWN";
                                                }*/
                                                
                                                if (isset($objApiPatient) && is_object($objApiPatient)
                                                        &&
                                                        (    
                                                            $objApiPatient->DateOfBirth != ''
                                                        )
                                                   ) 
                                                { ?>
                    <span class="small" style="margin-left: 15px;">Date of Birth:</span> <?php
                                                    echo date('d/m/Y', $objApiPatient->DateOfBirth);
                                                }
                                                /*
                                                else
                                                {
                                                    echo "UNKNOWN";
                                                }*/
                                                ?>
                    <?php 
            if ($objApiPreviousUser)
            {
            ?>
            <!--
                    <span class="small" style="margin-left: 15px;">Consultation Created by:</span> <?php
                    if (is_object($objApiPreviousUser) &&
                                                    (($objApiPreviousUser->FirstName != '') && ($objApiPreviousUser->LastName != ''))
                                                   ) 
                                                { 
                                                    echo $objApiPreviousUser->FirstName." ".$objApiPreviousUser->LastName;
                                                }
                    ?>
                
            -->
            <?php 
            }
            ?>
                </p>
            </div>
            <div class="col-md-4">

                <p class="h4 text-right"><?php
                                                    if (isset($objApiUser) && is_object($objApiUser) &&
                                                    (($objApiUser->FirstName != '') && ($objApiUser->LastName != ''))
                                                   ) 
                                                { 
                                                        ?>
                    <span class="small">Signed in as:</span>
                                                        <?php
                                                    echo $objApiUser->FirstName." ".$objApiUser->LastName;
                                                }/*
                                                else
                                                {
                                                    echo "UNKNOWN";
                                                }*/
                                                ?></p>
            </div>
        </div>
        
        <div class="row">
        
	<!--	
        <div class="col-md-12">
            <ol class="breadcrumb">
			  <li><a href="#">Asthma</a></li>
			  <li><a href="#">Patient</a></li>
			  <li class="active">Initial Optimisation Assessment</li>
			</ol>
        </div>		
	-->	
	
            
            <?php
            /*
                if ($this->session->userdata('sitestyle_navigation')!= '')
                {
                    $strSidebarTemplate = $this->session->userdata('sitestyle_navigation');
                }
                else
                {
                    $strSidebarTemplate = 'header_text';
                } */
            
            $strSidebarTemplate = 'horizontal-bubbles';
            if ($this->input->cookie('agc_BeginIndex_Cookieset')== true)
            {
                switch ($this->input->cookie('agc_BeginIndex_Navigation'))
                {
                    case 'sidebar':
                        $strSidebarTemplate = 'vertical-sidebar';
                        break;
                    case 'tabs':
                        $strSidebarTemplate = 'horizontal-tabs';
                        break;
                    default:
                        $strSidebarTemplate = 'horizontal-bubbles';
                        break;
                }
            }
            
            if (isset($strSidebarTemplateOverride) && strlen($strSidebarTemplateOverride)>0)
            {
                $strSidebarTemplate = $strSidebarTemplateOverride;
                
            }
            $this->load->view('common/sidebar/'.$strSidebarTemplate,$arrSidebarVariables); 
             
            
        	

		
		
		
		
		
		
        
        
        
               