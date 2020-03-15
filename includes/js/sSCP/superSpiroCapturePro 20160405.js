/*!
 * superSpiroCapturePro v0.0.1a
 * ===================================
 * Powerful jQuery plugin for capturing spirometry information
 *
 * (c) 2015 Barreh, http://www.barreh.co.uk/
 */

(function($) {

  // Cached vars
  var   _sSCP               = 'superSpiroCapturePro';
  var   _sSCPInputModal     = null;
  var   _sSCPButtonLauncher = null;
  var   _sSCPInsertLocation = '#agc_modal_loader';
  var   _sSCPSpiroFormTab   = 'tab1'; 
  var   _sSCPPEFFormTab     = 'tab2'; 
  var   _sSCPInputTabs      = 
            "<ul class='nav nav-tabs'>" +
              "<li class='active'><a href='#"+_sSCPSpiroFormTab+"' data-toggle='tab'>Spirometry</a></li>" +
              "<li><a href='#"+_sSCPPEFFormTab+"' data-toggle='tab'>PEF</a></li>" +
            "</ul>" +
            "<div class='tab-content'>" +
                 "<div class='tab-pane active' id='"+_sSCPSpiroFormTab+"'>tab1</div>" +
                 "<div class='tab-pane' id='"+_sSCPPEFFormTab+"'>tab2</div>" +
            "</div>";
  var   _sSCPAge            = null;
  var   _sSCPPredPef        = null;
  var   _sSCPPredFvc        = null;
  var   _sSCPPredFev        = null;
   
  var   _sSCPNumberValidator = {
                                    message: 'This field is invalid.',
                                    validators: {
                                            notEmpty: {
                                                message: 'You cannot leave this field empty.'
                                            },
                                            numeric: {
                                                message: 'Your input must be a number.'
                                            }
                                    }
                                };
  var   _sSCPDateValidator = {
                                    message: 'This field is invalid.',
                                    validators: {
                                            notEmpty: {
                                                message: 'You cannot leave this field empty.'
                                            }
                                    }
                                };
   
  
  $.fn[_sSCP] = function() {
      
      if (!Date.now) {
            Date.now = function() { return new Date().getTime(); };
        }
 
      
      function agc_sSCP_calc_predPef(age)
      {
            predictedPef     = 0;
          
            sex         = obj_agc_Review['InitialPatientDetails_Sex'];
            height      = obj_agc_Review['ClinicalExam_HeightM'];

            if (age !== '' && sex !== '' && height !== '')
            {

                       if (parseInt(age) < 16) {
                               predictedPef = 543.3794446 + (parseFloat(height) * -415.1345996) + ((parseFloat(height) * parseFloat(height)) * 665.7294331) + (Math.exp(-0.5087062 + parseFloat(height)) * -415.546444);
                       }
                       else if (parseInt(age) >= 16){
                               if(sex == 'M'){
                                       predictedPef = (((parseFloat(height) * 5.48) + 1.58) - (parseFloat(age) * 0.041)) * 60;
                               }
                       else if (sex == 'F'){
                                       predictedPef = (((parseFloat(height) * 3.72) + 2.24) - (parseFloat(age) * 0.03)) * 60;    
                               } else {
                                       predictedPef =  'Error';
                               }
                       }
                       else {
                               predictedPef =  'Error';
                       }

            }
            
            return predictedPef.toFixed(0);
      }
      
      function agc_sSCP_calc_predFvc(age)
      {
            predictedFvc    = 0;
            sex             = obj_agc_Review['InitialPatientDetails_Sex'];
            height          = obj_agc_Review['ClinicalExam_HeightM'];

            usedAge = parseInt(age);

            if (parseInt(age) < 25) {
              usedAge = 25;
            }

            if (age !== '' && sex !== '' && height !== ''){

             if(sex == 'F'){
              predictedFvc = parseFloat(height) * 4.43 - usedAge * 0.026 - 2.89;
             }
             else if (sex == 'M'){
              predictedFvc = parseFloat(height) * 5.76 - usedAge * 0.026 - 4.34;    
             }else {
              predictedFvc =  'Error';
             }  
              
              
            }
            return predictedFvc.toFixed(1);
      }
      
      function agc_sSCP_calc_predFev(age)
      {
            predictedFev    = 0;
            sex             = obj_agc_Review['InitialPatientDetails_Sex'];
            height          = obj_agc_Review['ClinicalExam_HeightM'];

            usedAge = parseInt(age);

            if (parseInt(age) < 25) {
                     usedAge = 25;
            }

            if (age !== '' && sex !== '' && height !== ''){

                    if(sex == 'F'){
                     predictedFev = parseFloat(height) * 3.95 - usedAge * 0.025 - 2.60;
                    }
                    else if (sex == 'M'){
                     predictedFev = parseFloat(height) * 4.30 - usedAge * 0.029 - 2.49;    
                    }else {
                     predictedFev =  'Error';
                    }  
              

            }
            return predictedFev.toFixed(1);
      }
      
      function agc_sSCP_calcAge_extractDate(inputarray)
      {
          arrDate = new Array();
		  
		  inputarrayNew = new Array();
		  
		  if(inputarray.length == 2){
			  inputarrayNew.push("01");
			  inputarrayNew = inputarrayNew.concat(inputarray);
		  } else {
			  inputarrayNew = inputarray;
		  }
		  
          $.each(inputarrayNew, function(i,v){
              datum = 0;
              if (i !== 1)
              {
                datum = Number(v);
              }
              else
              {
                datum = Number(v)-1;
              }
              arrDate.push(datum);
          });
          
		  return new Date(arrDate[2],arrDate[1],arrDate[0]);  
		  
      }      
      
      function agc_sSCP_calculateAge()
      {
          DateOfBirth = agc_sSCP_calcAge_extractDate(obj_agc_Review['InitialPatientDetails_DateOfBirth'].split('/'));
          TargetDate = Date.now();
          if ((typeof $('#sSCP_DateCaptured').val() !== 'undefined') && ($('#sSCP_DateCaptured').val() !== ''))
          {
                TargetDate = agc_sSCP_calcAge_extractDate($('#sSCP_DateCaptured').val().split('/'));
          }
          ageDifMs = TargetDate - DateOfBirth;
          ageDate = new Date(ageDifMs); // miliseconds from epoch
          age = Math.abs(ageDate.getUTCFullYear() - 1970);
            if (Date.now() < DateOfBirth) {
               age = -1;
            }
           
         return age;
      }
      
      function calculatePredictions()
      {
          _sSCPAge = agc_sSCP_calculateAge();
          
          _sSCPPredPef = agc_sSCP_calc_predPef(_sSCPAge);
          $('#sSCP_PredictedPEFValue').val(_sSCPPredPef);
          
          _sSCPPredFvc = agc_sSCP_calc_predFvc(_sSCPAge);
          $('#sSCP_PredictedFVCValue').val(_sSCPPredFvc);
          
          _sSCPPredFev = agc_sSCP_calc_predFev(_sSCPAge);
          $('#sSCP_PredictedFEVValue').val(_sSCPPredFev);
          
      }
      
      function clearAllFields()
      {
          $('#sSCP_PEF').val('');
          $('#sSCP_PredictedPEFValue').val('');
          $('#sSCP_PostbronchodilatorFEV1').val('');
          $('#sSCP_PostbronchodilatorFVC').val('');
          $('#sSCP_PostbronchodilatorSVC').val('');
          $('#sSCP_PrebronchodilatorFEV1').val('');
          $('#sSCP_PrebronchodilatorFVC').val('');
          $('#sSCP_PrebronchodilatorSVC').val('');
          $('#sSCP_PredictedFEVValue').val('');
          $('#sSCP_PredictedFVCValue').val('');
          $('#sSCP_DateCaptured').val('');
      }
      
      function captureSpiroData()
      {
          
          TargetDate = agc_sSCP_calcAge_extractDate($('#sSCP_DateCaptured').val().split('/'));
          date_stamp = Math.round(TargetDate/1000);

          sSCPpayload = {
                            timestamp: date_stamp, 
                            spirometry_data: {
                                                'PEF': $('#sSCP_PEF').val(),
                                                'PredPEF': $('#sSCP_PredictedPEFValue').val(),
                                                'PostFEV1': $('#sSCP_PostbronchodilatorFEV1').val(),
                                                'PostFVC': $('#sSCP_PostbronchodilatorFVC').val(),
                                                'PostSVC': $('#sSCP_PostbronchodilatorSVC').val(),
                                                'PreFEV1': $('#sSCP_PrebronchodilatorFEV1').val(),
                                                'PreFVC': $('#sSCP_PrebronchodilatorFVC').val(),
                                                'PreSVC': $('#sSCP_PrebronchodilatorSVC').val(),
                                                'PredFEV1': $('#sSCP_PredictedFEVValue').val(),
                                                'PredFVC': $('#sSCP_PredictedFVCValue').val()
                            }
                        };
          patientId = obj_agc_Review['PatientDetails_PatientId'];
          
          $.post( "/api/addSpirometry/"+patientId, 
                    { sSCP_payload: sSCPpayload}, 
                    function(data) {
                        if (data.error === false)
                        {
                            _sSCPInputModal.modal('hide');
                            clearAllFields();
                            BTP_start_spiro();
                            BCP_start_spiro();
                            $('#sSCP_modal_okay').unbind( "click" );
                        }
                        else if (data.error === true)
                        {
                            alert('There was a problem sending data to the API.  Please check inputs and try again.');
                        }
                    }
                );
          
          
      }
      
      function togglePreColumnVis(value)
      {
          if (value === 'Y')
          {
              $('.sSCP_pregroup').find("input").each(function(){
                  $(this).val('');
                  $(this).change();
              });
              
              $('.sSCP_pregroup').addClass('hidden');
              
              $('.sSCP_pregroup_nudge').addClass('col-md-offset-2');
          }
          else
          {
              $('.sSCP_pregroup').removeClass('hidden');
              $('.sSCP_pregroup_nudge').removeClass('col-md-offset-2');
          }
      }
      
      function doPercentPredicted (Val1,Val2,PredVal)
      {
          MaxVal = Math.max(Val1,Val2);
          Percent = (MaxVal/PredVal)*100;
          
          return Percent.toFixed(1);
      }
      
      /*function FevOverFvc()
      {
          FEV1Post  = ($('#sSCP_PostbronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFEV1').val()) : 0;
          FEV1Pre   = ($('#sSCP_PrebronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PrebronchodilatorFEV1').val()) : 0;
          
          FVCPre    = ($('#sSCP_PrebronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PrebronchodilatorFVC').val()) : 0;
          FVCPost   = ($('#sSCP_PostbronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFVC').val()) : 0;
          
          SVCPre    = ($('#sSCP_PrebronchodilatorSVC').val() !== '') ? parseFloat($('#sSCP_PrebronchodilatorSVC').val()) : 0;
          SVCPost   = ($('#sSCP_PostbronchodilatorSVC').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorSVC').val()) : 0;
          
          
          if (  ((FEV1Post !== 0) || (FEV1Pre !== 0))
                      && ((FVCPre !== 0) || (FVCPost !== 0) || (SVCPre !== 0) || (SVCPost !== 0)) )
            {
                MaxVCVal    = Math.max(FVCPre,FVCPost,SVCPre,SVCPost);
                MaxFEVVal   = Math.max(FEV1Pre,FEV1Post);
                Val         = (MaxFEVVal/MaxVCVal)*100;
                $('#sSCP_FEV1OverFVC').val(Val.toFixed(1));
            }
            else
            {
                $('#sSCP_FEV1OverFVC').val('');
            }
            
            
      }*/
	  
	  function FevOverFvc()
      {
          FEV1Post  = ($('#sSCP_PostbronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFEV1').val()) : 0;
          FVCPost   = ($('#sSCP_PostbronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFVC').val()) : 0;
           
          if ((FEV1Post !== 0) && (FVCPost !== 0)){
                Val = (FEV1Post/FVCPost)*100;
                $('#sSCP_FEV1OverFVC').val(Val.toFixed(1));
            }
            else
            {
                $('#sSCP_FEV1OverFVC').val('');
            }            
            
      }      
      
      /*function percentPredictedChangeFev()
      {
          FEV1Post  = ($('#sSCP_PostbronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFEV1').val()) : 0;
          FEV1Pre   = ($('#sSCP_PrebronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PrebronchodilatorFEV1').val()) : 0;
         
          if ((FEV1Post > 0) || (FEV1Pre > 0))
          {
                Percentage = doPercentPredicted (FEV1Pre,FEV1Post,_sSCPPredFev);

                if (parseFloat(Percentage) !== 'NaN')
                {
                    return Percentage;
                }
          }
          return '';
      }
      
      function percentPredictedChangeFvc()
      {
          
          FVCPre    = ($('#sSCP_PrebronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PrebronchodilatorFVC').val()) : 0;
          FVCPost   = ($('#sSCP_PostbronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFVC').val()) : 0;
          if ((FVCPre > 0) || (FVCPost > 0))
          {
                Percentage = doPercentPredicted (FVCPre,FVCPost,_sSCPPredFvc);
                if (parseFloat(Percentage) !== 'NaN')
                {
                    return Percentage;
                }
          }
          return '';
      }*/
	  
	  function percentPredictedChangeFev()
      {
          FEV1Post  = ($('#sSCP_PostbronchodilatorFEV1').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFEV1').val()) : 0;
         
          if (FEV1Post > 0)
          {
                Percentage = doPercentPredicted (0,FEV1Post,_sSCPPredFev);

                if (parseFloat(Percentage) !== 'NaN')
                {
                    return Percentage;
                }
          }
          return '';
      }
      
      function percentPredictedChangeFvc()
      {

          FVCPost   = ($('#sSCP_PostbronchodilatorFVC').val() !== '') ? parseFloat($('#sSCP_PostbronchodilatorFVC').val()) : 0;
		  
          if (FVCPost > 0)
          {
                Percentage = doPercentPredicted (0,FVCPost,_sSCPPredFvc);
                if (parseFloat(Percentage) !== 'NaN')
                {
                    return Percentage;
                }
          }
          return '';
      }
      
      function percentPredictedChangePef()
      {
          
          
          PEF  = ($('#sSCP_PEF').val() !== '') ? parseFloat($('#sSCP_PEF').val()) : 0;
          if (PEF > 0)
          {
                Percentage = doPercentPredicted (PEF,0,_sSCPPredPef);
                if (parseFloat(Percentage) !== 'NaN')
                {
                    return Percentage;
                }
          }
          return '';
      }
      
      function percentPredictedChange(target)
      {
          target = $(target);
          
          switch (target.attr('id'))
          {
              case 'sSCP_PrebronchodilatorFEV1':
              case 'sSCP_PostbronchodilatorFEV1':
                  $('#sSCP_PercentPredictedFEV1').val(percentPredictedChangeFev());
                  break;
              case 'sSCP_PrebronchodilatorFVC':
              case 'sSCP_PostbronchodilatorFVC':
                  $('#sSCP_PercentPredictedFVC').val(percentPredictedChangeFvc());
                  break;
              case 'sSCP_PEF':
                  $('#sSCP_PercentPredictedPEF').val(percentPredictedChangePef());
                  break;
          }
      }
      
      function createSpirometryForm()
      {
          /*myhtml = "<div class='col-md-8 layout-holder'>Have you taken any long acting bronchodilators within the last 12 hours or short acting bronchodilators within the last 4 hours?</div>";
          myhtml += "<div class='col-md-4 layout-holder'>";
          myhtml += "<div class='form-group'><label class='radio-inline'><input type='radio' name='sSCP_RecentBronchodilators' value='Y' id='sSCP_RecentBronchodilators_0' class='agc_sSCP_icheck' /> Yes</label>";
          myhtml += "<label class='radio-inline'><input type='radio' name='sSCP_RecentBronchodilators' value='N' id='sSCP_RecentBronchodilators_1' class='agc_sSCP_icheck'  /> No</label></div>";
          myhtml += "</div>";*/
          
		  //CR - Removed the bronchodilator question and added empty string myhtml
          myhtml = '';
          
          spiro_tab = $('#'+_sSCPSpiroFormTab);
          spiro_tab.html('');
          
          //spiro_tab.load('/includes/js/spirometry_form.html');
          
          $('<div/>', {class:'row','html':myhtml}).prependTo(spiro_tab);
          $('<div/>', {id:'sSCP_SpiroForm'}).appendTo(spiro_tab);
          $.get('/includes/js/sSCP/spirometry_form.html', function(data) {
            $('#sSCP_SpiroForm').html(data);
            $('#sSCP_PredictedFEVValue').val(_sSCPPredFev);
            $('#sSCP_PredictedFVCValue').val(_sSCPPredFvc);
            $('.percent-predicted').change(function (e) {percentPredictedChange(e.currentTarget);});
            $('.fev-over-fvc').change(function (e) {FevOverFvc();});
            
            $('#sSCP_SpiroForm').find("input.sSCPFormValidator")
                    .each(function(){  
                        $('#sSCPForm').bootstrapValidator('addField', $(this).attr('name'), _sSCPNumberValidator ); 
                    });
            });
            
          
      }
      
      function createPEFForm()
      {
          pef_tab = $('#'+_sSCPPEFFormTab);
          pef_tab.html('');
          $('<div/>', {id:'sSCP_PEFForm'}).appendTo(pef_tab);
          $.get('/includes/js/sSCP/pef_form.html', function(data) {
              $('#sSCP_PEFForm').html(data);
              $('#sSCP_PredictedPEFValue').val(_sSCPPredPef);
              $('#sSCP_PEF').change(function (e) {percentPredictedChange(e.currentTarget);});
              $('#sSCPForm').bootstrapValidator('addField', 'sSCP_PEF', _sSCPNumberValidator );
          });
      }
      
      /*function createDateForm()
      {
          
            DateForm = $('<div/>',{id:'sSCP_DateForm'}).prependTo($('#sSCPBody'));


            myhtml = "<div class='col-md-8 layout-holder text-right'>Date of Test</div>";
            myhtml += "<div class='col-md-3 layout-holder'>";
            myhtml += "<div class='form-group' id='sSCP_DatePicker'><input type='text' value='' class='form-control' name='sSCP_DateCaptured' id='sSCP_DateCaptured' placeholder='DD/MM/YYYY' /></div>";
            myhtml += "</div>";

            $('<div/>', {class:'row','html':myhtml,id:'sSCP_DateQuestion'}).appendTo(DateForm);
            $('#sSCP_DateCaptured')
                                  .datepicker({
                                                                          format: "dd/mm/yyyy",
                                                                          startView: 2,
                                                                          autoclose: true
                                                                      })
                                  .on('changeDate', function(e) {
                                                      // Revalidate the date field
                                                      $('#sSCPForm').bootstrapValidator('revalidateField', 'sSCP_DateCaptured');
                                                  });

           $('#sSCPForm').bootstrapValidator('addField', 'sSCP_DateCaptured', _sSCPDateValidator );
       
          
          
      }*/
	  
	  function createDateForm()
      {
          
            DateForm = $('<div/>',{id:'sSCP_DateForm'}).prependTo($('#sSCPBody'));

            myhtml = "<div class='col-md-8 layout-holder text-right'>Date of Test</div>";
            myhtml += "<div class='col-md-3 layout-holder'>";
            myhtml += "<div class='form-group' id='sSCP_DatePicker'><input type='text' value='' class='form-control' name='sSCP_DateCaptured' id='sSCP_DateCaptured' placeholder='MM/YYYY' /></div>";
            myhtml += "</div>";

            $('<div/>', {class:'row','html':myhtml,id:'sSCP_DateQuestion'}).appendTo(DateForm);
            $('#sSCP_DateCaptured')
                                  .datepicker({
																		  format: "mm/yyyy",
																		  minViewMode: 1, 
                                                                          startView: 2,
                                                                          autoclose: true,
																		  orientation: 'auto bottom',
																		  endDate: new Date()
                                                                      })
                                  .on('changeDate', function(e) {
                                                      // Revalidate the date field
                                                      $('#sSCPForm').bootstrapValidator('revalidateField', 'sSCP_DateCaptured');
                                                  });

           $('#sSCPForm').bootstrapValidator('addField', 'sSCP_DateCaptured', _sSCPDateValidator );
  
      }
      
      function createSpiroForms()
      {
           createDateForm();
          
           createSpirometryForm();
           createPEFForm();
           
          
      }
      
      function createSpiroModalContent()
        {
            
            
            jqoModalDialog = $('<div/>',{class:'modal-dialog'});
            jqoModalForm = $('<form/>',{id:'sSCPForm', 'role': 'form'}).appendTo(jqoModalDialog);
            
            jqoModalContent = $('<div/>', {class:'modal-content'}).appendTo(jqoModalForm);

            $('<h4/>', {class:'modal-title','html':'Add Spirometry'}).appendTo($('<div/>', {class:'modal-header'}).appendTo(jqoModalContent));


            jqoModalTabbable = $('<div/>', {class:'tabbable', 'html': _sSCPInputTabs}).appendTo($('<div/>', {class:'modal-body',id:'sSCPBody'}).appendTo(jqoModalContent));


            $('<div/>',{class:'row'}).prependTo(jqoModalTabbable);
            
            
          
            jqoModalFooter    = $('<div/>', {class:'modal-footer'}).appendTo(jqoModalContent);

            $('<button/>', {type:'button', class:'btn btn-default', id: 'sSCP_modal_cancel', text:'Cancel', 'data-dismiss':'modal'}).appendTo(jqoModalFooter);
            $('<button/>', {type:'button', class:'btn btn-primary', id: 'sSCP_modal_okay', text:'Okay'}).appendTo(jqoModalFooter);

            return jqoModalDialog;
        }
      
      
      
      _sSCPButtonLauncher = this;
      
      calculatePredictions();
      
      if (_sSCPInputModal === null)
      {
        _sSCPInputModal    = $('<div/>',{class: 'modal fade',id:'sSCP_modal_capture'}).insertAfter(_sSCPInsertLocation);

        jqoModalContent = createSpiroModalContent();

        jqoModalContent.appendTo(_sSCPInputModal);
        createSpiroForms();
      }
      _sSCPInputModal.modal({
                                show:       true,
                                backdrop:   'static',
                                keyboard:   false});
      
      
      
      $('.agc_sSCP_icheck')
            .iCheck({
                                        checkboxClass:  'icheckbox_square-'+striCheckTheme,
                                        radioClass:     'iradio_square-'+striCheckTheme,
                                        increaseArea:   '20%' 
                                    })
            .on('ifChanged', function(e) {
                theTarget = $(e.target);
                switch(theTarget.attr('name'))
                {
                    case 'sSCP_RecentBronchodilators':
                        togglePreColumnVis(theTarget.attr('value'));
                        break;
                    
                }
            });
      $('#sSCP_DateCaptured').change(function(){calculatePredictions();});
      
      $('#sSCPForm')
             .on('init.field.bv', function(e, data) {
                                        // data.bv      --> The BootstrapValidator instance
                                        // data.field   --> The field name
                                        // data.element --> The field element

                                        var $parent    = data.element.parents('.form-group');
                                        var $icon      = $parent.find('.form-control-feedback[data-bv-icon-for="' + data.field + '"]');
                                        var options    = data.bv.getOptions();                      // Entire options
                                        var validators = data.bv.getOptions(data.field).validators; // The field validators

                                        if (validators.notEmpty && options.feedbackIcons && options.feedbackIcons.required) 
                                        {
                                            // The field uses notEmpty validator
                                            // Add required icon
                                            $icon.addClass(options.feedbackIcons.required).show();
                                        }
                                    })
            .bootstrapValidator({
                feedbackIcons: {
                                                  required:       'glyphicon glyphicon-asterisk',
                                                  valid:          'glyphicon glyphicon-ok',
                                                  invalid:        'glyphicon glyphicon-remove',
                                                  validating:     'glyphicon glyphicon-refresh'
                                              },
                excluded: [':disabled', ':hidden', ':not(:visible)']
                

            });
      
      //$('#sSCPForm').find('input').add     
      
      // strAPIUriRoot
      $('#sSCP_modal_okay').click(function(){ captureSpiroData(); });
  };
  
})(window.jQuery);