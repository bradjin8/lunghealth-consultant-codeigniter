<div class="alert alert-success" role="alert"><?php echo $this->document->getErrorMessage(); ?></div>
<div class="col-md-2 col-md-offset-10">
        <ul class="pager">
            <li><button type="button" 
                        onClick="document.location.href='<?php echo $this->document->getAPIUrl(); ?>Patients/Find/';" 
                        id="btnReturnToApi" class="no-validate" value="true">Okay <span class="glyphicon glyphicon-ok"></span></button></li>
        </ul>
</div>