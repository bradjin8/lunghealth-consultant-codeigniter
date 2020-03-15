function removeDrugDropDowns(strFieldReference)
{
    if (($('#'+strFieldReference+'DrugDropDownInsert').attr('data-agccontentmarker') !== '')
                && ($('#'+strFieldReference+'DrugDropDownInsert').attr('data-agccontentmarker') !== 'stop')
                   
            )
    {
        $('#'+strFieldReference+'NewDrug').chosen('destroy');
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldReference+'NewDrug'));
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldReference+'NewUsage'));
    }
}

function agcAddStoredAmendments()
{
    $('#agc-form').find('.drug-amendment').each(function(index,object){
       jqO = $(object);
       
       strChangeType = jqO.attr('data-agcchangetype');
       if (strChangeType !== '') {
           strDrugType      = jqO.attr('data-agcdrugtype');
           strRowId         = jqO.attr('data-agcrowid');
           strOptions       = jqO.attr('data-agcoptions');
           
           //console.log(objDrugs);
           //jqO.html('<div class="col-md-6 col-md-offset-1"><em>This is presently changed in the review to '+arrDrugsForType[strNewDrug].label+', '+objChangedValues[jqO.attr('data-agcdrugtype')].newusage+"</em></div>");
           
           var arrInputArray = [strRowId,strDrugType,strOptions];
           agcAddDrugEditor(arrInputArray,'edit');
           
       } 
    });
    
    $('#agc-form').find('.drug-addition').each(function(index,object){
       jqO = $(object);
       
       //alert(jqO.attr('data-agcdrugtype'));
       
       strNewDrug   = jqO.attr('data-agcnewdrug');
       strNewUsage  = jqO.attr('data-agcnewusage');
       if ((strNewDrug !== '') || (strNewUsage !== '')) {
           strDrugType      = jqO.attr('data-agcdrugtype');
           strRowId         = jqO.attr('data-agcrowid');
           strOptions       = jqO.attr('data-agcoptions');
           
           //console.log(objDrugs);
           //jqO.html('<div class="col-md-6 col-md-offset-1"><em>This is presently changed in the review to '+arrDrugsForType[strNewDrug].label+', '+objChangedValues[jqO.attr('data-agcdrugtype')].newusage+"</em></div>");
           
           var arrInputArray = [strRowId,strDrugType,strOptions];
           agcAddDrugEditor(arrInputArray,'add');
           
       } });
}

function agcBuildModeSelector(strName)
{
    objValues = $('#btn'+strName+'Change');
    
	drugWarningFunctionNamesStop = "agc_calc_WarningMoreThanOneLama('"+strName+"stop');agc_calc_WarningMoreThanOneLaba('"+strName+"stop');agc_calc_WarningDrugsWithoutICS('"+strName+"stop');agc_calc_NoSABA('"+strName+"stop');";
	drugWarningFunctionNamesChange = "agc_calc_WarningMoreThanOneLama('"+strName+"changetherapy');agc_calc_WarningMoreThanOneLaba('"+strName+"changetherapy');agc_calc_WarningDrugsWithoutICS('"+strName+"changetherapy');agc_calc_NoSABA('"+strName+"changetherapy');";
	
        strModeSelector = '<div class="col-md-3 layout-holder" >';
        strModeSelector += '<div class="btn-group form-group" data-toggle="buttons">'+
                    '<label class="btn btn-default ';
                if (objValues.attr('data-agcchangetype') === 'changetherapy')
                {
                    strModeSelector += 'active';
                }
                strModeSelector += '" onClick="javascript:agcInsertDrugDropDowns(\''+strName+'\',\'changetherapy\',\'edit\');agcInsertReasonField(\''+strName+'\');'+drugWarningFunctionNamesChange+'">'+
                        '<input type="radio" name="'+strName+'ChangeType" id="'+strName+'ModeSelector_ChangeTherapy" value="changetherapy" ';
                if (objValues.attr('data-agcchangetype') === 'changetherapy')
                {
                    strModeSelector += 'checked="checked"';
                }
        strModeSelector += '  data-bv-notempty="true" /> Change Therapy' +
                    '</label>'+
                    /*'<label class="btn btn-default ';
                    if (objValues.attr('data-agcchangetype') === 'changedose')
                {
                    strModeSelector += 'active';
                }
                strModeSelector += '" onClick="javascript:agcInsertDrugDropDowns(\''+strName+'\',\'changedose\',\'edit\');agcInsertReasonField(\''+strName+'\');" >'+
                        '<input type="radio" name="'+strName+'ChangeType" id="'+strName+'ModeSelector_ChangeDose" value="changedose" ';
                if (objValues.attr('data-agcchangetype') === 'changedose')
                {
                    strModeSelector += 'checked="checked"';
                }
        strModeSelector += '/> Change Dose/Device'+
                    '</label>'+*/
                    '<label class="btn btn-default ';
                if (objValues.attr('data-agcchangetype') === 'stop')
                {
                    strModeSelector += 'active';
                }
                strModeSelector += '"  onClick="javascript:agcStopTherapy(\''+strName+'\');agcInsertReasonField(\''+strName+'\');'+drugWarningFunctionNamesStop+'">'+
                        '<input type="radio" name="'+strName+'ChangeType" id="'+strName+'ModeSelector_Stop" value="stop" ';
                if (objValues.attr('data-agcchangetype') === 'stop')
                {
                    strModeSelector += 'checked="checked"';
                }
        strModeSelector += '/> Stop Therapy'+
                    '</label>'+
                    '</div>';
            
    
    
    strModeSelector += '</div>';
    return strModeSelector;
}

// barreh: added 20150906 to support dosage amendments
function agcGetDoseTextType(objDrug)
{
    strDeviceId = '';
    if (typeof objDrug.deviceid !== 'undefined')
    {
        strDeviceId = objDrug.deviceid;
    }
    switch(strDeviceId.toLowerCase())
    {
            case '1':
            case '2':
                return 'puff';
                break;
            case '3':
                return 'nebule';
                break;
            case '4':
                return 'tablet';
                break;
            case '5':
            case '6':
                return objDrug.dose+objDrug.doseunit+' dose';
                break;
            case '7':
                return 'dose';
                break;
            default:
                return 'dose';
                break;
            
    }
}

// barreh: added 20150906 to support dosage amendments
function agcGetDoseText(objInput,strDose,objDrug)
{
   strLabel = objInput.label;
    strValue = objInput.value;
    
    strDosageText = agcGetDoseTextType(objDrug);
    if (strDose.length > 0)
    {
        switch(strDose.toLowerCase())
        {
            case '1':
                strLabel = '1 '+strDosageText+', '+strLabel;
                break;
            default:
                strLabel = strDose+' '+strDosageText+'s, '+strLabel;
        }
        strValue = strDose + '-' +strValue;
    }
    return {'label':strLabel,'value':strValue};
}

// barreh: added 20150719 to support API feed of drug usage options
// barreh: amended 20150906 to support dosage amendments

function agcBuildOptionsDropDownValueAndLabel(strDosage,strDose,objDrug)
{
    strLabel = '';
    strValue = '';
    strFrequency = 'Daily';
    if (typeof objDrug.frequency !== 'undefined')
    {
        strFrequency = objDrug.frequency;
    }
    
    switch(strDosage.toLowerCase())
    {
        case '1':
        case 'od':
            strLabel = 'Once '+strFrequency;
            strValue = '1';
            break;
        case '2':
        case 'bd':
            strLabel = 'Twice '+strFrequency;
            strValue = '2';
            break;
        case '3':
        case 'tds':
            strLabel = 'Thrice '+strFrequency;
            strValue = '3';
            break;
        case '4':
        case 'qds':
            strLabel = 'Four times '+strFrequency;
            strValue = '4';
            break;
        case 'prn':
            strLabel = 'As Required';
            strValue = 'prn';
            break;
        case '(s)mart':
            strLabel = '(S)MART';
            strValue = 'smart';
            break;
        default:
            strLabel = 'UNKNOWN CODE';
            strValue = 'NA';
    }
    
    return agcGetDoseText({'label':strLabel,'value':strValue},strDose,objDrug);
}



function agcBuildOptionsDropDown(strTargetForInsert, strDrugType,strDrug,strNewUsage ,strSelectedDrug,strDatabaseFunction,booSuppressSelected)
{
    
    
    
    if ($('#'+strTargetForInsert+'_group').length > 0)
    {
        //element already exists, delete...
        $('#agc-form').bootstrapValidator('removeField',strDrugType+'NewUsage');
        $('#'+strTargetForInsert+'_group').remove();
    }
    
    $('<div/>',{id:strTargetForInsert+'_group',class:'form-group'})
                .appendTo(
                    $('<div/>', {id:strTargetForInsert+'_layout',class:'col-md-4 layout-holder'})
                        .appendTo($('#'+strTargetForInsert))
                );
    arrOptions = {};
    
    
	if(typeof objDrugs['Drug'+strDrug][strSelectedDrug] !== 'undefined')
	{
	
		if (objDrugs['Drug'+strDrug][strSelectedDrug]['nooftimes'].length > 0)
		{    
			if (objDrugs['Drug'+strDrug][strSelectedDrug]['nooftimes'].split(',').length > 0)
			{
				$.each( objDrugs['Drug'+strDrug][strSelectedDrug]['nooftimes'].split(','), function (index,NoOfTimes) {
					arrOptions[NoOfTimes] = [];
					if (objDrugs['Drug'+strDrug][strSelectedDrug]['noperdose'].length > 0)
					{    
						if (objDrugs['Drug'+strDrug][strSelectedDrug]['noperdose'].split(',').length > 0)
						{
							arrOptions[NoOfTimes] = [];
							$.each( objDrugs['Drug'+strDrug][strSelectedDrug]['noperdose'].split(','), function (index_inner,NoOfDose) {
							   //function
							   
							   arrOptions[NoOfTimes].push(NoOfDose);
							});
						}
					}
				});
			}
			
			
			
			
			
			
			//if (arrOptions.size > 1)
			//{
				strOptionSelect = '<select name="'+strDrugType+'NewUsage" id="'+strDrugType+'NewUsage" class="form-control" data-bv-notempty="true"';
							//;
				
				drugWarningFunctions = "agc_calc_NoSABA();";
				
				if (strDatabaseFunction !== '')
				{
					strOptionSelect += ' onChange="'+strDatabaseFunction+';'+drugWarningFunctions+'"';
				} else {
					strOptionSelect += ' onChange="'+drugWarningFunctions+'"';
				}
                strOptionSelect += '>';
                
				
				
                strOptionSelectOptions = '<option value="">Select One...</option>';
				$.each(arrOptions, function(strDaily,arrDoses){
					
						
						
						$.each(arrDoses, function (ii,strDose) {
								objValueAndLabel = agcBuildOptionsDropDownValueAndLabel(strDaily,strDose,objDrugs['Drug'+strDrug][strSelectedDrug]);
								strOptionSelectOptions += '<option value="'+objValueAndLabel['value']+'">'+objValueAndLabel['label']+'</option>';
						});
						

				});
                strOptionSelect += strOptionSelectOptions+'</select>';
                
				agc_console(strDrug);
				/*if (strDrug === 'ICS')
				{
					$('#'+strDrug+"NewUsage").change(function(){agc_calc_BDPEquivalent_ICS();});
				}*/
                
                $('#'+strTargetForInsert+'_group').append(strOptionSelect);
				 //.appendTo($('#'+strTargetForInsert+'_group'))
				
				if ((strNewUsage !== '') && !booSuppressSelected)
				{
					$('#agc-form').bootstrapValidator('addField',$('#'+strDrugType+'NewUsage'));
					$('#'+strDrugType+"NewUsage option").each(function(select_index,objOption){
						if ($(objOption).val() === strNewUsage)
						{
							$(objOption).attr('selected','selected');
						}
					});           
					$('#agc-form').bootstrapValidator('revalidateField',$('#'+strDrugType+'NewUsage'));
				}
				else
				{
					$($('#'+strDrugType+"NewUsage option")[0]).attr('selected','selected');
					$('#agc-form').bootstrapValidator('addField',$('#'+strDrugType+'NewUsage'));
				}
				
			/*}
			else
			{
				objValueAndLabel = agcBuildOptionsDropDownValueAndLabel(objDrugs['Drug'+strDrug][strSelectedDrug]['nooftimes'],'');
				$('<option/>',{value:objValueAndLabel['value'],'html':objValueAndLabel['label'],'selected':'selected'}).appendTo($('#'+strDrug+"NewUsage"));
				console.log('added option pre-selected single field; one option available');
			}*/
		}
		else
		{
			$('<input/>',{type:'hidden',name:strDrugType+"NewUsage",id:strDrugType+"NewUsage",value:'NA'}).appendTo($('#'+strTargetForInsert+'_group'));
			
		}
    
	}
	else
	{
		$('<input/>',{type:'hidden',name:strDrugType+"NewUsage",id:strDrugType+"NewUsage",value:'NA'}).appendTo($('#'+strTargetForInsert+'_group'));
			
	}
}


function agcOptionsForDrugs(arrDrugsOptions,strMode,strCurrentDrugName,strNewDrugPreviouslySelected,strOptionClass)
{
    
    
    objReturn = {strDrugsOptions:'',selected:false};
    $.each(
                    arrDrugsOptions
                    ,function(key,obj)
                        { 
                            if ((strMode === 'start')
                                    || (strMode === 'current')
                                    || ((strMode === 'changetherapy') /*&& (strCurrentDrugName !== obj.drugname)*/)
                                    //|| ((strMode === 'changedose') && (strCurrentDrugName === obj.drugname))
                                )
                            {
                                strBDPValue = '';
                                if (obj.hasOwnProperty('bdpequivalentvalue'))
                                    {
                                        
                                        strBDPValue = obj.bdpequivalentvalue;
                                    }
                                        objReturn.strDrugsOptions += '<option value="'+key+'" data-agcbdpequivvalue="'+strBDPValue+'"';
                                        if ((strNewDrugPreviouslySelected !== '') && (strNewDrugPreviouslySelected === key))
                                            {
                                                objReturn.selected = true;
                                                
                                                objReturn.strDrugsOptions += ' selected="selected"';
                                            }
                                        if (strOptionClass !== '')
                                            {
                                                objReturn.strDrugsOptions += ' class="'+strOptionClass+'"';
                                            }
                                        objReturn.strDrugsOptions += '>';
                                        if (obj.locality.toUpperCase() !== 'FALSE')
                                        {
                                            objReturn.strDrugsOptions += '<span style="color:red;">&bull;</span>';
                                        }
                                        objReturn.strDrugsOptions += obj.label+'</option>';
                            }
                        }
                  );
    return objReturn;
}


function agcBuildDrugDropDownOptions(srcObject,strMode)
{
    
    
    jqO = srcObject;
    strName = jqO.attr('data-agcdrugtype');
    strNewDrugPreviouslySelected = jqO.attr('data-agcnewdrug');
    allselected = false;
    highselected = false;
    lowselected = false;
    strDrugsOptions = '';
    strCurrentDrugName = '';
    if (jqO.attr('data-agccurrentdrug') !== '')
    {
        objDrugList = objDrugs['Drug'+jqO.attr('data-agcdrug')];
        strCurrentDrugName = objDrugList[jqO.attr('data-agccurrentdrug')].drugname;
    }
    
    
    if (jqO.attr('data-agcbdpequiv') === 'true')
    {
        
        booUseHighLowDose = false;
        
        if (booUseHighLowDose)
        {
        
            arrDrugsOptions = {'highdose':{},'lowdose':{}};

            $.each(
                        objDrugs['Drug'+jqO.attr('data-agcdrug')]
                        ,function(key,obj)
                            { 
                                intBdp = 0;
                                if (obj.bdpequiv !== '')
                                {
                                    intBdp = parseInt(obj.bdpequiv);
                                }

                                obj.bdpequivalentvalue = intBdp;

                                if (intBdp >= 400)
                                {
                                    arrDrugsOptions.highdose[key] = obj;
                                }
                                else
                                {
                                    arrDrugsOptions.lowdose[key] = obj;
                                }
                            });
            strDrugsOptions += '<optgroup label="Low Doses">';
            arrReturn = agcOptionsForDrugs(arrDrugsOptions.lowdose,strMode,strCurrentDrugName,strNewDrugPreviouslySelected,"agc-drugs-lowdose");
            lowselected = arrReturn.selected;
            strDrugsOptions += arrReturn.strDrugsOptions;
            strDrugsOptions += '</optgroup>';

            strDrugsOptions += '<optgroup label="High Doses">';
            arrReturn = agcOptionsForDrugs(arrDrugsOptions.highdose,strMode,strCurrentDrugName,strNewDrugPreviouslySelected,"agc-drugs-highdose");
            highselected = arrReturn.selected;
            strDrugsOptions += arrReturn.strDrugsOptions;
            strDrugsOptions += '</optgroup>';
        }
        else
        {
            arrReturn = agcOptionsForDrugs(objDrugs['Drug'+jqO.attr('data-agcdrug')],strMode,strCurrentDrugName,strNewDrugPreviouslySelected,"");
            allselected = arrReturn.selected;
            strDrugsOptions += arrReturn.strDrugsOptions;
        }
    }
    else
    {
        arrReturn = agcOptionsForDrugs(objDrugs['Drug'+jqO.attr('data-agcdrug')],strMode,strCurrentDrugName,strNewDrugPreviouslySelected,"");
        allselected = arrReturn.selected;
        strDrugsOptions += arrReturn.strDrugsOptions;
    }
    
    
    
    
        strDrugsOptions +=                 '<option value="" data-title=""';
        if (!allselected && !highselected && !lowselected)
        {
            strDrugsOptions += ' selected="selected"';
        }
        strDrugsOptions +=                 '></option>';                   
   
   return strDrugsOptions;
}

function agcBuildDrugDropDown(srcObject,strMode)
{
    strDatabaseUpdateFunc = '';
    if (srcObject.attr('data-agcdrugfunc') !== '')
    {
        strDatabaseUpdateFunc = srcObject.attr('data-agcdrugfunc')+';';
    }
    
    strUpdateFunc = " onChange=\"agc_console('OnChangeTest');agcBuildOptionsDropDown('"+srcObject.attr('data-agcdrugtype')+'DrugDropDownInsert'+"','"+srcObject.attr('data-agcdrugtype')+"','"+srcObject.attr('data-agcdrug')+"','"+srcObject.attr('data-agcnewusage')+"', this.options[this.selectedIndex].value,'"+strDatabaseUpdateFunc+"',true);"+strDatabaseUpdateFunc+"; if (typeof agc_calc_WarningMoreThanOneLama == 'function') { agc_calc_WarningMoreThanOneLama(); }if (typeof agc_calc_WarningMoreThanOneLaba == 'function') {agc_calc_WarningMoreThanOneLaba(); }if (typeof agc_calc_WarningDrugsWithoutICS == 'function') { agc_calc_WarningDrugsWithoutICS();}if (typeof agc_calc_NoSABA == 'function') { agc_calc_NoSABA();} \"";
	
    strName = srcObject.attr('data-agcdrugtype');
    strDrugs = '<div class="col-md-8 layout-holder" >'+
                        '<div class="form-group">'+
                            '<select name="'+strName+'NewDrug" class = "form-control chosen-select" id = "'+strName+'NewDrug" data-bv-notempty="true" '+strUpdateFunc;
                            if (booAuditReadOnly)
                            {   
                                strDrugs+= ' disabled=\'disabled\'';
                            }
                            strDrugs += '>';
    
                            strDrugs += agcBuildDrugDropDownOptions(srcObject,strMode);
                                          
    
                  strDrugs += '</select>'+
                        '</div>'+
                 '</div>';
    
    return strDrugs;
}

function agcBuildRemoveButton(strRowId,strName,strMode)
{
    onClickJavascript = "javascript:agcRemoveRow('"+strRowId+"','"+strName+"','"+strMode+"');";
    
    strButton = '<div class="col-md-1 layout-holder" >'+
                        '<div class="form-group">'+
                            '<button  name="'+strName+'RemoveNewMeds" class = "form-control" id = "'+strName+'RemoveNewMeds" type="button" onClick="'+onClickJavascript+'"';
                    if (booAuditReadOnly)
                               {
                               strButton += ' disabled=\'disabled\'';
                               }
                           strButton +='>Cancel</button>'+
                        '</div>'+
                 '</div>';
    
    return strButton;
}

function agcInsertDrugDropDowns(strDrugType,strMode,strAddOrEdit)
{


	//console.log('JP Test '+strDrugType);
    srcObject = {};
    if (strAddOrEdit === 'edit')
    {
        srcObject = $('#btn'+strDrugType+'Change');
    }
    else
    {
        srcObject = $('#btn'+strDrugType+'Add');
    }
    objInput = {
                rowreference: srcObject.attr('data-agcrowid'),
                fieldreference: srcObject.attr('data-agcdrugtype'),
                options: srcObject.attr('data-agcoptions')
                };
    if ($('#'+objInput.fieldreference+'DrugDropDownInsert').attr('data-agccontentmarker') !== strMode)
    {
        
        
        removeDrugDropDowns(objInput.fieldreference);
        
        

        //strOptions      = agcBuildOptionsDropDown(srcObject);
        strDrugs        = agcBuildDrugDropDown(srcObject,strMode);



        $('#'+objInput.fieldreference+'DrugDropDownInsert').html(strDrugs);
		agc_console(strName+'NewDrug');
		
		
        /*$('#'+strName+'NewDrug').change(function(e){ console.log(objInput.fieldreference+'DrugDropDownInsert'); agcBuildOptionsDropDown(objInput.fieldreference+'DrugDropDownInsert', srcObject.attr('data-agcdrugtype'),srcObject.attr('data-agcdrug'),srcObject.attr('data-agcnewusage'), this.value,true);});
        */
        

        
        if (!booAuditReadOnly)
        {
            $('#'+objInput.fieldreference+'NewDrug').chosen({allow_single_deselect: true}).change(function(e) {
                                                    $('#agc-form')
                                                            .bootstrapValidator('updateStatus', $(this).attr('name'), 'NOT_VALIDATED')
                                                            .bootstrapValidator('revalidateField', $(this).attr('name'));
                                                }).end();
        

            $('#agc-form').bootstrapValidator('addField',$('#'+objInput.fieldreference+'NewDrug'));
            

            if (srcObject.attr('data-agcnewdrug') !== '')
            {
                $('#agc-form').bootstrapValidator('revalidateField',objInput.fieldreference+'NewDrug');
                agcBuildOptionsDropDown(srcObject.attr('data-agcdrugtype')+'DrugDropDownInsert',srcObject.attr('data-agcdrugtype'),srcObject.attr('data-agcdrug'),srcObject.attr('data-agcnewusage'),srcObject.attr('data-agcnewdrug'),srcObject.attr('data-agcdrugfunc'),false);
                //agcBuildOptionsDropDown(objInput.fieldreference+'DrugDropDownInsert',srcObject,srcObject.attr('data-agcnewdrug'),false);
                
            }
            /*if (srcObject.attr('data-agcnewusage') !== '')
            {
                $('#agc-form').bootstrapValidator('revalidateField',objInput.fieldreference+'NewUsage');
            }*/

        }
        $('#'+objInput.fieldreference+'DrugDropDownInsert').attr('data-agccontentmarker',strMode);



        arrUpdatedFields = [];
        if ($('#updated_fields').val().length > 0)
        {
            arrUpdatedFields = $('#updated_fields').val().split('|');
        }

        if (!Array.prototype.indexOf) {
            Array.prototype.indexOf = function(obj, start) 
            {
                    for (var i = (start || 0), j = this.length; i < j; i++) {
                        if (this[i] === obj) 
                        { 
                            return i; 
                        }
                    }
                    return -1;
            };
        }

        if (arrUpdatedFields.indexOf(objInput.fieldreference+'NewDrug') <0)
        {
            arrUpdatedFields.push(objInput.fieldreference+'NewDrug',objInput.fieldreference+'NewUsage');
            $('#updated_fields').val(arrUpdatedFields.join('|'));
        }
    }
}

function agcInsertReasonField(strFieldReference)
{
    if ($('#'+objInput.rowreference+'_insertreason').html() === '')
    {
        jqObjReasonLabel = $('<div/>',{id:strFieldReference+'reason-label',class:'col-md-3',html:'Reason:'});
        jqObjReasonField = $('<div/>',{id:strFieldReference+'reason-holder',class:'col-md-9'});
        $('<input/>',{type:'text',id:strFieldReference+'ChangeReason',name:strFieldReference+'ChangeReason',class:'form-control'}).appendTo(jqObjReasonField);

        

        jqObjReasonLabel.appendTo($('#'+objInput.rowreference+'_insertreason'));
        jqObjReasonField.appendTo($('#'+objInput.rowreference+'_insertreason'));
        $('#'+strFieldReference+'ChangeReason').attr('data-bv-notempty',"true");
        $('#agc-form').bootstrapValidator('addField',$('#'+objInput.fieldreference+'ChangeReason'));
    }
}

function agcStopTherapy(strFieldReference)
{
    if ($('#'+objInput.fieldreference+'DrugDropDownInsert').attr('data-agccontentmarker') !== 'stop') 
    {
        removeDrugDropDowns(strFieldReference);    
        $('#'+strFieldReference+'DrugDropDownInsert').html('Therapy Withdrawn');
        $('#'+objInput.fieldreference+'DrugDropDownInsert').attr('data-agccontentmarker','stop');
    }
}

function agcAddDrugEditor(srcObject,strMode)
{
    objInput = {rowreference:'',fieldreference:'',options:''};
    if (srcObject instanceof Array)
    {
        objInput.rowreference       = srcObject[0];
        objInput.fieldreference     = srcObject[1];
        objInput.options            = srcObject[2];
    }
    else
    {
        objInput.rowreference       = $(srcObject).attr('data-agcrowid');
        objInput.fieldreference     = $(srcObject).attr('data-agcdrugtype');
        objInput.options            = $(srcObject).attr('data-agcoptions');
        
    }
    
    objButtonObject = {};
    
    //strRowId,strFieldBaseNameID,strOptionsForUsage
    if (strMode==='add')
    {
        objButtonObject = $('#btn'+objInput.fieldreference+'Add');
    }
    else
    {
        objButtonObject = $('#btn'+objInput.fieldreference+'Change');
       
    }    
    
    objButtonObject.attr('disabled',true);
    objButtonObject.hide();
    
    
    strRemoveButton = agcBuildRemoveButton(objInput.rowreference,objInput.fieldreference,strMode);
    strControllerHolder ='<div class="col-md-8 layout-holder" id="'+objInput.fieldreference+'DrugDropDownInsert" data-agccontentmarker="empty" ></div>';
    //objFieldValues = objChangedValues[objInput.fieldreference];
    booInjectDrugDropDowns = false;
    if (strMode === 'edit')
    {
        if ((objButtonObject.attr('data-agcchangetype') === 'start') || ((objButtonObject.attr('data-agcchangetype') !== 'start') && (objButtonObject.attr('data-agccurrentdrug') === '')))
        {
            strModeSelector     = '<div class="col-md-3 col-md-offset-2 layout-holder" >Start Therapy:'+
                                    '<input type="hidden" name="'+objInput.fieldreference+'ChangeType" id="'+objInput.fieldreference+'ChangeType" value="start" />'+
                                    '</div>';
            booInjectDrugDropDowns = true;
        }
        else
        {
            strModeSelector = agcBuildModeSelector(objInput.fieldreference);
            if ((objButtonObject.attr('data-agcchangetype') !== 'stop') && (objButtonObject.attr('data-agcchangetype') !== ''))
            {
                booInjectDrugDropDowns = true;
            }
        }
    }
    else
    {
        strModeSelector     = '<div class="col-md-1 col-md-offset-1 layout-holder" >Currently:</div>';
    }
    
    $('#'+objInput.rowreference).after('<div class="row" id="'+objInput.rowreference+'_insert">'+strModeSelector+strControllerHolder+strRemoveButton+'</div>');
    if (strMode === 'edit')
    {
        $('#'+objInput.rowreference+'_insert').after($('<div/>',{class:'row',id:objInput.rowreference+'_insertreason'}));
    }
    
    if (strMode === 'edit')
    {
        if (objButtonObject.attr('data-agcchangetype') === 'stop')
        {
            agcStopTherapy(objInput.fieldreference);
        }
    
    
        if (booInjectDrugDropDowns)
        {
            strChangeType = objButtonObject.attr('data-agcchangetype');
            if (strChangeType === '')
            {
                strChangeType = 'start';
            }
            agcInsertDrugDropDowns(objInput.fieldreference,strChangeType,strMode);
        }
        else
        {
            $('#agc-form').bootstrapValidator('addField',objInput.fieldreference+'ChangeType');
            if ((objButtonObject.attr('data-agcchangetype') !== 'start') && (objButtonObject.attr('data-agcchangetype') !== ''))
                {
                    $('#agc-form').bootstrapValidator('revalidateField',objInput.fieldreference+'ChangeType');
                }
        }
        
       
    }
    else
    {
        agcInsertDrugDropDowns(objInput.fieldreference,'current',strMode);
    }
    
     
        arrUpdatedFields = [];
        if ($('#updated_fields').val().length > 0)
        {
            arrUpdatedFields = $('#updated_fields').val().split('|');
        }

        if (!Array.prototype.indexOf) {
            Array.prototype.indexOf = function(obj, start) 
            {
                    for (var i = (start || 0), j = this.length; i < j; i++) {
                        if (this[i] === obj) 
                        { 
                            return i; 
                        }
                    }
                    return -1;
            };
        }

        if (arrUpdatedFields.indexOf(objInput.fieldreference+'ChangeType') <0)
        {
            arrUpdatedFields.push(objInput.fieldreference+'ChangeType');
            $('#updated_fields').val(arrUpdatedFields.join('|'));
        }
    if ((strMode !== 'edit') && (objInput.fieldreference === 'CurrentMedication_CurrentDrugOralSteroids'))
    {
        agc_EventHandler(false);
        //$('#agc-form').bootstrapValidator('revalidateField','CurrentMedication_HowLongOralSteroids');
    }
	
	if (typeof agc_calc_WarningMoreThanOneLama == 'function') { 
	  agc_calc_WarningMoreThanOneLama(); 
	}
	
	if (typeof agc_calc_WarningMoreThanOneLaba == 'function') { 
	  agc_calc_WarningMoreThanOneLaba(); 
	}
	
	if (typeof agc_calc_WarningDrugsWithoutICS == 'function') { 
	  agc_calc_WarningDrugsWithoutICS(); 
	}
	
	if (typeof agc_calc_NoSABA == 'function') { 
	  agc_calc_NoSABA(); 
	}
	
}


function agcRemoveRow(strRowId,strFieldBaseNameID,strMode)
{
    if (strMode === 'edit')
    {
        $('#btn'+strFieldBaseNameID+'Change').attr('disabled',false);
        $('#btn'+strFieldBaseNameID+'Change').show();
    
        
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldBaseNameID+'NewDrug'));
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldBaseNameID+'NewUsage'));
        
        $('#agc-form').bootstrapValidator('removeField',$('#'+objInput.fieldreference+'ChangeReason'));
        $('#'+strRowId+'_insertreason').remove();
    }
    else
    {
        $('#btn'+strFieldBaseNameID+'Add').attr('disabled',false);
        $('#btn'+strFieldBaseNameID+'Add').show();
        
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldBaseNameID+'NewDrug'));
        $('#agc-form').bootstrapValidator('removeField',$('#'+strFieldBaseNameID+'NewUsage'));
        
        
    }
    $('#'+strRowId+'_insert').remove();
    if ((strMode !== 'edit') && (strFieldBaseNameID === 'CurrentMedication_CurrentDrugOralSteroids'))
    {
        agc_EventHandler(false);
    }
    
    
    /*
    arrUpdatedFields = $('#updated_fields').val().split('|');
    arrNewUpdatedFields = [];
    $.each(arrUpdatedFields, function(index,value) {
        if (value.search(strFieldBaseNameID) < 0)
        {
            arrNewUpdatedFields.push(value);
        }
    });
    
    $('#updated_fields').val(arrNewUpdatedFields.join('|'));
    */
	
	
	if (typeof agc_calc_WarningMoreThanOneLama == 'function') { 
	  agc_calc_WarningMoreThanOneLama(); 
	}
	
	if (typeof agc_calc_WarningMoreThanOneLaba == 'function') { 
	  agc_calc_WarningMoreThanOneLaba(); 
	}
	
	if (typeof agc_calc_WarningDrugsWithoutICS == 'function') { 
	  agc_calc_WarningDrugsWithoutICS(); 
	}

	if (typeof agc_calc_NoSABA == 'function') { 
	  agc_calc_NoSABA(); 
	}
	
}
