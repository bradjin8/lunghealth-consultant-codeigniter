<div class="alert alert-danger" role="alert">
    It appears you have an unfinished consultation for this patient.  Do you wish to complete or start again?
</div>
<?php
$attributes = array('role' => 'form', 'id' => 'agc-begin-form');

echo form_open($strCurrentPage."/".$intReviewId, $attributes);
?>

<div class="panel panel-default">
  
  <div class="panel-body">
      <div class="row" id="row_begin_index_3">
        <div id="layout-holder-begin_index_6" class="col-md-8 col-md-offset-2 layout-holder" >
            Use previous unfinished consultation started <strong><?php echo $strBeginConsultation_PreviousDate; ?></strong>?                 
        </div>
      </div>
      <div class="row" id="row_begin_index_4">
        <div id="layout-holder-begin_index_7" class="col-md-8 col-md-offset-2 layout-holder" >
            <div class="form-group">
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginConsultation_Continue" value="continue" checked="checked" id="BeginConsultation_Continue_0" class="agc_radiovert agc_icheck" />Continue with unfinished consultation
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginConsultation_Continue" value="new" id="BeginConsultation_Continue_1" class="agc_radiovert agc_icheck" />Start new consultation (will delete previous consultation)
                    </label>
                </div>
            </div>                    
        </div>
        
     </div>
     <div class="row text-right" id="row_begin_index_5">
        <button type="submit" class="btn btn-primary" name="BeginConsultation_submit" value="true">Okay <span class="glyphicon glyphicon-ok"></span></button>
     </div>
  </div>
</div>


<?php

echo form_close();
?>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#agc-begin-form').find('input.agc_icheck')
                                    // Init iCheck elements
                                    .iCheck({
                                        checkboxClass:  'icheckbox_square-purple',
                                        radioClass:     'iradio_square-purple',
                                        increaseArea:   '20%' 
                                    });
    });
    </script>
<?php
