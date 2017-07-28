$(document).ready(function () {
    //jQuery.validator.format("{0} debe tener un valor");
    $.extend(
        $.validator.messages, { 
            required: "Este campo es obligatorio.",
            max: jQuery.validator.format("Ingrese un monto menor que {0}."),
            min: jQuery.validator.format("Ingrese un monto mayor a {0}."),
            number: "Ingrese un monto correcto."
        });
    $('#frmenc').validate({ // initialize the plugin
        //set this to false if you don't what to set focus on the first invalid input
        focusInvalid: false,
        //by default validation will run on input keyup and focusout
        //set this to false to validate on submit only
        onkeyup: false,
        onfocusout: false,
        highlight: function (element, errorClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorLabelContainer: '#errors'
    });

    $("input[name='MaterialPOP']").each(function() {
        
        if($(this).is(':checked') && $(this).val() == 'Si') {
            $("#divMaterialPOP").show();
        }
        
    });

  
    $("input[name$='MaterialPOP']").click(function() {
        var answer = $(this).val();
        
        if(answer == "Si"){
            $("#divMaterialPOP").show();
        }else{
            $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").each(function() {
                $(this).prop('checked', false);
            })
            $("#divMaterialPOP").hide();
        }
        
    });

    $('.panel-collapse').each(function() {
        $(this).addClass('in');
    });

    /* open panel where we found the first error */
    if($('.has-error')[0]) {
        var errorFound = false;
        if(!$('#collapseTwo .has-error')[0]) {
            $('#collapseTwo').removeClass('in');
        }
        // open first error panel
        $('.panel-collapse').each(function() {
            if($(this).find('.has-error')[0] && errorFound === false) {
                $(this).addClass('in');
                errorFound = true;
            }
        });
    }

    $("#frmenc").submit(function(e) {
        var self = this;
        
        var idFormulario = $('input[name="idFormulario"]').val();
        if(idFormulario == 'F003' || idFormulario == 'F004'){
            var uploadedImagesMinValue = $('input[name="UploadedImagesMinValue"]').val();
            var uploadedImagesMaxValue = $('input[name="UploadedImagesMaxValue"]').val();
            var numberOFUploadedImages = $('input[name="UploadedImages[]"]').length - 1;
            
            if(numberOFUploadedImages < uploadedImagesMinValue){
                e.preventDefault();                
                $('#div-msg-error').html("Debe agregar al menos " + uploadedImagesMinValue + " fotos");
                $('#div-msg-error').show();
            }else if(numberOFUploadedImages > uploadedImagesMaxValue){
                e.preventDefault();            
                $('#div-msg-error').html("Solo puede agregar " + uploadedImagesMaxValue + " fotos");
                $('#div-msg-error').show();
            }else{
                $('#div-msg-error').html("");
                $('#div-msg-error').hide();
                //self.submit();
            }
        } else if(idFormulario == 'F002'){
            if($('#MaterialPOP_Si').is(':checked')){
                e.preventDefault();
                var isMaterialPOPSelected = false;
                $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").each(function() {
                    if($(this).is(':checked') && $(this).val() == 'Si') {
                        isMaterialPOPSelected = true;
                        return false;
                    }
                });

                if(isMaterialPOPSelected){
                    $('#div-msg-error').html("");
                    $('#div-msg-error').hide();
                    self.submit();
                }else{
                    $('#div-msg-error').html("No ha elegido ninguna opción de Material POP");
                    $('#div-msg-error').show();
                }
            }
        }
     
    });

    $('.onlyDecimal').keydown(function (event) {
        if (event.shiftKey == true) {
            event.preventDefault();
        }
        if ((event.keyCode >= 48 && event.keyCode <= 57) ||
            (event.keyCode >= 96 && event.keyCode <= 105) ||
            event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
            event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
        } else {
            event.preventDefault();
        }
        if($(this).val().indexOf('.') !== -1 && event.keyCode == 190) {
            event.preventDefault();
        }
        var decimal = $(this).val().split(".")[1];
        if (typeof decimal != 'undefined') {
            if (decimal.length > 1 && event.keyCode != 8 && event.keyCode != 46) {
                event.preventDefault();
            }
        }
    });
});