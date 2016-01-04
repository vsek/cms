$(document).ready(function(){
    //smazani obrazku/souboru uploadifive
    $('table.form a.delete').click(function(){
        $(this).parent().find('input.uploadifive').attr('value', '');
        $(this).parent().find('.deafultValue').hide();
        $(this).hide();
    });
    
    $('select').chosen();
    
    //zmena jazyka
    $('select.webLanguage').change(function(){
        window.location.href = $(this).val();
    });
    
    //inzerat - editace
    if($('select.locationCountry').length && $('select.locationDirectory').val() != 'directory'){
        $("select.locationCity").removeOption(/./);
        if($('select.locationCountry').attr('usa') == $('select.locationCountry').val()){
            loadCities($('select.locationState').val());
        }else{
            loadCities($('select.locationCountry').val());
        }
    }
    $('select.locationCountry').change(function(){
        if($('select.locationDirectory').val() != 'directory'){
            $("select.locationCity").removeOption(/./);
            if($('select.locationCountry').attr('usa') == $('select.locationCountry').val()){
                loadCities($('select.locationState').val());
            }else{
                loadCities($(this).val());
            }
        }
    });
    $('select.locationState').change(function(){
        if($('select.locationDirectory').val() != 'directory'){
            $("select.locationCity").removeOption(/./);
            loadCities($(this).val());
        }
    });
});

function loadCities(stateId){
    $.get($('select.locationCountry').attr('get').replace('11111111', stateId), { }, function(data){
        $("select.locationCity").removeOption(/./);
        for(var i = 0; i < data.cities.length; i++){
            $("select.locationCity").addOption(data.cities[i]['id'], data.cities[i]['name'], $("select.locationCity").attr('default') == data.cities[i]['id']);
        }
        console.log($("select.locationCity").attr('default'));
        $("select.locationCity").trigger("chosen:updated");
    });
}

$(function () {
    $('input.uploadifive').each(function(){
        $(this).uploadifive({
            'auto'             : true,
            'checkScript'      : '/js/uploadifive/check-exists.php',
            'formData'         : {
                                    'timestamp' : $(this).attr('timestamp'),
                                    'token'     : $(this).attr('token')
                                },
            'uploadScript'     : $(this).attr('upload'),
            'onUploadComplete' : function(file, data) { 
                                if($(this).attr('multi') == 'true'){
                                    $(this).val($(this).val() + ',' + data);
                                }else{
                                    $(this).val(data);
                                }
            },
            'buttonText'        : $(this).attr('buttonText'),
            'multiple'             : $(this).attr('multiple') == 'true'
        });
    });
});