<?php
    $arrJSFieldValidation = array();
    $arrTempVisibilitiesList = array();

    function getJSForValueOfControl($strControlName)
    {
        return "agc_getInputValue($(\":input[name='".$strControlName."']\"))";
    }
    
    




    function agcYearsToDate($intFirstYear)
    {
        $arrYears = array();
       
        
        for ($i = (int)date('Y');$i >= (int)$intFirstYear; $i--)
        {
            
            $arrYears[$i] = $i;
        }
        
        
        return $arrYears;
    }




    function buildDropDown($objField,$booVertical = false)
    {
        $strHtml = "";
        
        
        $arrOptions = array();
        
        if (($objField->ValidationMode !== "") && ($objField->ValidationMode === 'Func'))
        {
            $arrFuncParams = explode('(',substr($objField->ValidationString, 0, -1));
            if (function_exists($arrFuncParams[0]))
            {
                $arrOptions = call_user_func($arrFuncParams[0],$arrFuncParams[1]);
            }
            else
            {
                echo "Function ".$arrFuncParams[0]." does not exist!";
            }
        }
        else
        {
            $arrKeys = str_getcsv($objField->ValidationString);
            $arrLabels = str_getcsv($objField->LabelString);
            foreach ($arrKeys as $key => $option)
            {
                $arrOptions[$option] = $arrLabels[$key];
            }
        }
        
        $arrOptions[''] = "Please Choose One...";
        
        return form_dropdown($objField->DbFieldName,$arrOptions,'','class = "form-control" id = "'.$objField->DbFieldName.'"')."\r\n";
    }

    function buildRadio($objField,$booVertical = false)
    {
        $strHtml = "";
        $intCount = 0;
        $arrLabels = str_getcsv($objField->LabelString);
        foreach(str_getcsv($objField->ValidationString) as $strOption)
        {
            
            $arrRadioData = array(
                'name'        => $objField->DbFieldName,
                'id'          => $objField->DbFieldName."_".$intCount,
                'value'       => $strOption,
                'class'       => 'agc_icheck',
                'checked'     => FALSE
            );
            if ($booVertical) { 
                                $strHtml .= "<div class='radio agc_radiovert'>"; 
                                $arrRadioData['class'] = 'agc_radiovert agc_icheck';
            }
            
            $strHtml .= "<label";
            if (!$booVertical)
            {
                $strHtml .= " class='radio-inline'";
            }
            $strHtml .= ">".form_radio($arrRadioData)." ".$arrLabels[$intCount]."</label>";
            if ($booVertical) { $strHtml .= "</div>"; }
            $intCount++;
        }
        return $strHtml;
    }

    function buildCheckbox($objField)
    {
        $strFieldName = $objField->DbFieldName;
        if ($objField->ValidationMode === 'Group')
        {
            $strFieldName = $objField->ValidationString."[]";
        }
        
        $arrCheckBoxData = array(
            'name'        => $strFieldName,
            'class'       => 'agc_icheck',
            'id'          => $objField->DbFieldName,
            'value'       => "1",
            'checked'     => FALSE,
            'style'       => 'margin:10px;',
        );
        return "<label class='checkbox-inline'>".form_checkbox($arrCheckBoxData)." ".$objField->LabelString."</label>\r\n";
    }
    
    function buildNumberSlider($objField)
    {
        $arrMinMax = $arrLabels = explode("-", $objField->ValidationString);
        if ($objField->LabelString != "")
        {
            $arrLabels = str_getcsv($objField->LabelString);
        }
        
        $arrInputData = array(
            'name'              => $objField->DbFieldName,
            'id'                => $objField->DbFieldName,
            'value'             => "",
            'data-slider-id'    => "slider-".$objField->DbFieldName,
            'data-slider-min'   => $arrMinMax[0],
            'data-slider-max'   => $arrMinMax[1],
            'data-slider-step'  => 1,
            'data-slider-value' => 0,
            'class'             => 'slider-selector',
        );
        return "<span class='agc-slider-selector-spacing-right'>".$arrLabels[0]."</span>".form_input($arrInputData)."<span class='agc-slider-selector-spacing-left'>".$arrLabels[1]."</span>"."\r\n";
    }
    
    function buildDatePicker($objField)
    {
        $strDatePickerFormat        = "DD/MM/YYYY";
        $strDatePickerClass         = "dmy";
        if ($objField->ControlType === 'DatePickerMonth')
        {
            $strDatePickerFormat    = "MM/YYYY";
            $strDatePickerClass     = "my";
        }
        
        
        $strReturn = "<div class=\"input-group date ";
        
        if($objField->ControlType === 'DatePickerMonth')
        {
            $strReturn .= " months\"";
        }
        else
        {
            $strReturn .= " days\"";
        }
                
        $strReturn .= ">
                        <input class=\"form-control\" type=\"text\" name=\"".$objField->DbFieldName."\" id=\"".$objField->DbFieldName."\" value=\"\">
                        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-calendar\"></i></span>
                    </div>";
        return $strReturn;
    }
    
    function buildTextbox($objField)
    {
        $arrInputData = array(
            'name'        => $objField->DbFieldName,
            'class'       => 'form-control',
            'id'          => $objField->DbFieldName,
            'value'       => ""
        );
        
        if ($objField->ValidationMode === "Calc")
        {
            $arrInputData['disabled'] = 'disabled';
        }
        
        return form_input($arrInputData)."\r\n";
    }

    function buildTextArea($objField)
    {
        $arrInputData = array(
            'name'        => $objField->DbFieldName,
            'class'       => 'form-control',
            'id'          => $objField->DbFieldName,
            'value'       => ""
        );
        return form_textarea($arrInputData)."\r\n";
    }

    function buildField($objField)
    {
        switch ($objField->ControlType)
        {
            case "RadioButton":
                return buildRadio($objField);
                break;
            case "RadioButtonVert":
                return buildRadio($objField,true);
                break;
            case "CheckBox":
                return buildCheckbox($objField);
                break;
            case "DropDown":
                return buildDropDown($objField);
                break;
            case "TextBox":
                return buildTextbox($objField);
                break;
                /*switch (substr($objField->DbDataType, 0, 3))
                {
                    case 'Int':
                        return buildNumberSlider($objField);
                        break;
                    case 'Var':
                        return buildTextbox($objField);
                        break;
                    default :
                        return '';
                        break;
                }
                break;*/
            case "TextBoxSlider":
                return buildNumberSlider($objField);
                break;
            case "TextArea":
                return buildTextArea($objField);
                break;
            case "DatePickerDay":
            case "DatePickerMonth":
                return buildDatePicker($objField);
                break;
            default:
                return "";
                break;
        }
    }
    
    //$arrJSFieldValidation
    function completeFieldValidationSetup($objField,$arrJSFieldValidation)
    {
        if ($objField->Required === 'Y')
        {
            $arrJSFieldValidation[$objField->DbFieldName]['notEmpty']['message'] = "'You cannot leave this field empty.'";
        }
        else
        {
            if (($objField->DbDataType !== 'Binary') || (($objField->DbDataType === 'Binary') && ($objField->ValidationMode !== "Group")))
            {
                    $arrJSFieldValidation[$objField->DbFieldName]= array();
            }
        }
        
        switch (substr($objField->DbDataType, 0, 3))
        {
            case 'Int':
                if ($objField->ValidationMode === "Range")
                {
                    $arrJSFieldValidation[$objField->DbFieldName]['integer']['message'] = "'You must enter a whole number.'";
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrMinMax = explode('-',$objField->ValidationString);
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['message'] = "'You must enter a number between ".$arrMinMax[0]." and ".$arrMinMax[1].".'";
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['min'] = $arrMinMax[0];
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['max'] = $arrMinMax[1];
                    }
                }
                break;
            case 'Flo':
                if ($objField->ValidationMode === "Range")
                {
                    $arrJSFieldValidation[$objField->DbFieldName]['numeric']['message'] = "'You must enter a number, which can contain a decimal point.'";
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrMinMax = explode('-',$objField->ValidationString);
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['message'] = "'You must enter a number between ".$arrMinMax[0]." and ".$arrMinMax[1].".'";
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['min'] = $arrMinMax[0];
                        $arrJSFieldValidation[$objField->DbFieldName]['between']['max'] = $arrMinMax[1];
                    }
                }
                break;
            case 'Var':
            case 'Cha':
                if ($objField->ValidationMode === "Regex")
                {
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrJSFieldValidation[$objField->DbFieldName]['regexp']['message'] = "'".$objField->ValidationText."'";
                        $arrJSFieldValidation[$objField->DbFieldName]['regexp']['regexp'] = $objField->ValidationString;
                    }
                }
                
                $intNumberOfChars = (int) substr(explode('(',$objField->DbDataType)[1],0,-1);
                $arrJSFieldValidation[$objField->DbFieldName]['stringLength']['message'] = "'Your input must be less than ".$intNumberOfChars." characters long.'";
                $arrJSFieldValidation[$objField->DbFieldName]['stringLength']['max'] = $intNumberOfChars;
                break;
            case 'Bin':
                if ($objField->ValidationMode === "Group")
                {
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrJSFieldValidation[$objField->ValidationString."[]"]['choice']['message'] = "'You must select ".$objField->RequiredNumber." of these.'";
                        $arrJSFieldValidation[$objField->ValidationString."[]"]['choice']['min'] = $objField->RequiredNumber;
                        $arrJSFieldValidation[$objField->ValidationString."[]"]['choice']['max'] = $objField->RequiredNumber;
                    }
                }
                break;
            default:
                break;
        }
        
        return $arrJSFieldValidation;
    }
    
    

    function generateJSValidationString($arrJSFieldValidation)
    {
        $strOutput = "";
       
        $arrFields = array();
        foreach ($arrJSFieldValidation as $strFieldName=>$arrValidations)
        {
            $strField = "'".$strFieldName."': {
                                            message: 'This field is invalid.',
                                            validators: {";
            $arrValidationRules = array();
            foreach ($arrValidations as $strValidationName=>$arrAttributes)
            {
                $strValidationSetup = $strValidationName.": {";
                $arrAttributeLines = array();
                foreach($arrAttributes as $strAttributeName => $strAttributeValue)
                {
                    $arrAttributeLines[] = $strAttributeName.": ".$strAttributeValue;
                }
                
                
                $strValidationSetup .= implode(',',$arrAttributeLines)."}";
                $arrValidationRules[] = $strValidationSetup;
            }
            $strField .= implode(',',$arrValidationRules)."
                                } 
                          }";
            $arrFields[] = $strField;
        }
        $strOutput = implode(',',$arrFields);
        return $strOutput;
    }
    
    $strJSFieldValidation = "";
    $strJSCalculations = "";
?>



<script type="text/javascript">
    var eventList = {};
    
    
    
    
    
</script>


            <form id="agc-form" role="form"> 
    <?php
    $strJS_EventList = "";
        foreach ($objQgp->questiongroups as $objQuestionGroup)
        {
            ?>
        <div class="panel panel-default qg-holder<?php if ($objQuestionGroup->QuestionGroupCssClass != '') { echo " ".$objQuestionGroup->QuestionGroupCssClass; }?>" id="qg-holder-<?php echo $objQuestionGroup->QuestionGroupID; ?>">
                <div class="panel-heading">
                    <h3><?php echo $objQuestionGroup->QuestionGroupDisplayText; ?></h3>
                </div>
                <div class="panel-body">
            <?php
            
            
            
            foreach ($objQuestionGroup->layouts as $intRow => $arrRow)
            {
                
                
                
                
                ?>
                <div class="row" id="qg_row_<?php echo $objQuestionGroup->QuestionGroupID."_".$intRow; ?>">
                <?php
                    foreach ($arrRow as $objLayout)
                    {
                        
                        
                        ?>
                    <div id="layout-holder-<?php echo $objLayout->LayoutID; ?>" class="col-md-<?php 
                        echo $objLayout->SpanWidth; 
                        if ($objLayout->SpanOffset !== NULL)
                        {
                            echo " col-md-offset-".$objLayout->SpanOffset;
                        }
                        if ($objLayout->LayoutCssClass !== NULL)
                        {
                            echo " ".$objLayout->LayoutCssClass;
                        }
                        ?> layout-holder<?php
                        if (($objLayout->ContentType === 'Question') && ($objQuestionGroup->questions[$objLayout->ContentID]->QuestionCssClass != ''))
                        {
                            echo " ".$objQuestionGroup->questions[$objLayout->ContentID]->QuestionCssClass;
                        }
                        ?>" <?php if (count($objLayout->visibilities)>0) {
                        ?>style="display:none;"<?php
                    }
                        ?>>
                        <?php
                        
                        
                        
                        
                        switch($objLayout->ContentType)
                        {
                            case "Text":
                                $objText = $objQuestionGroup->texts[$objLayout->ContentID];
                                switch ($objText->TextType)
                                {
                                    case "Raw":
                                        if ($objText->TextCssCLass != '')
                                        {
                                        echo "<span style='".$objText->TextCssCLass."'>".$objText->Text."</span>";
                                        }
                                        else
                                        {
                                            echo $objText->Text;
                                        }
                                        break;
                                    case "Alert-Dismissable":
                                        echo "<div class=\"alert ".$objText->TextCssCLass." alert-dismissible\" role=\"alert\">
  <button type=\"button\" class=\"close no-validate\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>".$objText->Text."</div>";
                                        break;
                                    case "Alert":
                                        echo "<div class=\"alert ".$objText->TextCssCLass."\" role=\"alert\">".$objText->Text."</div>";
                                        break;
                                    default:
                                        echo $objText->Text;
                                        break;
                                }
                                     
                                break;
                            case "Question":
                                echo $objQuestionGroup->questions[$objLayout->ContentID]->QuestionDisplayText;
                                break;
                            case "Field":
                                $objField = $objQuestionGroup->fields[$objLayout->ContentID];
                                if ($objField->ValidationMode === "Group")
                                {
                                    $objField->RequiredNumber = $objQuestionGroup->questions[$objField->QuestionID]->ReqNumber;
                                }
                                
                                $arrJSFieldValidation = completeFieldValidationSetup($objField,$arrJSFieldValidation);
                                $arrTempVisibilitiesList['fields'][$objField->DbFieldName] = array();
                                
                                if ($objField->ValidationMode === "Calc")
                                {
                                    $strJSCalculations .= "".$objField->ValidationString."\r\n";
                                }
                                
                                foreach($objField->visibilities as $objVisibility)
                                {
                                    
                                    $arrTempVisibilitiesList['fields'][$objField->DbFieldName][$objVisibility->Operator]['data_type'] = $objField->DbDataType;
                                    
                                    $arrTempVisibilitiesList['fields'][$objField->DbFieldName][$objVisibility->Operator]['comparisons'][$objVisibility->Criteria][] 
                                            = $objVisibility->LayoutID;
                                           
                                }
                                
                                if ($objField->ValidationMode !== "Group")
                                {
                                    echo "<div class=\"form-group\">".buildField($objField)."</div>";
                                }
                                else
                                {
                                    echo buildField($objField);
                                }
                                
                                break;
                        }

                        
                        ?>
                    </div>
                        <?php

                        if (count($objLayout->visibilities)>0) 
                        {



                                        $arrVisibilityCriteria = array();

                                        foreach ($objLayout->visibilities as $objVisibility)
                                        {
                                            $objField = $objQgp->questiongroups[$objVisibility->QuestionGroupID]->fields[$objVisibility->FieldID];
                                            $strDBFieldName = $objField->DbFieldName;
                                            $strControlType = $objField->ControlType;
                                            $strDataType    = $objField->DbDataType;

                                            $strCriterion = "";
                                            if (($objField->DbDataType !== 'Binary') || (($objField->DbDataType === 'Binary') && ($objField->ValidationMode !== "Group")))
                                            {
                                                if ($objVisibility->Criteria != 'null') {
                                                    switch (substr($strDataType,0,3))
                                                    {
                                                        case "Var":
                                                        case "Bin":
                                                        case "Cha":
                                                            $strCriterion .= "'".$objVisibility->Criteria."'";
                                                            break;
                                                        default :
                                                            $strCriterion .= $objVisibility->Criteria;
                                                    }
                                                }
                                                else
                                                {
                                                    $strCriterion = "null";
                                                }
                                                $arrVisibilityCriteria[] = "(".getJSForValueOfControl($strDBFieldName).$objVisibility->Operator." ".$strCriterion.")";
                                            }
                                            else
                                            {
                                                if ($objVisibility->Operator === '==')
                                                {
                                                    $arrVisibilityCriteria[] = "$('#".$strDBFieldName."').is(':checked')";
                                                }
                                                else
                                                {
                                                    $arrVisibilityCriteria[] = "$('#".$strDBFieldName."').is(':unchecked')";
                                                }
                                            }
                                            
                                        }

                                        $strJS_EventList .= "
                                                                if (".implode($objLayout->VisabilityLogic,$arrVisibilityCriteria).")
                                                                {
                                                                    agc_addToShowList(".$objLayout->LayoutID.");
                                                                }
                                                                else
                                                                {
                                                                    agc_addToHideList(".$objLayout->LayoutID.");
                                                                }
                                                                ";
                                        
                                        
                                        
                        }
                        
                    }
                ?>
                </div>
                <?php
            }
            ?>
                </div>
        </div>
            <?php

        }
    ?>
                </form>
        
<?php
                                echo "<!--\r\n";
                                var_dump(json_encode($arrTempVisibilitiesList));
                                echo "\r\n-->";
                                
                                
?>
<script type="text/javascript">
                            
                            <?php echo "var visibilityEvents = ".json_encode($arrTempVisibilitiesList).";" ?>
                            var showConsole = true;
                            var fieldValues = [];
                            
                            var arrRevealed = [];
                            var arrHidden   = [];
                            var booShowDialog = false;
                            var arrNotBlank = [];
                            
                            var dialogOnDisplay = false;
                            
                            var suppressVisibilityFunction = false;
                            
                            
                            function agc_console(strInput)
                            {
                                if (showConsole)
                                {
                                    console.log(strInput);
                                }
                            }
                            
                            function attachValidator()
                            {
                                <?php 
                                
                                
                                
                                echo "$('#agc-form').bootstrapValidator({
                                    message: 'This value is not valid',
                                    feedbackIcons: {
                                        valid:          'glyphicon glyphicon-ok',
                                        invalid:        'glyphicon glyphicon-remove',
                                        validating:     'glyphicon glyphicon-refresh'
                                    },
                                     fields: {
                                            ".generateJSValidationString($arrJSFieldValidation)."
                                    }
                                }).find('input.agc_icheck')
                                    // Init iCheck elements
                                    .iCheck({
                                        checkboxClass:  'icheckbox_square-purple',
                                        radioClass:     'iradio_square-purple',
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
                                            .bootstrapValidator('validateField', field);
                                        
                                    });"; 
                               
                                
                                
                                ?>
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
                                        inputObject.children().filter(':selected').prop('selected',false);
                                        inputObject.children().filter('[value="-1"]').prop('selected',true);
                                        break;
                                }
                                agc_console(inputObject.val());
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
                            
                            function agc_checkVisibilities()
                            {
                                <?php echo $strJS_EventList; ?>
                                        
                                agc_console(arrRevealed);
                                
                                agc_console(arrHidden);
                            }
                            
                            
                            function agc_HideLayouts()
                            {
                                
                                
                                agc_console('+++++ DELETING ANY DATA');
                                
                                suppressVisibilityFunction = true;
                                $.each(arrNotBlank, function (index,inputObject){
                                    agc_console('    + DELETING input.val() with name:'+inputObject.attr('name'));
                                    agc_clearInputValue(inputObject.attr('name'));
                                });
                                suppressVisibilityFunction = false;
                                agc_console('+++++ HIDING LAYOUTS');
                                $.each(arrHidden, function(hide_index, layout_id){
                                    agc_console('    + HIDING LAYOUT WITH ID:'+layout_id);
                                    $('#layout-holder-'+layout_id).hide();
                                });
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
                                                    
                                                    $("#layout-holder-"+intLayoutID+" :input").filter(':not(.no-validate)').each(function(index,inputObject)
                                                    {
                                                        
                                                        inputName = $(inputObject).attr('name');
                                                        $('#agc-form')// Mark the field as not validated
                                                            .bootstrapValidator('updateStatus', inputName, 'NOT_VALIDATED');
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
                                $(".qg-holder").each(function (index,QgHolderDiv)
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
                            
                            function agc_EventHandler(eventTarget)
                            {
                                var inputChangeObject = $(eventTarget);
                                            
                                            agc_console('EVENT FIRED ---------------'+eventTarget.id);
                                            
                                            if (!suppressVisibilityFunction)
                                            {
                                                suppressVisibilityFunction = true;
                                                if ((inputChangeObject.attr('type') !== 'radio') || ((inputChangeObject.attr('type') === 'radio')&& !agc_getInputBlank($(eventTarget))))
                                                {
                                                    newValues = {};
                                                    $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                                        //agc_console($(jqObject));

                                                        newValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                                    });
                                                    fieldValues.push(newValues);
                                                    
                                                    agc_checkVisibilities();
                                                    
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
                                            
                                            
                                            agc_console(":input.change complete: "+eventTarget.id);
                                            agc_console(fieldValues);
                            }
                            
                            $(document).ready(
                                function()
                                {
                                    originalValues = {};
                                    $('#agc-form :input').filter(':not(.no-validate)').each(function(index,jqObject){
                                        //agc_console($(jqObject));
                                        originalValues[$(jqObject).attr('name')] = agc_getInputValue($(jqObject));
                                    });
                                    fieldValues.push(originalValues);
                                    attachValidator();
                                    //agc_console(visibilityEvents);
                                    agc_console(fieldValues);
                                    
                                    displayQuestionGroups();
                                    
                                    $(".slider-selector").slider({
                                        tooltip: 'always'
                                    });
                                    
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
                                        
                                    }
                                );

                            <?php
                            echo $strJSCalculations;
                            ?>
                            
                         
                    </script>


<div class="modal fade" id="agc_modal_dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="agc_modal_title">Are you sure?</h4>
      </div>
      <div class="modal-body" id="agc_modal_body">
        <p>That change made some answers disappear.  Would you like to confirm this and remove the answers, or undo and reinstate them?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="agc_modal_dialog_proceed" data-dismiss="modal"><span class="glyphicon glyphicon-exclamation-sign"></span> Confirm</button>
        <button type="button" class="btn btn-primary" id="agc_modal_dialog_reset" data-dismiss="modal"><span class="glyphicon glyphicon-refresh"></span> Undo</button>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->