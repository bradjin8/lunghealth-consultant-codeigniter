<!--<pre>
    <?php
    var_dump($arrConsultations);
    ?>
</pre>-->


<?php 
    function getAssessmentType($strCode)
    {
        switch ($strCode)
        {
            case "FU":
                return "Follow up";
            case "AR":
                return "Annual Review";
            case "EX":
                return "Exacerbation";
            case "1A":
                return "First Assessment";
        }
                

    }


    if (count($arrConsultations) > 0)
    {
        
        ?>
<div class="panel panel-default">

      <div class="panel-body">
            <h3>Previous Consultations</h3>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width:15%;">Date</th>
                        <th style="width:20%;">Type</th>
                        <th style="width:45%;">Status</th>
                        <th style="width:10%;"></th>
                        <th style="width:10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($arrConsultations as $objConsultation)
                    {
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y',$objConsultation->Timestamp); ?></td>
                        <td><?php echo getAssessmentType($objConsultation->ConsultationType); ?></td>
                        <td><?php   if ($objConsultation->Status->State !== 'Incomplete') 
                                    { 
                                        if ($objConsultation->ApiStatus !== 'CompleteNoMedication')
                                        {
                                            echo $objConsultation->Status->State; 
                                        }
                                        else
                                        {
                                            echo "Complete, No Medication (First Assessment Required)";
                                        }
                                            
                                    } 
                                    else 
                                    { 
                                            echo $objConsultation->Status->Message; 
                                            
                                    } ?></td>
                        <td><a href="/begin/audit/<?php echo $objConsultation->ConsultationId; ?>/<?php echo $objConsultation->ConsultationType; ?>/">Audit</a></td>
                        <td><a href="/download/auditpdf/<?php echo $objConsultation->ConsultationId; ?>/<?php echo $objConsultation->ConsultationType; ?>/">Report</a></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
      </div>
</div>
<?php
    }
    else
    {
        ?>
    <div class="alert alert-info" role="alert">
        This patient has no previous consultations.
    </div>
    <?php
    }
    
    $attributes = array('role' => 'form', 'id' => 'agc-begin-form');
    $arrHidden = array();
    if (!$booCompletedFA)
    {
        $arrHidden = array('agc-begin-AssessmentType' => '1A');
    }
    if ($intReviewId >  0)
    {
    ?>
        <div class="alert alert-danger" role="alert">
            It appears you have an unfinished consultation for this patient.  Do you wish to complete or start again?
        </div>
        <?php
        echo form_open("/begin/loadreview/".$intReviewId, $attributes,$arrHidden);
        
        
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
                                <input type="radio" name="agc-begin-Continue" value="continue" checked="checked" id="BeginConsultation_Continue_0" class="agc_radiovert agc_icheck" />Continue with unfinished consultation
                            </label>
                        </div>
                        <div class='radio agc_radiovert'>
                            <label>
                                <input type="radio" name="agc-begin-Continue" value="new" id="BeginConsultation_Continue_1" class="agc_radiovert agc_icheck" />Start new consultation (will delete previous consultation)
                            </label>
                        </div>
                    </div>                    
                </div>

             </div>
             
          


    <?php

       
    
    }
    else
    {
        
    
    
    
    
        echo form_open("/begin/create/", $attributes, $arrHidden);
    
    ?>
              <div class="panel panel-default">

      <div class="panel-body">

    <?php
    }
    ?>
          <h3 id="Question_Type_Assessment_Header">
              New Consultation
          </h3>
          <?php
          if ($booCompletedFA)
            {
              ?>
          
          <div class="row" id="Question_Type_Assessment_Label">
            <div id="layout-holder-begin_index_6" class="col-md-8 col-md-offset-2 layout-holder" >
                Which type of Assessment would you like to complete?                 
            </div>
          </div>
          <div class="row" id="Question_Type_Assessment_Question">
            <div id="layout-holder-begin_index_7" class="col-md-8 col-md-offset-2 layout-holder" >
                <div class="form-group">
                    <div class='radio agc_radiovert'>
                        <label>
                            <input type="radio" name="agc-begin-AssessmentType" value="FU"  id="agc-begin-AssessmentType_0" class="agc_radiovert agc_icheck" />Follow Up
                        </label>
                    </div>
                    <div class='radio agc_radiovert'>
                        <label>
                            <input type="radio" name="agc-begin-AssessmentType" value="AR" id="agc-begin-AssessmentType_1" class="agc_radiovert agc_icheck" />Annual Review
                        </label>
                    </div>
                    <div class='radio agc_radiovert'>
                        <label>
                            <input type="radio" name="agc-begin-AssessmentType" value="EX" id="agc-begin-AssessmentType_2" class="agc_radiovert agc_icheck" />Exacerbation
                        </label>
                    </div>
                </div>                    
            </div>

         </div>
          <?php
            }
          ?>
         <div class="row" id="row_begin_index_5">
             <div id="layout-holder-begin_index_8" class="col-md-2 layout-holder" >
                <button type="button" class="btn btn-primary" name="agc-begin-Cancel" onClick="javascript:document.location.href = '<?php echo $this->document->getAPIUrl(); ?>Patients/Find/';"><span class="glyphicon glyphicon-backward"></span> Cancel</button>
             </div>
             <div id="layout-holder-begin_index_9" class="col-md-2 col-md-offset-8 layout-holder text-right" >
                <button type="submit" class="btn btn-primary" name="agc-begin-Submit" value="true">Okay <span class="glyphicon glyphicon-ok"></span></button>
             </div>
         </div>
      </div>
    </div>
    
    <?php
    
    echo form_close();
    
?>









<script type='text/javascript'>
    var FirstAssessmentComplete = <?php if($booCompletedFA) { echo "true";}else { echo "false";} ?>;
    var UnfinishedReview = <?php if ($intReviewId >  0) { echo "true";}else { echo "false";} ?>;
    var ReviewId = <?php echo $intReviewId; ?>;
    
    $(document).ready(function(){
        $('#agc-begin-form').find('input.agc_icheck')
                                    // Init iCheck elements
                                    .iCheck({
                                        checkboxClass:  'icheckbox_square-purple',
                                        radioClass:     'iradio_square-purple',
                                        increaseArea:   '20%' 
                                    });
        if (!FirstAssessmentComplete)
        {
            $('#Question_Type_Assessment_Label').hide();
            $('#Question_Type_Assessment_Question').hide();
            $('#Question_Type_Assessment_Header').hide();
        }
        
        if (UnfinishedReview)
        {
            $('#Question_Type_Assessment_Header').hide();
            $('#Question_Type_Assessment_Label').hide();
            $('#Question_Type_Assessment_Question').hide();
            $('#BeginConsultation_Continue_1').on('ifChecked', function(event){
                $('#Question_Type_Assessment_Label').show();
                $('#Question_Type_Assessment_Question').show();
                $('#Question_Type_Assessment_Header').show();
                $('#agc-begin-form').attr('action','/begin/deletereview/'+ReviewId);
                
              });
              $('#BeginConsultation_Continue_1').on('ifUnchecked', function(event){
                  $('#Question_Type_Assessment_Header').hide();
                $('#Question_Type_Assessment_Label').hide();
                $('#Question_Type_Assessment_Question').hide();
                $('#agc-begin-form').attr('action','/begin/loadreview/'+ReviewId);
              });
        }
        
        if (!UnfinishedReview)
        {
            $('#Question_Type_Assessment_Header').show();
        }
        
    });
    </script>
<?php
