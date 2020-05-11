function agc_calc_BDPEquivalent_ICS() {

    DrugSelected = $('select[name=CurrentMedication_CurrentDrugICSNewDrug] option:selected');
    Usage = $('select[name=CurrentMedication_CurrentDrugICSNewUsage] option:selected');

    Multipliers = Array();

    if (Usage.val().indexOf('-') > -1)
    {
        Multipliers = Usage.val().split('-');
    }
    else
    {
        Multipliers.push(Usage.val());
    }

    totalMultiplier = 1;

    $.each(Multipliers, function(i,Multiplier){

        if ($.isNumeric(Multiplier))
        {
            totalMultiplier = totalMultiplier * parseFloat(Multiplier);
        }

    });

    if (DrugSelected.attr('data-agcbdpequivvalue') >= 0) {
        finalDose = totalMultiplier * DrugSelected.attr('data-agcbdpequivvalue');
    } else {
        finalDose = '';
    }


    $('input[name=CurrentMedication_IcsBdpEquivIncUse]').val(finalDose);
}

$('select[name=CurrentMedication_CurrentDrugICSNewDrug]').on('change', function(){agc_calc_BDPEquivalent_ICS();});
$('select[name=CurrentMedication_CurrentDrugICSNewUsage]').on('change',function(){agc_calc_BDPEquivalent_ICS();});
