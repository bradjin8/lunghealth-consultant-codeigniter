<!--
<?php
    var_dump ($arrSidebarScreens);
?>

-->
<div class="col-md-12">
    <div  id="nav_background">
        <div id="nav_background_exit" <?php
            
                if (($floPercentageComplete != '') && ($floPercentageComplete == 100))
                {
                    echo "style=\"background-image: none;\"";
                }
            
            ?>>
            <ul  class="list-inline bus-nav" style="margin-left: 0px; margin-top: 8px; margin-bottom: 15px; text-align: justify; height: 35px; overflow: hidden; <?php
            
                if ($floPercentageComplete != '')
                {
                    echo "width: ".$floPercentageComplete."%;";
                }
            
            ?>">
<?php



foreach ($arrSidebarScreens as $intFlowID => $arrFlowScreens)
{
    foreach ($arrFlowScreens as $arrScreen)
    {
        if ($arrScreen['strScreenName'] == $strScreenName)
        {
        ?>
            <li class="active"><a href="#" style="display: inline-block;"><span style="padding-left: 8px; padding-right: 8px;"><span class="active_bubble_title"><?php echo $arrScreen['strScreenDisplayText']; ?></span>
                        <?php
        }
        else
        {
        ?>
            <li class="inactive"><a style="display: inline-block;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $arrScreen['strScreenDisplayText'];?>" <?php
                                    $CI =& get_instance();
                                    if ($CI->session->userdata('booAudit') === true)
                                    {
                                        echo "href=\"/flowcontroller/screen/".$arrScreen['strScreenName']."\"";
                                    }  
                                    else
                                    {
                                        echo "onClick=\"javascript:agc_setForSubmit('".$arrScreen['strScreenName']."');\"";
                                    }
                                    
                                        
                                        ?>><span><?php 
        }
        if ($arrScreen['intValidated']== 1)
        {
            ?>
        <i class="glyphicon glyphicon-ok"></i>
        <?php
        }
        else
        {
            if ($arrScreen['intValidated']== -1)
            {
                ?>
                <i class="glyphicon glyphicon-remove"></i>
                <?php
            }
            else
            {
                if ($arrScreen['intValidated']== 2)
                {
                ?>
                <i class="glyphicon glyphicon-question-sign"></i>
                <?php
                }
                else
                {
                ?>
                <i class="glyphicon glyphicon-asterisk"></i>
                <?php
                }   
            }
        }
        ?>
        </span></a></li>
        <?php
    }
}




	/*
				<li class="active"><a href="#">Entry Criteria</a></li>
				<li><a href="#">Asthma History</a></li>
				<li><a href="#">Medical History</a></li>
				<li><a href="#">Family And Social History</a></li>
				<li><a href="#">Current Medication</a></li>
				<li><a href="#">Clinical Examination</a></li>
				<li><a href="#">Investigations</a></li>
				<li><a href="#">Asthma Confirmation</a></li>*/
			?>
        <li style="display: inline-block;
    width: 100%;"></li>
        </ul>
        </div>
    </div>

</div>
<div class="col-md-12">
	
	
	
