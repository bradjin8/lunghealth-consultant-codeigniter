
                                        
                                       
                            var showConsole = false;
                            var fieldValues = [];
                            
                            var arrRevealed = [];
                            var arrHidden   = [];
                            var booShowDialog = false;
                            var arrNotBlank = [];
                            
                            var loaderHolder = -1;
                            
                            var dialogOnDisplay = false;
                            
                            var suppressVisibilityFunction = false;
                            
                            /*var objChangedValues = {
                                "MedicationReview_ReviewDrugISABA": {currentdrug:'0AFC0644-ACC0-46D3-82FE-691300C15C5A',currentusage:'qds',newdrug:'',newusage:'',changemode:''},
                                "MedicationReview_ReviewDrugICS": {currentdrug:'',currentusage:'',newdrug:'01E5B685-08B3-4C9A-B969-E620D017EC7C',newusage:'tdz',changemode:'start'}
                            };*/
    
                            
                            
                            function agc_console(strInput)
                            {
                                if (showConsole)
                                {
                                    console.log(strInput);
                                }
                            }
                            
                            function agc_PEFValueDialog(strMessage)
                            {
                                $('#agc_modal_pefbest_text').html(strMessage);
                                
                                $('#agc_modal_dialog_pefbest').modal({
                                    show:       true,
                                    backdrop:   'static',
                                    keyboard:   false});
                                
                                $('input[name=agc_modal_pefbest_response]').iCheck({
                                        checkboxClass:  'icheckbox_square-'+striCheckTheme,
                                        radioClass:     'iradio_square-'+striCheckTheme,
                                        increaseArea:   '20%' 
                                    })
                                    // Called when the radios/checkboxes are changed
                                    .on('ifChanged', function(e) {
                                        // Get the field name
                                        
                                        
                                
                                        if ($('input[name=agc_modal_pefbest_response]:checked').val() === 'NO')
                                        {
                                            $('#agc_modal_pefbest_furtherquestion').removeClass('hidden');
                                        }
                                        else
                                        {
                                            $('#agc_modal_pefbest_furtherquestion').addClass('hidden');
                                        }
                                        
                                        
                                    });
                                
                                $('#agc_modal_pefbest_okay').click(
                                    function ()
                                    {
                                        strRadioResult  = $('input[name=agc_modal_pefbest_response]:checked').val();
                                        strTextResult   = $('#agc_modal_pefbest_pef_value').val();
                                        
                                        $('#agc_modal_pefbest_okay').unbind('click');
                                        $('input[name=agc_modal_pefbest_response]').unbind('ifChanged');
                                        $('#agc_modal_pefbest_response_yes').iCheck('check');
                                        $('input[name=agc_modal_pefbest_response]').iCheck('destroy');
                                        
                                        $('#agc_modal_pefbest_text').html('');
                                        
                                        $('#agc_modal_pefbest_furtherquestion').removeClass('hidden');
                                        $('#agc_modal_pefbest_furtherquestion').addClass('hidden');
                                        
                                        $('#agc_modal_pefbest_pef_value').val('');
                                        
                                        agc_PEFAlertResults(strRadioResult,strTextResult);
                                        
                                    });
                            }
                            
                            function agc_forcesubmitForm()
                            {
                                agc_console('force submit form routine');
                                
                                invalidFields = $('#agc-form').data('bootstrapValidator').getInvalidFields();
                                $('#agc-form').data('bootstrapValidator').destroy();
                                $.each(invalidFields,
                                        function (index,inputElement)
                                        {
                                            
                                            agc_console(inputElement);
                                            agc_clearInputValue($(inputElement).attr('name'));
                                        }
                                    );
                                $('#agc-form').submit();
                            }
                            
                            function agc_setForSubmit(screenName)
                            {
                                agc_console('set submit form routine');
                                strInputId = 'agcscreenname_'+strScreenName+'_destination';
                                $('#'+strInputId).val(screenName);
                                $('#agc-form').data('bootstrapValidator').validate();
                                if (!$('#agc-form').data('bootstrapValidator').isValid())
                                {
                                    $('#agc_modal_dialog_invalid').modal({
                                                                        show:       true,
                                                                        backdrop:   'static',
                                                                        keyboard:   false});
                                    $('#agcscreenname_'+strScreenName+'_validated').val('false');
                                    $('#agc_modal_invalid_ignore').click(
                                                                    function ()
                                                                    {
                                                                        agc_forcesubmitForm();
                                                                        $('#agc_modal_invalid_ignore').unbind('click');
                                                                    });
                                }
                                else
                                {
                                    agc_forcesubmitForm();
                                }
                            }
                            
                            function attachValidator()
                            {
                                /*
                                $(':submit').click(function(event){

                                        $('#agc-form').bootstrapValidator('validate'); //secondary validation using Bootstrap Validator      
                                        var bootstrapValidator = $('#agc-form').data('bootstrapValidator');
                                        if (bootstrapValidator.isValid()) //if the page fields validate
                                        {
                                            agc_submitForm();               
                                        }
                                        else
                                        {
                                            
                                            $('#agc_modal_dialog_invalid').modal({
                                                                        show:       true,
                                                                        backdrop:   'static',
                                                                        keyboard:   false});
                                            
                                        }

                                    });
                                */
                               if (!booAuditReadOnly)
                               {
                                $("#agc-form")
                                            .find('.chosen-select').chosen({allow_single_deselect: true}).change(function(e) {
    
                                            $('#agc-form').bootstrapValidator('revalidateField', $(this).attr('name'));
                                        });
                                    }  
                            
                                $("#agc-form")
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
                                        excluded: [':disabled', 
                                        function($field,validator){
                                            if ($field.hasClass('chosen-select'))
                                            {
                                                return false;
                                            }
                                            else
                                            {
                                                // \':hidden\', \':not(:visible)\'
                                                if ($field.is(':hidden') || $field.is(':not(:visible)'))
                                                {
                                                    return true;
                                                }
                                                else
                                                {
                                                    return false;
                                                }
                                            }
                                        }],
                                        feedbackIcons: {
                                            required:       'glyphicon glyphicon-asterisk',
                                            valid:          'glyphicon glyphicon-ok',
                                            invalid:        'glyphicon glyphicon-remove',
                                            validating:     'glyphicon glyphicon-refresh'
                                        },
                                        fields: objFieldValidations
                                    
                                    
                                })
                                .on('removed.field.bv', function(e, data) {
                                    // The e and data parameters are same as one
                                    // in the added.field.bv event above

                                    // Do something ...
                                    
                                    agc_console('FIELD REMOVED');
                                })
                                .on('error.form.bv', function(e) {
                                    // $(e.target) --> The form instance
                                    // $(e.target).data('bootstrapValidator')
                                    //             --> The BootstrapValidator instance

                                    // Do something ...
                                    agc_console('FORM INVALID');
                                    e.preventDefault();
                                    if ($('#agc-form').data('bootstrapValidator').getSubmitButton() !== null)
                                    {
                                        $('#agc_modal_dialog_invalid').modal({
                                                                            show:       true,
                                                                            backdrop:   'static',
                                                                            keyboard:   false});
                                        $('#agcscreenname_' + strScreenName + '_validated').val('false');
                                        $('#agc_modal_invalid_ignore').click(
                                                                        function ()
                                                                        {
                                                                            agc_forcesubmitForm();
                                                                            $('#agc_modal_invalid_ignore').unbind('click');
                                                                        });
                                    }
                                    $('#btnFormSubmitNext').prop('disabled',false);
                                    
                                                                        
                                })
                                .on('success.form.bv', function(e,data) {
                                    agc_console('SUCCESS DISCOVERED');
                                    $('#agcscreenname_' + strScreenName + '_validated').val('true');
                                    /*$('#agc-form :input').each(function(index,jqObject){
                                                        //agc_console($(jqObject));

                                                        alert('inputName:'+$(jqObject).attr('name')+', set to ['+agc_getInputValue($(jqObject))+'];');
                                                    });*/
                                    if (booAuditReadOnly)
                                    {
                                        $('.form-control-feedback').hide();
                                        $('.form-group').removeClass('has-success');
                                    }


                                })
                                .find('input.agc_icheck')
                                    // Init iCheck elements
                                    .iCheck({
                                        checkboxClass:  'icheckbox_square-'+striCheckTheme,
                                        radioClass:     'iradio_square-'+striCheckTheme,
                                        increaseArea:   '20%' 
                                    })
                                    // Called when the radios/checkboxes are changed
                                    .on('ifChanged', function(e) {
                                        // Get the field name
                                        var field = $(this).attr('name');
                                        agc_console($(this).attr('name'));
                                        agc_console($(this).is(':checked'));
                                        agc_EventHandler(this);
                                        $('#agc-form')
                                            // Mark the field as not validated
                                            .bootstrapValidator('updateStatus', field, 'NOT_VALIDATED')
                                            // Validate field
                                            .bootstrapValidator('revalidateField', field);
                                        
                                    });
                            }
                            
                            
                            
                            function agc_getInputValue(inputObject)
                            {
                                //agc_console(inputObject);
                                var type = inputObject.attr('type') || inputObject.prop("tagName").toLowerCase();
                                switch (type)
                                {
                                    case 'hidden':
                                    case 'text':
                                    case 'textarea':
                                        return inputObject.val();
                                    case 'radio':
                                        return $('input[name='+inputObject.attr('name')+']:checked').val();
                                    case 'checkbox':
                                        return inputObject.is(':checked') ? 'Y' : 'N';
                                    case 'select':
                                        agc_console($('select[name='+inputObject.attr('name')+'] option:selected').val());
                                        return $('select[name='+inputObject.attr('name')+'] option:selected').val();
                                }
                            }
                            
                            function agc_getInputBlank(inputObject)
                            {
                                var value = agc_getInputValue(inputObject);
                                var type = inputObject.attr('type') || inputObject.prop("tagName").toLowerCase();
                                switch (type)
                                {
                                    case 'hidden':
                                    case 'text':
                                    case 'textarea':
                                        return (value === '') ? true : false;
                                    case 'radio':
                                        return (typeof value === 'undefined') ? true : false;
                                    case 'checkbox':
                                        return (value === 'N') ? true : false;
                                    case 'select':
                                        return (value === '') ? true : false;
                                }
                            }
                            
                            function agc_setInputValue(inputName,valueToSet)
                            {
                                
                                if ((typeof inputName !== 'undefined') && (inputName !== 'undefined'))
                                {
                                    if ((typeof valueToSet !== 'undefined') && (valueToSet !== 'undefined'))
                                    {
                                        
                                        inputObject = $("[name='"+inputName+"']");

                                        if (agc_getInputValue(inputObject) !== valueToSet) 
                                        {
                                            agc_console('Setting:'+inputName+' to:'+valueToSet);
                                        
                                            var type = inputObject.attr('type') || inputObject.prop("tagName").toLowerCase();
                                            switch (type)
                                            {
                                                case 'hidden':
                                                case 'text':
                                                case 'textarea':
                                                    inputObject.val(valueToSet);
                                                    break;
                                                case 'radio':
                                                    /*$('input[name='+inputName+']').prop('checked',false);
                                                    $('input[name='+inputName+']').iCheck('uncheck');*/
                                                    agc_console('unchecked everything');
                                                   /* $('input[name='+inputName+'][value='+valueToSet+']').prop('checked',true);*/
                                                    $('input[name='+inputName+'][value='+valueToSet+']').iCheck('check');
                                                    break;
                                                case 'checkbox':
                                                    valueToSet === 'Y' ? inputObject.iCheck('check') : inputObject.iCheck('uncheck');
                                                    break;
                                                case 'select':
                                                    inputObject.children().filter('[value="'+valueToSet+'"]').prop('selected',true);
                                                    break;
                                            }
                                            recentlyHidden = [];
                                            inputObject.change();
                                        }
                                    }
                                }
                            }
                            
                            
                            function agc_clearInputValue(inputName)
                            {
                                inputObject = $("[name='"+inputName+"']");
                                var type = inputObject.attr('type') || inputObject.prop("tagName").toLowerCase();
                                switch (type)
                                {
                                    case 'hidden':
                                        agc_console('HIDDEN FOUND');
                                        agc_console(inputObject);
                                        if ($.isFunction(inputObject.combobox))
                                        {
                                            agc_console('combobox is function');
                                            inputObject.siblings("div.input-group").children('ul').children().removeClass('active');
                                            inputObject.siblings("div.input-group").children('input').val('');
                                            inputObject.parent().removeClass('combobox-selected');
                                        }
                                        
                                        inputObject.val('');
                                        break;
                                    case 'text':
                                    case 'textarea':
                                        inputObject.val('');
                                        break;
                                    case 'radio':
                                        inputObject.iCheck('uncheck');
                                        break;
                                    case 'checkbox':
                                        inputObject.iCheck('uncheck');
                                        break;
                                    case 'select':
                                        //inputObject.prop('selectedIndex','');
                                        inputObject.val('');
                                        break;
                                }
                                agc_console("+++ CLEAR INPUT VALUE END inputName:"+inputName+" is set to "+agc_getInputValue(inputObject));
                            }
                            
                                                        
                            
                            function agc_getAllInputsIn(inputObject)
                            {
                                return $('#'+inputObject.attr('id')+' :input').filter(':not(.no-validate)');
                            }
                            
                            
                            function agc_checkForNoneBlankInputs(intLayoutId)
                            {
                                agc_getAllInputsIn($("#layout-holder-"+intLayoutId)).each(function(indexinputs,inputObject){
                                                        // input holder found, so check if not blank...
                                                        
                                                        if (!agc_getInputBlank($(inputObject)))
                                                        {
                                                            // it's not blank, we need to prompt for user decision - undo or continue
                                                            agc_console($(inputObject).attr('name')+' is not blank.  Show dialog.');
                                                            booShowDialog = true;
                                                            // push into non-blank array - so we can blank value if necessary
                                                            arrNotBlank.push($(inputObject));
                                                        }
                                                        else
                                                            {
                                                                agc_console($(inputObject).attr('name')+' is blank.');
                                                            }
                                
                                });
                            }    
                            
                            function agc_addToShowList(intLayoutID)
                            {
                                if (($.inArray(intLayoutID,arrRevealed) === -1) 
                                                        && ($("#layout-holder-"+intLayoutID).css("display") === 'none'))
                                                {
                                                    agc_console('displaying:'+"#layout-holder-"+intLayoutID);
                                                    
                                                    
                                                    arrRevealed.push(intLayoutID);
                                                }
                            }
                            
                            function agc_addToHideList(intLayoutID)
                            {
                                if (($.inArray(intLayoutID,arrHidden) === -1) 
                                                        && ($("#layout-holder-"+intLayoutID).css("display") !== 'none'))
                                                {
                                                    agc_console('hiding:'+"#layout-holder-"+intLayoutID);
                                                    //$("#layout-holder-"+intLayoutID).hide();
                                                    
                                                    agc_checkForNoneBlankInputs(intLayoutID);
                                                    
                                                    arrHidden.push(intLayoutID);
                                                }
                            }
                            
                            
                            
                            
                            function agc_HideLayouts()
                            {
                                
                                
                                agc_console('+++++ DELETING ANY DATA');
                                
                                suppressVisibilityFunction = true;
                                
                                agc_console('+++++ HIDING LAYOUTS');
                                
                                $.each(arrHidden, function(hide_index, layout_id){
                                    agc_console('    + HIDING LAYOUT WITH ID:'+layout_id);
                                    $('#layout-holder-'+layout_id).hide();
                                    
                                });
                                
                                $.each(arrNotBlank, function (index,inputObject){
                                    if (inputObject.attr('data-no-delete') === '0')
                                    {
                                        agc_console('    + DELETING input.val() with name:'+inputObject.attr('name'));
                                        agc_clearInputValue(inputObject.attr('name'));
                                    }
                                    
                                    
                                });
                                if(!$('#agc-form').data('bootstrapValidator') == undefined){
									$('#agc-form').data('bootstrapValidator').disableSubmitButtons(false);
								}
                                suppressVisibilityFunction = false;
                                
                                arrHidden       = [];
                                arrNotBlank     = [];
                                arrRevealed     = [];
                                
                                agc_checkVisibilities();
                                booShowDialog = false;
                            }
                            
                            
                            function agc_ShowLayouts()
                            {
                                $.each(arrRevealed, function(reveal_index,intLayoutID)
                                {
                                                    $("#layout-holder-"+intLayoutID).show();
                                                    
                                                    $("#layout-holder-"+intLayoutID+" :input").filter(':not(.no-validate)').filter(':not(.chosen-select-text)').each(function(index,inputObject)
                                                    {
                                                        if ($(inputObject).hasClass('chosen-select'))
                                                        {
                                                            $(inputObject).chosen("destroy");
                                                            $(inputObject).chosen({allow_single_deselect: true});
                                                            agc_console("resetting chosen field ");
                                                        }
                                                        
                                                            inputName = $(inputObject).attr('name');
															
															if(!$('#agc-form').data('bootstrapValidator') == undefined){
																$('#agc-form')// Mark the field as not validated
																	.bootstrapValidator('updateStatus', inputName, 'NOT_VALIDATED');
															}
                                                            agc_console("resetting: "+inputName);
                                                            /*$('#agc-form')// Mark the field as not validated
                                                                    .data('bootstrapValidator')
                                                                    .resetField(inputName,true);*/
                                                        

                                                    });
                                                    
                                });
                            }
                            
                            
                            function agc_DialogConfirm()
                            {
                                // stop events causing the visibility updates
                                agc_console('DIALOG CONFIRM');
                                
                                
                                agc_ShowLayouts();
                                
                                agc_HideLayouts();
                                
                                
                                agc_console('DIALOG CONFIRM END');
                                dialogOnDisplay = false;
                                booShowDialog = false;
                                
                                arrHidden = [];
                                arrNotBlank = [];
                                arrRevealed = [];
                                
                                agc_checkVisibilities();
                                
                                while (arrHidden.length > 0)
                                {
                                    agc_console('Looping');
                                    agc_HideLayouts();
                                    
                                }
                                
                                agc_console(fieldValues);
                            }
                            
                            
                            function restorePreviousValues(strNameOfControl)
                            {
                                agc_console('DIALOG RESTORE');
                                
                                agc_console('+++++ RESETTING DATA');
                                suppressVisibilityFunction = true;
                                objOldValues = fieldValues[(fieldValues.length - 2)];
                                agc_console('    + RESETTING input.val() with name:'+strNameOfControl+' to: '+objOldValues[strNameOfControl]);
                                
                                
                                agc_setInputValue(strNameOfControl,objOldValues[strNameOfControl]);
                                
                                suppressVisibilityFunction = false;
                                
                                arrHidden = [];
                                arrNotBlank = [];
                                arrRevealed = [];
                                fieldValues.push(objOldValues);
                                
                                agc_console('DIALOG RESTORE END');
                                dialogOnDisplay = false;
                                booShowDialog = false;
                                
                                agc_console(fieldValues);
                            }
                            
                            function displayQuestionGroups()
                            {
                                $(".qg-holder:not(.show-always)").each(function (index,QgHolderDiv)
                                    {
                                        var booSomethingVisible = false;
                                        var arrInputsToRevalidate = [];
                                        $(QgHolderDiv).find(".layout-holder").each(function (index_inner,LayoutHolder) 
                                        {
                                            if ($(LayoutHolder).css("display") !== 'none')
                                            {
                                                ///ljkhglkjbgldfkjbg
                                                booSomethingVisible = true;
                                                
                                                $.each(agc_getAllInputsIn($(LayoutHolder)),function (index_inputs,InputObject){
                                                    arrInputsToRevalidate.push($(InputObject).attr('id'));
                                                });
                                                
                                            }
                                        });
                                        if (!booSomethingVisible)
                                        {
                                            if ($(QgHolderDiv).css("display") !== 'none')
                                            {
                                                $(QgHolderDiv).hide();
                                            }
                                        }
                                        else
                                        {
                                            if ($(QgHolderDiv).css("display") === 'none')
                                            {
                                                $(QgHolderDiv).show();
                                                $.each(arrInputsToRevalidate, function(index,inputToRevalidate){
                                                    inputName = $('#'+inputToRevalidate).attr('name');
                                                            $('#agc-form')// Mark the field as not validated
                                                                .bootstrapValidator('updateStatus', inputName, 'NOT_VALIDATED');
                                                            agc_console("resetting: "+inputName);
                                                });
                                            }
                                            
                                        }
                                    });
                            }
                            
                            function agc_SpawnLoader()
                            {
                                agc_console("spawned loader");
                                loaderHolder = setTimeout(function(){
                                   $('#agc_modal_loader').modal({
                                                                        show:       true,
                                                                        backdrop:   'static',
                                                                        keyboard:   false});
                                   loaderHolder=-1;
                                },1);
                            }
                            
                            function agc_RemoveLoader()
                            {
                                agc_console(loaderHolder);
                                if (loaderHolder !== -1)
                                {
                                    agc_console("cleared loader");
                                    clearTimeout(loaderHolder);
                                    loaderHolder = -1;
                                }
                                else
                                {
                                    agc_console("hid loader");
                                    $('#agc_modal_loader').modal('hide');
                                }
                                
                            }
                            
                            function agc_resetSliders()
                            {
                                if ($(".slider-selector").length !== 0)
                                {
                                    arrSliderValues = {};
                                    $(".slider-selector").each(function (index,inputSlider)
                                    {
                                        arrSliderValues[$(inputSlider).attr('name')] = $(inputSlider).slider('getValue');
                                    }
                                    );

                                    $(".slider-selector").slider('destroy');
                                    $(".slider-selector").slider({
                                        tooltip: 'always'
                                    });

                                    $(".slider-selector").each(function (index,inputSlider)
                                    {
                                        $(inputSlider).slider('setValue', arrSliderValues[$(inputSlider).attr('name')]);
                                    }
                                    );
                                    arrSliderValues = {};    
                                }
                            }
                            
                            function agc_EventHandler(eventTarget)
                            {
                                if (eventTarget !== false)
                                {
                                    
                                     
                                    var inputChangeObject = $(eventTarget);
                                    
                                    agc_console('EVENT FIRED ---------------'+eventTarget.id);
                                
                                            if (!suppressVisibilityFunction)
                                            {
                                                
                                                suppressVisibilityFunction = true;
                                                if ((inputChangeObject.attr('type') !== 'radio') || ((inputChangeObject.attr('type') === 'radio')&& !agc_getInputBlank($(eventTarget))))
                                                {
                                                    agc_SpawnLoader();
                                                    newValues = {};
                                                    $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                                        //agc_console($(jqObject));

                                                        newValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                                    });
                                                    fieldValues.push(newValues);
                                                    
                                                    agc_checkVisibilities();
                                                    agc_RemoveLoader(); 
                                                        if (!booShowDialog)
                                                        {
                                                            agc_ShowLayouts();
                                                            $.each(arrHidden, function(index,layout_id) {
                                                                $('#layout-holder-'+layout_id).hide();
                                                            });
                                                            displayQuestionGroups();
                                                            arrHidden = [];
                                                            arrNotBlank = [];
                                                            arrRevealed = [];
                                                        }
                                                        else
                                                        {
                                                            if (!dialogOnDisplay)
                                                            {
                                                                
                                                                dialogOnDisplay = true;
                                                                $('#agc_modal_dialog').modal({
                                                                        show:       true,
                                                                        backdrop:   'static',
                                                                        keyboard:   false});
                                                                $('#agc_modal_dialog_reset').click(
                                                                    function ()
                                                                    {
                                                                        restorePreviousValues(inputChangeObject.attr('name'));
                                                                        displayQuestionGroups();
                                                                        $('#agc_modal_dialog_reset').unbind('click');
									$('#agc_modal_dialog_proceed').unbind('click');	
                                                                    });
                                                                $('#agc_modal_dialog_proceed').click(
                                                                    function ()
                                                                    {
                                                                        agc_DialogConfirm();
                                                                        displayQuestionGroups();
                                                                        $('#agc_modal_dialog_reset').unbind('click');
									$('#agc_modal_dialog_proceed').unbind('click');	
                                                                    });
                                                            }
                                                        }
                                                        agc_resetSliders();
                                                        
                                                    
                                                    
                                                    
                                                }
                                                else
                                                {
                                                    agc_console("+++++ Visibility Function Suppressed due to undefined value.");
                                                }
                                                suppressVisibilityFunction = false;
                                            }
                                            else
                                            {
                                                agc_console("+++++ Visibility Function Suppressed due to suppression boolean set.");
                                            }
                                            $('#agc-form').data('bootstrapValidator').disableSubmitButtons(false);
                                            
                                            agc_console(":input.change complete: "+eventTarget.id);
                                            /*newValues = {};
                                            $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                                //agc_console($(jqObject));

                                                newValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                            });
                                            fieldValues.push(newValues);*/
                                            agc_console(fieldValues);
                                }
                                else
                                {
                                    agc_console('FIRST RUN ---------------');
                                    
                                    
                                    newValues = {};
                                    $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                        //agc_console($(jqObject));

                                        newValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                    });
                                    fieldValues.push(newValues);
                                    
                                    /*$('#agc-form :input').filter(':not(:visible)').each(function(index,jqObject){
                                        inputObject = $(jqObject);
                                        agc_console('element found:'+inputObject.attr('name'));
                                        
                                        inputObjectTag = inputObject.prop("tagName").toLowerCase();
                                        
                                        if (    (inputObject.attr('data-no-delete') === '0') &&
                                                ((inputObjectTag !== 'input')
                                                || (inputObject.attr('type') !== 'hidden'))
                                            )
                                        {
                                            agc_console('deleting: '+inputObject.attr('name'));
                                            agc_clearInputValue(inputObject.attr('name'));
                                        }
                                    });*/ 
                                    
                                    
                                    
                                    agc_checkVisibilities();
                                    agc_DialogConfirm();
                                    displayQuestionGroups();
                                    agc_resetSliders();
                                    arrHidden = [];
                                    arrNotBlank = [];
                                    arrRevealed = [];
                                    
                                    if ((intValidationState !== 0) || ($('#agc-form :input').length < 5))
                                    {
                                        agc_console('inputcount2:'+$('#agc-form :input').length);
                                        if ($('#agc-form :input').length >= 5)
                                        {
                                            $('#agc-form').bootstrapValidator('validate');
                                        }
                                        else
                                        {
                                            $('#btnFormSubmitNext').removeAttr('disabled');
                                        }
                                    }
                                    else
                                    {
                                        agc_console('inputcount:'+$('#agc-form :input').length);
                                    }
                                    $('#agc-form').data('bootstrapValidator').disableSubmitButtons(false);
                                    
                                    agc_console('FIRST RUN END ---------------');
                                    
                                    if (booAuditReadOnly)
                                    {
                                        $('button.drug-other, button.drug-addition, button.drug-amendment').attr('disabled', true);
                                        $('input, select, textarea').attr('disabled', true);
                                    }   
                                        
                                    
                                    
                                }
                            }
                            
                            $(document).ready(
                                
                                
                                function()
                                {
                                    
                                    
                                    
                                    $('input, select').on('keydown', function(e) {
                                        if (e.keyCode == 13) {
                                            $(e.target).change();
                                            return false;
                                        }
                                    });
                                    
                                    $('ul.bus-nav li.inactive a').tooltip();
                                    //agc_console(visibilityEvents);
                                    agc_console(fieldValues);
                                    
                                    displayQuestionGroups();
                                    
                                    
                                   // $('.chosen-select').chosen({allow_single_deselect: true});
                                   
                                    $(".slider-selector").slider();
                                    $(".slider-handle").click();
                                    
                                    $('.input-group.date.months').datepicker({
                                                        format: "mm/yyyy",
                                                        startView: 2,
                                                        minViewMode: 1,
                                                        autoclose: true
                                                    });
                                     $('.input-group.date.days').datepicker({
                                                                        format: "dd/mm/yyyy",
                                                                        startView: 2,
                                                                        autoclose: true
                                                                    });             
                                    
                                    /*
                                    $('.input-group.date.months').each(function(index,jqObject)
                                         {   
                                             
                                                if ($(jqObject).attr('data-date-start-date') !== '')
                                                {
                                                    arrStartDate = $(jqObject).attr('data-date-start-date').split('/');
                                                    objStartDate = new Date(arrStartDate[1],arrStartDate[0]);
                                                    arrEndDate = $(jqObject).attr('data-date-end-date').split('/');
                                                    objEndDate = new Date(arrEndDate[1],arrEndDate[0]);
                                                    
                                                    
                                                    $(jqObject).datepicker({
                                                        format: "mm/yyyy",
                                                        startView: 2,
                                                        minViewMode: 1,
                                                        startDate: objStartDate,
                                                        endDate: objEndDate, 
                                                        autoclose: true
                                                    });
                                                }
                                                else
                                                {
                                                    $(jqObject).datepicker({
                                                        format: "mm/yyyy",
                                                        startView: 2,
                                                        minViewMode: 1,
                                                        autoclose: true
                                                    });
                                                }
                                                
                                         });
                                    
                                    $('.input-group.date.days').each(function(index,jqObject)
                                    {   
                                            if ($(jqObject).attr('data-date-start-date') !== '')
                                            {
                                                arrStartDate = $(jqObject).attr('data-date-start-date').split('/');
                                                    objStartDate = new Date(arrStartDate[1],arrStartDate[0]);
                                                    arrEndDate = $(jqObject).attr('data-date-end-date').split('/');
                                                    objEndDate = new Date(arrEndDate[2],arrEndDate[1],arrEndDate[0]);
                                                
                                                $(jqObject).datepicker({
                                                                        format: "dd/mm/yyyy",
                                                                        startView: 2,
                                                                        startDate: objStartDate,
                                                                        endDate: objEndDate, 
                                                                        autoclose: true
                                                                    });
                                            }
                                            else
                                            {
                                                $(jqObject).datepicker({
                                                                        format: "dd/mm/yyyy",
                                                                        startView: 2,
                                                                        autoclose: true
                                                                    });
                                            }
                                    });
                                   */
                                    
                                    originalValues = {};
                                    
                                    $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                        //agc_console($(jqObject));
                                        if ($(jqObject).hasClass('chosen-select-text'))
                                        {
                                            $(jqObject).addClass('no-validate');
                                        }
                                        else
                                        {
                                            originalValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                            
                                        }
                                    });
                                    fieldValues.push(originalValues);
                                    
                                    
                                    attachValidator();
                                    
                                     
                                    $("#agc-form select").change(
                                        function(event)
                                        {
                                            agc_EventHandler(event.target);
                                        });
                                            
                                    
                                    $("#agc-form input").filter(':not(:checkbox)').change(
                                        function(event)
                                        {
                                            agc_console('****FIRED FROM :input :not(:checkbox)');
                                            agc_EventHandler(event.target);
                                        });
                                        
                                        $('div.form-group:has(div.slider)').addClass('slider-container');
                                        
                                        
                                        
                                    
                                        
                                    agc_EventHandler(false);
                                    
                                    if ((strScreenName === 'MRNew')  || (strScreenName === 'CMNew2')) { 
                                        agcAddStoredAmendments();
                                    }
                            
                                     
                                    }
                                );


