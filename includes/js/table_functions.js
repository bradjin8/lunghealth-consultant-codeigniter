var agc_TAB_booAllowZeroes = true;

function agc_TAB_alignColumn(strTargetTable,strAlignment,mixColumnReference)
{
    
    if ($.isNumeric(mixColumnReference))
    {
        intColumnPosition = mixColumnReference;
    }
    else
    {   
        intColumnPosition = agc_TAB_getColumnPositionOf(strTargetTable,mixColumnReference);
        
    }
    
    
    
    $($("#"+strTargetTable).find('thead').find('tr').find('th')[intColumnPosition]).css('text-align',strAlignment);
    
    
    
    arrBodyRows = $("#"+strTargetTable).find('tbody').find('tr');
    
    $.each(arrBodyRows, function(i,row){
        $($(row).find('td')[intColumnPosition]).css('text-align',strAlignment);
    });
}


function agc_TAB_colourHistoricValues(strTargetTable,arrColumnsToCheck)
{
    
    arrRows = $("#"+strTargetTable).find('tbody').find('tr');
    arrColumnPositionsToCheck = new Array();
    if (typeof arrColumnsToCheck !== 'undefined')
    {
        $.each(arrColumnsToCheck, function(i,column_reference){
            intColumnPosition = agc_TAB_getColumnPositionOf(strTargetTable,column_reference);
            if (intColumnPosition > 0)
            {
                arrColumnPositionsToCheck.push(intColumnPosition);
            }
        });
    }
    
    $.each(arrRows, function (i,row){
        
        jqoRow = $(row);
        booValueFound = false;
        if (arrColumnPositionsToCheck.length > 0)
        {
           
           $.each(arrColumnPositionsToCheck, function (ii,cell_pos) {
                cell = jqoRow.find('td')[cell_pos];
                
                cell_contents = $(cell).html();
                
                if (cell_contents == 'true')
                {
                    booValueFound = true;
                    
                }
                
           });
        }
        else
        {
           
           arrCells = jqoRow.find('td');
           $.each(arrCells, function (ii,cell) {
                
                cell_contents = $(cell).html();
                
                if (cell_contents == 'true')
                {
                    booValueFound = true;
                }
                
           });
        }
        if (booValueFound)
        {
            jqoRow.css('background-color','#C0C0C0');
            
        }
    });
}

function agc_TAB_hideRowsOfNoUse(strTargetTable,arrColumnsToCheck)
{
    
    arrRows = $("#"+strTargetTable).find('tbody').find('tr');
    arrColumnPositionsToCheck = new Array();
    if (typeof arrColumnsToCheck !== 'undefined')
    {
        $.each(arrColumnsToCheck, function(i,column_reference){
            intColumnPosition = agc_TAB_getColumnPositionOf(strTargetTable,column_reference);
            if (intColumnPosition > 0)
            {
                arrColumnPositionsToCheck.push(intColumnPosition);
            }
        });
    }
    
    $.each(arrRows, function (i,row){
        
        jqoRow = $(row);
        booValueFound = false;
        if (arrColumnPositionsToCheck.length > 0)
        {
           
           $.each(arrColumnPositionsToCheck, function (ii,cell_pos) {
                cell = jqoRow.find('td')[cell_pos];
                
                cell_contents = $(cell).html();
                
                if ((cell_contents !== '') && (cell_contents !== '-'))
                {
                    booValueFound = true;
                    
                }
                
           });
        }
        else
        {
           
           arrCells = jqoRow.find('td');
           $.each(arrCells, function (ii,cell) {
                
                cell_contents = $(cell).html();
                
                if ((cell_contents !== '') && (cell_contents !== '-'))
                {
                    booValueFound = true;
                    
                }
                
           });
        }
        if (!booValueFound)
        {
            jqoRow.hide();
            
        }
    });
}

function agc_TAB_getStandardDeviation(arrDataSet)
{
    total = 0;
    arrCleanData = new Array();
    
    $.each(arrDataSet, function(i,v){
        if ((v !== '') && (v !== '-'))
        {
            arrCleanData.push(parseFloat(v));
        }
    });
    
    arrDataSet = arrCleanData;
    $.each(arrDataSet, function (index,number){
        total += number;
    });
    mean_average = total/arrDataSet.length;
    
    top_side_calc = 0;
    $.each(arrDataSet, function (index,number){
        top_side_calc += Math.pow((number-mean_average),2);
    });
    
    return ((Math.sqrt((top_side_calc/(arrDataSet.length - 1)))/mean_average)*100).toFixed(1);
}



function agc_TAB_BuildTableColumn(strTargetTable,arrColumnData,intPosition)
{
    if (typeof intPosition === 'undefined')
    {
        intPosition = -1;
    }
    if (typeof arrColumnData.suffix === 'undefined')
    {
        arrColumnData.suffix = '';
    }
    jqOTable = $('#'+strTargetTable);
    jqOHeaderRow = $(jqOTable.find('thead').find('tr')[0]);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    
    strNewHeaderId = jqOHeaderRow.attr('id')+'_c'+jqOHeaderRow.find('th').length;
    objCell = {id:strNewHeaderId,html:arrColumnData.title};
    
    
    if (intPosition < 0)
    {
        $('<th/>',objCell).appendTo(jqOHeaderRow);
    }
    else
    {
        if (intPosition === 0)
        {
            $('<th/>',objCell).prependTo(jqOHeaderRow);
        }
        else
        {
            $('<th/>',objCell).insertBefore($(jqOHeaderRow.find('th')[intPosition]));
        }

    }
    
    arrTableBodyRows.each(function(row_index,row_element){
        jqOBodyRow = $(row_element);
        
        strCellContent = arrColumnData.data[row_index];
        if (arrColumnData.data[row_index] !== '-')
        {
            strCellContent = arrColumnData.data[row_index]+arrColumnData.suffix;
        }
        
        objCell = {
                id:             jqOBodyRow.attr('id')+'_c'+jqOBodyRow.find('td').length,
                html:           strCellContent,
                data_original:  arrColumnData.data[row_index]
            };
        
        if ($.isNumeric(arrColumnData.data[row_index]))
        {
            objCell['class'] = 'text-right';
        }
        
        if (intPosition < 0)
        {
            $('<td/>',objCell).appendTo(jqOBodyRow);
        }
        else
        {
            if (intPosition === 0)
            {
                $('<td/>',objCell).prependTo(jqOBodyRow);
            }
            else
            {
                $('<td/>',objCell).insertBefore($(jqOBodyRow.find('td')[intPosition]));
            }
            
        }
    });
    
    arrTableFooterRows = jqOTable.find('tfoot').find('tr');
    if (arrTableFooterRows.length > 0)
    {
        arrTableFooterRows.each(function(row_index,row_element){
            jqOFooterRow = $(row_element);
            objCell = {id:jqOFooterRow.attr('id')+'_c'+jqOFooterRow.find('td').length,html:''};
            if (intPosition < 0)
            {
                $('<td/>',objCell).appendTo(jqOFooterRow);
            }
            else
            {
                if (intPosition === 0)
                {
                    $('<td/>',objCell).prependTo(jqOFooterRow);
                }
                else
                {
                    $('<td/>',objCell).insertBefore($(jqOFooterRow.find('td')[intPosition]));
                }
            }
        });
    }
}



function agc_TAB_BuildTableRow(jqObjTarget,arrRowData,strMode)
{
	
    strRowId     = jqObjTarget.attr('id')+'_r'+jqObjTarget.find('tr').length;
    
    $('<tr/>',{id:strRowId}).appendTo(jqObjTarget);
    jqObjRow = $('#'+strRowId);
    var intCellCount = 0;
    
    $.each(arrRowData,function(k,v){
        strCellTag = 'td';
        objCell = {id:strRowId+'_c'+intCellCount,html:v};
        
        
        if (strMode === 'head')
        {
            strCellTag = 'th';
        }
        else
        {
            if ((v === '') || ((agc_TAB_booAllowZeroes !== true) && (parseFloat(v) === 0)))
            {
                objCell.html = '-';
            }
            else
            {
                if ($.isNumeric(v))
                {
                    objCell['class'] = 'text-right';
                }
            }
        }
        
        $('<' +strCellTag+ '/>',objCell).appendTo(jqObjRow);
        
        intCellCount++;
    });
}

function agc_TAB_BuildFooterRow(strTargetTable,arrRowData)
{
    jqObjTfooter        = $($('#'+strTargetTable).find('tfoot')[0]);
    arrHeaderCells      = $('#'+strTargetTable).find('thead').find('tr').find('th');
    
    intTitleLocation    = arrRowData.column -1;
    intContentLocation  = arrRowData.column;
    
    strRowId            = jqObjTfooter.attr('id')+'_r'+jqObjTfooter.find('tr').length;
    
    jqoFooterRow        = $('<tr/>',{id:strRowId}).appendTo(jqObjTfooter);
    
    $.each(arrHeaderCells, function(index,eleHeaderCell){
        jqoHeaderCell = $(eleHeaderCell);
        objFooterCell = {
            id: strRowId+'_c'+jqoFooterRow.find('td').length
        };
        
        
        if (index === intTitleLocation)
        {
            objFooterCell = arrRowData.title;
        }
        if (index === intContentLocation)
        {
            objFooterCell = arrRowData.content;
        }
        jqoFooterCell = $('<td/>',objFooterCell).appendTo(jqoFooterRow);
        
        if (jqoHeaderCell.css('display') === 'none')
        {
            jqoFooterCell.hide();
        }
    });
    
    
    /*
    jqObjectFooter = $(eleFooter);
    strRowId = jqObjectFooter.attr('id')+'_r'+jqObjectFooter.find('tr').length;
    $('<tr/>',{id:strRowId}).appendTo(jqObjectFooter);
    intCellCount = 0;
    $.each(arrRowData,function(k,objCell){
        objCell['id'] = strRowId+'_c'+intCellCount;
        $('<td/>',objCell).appendTo($('#'+strRowId));
        intCellCount++;
    });*/
}

function agc_TAB_getNumberOfColumns(strTargetTable)
{
    return $("#"+strTargetTable).find('thead').find('tr').find('th').length;
}

function agc_TAB_hideColumn(strTargetTable,mixColumnToHide)
{
    if ($.isNumeric(mixColumnToHide))
    {
        arrTargetColumns = [mixColumnToHide];
    }
    else
    {
        arrTargetColumns = mixColumnToHide;
    }
    $.each(arrTargetColumns,function(index,intColumnTarget) {
        $($("#"+strTargetTable).find('thead').find('tr').find('th')[intColumnTarget]).hide();
        $("#"+strTargetTable).find('tbody').find('tr').each(function (index,element)
        {
            $($(element).find('td')[intColumnTarget]).hide();
        });
    });
}

function agc_TAB_showColumn(strTargetTable,mixColumnToShow)
{
    if ($.isNumeric(mixColumnToShow))
    {
        arrTargetColumns = [mixColumnToShow];
    }
    else
    {
        arrTargetColumns = mixColumnToShow;
    }
    $.each(arrTargetColumns,function(index,intColumnTarget) {
        $($("#"+strTargetTable).find('thead').find('tr').find('th')[intColumnTarget]).show();
        $("#"+strTargetTable).find('tbody').find('tr').each(function (index,element)
        {
            $($(element).find('td')[intColumnTarget]).show();
        });
    });
}

function agc_TAB_getColumnPositionOf(strTargetTable,strHeaderText)
{
    intCounter = 0;
    intReturn = -1;
    $("#"+strTargetTable).find('thead').find('tr').find('th').each(function (index,jqO)
    {
        
        if ($(jqO).html() === strHeaderText)
        {
            intReturn = intCounter;
        }
        intCounter++;
    });
    return intReturn;
}

function agc_TAB_BuildTable_Pro(strTargetTable,arrData,booAllowZeroes)
{
    if (typeof booAllowZeroes !== 'undefined')
    {
        agc_TAB_booAllowZeroes = booAllowZeroes;
    }
    
    jqObjTable = $("#"+strTargetTable);
    
    jqObjTable.html('');
    
    strTableHeadId = jqObjTable.attr('id')+'_thead';
    strTableBodyId = jqObjTable.attr('id')+'_tbody';
    strTableFootId = jqObjTable.attr('id')+'_tfoot';
    
    $('<thead/>',{id:strTableHeadId}).appendTo(jqObjTable);
    $('<tbody/>',{id:strTableBodyId}).appendTo(jqObjTable);
    $('<tfoot/>',{id:strTableFootId}).appendTo(jqObjTable);
    
    
    arrHeaderData = new Array();
    $.each(arrData[0], function(k , v) {
       arrHeaderData.push(k); 
    });
    agc_TAB_BuildTableRow($('#'+strTableHeadId),arrHeaderData,'head');
    
    //Need to sort into Date Order?
    
    
    $.each(arrData,function(k,v){
        agc_TAB_BuildTableRow($('#'+strTableBodyId),v,'body');
    });
    
    
}

function agc_TAB_getPEFValues(arrData)
{
    arrPEFValues = new Array();
    $.each(arrData,function(k,v){
        $.each(v,function(index,value){
            if ((index === 'PEF') && (value !== ''))
            {
                arrPEFValues.push(value);
            }
        });
    });
    return arrPEFValues;
}

function agc_TAB_CalculateReversibility(strTargetTable)
{
    arrReversibilityValues  = new Array();
    intPostFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'PostFEV1');
    intPreFEV1Column        = agc_TAB_getColumnPositionOf(strTargetTable,'PreFEV1');
    
    jqOTable = $('#'+strTargetTable);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents){
        arrCells    = $(row_contents).find('td');
        preText = $(arrCells[intPreFEV1Column]).html();
        postText = $(arrCells[intPostFEV1Column]).html();
        if ((preText !== '-') && (postText !== '-'))
        {
            floPre      = parseFloat(preText);
            floPost     = parseFloat(postText);
            if ((parseInt(floPost*10)-parseInt(floPre*10)) >= 4)
            {
                arrReversibilityValues.push('&bull;');
            }
            else
            {
                arrReversibilityValues.push('-');
            }
        }
        else
        {
            arrReversibilityValues.push('');
        }
    });
    return arrReversibilityValues;
    
}

function agc_TAB_CalculateBestFEV1(strTargetTable)
{
    jqOTable = $('#'+strTargetTable);
    
    
    
    arrBestFEV1Values  = new Array();
    intPostFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'PostFEV1');
    intPreFEV1Column        = agc_TAB_getColumnPositionOf(strTargetTable,'PreFEV1');
    
    
    
    
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents){
        arrCells    = $(row_contents).find('td');
        
        
        preText = $(arrCells[intPreFEV1Column]).html();
        postText = $(arrCells[intPostFEV1Column]).html();
        
        
        
        if ((preText !== '-') || (postText !== '-'))
        {
            if ((preText !== '-') && (postText !== '-'))
            {
                floPre      = parseFloat(preText);
                floPost     = parseFloat(postText);
                arrBestFEV1Values.push(Math.max(floPre,floPost));
            }
            else
            {
                if (postText !== '-')
                {
                    arrBestFEV1Values.push(parseFloat(postText));
                }
                else
                {
                    arrBestFEV1Values.push(parseFloat(preText));
                }
            }
        }
        else
        {
            arrBestFEV1Values.push('-');
        }
    });
    return arrBestFEV1Values;
}

function agc_TAB_CalculateBestSVC(strTargetTable)
{
    arrBestSVCValues        = new Array();
    
    intPostSVCColumn       = agc_TAB_getColumnPositionOf(strTargetTable,'PostSVC');
    intPreSVCColumn        = agc_TAB_getColumnPositionOf(strTargetTable,'PreSVC');
    
    intPostFVCColumn       = agc_TAB_getColumnPositionOf(strTargetTable,'PostFVC');
    intPreFVCColumn        = agc_TAB_getColumnPositionOf(strTargetTable,'PreFVC');
    
    jqOTable = $('#'+strTargetTable);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents){
        arrCells    = $(row_contents).find('td');
        preSVCText = $(arrCells[intPreSVCColumn]).html();
        postSVCText = $(arrCells[intPostSVCColumn]).html();
        
        preFVCText = $(arrCells[intPreFVCColumn]).html();
        postFVCText = $(arrCells[intPostFVCColumn]).html();
        
		console.log(preSVCText);
		console.log(postSVCText);
		console.log(preFVCText);
		console.log(postFVCText);
		
        if ((preSVCText !== '-') || (postSVCText !== '-') || (preFVCText !== '-') || (postFVCText !== '-'))
        {
            if (preSVCText !== '-')
            {
                floSVCPre      = parseFloat(preSVCText);
            }
            else
            {
                floSVCPre      = 0;
            }
            
            if (postSVCText !== '-')
            {
                floSVCPost      = parseFloat(postSVCText);
            }
            else
            {
                floSVCPost      = 0;
            }
            
            if (preFVCText !== '-')
            {
                floFVCPre      = parseFloat(preFVCText);
            }
            else
            {
                floFVCPre      = 0;
            }
            
            if (postFVCText !== '-')
            {
                floFVCPost     = parseFloat(postFVCText);
            }
            else
            {
                floFVCPost     = 0;
            }
            floMaxValue = Math.max(floSVCPre,floSVCPost,floFVCPre,floFVCPost);
            if (floMaxValue === 0)
            {
                floMaxValue = '-';
            }
            arrBestSVCValues.push(floMaxValue);
            
        }
        else
        {
            arrBestSVCValues.push('-');
        }
    });
    return arrBestSVCValues;
}

function agc_TAB_CalculatePercentPredicted(strTargetTable)
{
    arrPercentPredicted        = new Array();
    
    intBestFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'Best FEV1');
    intPredFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'PredFEV1');
    
    intPEFColumn            = agc_TAB_getColumnPositionOf(strTargetTable,'PEF');
    intPredPEFColumn        = agc_TAB_getColumnPositionOf(strTargetTable,'PredPEF');
    
    jqOTable = $('#'+strTargetTable);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents){
        arrCells    = $(row_contents).find('td');
        BestFEV1Text = $(arrCells[intBestFEV1Column]).html();
        PredFEV1Text = $(arrCells[intPredFEV1Column]).html();
        
        PEFText         = $(arrCells[intPEFColumn]).html();
        PredPEFText     = $(arrCells[intPredPEFColumn]).html();
        
        if ((PredFEV1Text !== '-') && (BestFEV1Text !== '-'))
        {
            
            floPredFEV = parseFloat(PredFEV1Text);
            floBestFEV = parseFloat(BestFEV1Text);
            floPercentPredicted = (floBestFEV/floPredFEV)*100;
            arrPercentPredicted.push(floPercentPredicted.toFixed(1));
        }
        else
        {
            if (PredPEFText !== '-')
            {
                floPredPEF = parseFloat(PredPEFText);
                floBestPEF = parseFloat(PEFText);
                floPercentPredicted = (floBestPEF/floPredPEF)*100;
                
                arrPercentPredicted.push(floPercentPredicted.toFixed(1));
            }
            else
            {
                arrPercentPredicted.push('-');
            }
        }
        
        
    });
    return arrPercentPredicted;
}

function agc_TAB_CalculateAirwayObstruction(strTargetTable)
{
    arrObstructionValues  = new Array();
    intPreFEV1Column        = agc_TAB_getColumnPositionOf(strTargetTable,'PreFEV1');
    intPostFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'PostFEV1');
    intPreFVCColumn         = agc_TAB_getColumnPositionOf(strTargetTable,'PreFVC');
    intPostFVCColumn        = agc_TAB_getColumnPositionOf(strTargetTable,'PostFVC');
    intPreSVCColumn         = agc_TAB_getColumnPositionOf(strTargetTable,'PreSVC');
    intPostSVCColumn        = agc_TAB_getColumnPositionOf(strTargetTable,'PostSVC');
    
    jqOTable = $('#'+strTargetTable);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents)
    {
        arrCells        = $(row_contents).find('td');
        preFEV1Text     = $(arrCells[intPreFEV1Column]).html();
        postFEV1Text    = $(arrCells[intPostFEV1Column]).html();
        preFVCText      = $(arrCells[intPreFVCColumn]).html();
        postFVCText     = $(arrCells[intPostFVCColumn]).html();
        preSVCText      = $(arrCells[intPreSVCColumn]).html();
        postSVCText     = $(arrCells[intPostSVCColumn]).html();
        
                
        if (preFEV1Text !== '-')
        {
            preFEV1Number = parseFloat(preFEV1Text);
        }
        else
        {
            preFEV1Number = 0;
        }
        if (postFEV1Text !== '-')
        {
            postFEV1Number = parseFloat(postFEV1Text);
        }
        else
        {
            postFEV1Number = 0;
        }
        if (preFVCText !== '-')
        {
            preFVCNumber = parseFloat(preFVCText);
        }
        else
        {
            preFVCNumber = 0;
        }
        if (postFVCText !== '-')
        {
            postFVCNumber = parseFloat(postFVCText);
        }
        else
        {
            postFVCNumber = 0;
        }
        if (preSVCText !== '-')
        {
            preSVCNumber = parseFloat(preSVCText);    
        }
        else
        {
            preSVCNumber = 0;
        }
        if(postSVCText !== '-')
        {
            postSVCNumber = parseFloat(postSVCText);
        }
        else
        {
            postSVCNumber = 0;
        }
            
        FEVMax = Math.max(preFEV1Number,postFEV1Number);
        OtherMax = Math.max(preFVCNumber,postFVCNumber,preSVCNumber,postSVCNumber);
        
        if 
            ((FEVMax !== 0) || (OtherMax !== 0))
            
          
        {
            if ((FEVMax/OtherMax) < 0.7)
            {
				console.log(FEVMax);
				console.log(OtherMax);
                arrObstructionValues.push('&bull;');
            }
            else
            {
                arrObstructionValues.push('-');
            }
        }
        else
        {
            arrObstructionValues.push('');
        }
        
        
    });
    return arrObstructionValues;
}

function agc_TAB_FEV1Variability(strTargetTable)
{
    booFEV1Variability  = false;
    floDifference       = 0.4;
    floMinFEV1          = 0;
    floMaxFEV1          = 0;
    
    intBestFEV1Column       = agc_TAB_getColumnPositionOf(strTargetTable,'Best FEV1');
    
    jqOTable = $('#'+strTargetTable);
    
    arrTableBodyRows = jqOTable.find('tbody').find('tr');
    arrTableBodyRows.each(function(row_index,row_contents){
        
        arrCells    = $(row_contents).find('td');
        BestFEV1Text = $(arrCells[intBestFEV1Column]).html();
        
        if(BestFEV1Text !== '-')
        {
            BestFEV1Number = parseFloat(BestFEV1Text);
            
            if ((floMinFEV1 === 0) || (BestFEV1Number < floMinFEV1))
            {
                floMinFEV1 = BestFEV1Number;
            }
            
            if ((floMaxFEV1 === 0) || (BestFEV1Number > floMaxFEV1))
            {
                floMaxFEV1 = BestFEV1Number;
            }
            
        }
    });
    
    if ((floMinFEV1 !== 0) && (floMaxFEV1 !== 0))
    {
        if ((floMaxFEV1-floMinFEV1) > floDifference)
        {
            booFEV1Variability = true;
        }
    }
    
    return booFEV1Variability;
    
}