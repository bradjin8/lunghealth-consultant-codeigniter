<!--
<?php
    var_dump ($arrSidebarScreens);
?>

-->

            <ul  class="list-inline bus-nav" style="margin-top: 8px; margin-bottom: 15px;" role="tablist">
<?php



foreach ($arrSidebarScreens as $intFlowID => $arrFlowScreens)
{
    foreach ($arrFlowScreens as $arrScreen)
    {
        if ($arrScreen['strScreenName'] == $strScreenName)
        {
        ?>
            <li class="active"><a href="#" style="display: inline-block;"><span style="padding-left: 8px; padding-right: 8px;"><?php echo $arrScreen['strScreenDisplayText']; ?>
                        <?php
        }
        else
        {
        ?>
            <li class="inactive"><a style="display: inline-block;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $arrScreen['strScreenDisplayText'];?>"  onClick="javascript:agc_setForSubmit('<?php echo $arrScreen['strScreenName']; ?>');"><span><?php 
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
        </ul>

	
	
	
	
