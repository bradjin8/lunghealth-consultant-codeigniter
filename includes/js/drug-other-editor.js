var objAgcOtherDrugsList            = {};
var intAgcOtherDrugsListRegister    = 0;

function agc_ODE_DrugForm(objInput, strMode)
{
    if (strMode === 'form')
    {
        strName = objInput.attr('data-agcfieldbasename');
    }
    else
    {
        strName = objInput.strName;
        intNewRowReference = $('#'+objInput.strRowId+'_insertlisting').find('.agc-drugs-listing').length;
    }    
    
    
    arrDrugTypeOptions = {
            other: 'Other',
            nsaid: 'NSAID/Aspirin',
            betablocker: 'Beta Blocker',
            nasalsteroid: 'Nasal Steroid',
			antibiotic: 'Antibiotic',
			diuretic: 'Diuretic',
			boneprophylaxis: 'Bone Prophylaxis',
			antihistamine: 'Antihistamine'
    };
    
    strDrugTypeSelector = '';
    strDrugTypeSelector += 
        '<div class="col-md-5 layout-holder" >';
        if (strMode === 'form')
        {
            strDrugTypeSelector += 
            '<div class="form-group">'+
                '<input type="text" name="'+strName+'DrugName" id="'+strName+'DrugName" placeholder="Drug Name" value="" class="form-control" data-no-delete="0">'+
            '</div>';
        }
        else
        {
            strDrugTypeSelector += '<input type="hidden" name="CurrentMedication_OtherDrugs_'+intNewRowReference+'_Name" value="'+objInput.OtherDrugsName+'" />';
            strDrugTypeSelector += objInput.OtherDrugsName;
        }
    strDrugTypeSelector +=    
        '</div>';

    strDrugTypeSelector += 
        '<div class="col-md-3 layout-holder" >';
        if (strMode === 'form')
        {
            strDrugTypeSelector += 
            '<div class="form-group">'+
                '<input type="text" name="'+strName+'DrugUsage" id="'+strName+'DrugUsage" placeholder="Drug Usage" value="" class="form-control" data-no-delete="0">'+
            '</div>';
        }
        else
        {
            strDrugTypeSelector += '<input type="hidden" name="CurrentMedication_OtherDrugs_'+intNewRowReference+'_Usage" value="'+objInput.OtherDrugsUsage+'" />';
            strDrugTypeSelector += objInput.OtherDrugsUsage;
        }
    strDrugTypeSelector +=    
        '</div>';
    
    strDrugTypeSelector += 
        '<div class="col-md-3 layout-holder" >';
        if (strMode === 'form')
        {
            strDrugTypeSelector += 
            '<div class="form-group">'+
                '<select name="'+strName+'DrugType" id="'+strName+'DrugType" class="form-control" data-no-delete="0">';
        
                    $.each(arrDrugTypeOptions, function(index,value){
                        strDrugTypeSelector += '<option value="'+index+'">'+value+'</option>';
                    });
                    
                strDrugTypeSelector +=   
                    '<option value="" selected="selected">Please Choose One...</option>'+
                '</select>'+
            '</div>';
        }
        else
        {
            strDrugTypeSelector += '<input type="hidden" name="CurrentMedication_OtherDrugs_'+intNewRowReference+'_Type" value="'+objInput.OtherDrugsType+'" />';
            strDrugTypeSelector += arrDrugTypeOptions[objInput.OtherDrugsType];
        }
    strDrugTypeSelector +=    
        '</div>';
    
    
    strDrugTypeSelector += 
        '<div class="col-md-1 layout-holder" >'+
            '<div class="form-group">';
    if (strMode === 'form')
    {    
        strDrugTypeSelector += 
                '<button type="button" name="'+strName+'DrugAddition" class="btn btn-default" id="'+strName+'DrugAddition" value="true" class="no-validate" data-no-delete="0" '+
                        'onClick="javascript:agcAddOtherDrugToList(this);" data-agcfieldbasename="'+strName+'" data-agcrowid="'+objInput.attr('data-agcrowid')+'">'+
                    '<span>Add</span>'+
                '</button>';
    }
    else
    {
         
         strDrugTypeSelector += 
                '<button type="button" name="'+strName+'DrugAddition" class="btn btn-default" id="'+strName+'DrugSubtraction" value="true" class="no-validate" data-no-delete="0" '+
                        'onClick="javascript:agcRemoveOtherDrugFromList(this);" data-agcfieldbasename="'+strName+'" data-agclistingref="'+intNewRowReference+'" '+
                        
                        '">'+
                    '<span>Remove</span>'+
                '</button>';
    }
    strDrugTypeSelector += 
            '</div></div>'; 
    if (strMode === 'form')
    { 
        return strDrugTypeSelector;
    }
    else
    {
        return '<div class="agc-drugs-listing" id="agc-drugs-listing-'+intNewRowReference+'">'+strDrugTypeSelector+'</div>';
    }
}


function agcAddOtherDrugToList(objInput)
{
    
    agc_console(objInput);
    
    jqO = $(objInput);    
    jqO.attr('disabled',true);    
    
    strName = jqO.attr('data-agcfieldbasename');
    
    strDrugName = $('#'+strName+'DrugName').val();
    jqO.attr('data-agc-strDrugName', strDrugName);
    strDrugUsage = $('#'+strName+'DrugUsage').val();
    jqO.attr('data-agc-strDrugUsage', strDrugUsage);
    strDrugType = $('#'+strName+'DrugType option:selected').val();
    jqO.attr('data-agc-strDrugType', $('#'+strName+'DrugType option:selected').html());
    
    objDrugToAdd = {OtherDrugsName:strDrugName, OtherDrugsUsage:strDrugUsage,OtherDrugsType:strDrugType,strName:jqO.attr('data-agcfieldbasename'),strRowId:jqO.attr('data-agcrowid')};
    
    
    if ((strDrugName !== '') && (strDrugUsage !== '') && (strDrugType !== ''))
    {
        strCurrentListing = $('#'+jqO.attr('data-agcrowid')+'_insertlisting').html();
        $('#'+jqO.attr('data-agcrowid')+'_insertlisting').html(strCurrentListing+agc_ODE_DrugForm(objDrugToAdd, 'listing'));
        arrAGCOtherDrugsJSON.push(objDrugToAdd);
        $('#'+jqO.attr('data-agcrowid')+'_insertform').html();
        $('#'+jqO.attr('data-agcrowid')+'_insertform').html(agc_ODE_DrugForm(jqO,'form'));
    }
    else
    {
        jqO.attr('disabled',false);
    }
    agc_CheckOtherDrugs();
}

function agcRemoveOtherDrugFromList(objInput)
{
    jqO = $(objInput);
    
    jqO.attr('disabled',true);
    
    strFieldReferenceBaseName = "CurrentMedication_OtherDrugs_";
    
    arrOtherDrugsCurrentList = arrAGCOtherDrugsJSON;
    arrAGCOtherDrugsJSON = [];
    
    $.each(arrOtherDrugsCurrentList, function (index, object) {
        if ((object.OtherDrugsName !== $("[name='"+strFieldReferenceBaseName+jqO.attr('data-agclistingref')+"_Name']").val())  
                && (object.OtherDrugsUsage !==  $("[name='"+strFieldReferenceBaseName+jqO.attr('data-agclistingref')+"_Usage']").val())   
                && (object.OtherDrugsType !==  $("[name='"+strFieldReferenceBaseName+jqO.attr('data-agclistingref')+"_Type']").val()))
            {
                arrAGCOtherDrugsJSON.push(object);
            }
    });
    
    $("#agc-drugs-listing-"+jqO.attr('data-agclistingref')).remove();
    
    agc_CheckOtherDrugs();
}

function agcAddOtherDrugEditor(objInput)
{
    
    
    jqO = $(objInput);
    
    jqO.attr('disabled',true);
    jqO.hide();
    
    
    
    if ($('#'+jqO.attr('data-agcrowid')+'_insert').length === 0)
    {
        
        //strDivCode = '<div class="row" id="'+jqO.attr('data-agcrowid')+'_insert"><div class="row" id="'+jqO.attr('data-agcrowid')+'_insertlisting"></div><div class="row" id="'+jqO.attr('data-agcrowid')+'_insertform"></div></div>';
        
        $('#'+jqO.attr('data-agcrowid')).before($('<div/>', {id:jqO.attr('data-agcrowid')+'_insert', "class":'row'}));
        
                                           
    }
    else
    {
        // delete current contents
        $('#'+jqO.attr('data-agcrowid')+'_insert').html();
    }
    $('<div/>',{id:jqO.attr('data-agcrowid')+'_insertlisting'}).appendTo('#'+jqO.attr('data-agcrowid')+'_insert');
    // get current contents (from db)
    if (arrAGCOtherDrugsJSON.length > 0)
        {
            
            $.each(arrAGCOtherDrugsJSON, function(index, objDrugAdded) {
                objDrugAdded['strName']     = jqO.attr('data-agcfieldbasename');
                objDrugAdded['strRowId']    = jqO.attr('data-agcrowid');
                    
                $('#'+jqO.attr('data-agcrowid')+'_insertlisting').html($('#'+jqO.attr('data-agcrowid')+'_insertlisting').html()+agc_ODE_DrugForm(objDrugAdded,'listing'));
            });
            //('#'+jqO.attr('data-agcrowid')+'_insertlisting').html(strOtherDrugsAdded);
        }
    
    
    $('<div/>',{id:jqO.attr('data-agcrowid')+'_insertform'}).appendTo('#'+jqO.attr('data-agcrowid')+'_insert');
    
    //strDrugType = agc_ODE_DrugTypeSelector(jqO);
    
    $('#'+jqO.attr('data-agcrowid')+'_insertform').html(agc_ODE_DrugForm(jqO,'form'));
    
    agc_CheckOtherDrugs();
    
    
    /*
    jqO_InsertOutterDiv = $('#'+jqO.attr('data-agcrowid')+'_insert');   
    if (jqO.attr('data-agcotherdrugdata').length > 0)
    {
        // decipher stored stuff
    }
    
    strDrugTypeSelector = agc_ODE_DrugTypeSelector(jqO);
    jqO_InsertDiv.html(jqO_InsertDiv.html() + '<div class="row" id="'+jqO.attr('data-agcrowid')+'_insert_'+intAgcOtherDrugsListRegister+'"></div>');
    
    jqO_InsertNewDiv    = $('#'+jqO.attr('data-agcrowid')+'_insert'+intAgcOtherDrugsListRegister);
    */
}

function agc_CheckOtherDrugs()
{
    $("[name='CurrentMedication_CurrentOtherDrugsBB']").val('N');
    $("[name='CurrentMedication_CurrentOtherDrugsNS']").val('N');
    $("[name='CurrentMedication_CurrentOtherDrugsNSAID']").val('N');
	$("[name='CurrentMedication_CurrentOtherDrugsA']").val('N');
	$("[name='CurrentMedication_CurrentOtherDrugsD']").val('N');
	$("[name='CurrentMedication_CurrentOtherDrugsBP']").val('N');
	$("[name='CurrentMedication_CurrentOtherDrugsOther']").val('N');
	$("[name='CurrentMedication_CurrentOtherDrugsAH']").val('N');
	     
    if (arrAGCOtherDrugsJSON.length > 0)
    {

        $.each(arrAGCOtherDrugsJSON, function(index, objDrugAdded) {
			
            switch (objDrugAdded.OtherDrugsType)
            {
                case "nsaid":
                    $("[name='CurrentMedication_CurrentOtherDrugsNSAID']").val('Y');
                    break;
                case "betablocker":
                    $("[name='CurrentMedication_CurrentOtherDrugsBB']").val('Y');
                    break;
                case "nasalsteroid":
                    $("[name='CurrentMedication_CurrentOtherDrugsNS']").val('Y');
                    break;
				case "antibiotic":
                    $("[name='CurrentMedication_CurrentOtherDrugsA']").val('Y');
                    break;
				case "diuretic":
                    $("[name='CurrentMedication_CurrentOtherDrugsD']").val('Y');
                    break;
				case "boneprophylaxis":
                    $("[name='CurrentMedication_CurrentOtherDrugsBP']").val('Y');
                    break;
				case "other":
                    $("[name='CurrentMedication_CurrentOtherDrugsOther']").val('Y');
                    break;
				case "antihistamine":
                    $("[name='CurrentMedication_CurrentOtherDrugsAH']").val('Y');
                    break;
            }
        });
    }
}