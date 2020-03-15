<div class="alert alert-info" role="alert"><strong>Hello!</strong> You use this page to set up the styling of the system.</div>
<?php
$attributes = array('role' => 'form', 'id' => 'agc-begin-form');

echo form_open($strCurrentPage, $attributes);
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title" style="font-size:1.4em;">Settings</h3>
  </div>
  <div class="panel-body">
      
      <div class="row" id="row_begin_index_2">
        <div id="layout-holder-begin_index_4" class="col-md-2 layout-holder" >
            Typeface                
        </div>
        <div id="layout-holder-begin_index_5" class="col-md-3 layout-holder" >
            <div class="form-group">
                <select name="BeginIndex_Typeface" class = "form-control" id = "BeginIndex_Typeface" onchange="$(body).css('font-family',$('#BeginIndex_Typeface').val());">
                    <optgroup label='Serif'>
                        <option value="'Georgia', serif">Georgia</option>
                        <option value="'Palatino Linotype', 'Book Antiqua', 'Palatino', serif">Palatino</option>
                        <option value="'Times New Roman', 'Times', serif">Times New Roman</option>
                    </optgroup>
                    <optgroup label='Sans-serif'>
                        <option value="'Arial', 'Helvetica', sans-serif">Arial</option>
                        <option value="'Arial Black', 'Gadget', sans-serif">Arial Black</option>
                        <option value="'Arial Narrow', sans-serif">Arial Narrow</option>
                        <option value="'Century Gothic', sans-serif">Century Gothic</option>
                        <option value="'Comic Sans MS', sans-serif">Comic Sans</option>
                        <option value="'Gill Sans','Gill Sans MT', sans-serif">Gill Sans</option>
                        <option value="'Helvetica', sans-serif">Helvetica</option>
                        <option value="'Helvetica Neue', 'Helvetica', sans-serif">Helvetica Neue</option>
                        <option value="'Impact', 'Charcoal', sans-serif">Impact</option>
                        <option value="'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif" selected='selected'>Lato</option>
                        <option value="'Tahoma', 'Geneva', sans-serif">Tahoma</option>
                        <option value="'Trebuchet MS', 'Helvetica', sans-serif">Trebuchet</option>
                    </optgroup>
                </select>
            </div>                    
        </div>
      </div>
      <div class="row" id="row_begin_index_3">
        <div id="layout-holder-begin_index_6" class="col-md-2 layout-holder" >
            Typeface Weight                 
        </div>
        <div id="layout-holder-begin_index_7" class="col-md-3 layout-holder" >
            <div class="form-group">
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceWeight" value="light" id="BeginIndex_TypefaceWeight_0" class="agc_radiovert agc_icheck" /> <span  style='font-weight: 200;'>Light</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceWeight" value="normal" checked="checked" id="BeginIndex_TypefaceWeight_1" class="agc_radiovert agc_icheck" /> <span  style='font-weight: 400;'>Normal</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceWeight" value="medium" id="BeginIndex_TypefaceWeight_2" class="agc_radiovert agc_icheck" /> <span  style='font-weight: 600;'>Medium</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceWeight" value="heavy" id="BeginIndex_TypefaceWeight_3" class="agc_radiovert agc_icheck" /> <span  style='font-weight: 900;'>Heavy</span>
                    </label>
                </div>
            </div>                    
        </div>
        <div id="layout-holder-begin_index_8" class="col-md-2 layout-holder" >
            Standard Typeface Size                 
        </div>
        <div id="layout-holder-begin_index_9" class="col-md-3 layout-holder" >
            <div class="form-group">
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="extrasmall" id="BeginIndex_TypefaceSize_0" class="agc_radiovert agc_icheck" /> <span  style='font-size: 12px;'>Extra Small</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="small" id="BeginIndex_TypefaceSize_1" class="agc_radiovert agc_icheck" /> <span  style='font-size: 14px;'>Small</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="normal" checked="checked" id="BeginIndex_TypefaceSize_2" class="agc_radiovert agc_icheck" /> <span  style='font-size: 16px;'>Normal</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="large" id="BeginIndex_TypefaceSize_3" class="agc_radiovert agc_icheck" /> <span  style='font-size: 17px;'>Large</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="extralarge" id="BeginIndex_TypefaceSize_4" class="agc_radiovert agc_icheck" /> <span  style='font-size: 18px;'>Extra Large</span>
                    </label>
                </div>
                <div class='radio agc_radiovert'>
                    <label>
                        <input type="radio" name="BeginIndex_TypefaceSize" value="extraextralarge" id="BeginIndex_TypefaceSize_5" class="agc_radiovert agc_icheck" /> <span  style='font-size: 20px;'>Extra Extra Large</span>
                    </label>
                </div>
            </div>                    
        </div> 
     </div>
     <div class="row text-right" id="row_begin_index_4">
        <button type="submit" class="btn btn-primary" name="agc-style-settings-submit" value="true">Go! <span class="glyphicon glyphicon-ok"></span></button>
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
