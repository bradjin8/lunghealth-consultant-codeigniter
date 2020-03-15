<?php
    $arrJSFieldValidation = array();
    $arrTempVisibilitiesList = array();
    
    function agc_calc_SmokingYears($objReview,$strBlank)
    {
        
        $arrDate = explode('/',$objReview->InitialPatientDetails_DateOfBirth);

        $dateOfBirth = date_create(date('Y-m-d',mktime(0,0,0,(int) $arrDate[1],(int) $arrDate[0],(int) $arrDate[2])));
        //$dateOfBirth = date_create(date('Y-m-d',strtotime($objReview->InitialPatientDetails_DateOfBirth)));
        $today = date_create(date("Y-m-d"));
        //strtotime(InitialPatientDetails_DateOfBirth)
        
        
        
        $diff=date_diff($dateOfBirth,$today)->format("%y");

        $arrYears = array();
		
        for ($i = (int)0;$i <= (int)$diff - 5; $i++)
        {
            $arrYears[$i] = $i;
        }      
        
		//if(!$objReview->Smoking_NoYearsSmoked){
			return $arrYears;
		//}
		
    }
    

    function getJSForValueOfControl($strControlName)
    {
        return "agc_getInputValue($(\":input[name='".$strControlName."']\"))";
    }
    


    function agcYearsToDate($objReview,$intFirstYear)
    {
        $arrYears = array();
       
        
        for ($i = (int)date('Y');$i >= (int)$intFirstYear; $i--)
        {
            
            $arrYears[$i] = $i;
        }
        
        
        return $arrYears;
    }


    function agcDrugsTable(&$drugs_model,$intDrugType)
    {
        
        
        return $drugs_model->getValuesForDropDown($intDrugType);
        
        
    }
    
    


    function buildComboBox($objField,$objReview,&$arrModels)
    {
        $arrOptions = array();
        
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        $arrDataSource = explode(',',$objField->ValidationString);
        
        switch($arrDataSource[0])
        {
            case 'Drugs':
                $arrOptions = agcDrugsTable($arrModels['drugs_model'],$arrDataSource[1]);
        }
        
        $strOptions = '';
        foreach ($arrOptions as $strIndex => $mixContents)
        {
            $strOptions .= "<option";
            if ($strIndex === $strValueForForm)
            {
                $strOptions .= " selected=\"selected\" ";
            }
            if (is_array($mixContents))
            {
                $strOptions .= " value=\"".$strIndex."\" data-title=\"".$mixContents['title']."\">".$mixContents['label'];
            }
            else
            {
                $strOptions .= " value=\"".$strIndex."\">".$mixContents;
            }
            $strOptions .= "</option>\r\n";
        }
        
        return "<select name=\"".$objField->strFieldNameForForm."\" class=\"form-control chosen-select\" id = \"".$objField->strFieldNameForForm."\" data-no-delete=\"".$objField->DontRemoveContents."\">".$strOptions."</select>\r\n";
    }
    
    function buildDropDown($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        
        
        $strHtml = "";
        
        
        $arrOptions = array();
        
        if (($objField->ValidationMode !== "") && ($objField->ValidationMode === 'Func'))
        {
            $arrFuncParams = explode('(',substr($objField->ValidationString, 0, -1));
            if (function_exists($arrFuncParams[0]))
            {
                $arrOptions = call_user_func($arrFuncParams[0],$objReview,$arrFuncParams[1]);
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
        
        return form_dropdown($objField->strFieldNameForForm,$arrOptions,$strValueForForm,'class = "form-control" id = "'.$objField->strFieldNameForForm.'" data-no-delete="'.$objField->DontRemoveContents.'"')."\r\n";
    }

    function buildRadio($objField,$objReview,$booVertical = false)
    {
        
        
        
        $strHtml = "";
        $intCount = 0;
        echo "<!-- ";
        $mixValueSelected = null;
        if (property_exists($objReview, $objField->strFieldNameForForm) && ( $objReview->{$objField->strFieldNameForForm} != null))   
        {
                $mixValueSelected = $objReview->{$objField->strFieldNameForForm};
                echo " val:(".$objReview->{$objField->strFieldNameForForm}.") ";
        }
        
        
        $arrLabels = str_getcsv($objField->LabelString);
        foreach(str_getcsv($objField->ValidationString) as $strOption)
        {
            
            $arrRadioData = array(
                'name'        => $objField->strFieldNameForForm,
                'id'          => $objField->strFieldNameForForm."_".$intCount,
                'value'       => $strOption,
                'class'       => 'agc_icheck',
                'data-no-delete' => $objField->DontRemoveContents,
                'checked'    => false
            );
            
            if (($mixValueSelected != null) && ($mixValueSelected == $strOption))
            {
                
                echo " ".$mixValueSelected." == ".$strOption." ";
                $arrRadioData['checked'] = 'checked';
            }
            
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
        echo "-->";
        return $strHtml;
    }

    function buildCheckbox($objField,$objReview)
    {
        $booValueForForm = FALSE;
        if (($objReview->{$objField->strFieldNameForForm} != '') && ($objReview->{$objField->strFieldNameForForm} == 'Y'))
        {
            $booValueForForm = TRUE;
        }
        
        
        $strFieldName = $objField->strFieldNameForForm;
        /*if ($objField->ValidationMode === 'Group')
        {
            $strFieldName = $objField->ValidationString."[]";
        }*/
        
        $arrCheckBoxData = array(
            'name'        => $strFieldName,
            'class'       => 'agc_icheck',
            'id'          => $objField->strFieldNameForForm,
            'value'       => "Y",
            'checked'     => $booValueForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'style'       => 'margin:10px;',
        );
        return "<label class='checkbox-inline'>".form_checkbox($arrCheckBoxData)." ".$objField->LabelString."</label>\r\n";
    }
    
    
    function buildButton($objField,$objReview,$strRowRef)
    {
        
        
        
        $strFieldName = $objField->strFieldNameForForm;
        /*if ($objField->ValidationMode === 'Group')
        {
            $strFieldName = $objField->ValidationString."[]";
        }*/
        
        /*$arrButton = array(
            'name'        => $strFieldName,
            'class'       => 'agc_icheck',
            'id'          => $objField->strFieldNameForForm,
            'value'       => "Y",
            'checked'     => $booValueForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'style'       => 'margin:10px;',
        );*/
        
        
        if ($objField->ValidationMode === 'SpiroCapture')
        {
            return "<button type=\"button\" name=\"btn".$strFieldName."\" 
                                        id=\"btn".$strFieldName."\" 
                                        class=\"no-validate spiro-capture\" 
                                        value=\"true\" 
                                        onClick=\"javascript:$(this).superSpiroCapturePro();\"><span class='glyphicon glyphicon-plus-sign'></span> "
                                    .$objField->LabelString
                                    ."</button>\r\n";
        }
        
        
        if ($objField->ValidationMode === 'DrugReview')
        {
            $strDrugReferenceField = "CurrentMedication_Current".substr($objField->DbFieldName, 6)."NewDrug";
            $strUsageReferenceField = "CurrentMedication_Current".substr($objField->DbFieldName, 6)."NewUsage";
            
            $arrValidationString = explode('|',$objField->ValidationString);
            
            $strDrugFunction = '';
            $strUsageFunction = '';
            if (count($arrValidationString) > 3)
            {
                $strDrugFunction = $arrValidationString[3];
                $strUsageFunction = $arrValidationString[4];
            }
            
            return "<button type=\"button\" name=\"btn".$strFieldName."Change\" 
                                        id=\"btn".$strFieldName."Change\" 
                                        class=\"no-validate drug-amendment\" 
                                        data-agcdrugtype=\"".$strFieldName."\" 
                                        data-agcdrug=\"".$arrValidationString[2]."\" 
                                        data-agcrowid=\"".$strRowRef."\"
                                        data-agcbdpequiv=\"".$arrValidationString[0]."\"  
                                        data-agcoptions=\"".$arrValidationString[1]."\"
                                        data-agccurrentdrug=\"".$objReview->{$strDrugReferenceField}."\"
                                        data-agccurrentusage=\"".$objReview->{$strUsageReferenceField}."\"
                                        data-agcnewdrug=\"".$objReview->{$objField->strFieldNameForForm."NewDrug"}."\"
                                        data-agcnewusage=\"".$objReview->{$objField->strFieldNameForForm."NewUsage"}."\"
                                        data-agcchangetype=\"".$objReview->{$objField->strFieldNameForForm."ChangeType"}."\"
                                        data-agcmode=\"edit\"
                                        data-agcdrugfunc=\"".$strDrugFunction."\"
                                        data-agcusagefunc=\"".$strUsageFunction."\"
                                        value=\"true\" 
                                        onClick=\"javascript:agcAddDrugEditor(this,'edit');\">".$objField->LabelString."</button>\r\n";
        }
        
        if ($objField->ValidationMode === 'DrugCurrent')
        {
            //$strDrugReferenceField = "CurrentMedication_OnDrugs".substr($objField->DbFieldName, 10);
            //$strUsageReferenceField = "CurrentMedication_Usage".substr($objField->DbFieldName, 10);
            
            
            
            
            $arrValidationString = explode('|',$objField->ValidationString);
            
            $strDrugFunction = '';
            $strUsageFunction = '';
            if (count($arrValidationString) > 3)
            {
                $strDrugFunction = $arrValidationString[3];
                $strUsageFunction = $arrValidationString[4];
            }
            
            
            return "<button type=\"button\" name=\"btn".$strFieldName."Add\" 
                                        id=\"btn".$strFieldName."Add\" 
                                        class=\"no-validate drug-addition\" 
                                        data-agcdrugtype=\"".$strFieldName."\" 
                                        data-agcdrug=\"".$arrValidationString[2]."\" 
                                        data-agcrowid=\"".$strRowRef."\"
                                        data-agcbdpequiv=\"".$arrValidationString[0]."\"  
                                        data-agcoptions=\"".$arrValidationString[1]."\"
                                        data-agccurrentdrug=\"\"
                                        data-agccurrentusage=\"\"
                                        data-agcnewdrug=\"".$objReview->{$objField->strFieldNameForForm."NewDrug"}."\"
                                        data-agcnewusage=\"".$objReview->{$objField->strFieldNameForForm."NewUsage"}."\"
                                        data-agcchangetype=\"\"
                                        data-agcmode=\"add\"
                                        data-agcdrugfunc=\"".$strDrugFunction."\"
                                        data-agcusagefunc=\"".$strUsageFunction."\"
                                        value=\"true\" 
                                        onClick=\"javascript:agcAddDrugEditor(this,'add');\">".$objField->LabelString."</button>\r\n";
        }
        
        if ($objField->ValidationMode === 'DrugOther')
        {
            //$strDrugReferenceField = "CurrentMedication_OnDrugs".substr($objField->DbFieldName, 10);
            //$strUsageReferenceField = "CurrentMedication_Usage".substr($objField->DbFieldName, 10);
            
            $strOtherDrugsJSON = "<script> arrAGCOtherDrugsJSON = ".json_encode($objReview->arrOtherDrugs).";";
            
            if (count($objReview->arrOtherDrugs) > 0)
            {
                $strOtherDrugsJSON .= "$(document).ready(
                                function()
                                {
                                    agcAddOtherDrugEditor(document.getElementById(\"btn".$strFieldName."Set\"));
                                }
                                );";
            }
            
            $strOtherDrugsJSON .= "</script>";
            
            return "<button type=\"button\" name=\"btn".$strFieldName."Set\" 
                                        id=\"btn".$strFieldName."Set\" 
                                        class=\"no-validate drug-other\" 
                                        data-agcotherdrugstorage=\"".$strFieldName."Data\"
                                        data-agcfieldbasename=\"".$strFieldName."\"
                                        data-agcrowid=\"".$strRowRef."\"
                                        data-agcotherdrugdata=\"".$objReview->{$objField->strFieldNameForForm."Data"}."\"
                                        data-reviewid=\"".$objReview->intReviewID."\"
                                        value=\"true\" 
                                        onClick=\"javascript:agcAddOtherDrugEditor(this);\">".$objField->LabelString."</button>\r\n".$strOtherDrugsJSON."\r\n";
            
                                        
        }
    }
    
    
    function buildNumberSlider($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        $arrMinMax = $arrLabels = explode("-", $objField->ValidationString);
        if ($objField->LabelString != "")
        {
            $arrLabels = str_getcsv($objField->LabelString);
        }
        
        $arrInputData = array(
            'name'              => $objField->strFieldNameForForm,
            'id'                => $objField->strFieldNameForForm,
            
            'data-slider-id'    => "slider-".$objField->strFieldNameForForm,
            'data-slider-min'   => $arrMinMax[0],
            'data-slider-max'   => $arrMinMax[1],
            'data-slider-step'  => 1,
            'data-slider-value' => $strValueForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'class'             => 'slider-selector',
        );
        return "<span class='agc-slider-selector-spacing-right'>".$arrLabels[0]."</span>".form_input($arrInputData)."<span class='agc-slider-selector-spacing-left'>".$arrLabels[1]."</span>"."\r\n";
    }
    
    function buildDatePicker($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        
        $strDatePickerFormat        = "DD/MM/YYYY";
        $strDatePickerClass         = "dmy";
        if ($objField->ControlType === 'DatePickerMonth')
        {
            $strDatePickerFormat    = "MM/YYYY";
            $strDatePickerClass     = "my";
        }
        
        
        $arrTheDateRange = array();
        if ($objField->ValidationString != '')
        {
            
            $strTheDateFormatForPHP = ($objField->ControlType === 'DatePickerMonth' ? 'm/Y':'d/m/Y');
            
            $strToday = date($strTheDateFormatForPHP);
            $arrRange = explode('|',$objField->ValidationString);
            
            foreach($arrRange as $strDateFromValidation)
            {
                
                    $arrTheDateRange[] = date($strTheDateFormatForPHP,  strtotime($strDateFromValidation));
                
            }
        }
        
        $strReturn = "<div id=\"".$objField->strFieldNameForForm."-datepicker\" class=\"input-group date ";
        
        if($objField->ControlType === 'DatePickerMonth')
        {
            $strReturn .= " months\"";
        }
        else
        {
            $strReturn .= " days\"";
        }
        if (count($arrTheDateRange)>0)
        {
            $strReturn .= "data-date-start-date=\"".$arrTheDateRange[0]."\" data-date-end-date=\"".$arrTheDateRange[1]."\" ";
        }      
        $strReturn .= ">
                        <input class=\"form-control\" type=\"text\" name=\"".$objField->strFieldNameForForm."\" id=\"".$objField->strFieldNameForForm."\" data-no-delete=\"".$objField->DontRemoveContents."\" value=\"".$strValueForForm."\" ";
        
                
        $strReturn .=  ">
                        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-calendar\"></i></span>
                    </div>";
        return $strReturn;
    }
    
    function buildTextbox($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        $arrInputData = array(
            'name'        => $objField->strFieldNameForForm,
            'class'       => 'form-control',
            'id'          => $objField->strFieldNameForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'value'       => $strValueForForm
        );
        
        if ($objField->ValidationMode === "Calc")
        {
            $arrInputData['readonly'] = 'readonly';
        }
        
        return form_input($arrInputData)."\r\n";
    }

    function buildHidden($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        $arrInputData = array(
            'type'        => 'hidden',
            'name'        => $objField->strFieldNameForForm,
            'id'          => $objField->strFieldNameForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'value'       => $strValueForForm
        );
        
        return form_input($arrInputData)."\r\n";
    }
    
    function buildTextArea($objField,$objReview)
    {
        $strValueForForm = '';
        if ($objReview->{$objField->strFieldNameForForm} != '')
        {
            $strValueForForm = $objReview->{$objField->strFieldNameForForm};
        }
        
        $arrInputData = array(
            'name'        => $objField->strFieldNameForForm,
            'class'       => 'form-control',
            'id'          => $objField->strFieldNameForForm,
            'data-no-delete' => $objField->DontRemoveContents,
            'value'       => $strValueForForm
        );
        return form_textarea($arrInputData)."\r\n";
    }

    function buildField($objField,$objReview,&$arrModels,$strRowReference)
    {
        switch ($objField->ControlType)
        {
            case "Button":
                return buildButton($objField,$objReview,$strRowReference);
                break;
            case "RadioButton":
                return buildRadio($objField,$objReview);
                break;
            case "RadioButtonVert":
                return buildRadio($objField,$objReview,true);
                break;
            case "CheckBox":
                return buildCheckbox($objField,$objReview);
                break;
            case "DropDown":
                return buildDropDown($objField,$objReview);
                break;
            case "ComboBox":
                return buildComboBox($objField, $objReview, $arrModels);
                break;
            case "TextBox":
                return buildTextbox($objField,$objReview);
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
                return buildNumberSlider($objField,$objReview);
                break;
            case "TextArea":
                return buildTextArea($objField,$objReview);
                break;
            case "DatePickerDay":
            case "DatePickerMonth":
                return buildDatePicker($objField,$objReview);
                break;
            case "Hidden":
                return buildHidden($objField,$objReview);
                break;
            default:
                return "";
                break;
        }
    }
    
    
    function agcDrugsName(&$drugs_model,$strDrugReference)
    {
        return $drugs_model->getLabelFor($strDrugReference);
    }
    
    function agcUsageName($strUsage)
    {
        $strDose = '';
        $strDaily = '';
        $strLabel = '';
        
        if (strlen($strUsage))
        {
            $arrParts = explode('-', $strUsage);
            
            if (count($arrParts) > 1)
            {
                $strDose = $arrParts[0];
				$strDaily = $arrParts[1];

            } else {
                $strDaily = $arrParts[0];	
			}


            switch($strDaily)
            {
                case '1':
                    $strLabel = 'Once Daily';
                    break;
                case '2':
                    $strLabel = 'Twice Daily';
                    break;
                case '3':
                    $strLabel = 'Thrice Daily';
                    break;
                case '4':
                    $strLabel = 'Four times Daily';
                    break;
                case 'prn':
                    $strLabel = 'As Required';
                    break;
                case 'smart':
                    $strLabel = 'SMART';
                    break;
                default:
                    $strLabel = 'UNKNOWN CODE';
            }

            if (strlen($strDose) > 0)
            {
                switch($strDose)
                {
                    case '1':
                        $strLabel = '1 Puff, '.$strLabel;
                        break;
                    default:
                        $strLabel = $strDose.' Puffs, '.$strLabel;
                }
            }
        }
        return $strLabel;
        
        
        
        
    }
    
    
    function getDataFieldReadOnlyContents($objField,$objReview,&$arrModels)
    {
        $arrFieldValidationField = explode(",",$objField->ValidationString);
        switch ($arrFieldValidationField[0])
        {
            case "Drugs":
                return agcDrugsName($arrModels['drugs_model'], $objReview->{$objField->strFieldNameForForm});
            case "Usage":
                return agcUsageName($objReview->{$objField->strFieldNameForForm});
            default:
                break;
        }
    }
    
    
    
    function getDropDownFieldContents($objField,$objReview)
    {
        $arrKeys = str_getcsv($objField->ValidationString);
        $arrLabels = str_getcsv($objField->LabelString);
        
        return $arrLabels[array_search($objReview->{$objField->strFieldNameForForm}, $arrKeys)];
    }
    
    function getReadOnlyFieldContents($objField,$objReview,&$arrModels)
    {
        if ($objReview->{$objField->strFieldNameForForm} != "")
        {
            switch($objField->ControlType)
            {
                case "ComboBox":
                    switch ($objField->ValidationMode)
                    {
                        case "Data":
                            return getDataFieldReadOnlyContents($objField,$objReview,$arrModels);
                            break;
                    }
                    break;
                case "DropDown":
                    switch ($objField->ValidationMode)
                    {
                        case "Func":
                            return $objReview->{$objField->strFieldNameForForm};
                            break;
                        default:
                            return getDropDownFieldContents($objField, $objReview);
                            break;
                    }
                default:
                    return $objReview->{$objField->strFieldNameForForm};
                    break;
            }
        }
        else 
        {
           
            
            return "-";
        }
    }
    
    //$arrJSFieldValidation
    function completeFieldValidationSetup($objField,$arrJSFieldValidation)
    {
        if ($objField->Required === 'Y')
        {
            $arrJSFieldValidation[$objField->strFieldNameForForm]['notEmpty']['message'] = "'You cannot leave this field empty.'";
        }
        else
        {
            /*
            if (($objField->DbDataType !== 'Binary') || (($objField->DbDataType === 'Binary') 
                    && ($objField->ValidationMode !== "Group")))
            {*/
                    $arrJSFieldValidation[$objField->strFieldNameForForm]= array();
            /*}*/
        }
        
        switch (substr($objField->DbDataType, 0, 3))
        {
            case 'Int':
                if ($objField->ValidationMode === "Range")
                {
                    $arrJSFieldValidation[$objField->strFieldNameForForm]['integer']['message'] = "'You must enter a whole number.'";
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrMinMax = explode('-',$objField->ValidationString);
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['message'] = "'You must enter a number between ".$arrMinMax[0]." and ".$arrMinMax[1].".'";
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['min'] = $arrMinMax[0];
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['max'] = $arrMinMax[1];
                    }
                }
                break;
            case 'Flo':
                if ($objField->ValidationMode === "Range")
                {
                    $arrJSFieldValidation[$objField->strFieldNameForForm]['numeric']['message'] = "'You must enter a number, which can contain a decimal point.'";
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrMinMax = explode('-',$objField->ValidationString);
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['message'] = "'You must enter a number between ".$arrMinMax[0]." and ".$arrMinMax[1].".'";
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['min'] = $arrMinMax[0];
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['between']['max'] = $arrMinMax[1];
                    }
                }
                break;
            case 'Var':
            case 'Cha':
                if ($objField->ValidationMode === "Regex")
                {
                    if ($objField->ValidationString !== NULL)
                    {
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['regexp']['message'] = "'".$objField->ValidationText."'";
                        $arrJSFieldValidation[$objField->strFieldNameForForm]['regexp']['regexp'] = $objField->ValidationString;
                    }
                }
                $intNumberOfChars = (int) substr(explode('(',$objField->DbDataType)[1],0,-1);
                $arrJSFieldValidation[$objField->strFieldNameForForm]['stringLength']['message'] = "'Your input must be less than ".$intNumberOfChars." characters long.'";
                $arrJSFieldValidation[$objField->strFieldNameForForm]['stringLength']['max'] = $intNumberOfChars;
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

<?php

    $arrFields = array();

    $attributes = array('role' => 'form', 'id' => 'agc-form');

    echo form_open($strCurrentPage."/".$strScreenName, $attributes);
    
    echo form_input(array(
            'type'        => 'hidden',
            'name'        => "agcscreenname_".$strScreenName."_validated",
            'id'          => "agcscreenname_".$strScreenName."_validated",
            'value'       => "false"));
    
     echo form_input(array(
            'type'        => 'hidden',
            'name'        => "agcscreenname_".$strScreenName."_destination",
            'id'          => "agcscreenname_".$strScreenName."_destination",
            'value'       => ""));
    
    $strJS_EventList = "";
        foreach ($objQgp->questiongroups as $objQuestionGroup)
        {
            ?>
        <div class="panel panel-default qg-holder<?php if ($objQuestionGroup->QuestionGroupCssClass != '') 
                                                            { echo " ".$objQuestionGroup->QuestionGroupCssClass; 
                                                            
                                                            }
                                                       if ((($intFlowID >= 20000) && ($intScreenFlowPosition === ($intFlowScreenCount-1))) || ($intFlowID >= 19000))
                                                        {     
                                                         echo " show-always";   
                                                        }  
                                                            ?>" id="qg-holder-<?php echo $objQuestionGroup->QuestionGroupID; ?>">
                <div class="panel-heading">
                    <h3 class="panel-title" style="font-size:1.4em;"><?php echo $objQuestionGroup->QuestionGroupDisplayText; 
                            
                    ?></h3>
                    
                    <?php
                    if (($intFlowID >= 20000) && ($intScreenFlowPosition === ($intFlowScreenCount-1)))
                    {
                            ?><a href="<?php echo site_url('download/pdf'); ?>" id="btnDownloadAsPDF" class="rounded-button-purple right" value="true"><span>Download as PDF &nbsp;&nbsp;<span class="glyphicon glyphicon-floppy-disk"></span></span></a><?php
                    }
                    else
                    {
                        if ($intFlowID >= 19000)
                        {
                            ?><a href="<?php echo site_url('download/previous_pdf'); ?>" id="btnDownloadAsPDF" class="rounded-button-purple right" value="true"><span>Download as PDF &nbsp;&nbsp;<span class="glyphicon glyphicon-floppy-disk"></span></span></a><?php
                        }
                    }
                    ?>
                    
                </div>
                <div class="panel-body">
            <?php
            
            if (($intFlowID >= 20000) && ($intScreenFlowPosition === ($intFlowScreenCount-1)))
            {
                $this->load->view('reports/report',array('objReview'=>$objReview,'arrModels'=>$arrModels)); 
            }
            else
            {
                if ($intFlowID >= 19000)
                {					
					echo $objReview->agcsystem_PreviousExacerbationId . "|";
					echo $objReview->agcsystem_PreviousConsultationId;
					
					//NOTE TO SELF - Could do "if ($objReview->agcsystem_PreviousExacerbationId > $objReview->agcsystem_PreviousConsultationId"? I.e. exacerbation ID more recent than regular consultation... but this only works if review ID is ALWAYS higher for later reviews?
                    if ($objReview->agcsystem_PreviousExacerbationId > 0 && $objReview->agcsystem_PreviousExacerbationId > $objReview->agcsystem_PreviousConsultationId)
										
                    {
                        if ($arrModels['pce_model']->setConsultationID($objReview->agcsystem_PreviousExacerbationId)->retrieve()->isLoaded())
                        {
                            $objPreviousExacerbation = $arrModels['pce_model']->getAsObject();
                            $this->load->view('lastconsultationreview/report',array('objReview'=>$objPreviousExacerbation,'arrModels'=>$arrModels));

                        }
                        else
                        {
                            echo "ERROR LOADING PREVIOUS EXACERBATION: ".$objReview->agcsystem_PreviousExacerbationId;
                        }
                    }
                    if (($objReview->agcsystem_PreviousConsultationId > 0)
                            && ($arrModels['pcv_model']->setConsultationID($objReview->agcsystem_PreviousConsultationId)->retrieve()->isLoaded()))
                    {
                        $objPreviousConsultation = $arrModels['pcv_model']->getAsObject();
                        $this->load->view('lastconsultationreview/report',array('objReview'=>$objPreviousConsultation,'arrModels'=>$arrModels));

                    }
                    else
                    {
                        echo "ERROR LOADING PREVIOUS CONSULTATION: ".$objReview->agcsystem_PreviousConsultationId;
                    }
                    
                    
                }
                else
                {
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
                                                    echo "<span id=\"text-".$objText->TextID."\" style='".$objText->TextCssCLass."'>".$objText->Text."</span>";
                                                    }
                                                    else
                                                    {
                                                        echo "<span id=\"text-".$objText->TextID."\">".$objText->Text."</span>";
                                                    }
                                                    break;
                                                case "Alert-Dismissable":
                                                    echo "<div class=\"alert ".$objText->TextCssCLass." alert-dismissible\" role=\"alert\">
              <button type=\"button\" class=\"close no-validate\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button><div  id=\"text-".$objText->TextID."\">".$objText->Text."</div></div>";
                                                    break;
                                                case "Alert":
                                                    echo "<div id=\"text-".$objText->TextID."\" class=\"alert ".$objText->TextCssCLass."\" role=\"alert\">".$objText->Text."</div>";
                                                    break;
                                                default:
                                                    echo $objText->Text;
                                                    break;
                                            }

                                            break;
                                        case "Tabledata":
                                            $objTable = $objQuestionGroup->tables[$objLayout->ContentID];
                                            switch ($objTable->TableType)
                                            {
                                                case "data_json":
                                                    ?>
                                                    <ul class="nav nav-tabs" id="agcTab_<?php echo $objTable->TableID; ?>" role="tablist">
                                                        <li><a href="#agcTab_<?php echo $objTable->TableID; ?>_chart" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-stats"></span> Chart</a></li>
                                                        <li class="active"><a href="#agcTab_<?php echo $objTable->TableID; ?>_table" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> Table</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <?php
                                                        echo '<div class="tab-pane" id="agcTab_'.$objTable->TableID.'_chart"><div id="agcDataChart_'.$objTable->TableID.'"></div></div>';
                                                        echo '<div class="tab-pane active" id="agcTab_'.$objTable->TableID.'_table"><table class="table '.$objTable->TableCssClass.'" id="agcDataTable_'.$objTable->TableID;
                                                        echo        '" data-agc_chartref="agcDataChart_'.$objTable->TableID.'"></table></div>';
                                                        
                                                        ?>
                                                    </div>
                                                    <?php
                                                    echo '<script type="text/javascript">';
                                                    //echo "//$(document).ready(function () {\r\n";
                                                    echo 'var strTargetTable = "agcDataTable_'.$objTable->TableID.'";';
                                                    echo 'var strTargetChart = "agcDataChart_'.$objTable->TableID.'";';
                                                    echo 'var strDataURLStart = "'.site_url('api/').'";';
                                                    echo 'var intReviewID = '.$objReview->intReviewID.';';
                                                    echo 'var intPatientID = '.$objReview->PatientDetails_PatientId.';';
                                                    echo $objTable->TableContents;
                                                    echo "
                                                    $('#agcTab_".$objTable->TableID." a').click(function (e) {
                                                        e.preventDefault();
                                                        $(this).tab('show');
                                                      });
                                                    ";
                                                    echo "
                                                    $('#agcTab_".$objTable->TableID." a').click(function (e) {
                                                        e.preventDefault();
                                                        $(this).tab('show');
                                                      });
                                                    ";
                                                    //echo "//});\r\n";
                                                    echo '</script>';
                                            
                                            }
                                            
                                            break;
                                        case "Question":
                                            echo $objQuestionGroup->questions[$objLayout->ContentID]->QuestionDisplayText;
                                            break;
                                        case "Field-readonly":
                                            if ($objLayout->LayoutCssClass != '')
                                            {
                                                echo "<div id=\"text-".$objLayout->LayoutID."\" class=\"alert alert-".$objLayout->LayoutCssClass."\" role=\"alert\">".getReadOnlyFieldContents($objQuestionGroup->fieldsreadonly[$objLayout->ContentID],$objReview,$arrModels)."</div>";
                                            
                                            }
                                            else
                                            {
                                                echo getReadOnlyFieldContents($objQuestionGroup->fieldsreadonly[$objLayout->ContentID],$objReview,$arrModels);
                                            }
                                            //$arrFields[] = $objQuestionGroup->fieldsreadonly[$objLayout->ContentID]->strFieldNameForForm;
                                            break;
                                        
                                        case "Field":
                                            $objField = $objQuestionGroup->fields[$objLayout->ContentID];
                                            /*if ($objField->ValidationMode === "Group")
                                            {
                                                $objField->RequiredNumber = $objQuestionGroup->questions[$objField->QuestionID]->ReqNumber;
                                            }*/

                                            $arrFields[] = $objField->strFieldNameForForm;

                                            $arrJSFieldValidation = completeFieldValidationSetup($objField,$arrJSFieldValidation);
                                            $arrTempVisibilitiesList['fields'][$objField->strFieldNameForForm] = array();

                                            if ($objField->ValidationMode === "Calc")
                                            {
                                                $strJSCalculations .= "".$objField->ValidationString."\r\n";
                                            }

                                            foreach($objField->visibilities as $objVisibility)
                                            {

                                                $arrTempVisibilitiesList['fields'][$objField->strFieldNameForForm][$objVisibility->Operator]['data_type'] = $objField->DbDataType;

                                                $arrTempVisibilitiesList['fields'][$objField->strFieldNameForForm][$objVisibility->Operator]['comparisons'][$objVisibility->Criteria][] 
                                                        = $objVisibility->LayoutID;

                                            }

                                            /*if ($objField->ValidationMode !== "Group")
                                            {*/
                                                echo "<div class=\"form-group\">".buildField($objField,$objReview,$arrModels,'qg_row_'.$objQuestionGroup->QuestionGroupID."_".$intRow)."</div>";
                                            /*}
                                            else
                                            {
                                                echo buildField($objField,$objPatient,$arrModels);
                                            }
                                            */
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
                                                        $objField = $objQuestionGroup->fields[$objVisibility->FieldID];
                                                        $strDBFieldName = $objField->strFieldNameForForm;
                                                        $strControlType = $objField->ControlType;
                                                        $strDataType    = $objField->DbDataType;

                                                        $strCriterion = "";
                                                        
                                                        if ($objVisibility->Operator !== 'visible')
                                                        {
                                                            if ($objVisibility->Operator !== 'exists')
                                                            {
                                                                if (($objVisibility->Operator === '!typeof') || ($objVisibility->Operator === 'typeof'))
                                                                {
                                                                    $operator = 'typeof';
                                                                    $logic_operator = '===';
                                                                    if ($objVisibility->Operator === '!typeof')
                                                                    {
                                                                        $logic_operator = '!==';
                                                                    }

                                                                    $arrVisibilityCriteria[] = "(".$operator.' '.getJSForValueOfControl($strDBFieldName)." ".$logic_operator." '".$objVisibility->Criteria."')";
                                                                }
                                                                else
                                                                {
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
                                                                            $arrVisibilityCriteria[] = "$(\":input[name='".$strDBFieldName."']\").is(':checked')";
                                                                        }
                                                                        else
                                                                        {
                                                                            $arrVisibilityCriteria[] = "$(\":input[name='".$strDBFieldName."']\").is(':unchecked')";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $arrVisibilityCriteria[] = "document.getElementById('".$strDBFieldName."') !== null";
                                                            }
                                                        }
                                                        else
                                                        {
                                                             $arrVisibilityCriteria[] = "$(\":input[name='".$strDBFieldName."']\").is(':visible')";
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
                }
            }
            ?>
                </div>
        </div>
            <?php

        }
        
        /*$data = array(
            'name' => 'btnFormSubmit',
            'id' => 'btnFormSubmit',
            'value' => 'true',
            'type' => 'submit',
            'content' => '<span class="glyphicon glyphicon-floppy-disk"></span> Save'
        );
        echo form_button($data);*/
        
        
        
    ?>
<div class="row">          
    
                            <?php
                            $CI =& get_instance();
                            if ($CI->session->userdata('booAudit') === true)
                            {
                                
                                $arrPages = $CI->session->userdata('arrScreenHistory');
                                $intCurrentPosition = array_search($strScreenName,$arrPages);
                                $strNextPage = '';
                                $strPrevPage = '';
                                if ($intCurrentPosition > 0)
                                {
                                    $strPrevPage = $arrPages[$intCurrentPosition - 1];
                                }
                                if ($intCurrentPosition < (count($arrPages) - 1))
                                {
                                    $strNextPage = $arrPages[$intCurrentPosition + 1];
                                }
                                
                            ?>
                                    <div class="col-md-2"><ul class="pager"><li>          
                                    <?php
                                    if ($strPrevPage !== '')
                                    {
                                    ?>
                                    <a href="/flowcontroller/screen/<?php echo $strPrevPage; ?>"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
                                    <?php
                                    }      
                                    ?>
                                    </li></ul></div>
                                    <div class="col-md-8">
                                        <ul class="pager">
                                            

                                            <li>
                                                <a href="/begin/auditcleanup/<?php echo $this->input->cookie('agc_APIVars_token'); ?>">Finish <span class="glyphicon glyphicon-home"></span></a> 
                                            </li>

                                            
                                        </ul>
                                    </div>


                                    <?php
                                    if ($strNextPage !== '')
                                    {
                                    ?>
                                    <div class="col-md-2"><ul class="pager"><li>
                                                <a href="/flowcontroller/screen/<?php echo $strNextPage; ?>">Next <span class="glyphicon glyphicon-chevron-right"></span></a>
                                            </li></ul></div>
                                    <?php
                                            }
                                            ?>
                            <?php 
                            }  
                            else
                            {
                            ?>
                                    <div class="col-md-2"><ul class="pager"><li>          
                                    <?php
                                    if ($strPreviousScreen !== '')
                                    {
                                    ?>
                                    <button name="btnFormSubmitPrev" id="btnFormSubmitPrev" value="true" onClick="javascript:agc_setForSubmit('<?php echo $strPreviousScreen ?>');"><span class="glyphicon glyphicon-chevron-left"></span> Back</button>
                                    <?php
                                    }      
                                    ?>
                                    </li></ul></div>
                                    <div class="col-md-8">
                                        <ul class="pager">
                                            <?php
                                            if (($intFlowID < 20000) || ($intScreenFlowPosition != ($intFlowScreenCount-1)))
                                            {
                                            ?>

                                            <li>
                                                <button name="btnFormSubmitAbandon" id="btnFormSubmitAbandon" value="true" onClick="javascript:agc_setForSubmit('ABANDON');"><span class="glyphicon glyphicon-new-window"></span> Save & Exit</button> 
                                            </li>

                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>



                                    <div class="col-md-2"><ul class="pager"><li><button type="submit" name="btnFormSubmitNext" id="btnFormSubmitNext" class="no-validate" value="true"><?php

                                    if (($intFlowID < 20000) || ($intScreenFlowPosition != ($intFlowScreenCount-1)))
                                    {
                                            ?>Next <span class="glyphicon glyphicon-chevron-right"></span><?php

                                    }
                                    else
                                    {
                                            ?>Finish <span class="glyphicon glyphicon-home"></span><?php
                                    }
                                                    ?></button></li></ul></div>
                            <?php 

                            } 
                            ?>
                            
</div>                            
                            
			
                        <?php
                        echo form_input(array(
                                                'type'        => 'hidden',
                                                'name'        => "updated_fields",
                                                'id'          => "updated_fields",
                                                'value'       => implode('|',$arrFields)));
                        ?>
                </form>
                
                
                                
                                
                                
<?php
                            if (($strScreenName === 'LF')) 
                            {
                            ?>
                                <script type="text/javascript" src="<?php 
                                echo $this->document->getJavascriptIncludeUrlString().'sSCP/superSpiroCapturePro.js'; 
                                ?>"></script>
								<script type="text/javascript" src="<?php 
                                    echo $this->document->getJavascriptIncludeUrlString().'d3.min.js'; 
                                ?>"></script>
                                <script type="text/javascript" src="<?php 
                                    echo $this->document->getJavascriptIncludeUrlString().'table_functions.js'; 
                                ?>"></script>
                                <script type="text/javascript" src="<?php 
                                    echo $this->document->getJavascriptIncludeUrlString().'chart_functions.js'; 
                                ?>"></script>
                            <?php 
                            
                            } 
                                if (($strScreenName === 'MRNew')  || ($strScreenName === 'CMNew2')) {
                            ?>
                                <script type="text/javascript" src="<?php 
                                echo site_url('api/getdrugslist'); 
                                ?>"></script>
                                <script type="text/javascript" src="<?php 
                                echo $this->document->getJavascriptIncludeUrlString().'drug-editor.js'; 
                                ?>"></script>
								<script type="text/javascript" src="<?php 
								echo $this->document->getJavascriptIncludeUrlString().'drug-other-editor.js'; 
								?>"></script><?php
								}
								?>
                                <?php
									//CORBAN CHANGE 2015-07-29 and 2015-10-02
									//if ($strScreenName === 'CMNew2') {
									if ($strScreenName === 'NPRX1' || $strScreenName === 'NPRX2') {
										?>
									<script type="text/javascript" src="<?php 
									echo $this->document->getJavascriptIncludeUrlString().'drug-other-editor.js'; 
									?>"></script><?php
									}
                            ?>
                                
                     

<script type="text/javascript">
                            
                            <?php 
                                  $CI =& get_instance();
                                    if ($CI->session->userdata('booAudit') === true)
                                    {
                                        echo "var booAuditReadOnly = true;"
                                        . "var striCheckTheme = 'grey';";
                                    }  
                                    else
                                    {
                                        echo "var booAuditReadOnly = false;"
                                        . "var striCheckTheme = 'purple';";
                                    }
                                    
                                  echo "var strAPIUriRoot = '".site_url('api/')."';";  
                                    
                                  echo "var intValidationState = ".$intScreenValidated.";";
                                  
                                  
                                  echo "var strScreenName = '".$strScreenName."';";
                                  
                                  
                                  echo " \r\n \r\n var obj_agc_Review = ".json_encode($objReview)."; \r\n \r\n";
                                  
                                  
                                  echo "
                                      var objFieldValidations = {".generateJSValidationString($arrJSFieldValidation)."};
                                          ";
                                    ?>
                                        
                                       
                            
                                //functions needed in screen.php
                                function agc_checkVisibilities()
                                {
                                    <?php echo $strJS_EventList; ?>

                                    agc_console(arrRevealed);

                                    agc_console(arrHidden);
                                }


                            
                                


                            <?php
                            echo $strJSCalculations;
                            ?>
                            
                         
                    </script>
                    <script type="text/javascript" src="<?php 
                                echo $this->document->getJavascriptIncludeUrlString().'screens.js'; 
                    ?>"></script>

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

<div class="modal fade" id="agc_modal_dialog_invalid">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Oops...!</h4>
      </div>
      <div class="modal-body">
        <p>You're missing some details.</p>
        <p>You can fix it and submit again, or submit your details so far.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="agc_modal_invalid_ignore" data-dismiss="modal"><span class="glyphicon glyphicon-trash"></span> Submit Anyway</button>
        <button type="button" class="btn btn-primary" id="agc_modal_invalid_undo" data-dismiss="modal"><span class="glyphicon glyphicon-wrench"></span> I'll Fix It</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="agc_modal_dialog_pefbest">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Best PEF</h4>
      </div>
      <div class="modal-body">
        <p id="agc_modal_pefbest_text"></p>
        
        <div class="row">
            <div class="form-group">
                <label class='radio-inline'><input type="radio" name="agc_modal_pefbest_response" id="agc_modal_pefbest_response_yes" value="YES" checked='checked' /> Yes</label>
                <label class='radio-inline'><input type="radio" name="agc_modal_pefbest_response" id="agc_modal_pefbest_response_no" value="NO" /> No</label>
            </div>
        </div>
        <div class="row hidden" id="agc_modal_pefbest_furtherquestion">
            <div class="form-group">
                <label class='radio-inline'>Please enter the patient's best known PEF <input type="text" name="agc_modal_pefbest_pef_value" id="agc_modal_pefbest_pef_value" /></label>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="agc_modal_pefbest_okay" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Okay</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="agc_modal_loader" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content text-center">
      <img src="<?php echo site_url('/includes/images/gear.GIF'); ?>" />
      <br />Please Wait...
    </div>
  </div>
</div>