<!--
<?php
    var_dump ($arrSidebarScreens);
?>

-->
<div class="col-md-12">
    
            <ul class="nav nav-tabs">
    <?php



    foreach ($arrSidebarScreens as $intFlowID => $arrFlowScreens)
    {
        foreach ($arrFlowScreens as $arrScreen)
        {
            if ($arrScreen['strScreenName'] == $strScreenName)
            {
            ?>
                <li class="active"><a href="#"><?php echo $arrScreen['strScreenDisplayText']; 
            }
            else
            {
            ?>
                <li><a href="<?php echo site_url('/flowcontroller/screen/'.$arrScreen['strScreenName']); ?>"><?php echo $arrScreen['strScreenDisplayText']; 
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
            </a></li>
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
            </ul>
        
</div>
<div class="col-md-12">
	
	
	
	
