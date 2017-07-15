$(document).ready(function () {
    //jQuery.validator.format("{0} debe tener un valor");
    
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
        errorLabelContainer: '#errors',
        rules: {
            Foto1: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor1"]').val() != ''
                                ||$("#MontoBoleta1").is(":filled"));//return $("#Distribuidor1").val().length > 0;
                    }
                }
            },
            MontoBoleta1: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor1"]').val() != ''
                                || $("#Foto1").is(":filled")); 
                        //$("#Foto1").is(":filled");//return $("#Distribuidor1").val().length > 0;
                    }
                }
            },
            Distribuidor1: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta1").is(":filled") 
                            ||  $("#Foto1").is(":filled"));
                    }
                }
            },
            Foto2: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor2"]').val() != ''
                                ||$("#MontoBoleta2").is(":filled"));//return $("#Distribuidor2").val().length > 0;
                    }
                }
            },
            MontoBoleta2: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor2"]').val() != ''
                                || $("#Foto2").is(":filled")); 
                    }
                }
            },
            Distribuidor2: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta2").is(":filled") 
                            ||  $("#Foto2").is(":filled"));
                    }
                }
            },
            Foto3: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor3"]').val() != ''
                                ||$("#MontoBoleta3").is(":filled"));//return $("#Distribuidor3").val().length > 0;
                    }
                }
            },
            MontoBoleta3: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor3"]').val() != ''
                                || $("#Foto3").is(":filled")); 
                    }
                }
            },
            Distribuidor3: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta3").is(":filled") 
                            ||  $("#Foto3").is(":filled"));
                    }
                }
            },

            Foto4: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor4"]').val() != ''
                                ||$("#MontoBoleta4").is(":filled"));//return $("#Distribuidor4").val().length > 0;
                    }
                }
            },
            MontoBoleta4: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor4"]').val() != ''
                                || $("#Foto4").is(":filled")); 
                    }
                }
            },
            Distribuidor4: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta4").is(":filled") 
                            ||  $("#Foto4").is(":filled"));
                    }
                }
            },
            Foto5: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor5"]').val() != ''
                                ||$("#MontoBoleta5").is(":filled"));//return $("#Distribuidor5").val().length > 0;
                    }
                }
            },
            MontoBoleta5: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor5"]').val() != ''
                                || $("#Foto5").is(":filled")); 
                    }
                }
            },
            Distribuidor5: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta5").is(":filled") 
                            ||  $("#Foto5").is(":filled"));
                    }
                }
            },
            Foto6: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor6"]').val() != ''
                                ||$("#MontoBoleta6").is(":filled"));//return $("#Distribuidor6").val().length > 0;
                    }
                }
            },
            MontoBoleta6: {
                required: {
                    depends: function (element) {
                        return ($('#frmenc select[name="Distribuidor6"]').val() != ''
                                || $("#Foto6").is(":filled")); 
                    }
                }
            },
            Distribuidor6: {
                required: {
                    depends: function (element) {
                        return ($("#MontoBoleta6").is(":filled") 
                            ||  $("#Foto6").is(":filled"));
                    }
                }
            }
        },
        // groups: {
        //     materialpopgroup: "Colgante Jalavista TachoDispensador BannerToldo"
        // },
        messages: {
            // Colgante: {
            //      required: "Seleccionar al menos 1 de estas opciones."
            // },
            Foto1: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta1: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor1:{
                required: "Indique el distribuidor de la boleta."
            },
            Foto2: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta2: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor2:{
                required: "Indique el distribuidor de la boleta."
            },
            Foto3: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta3: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor3:{
                required: "Indique el distribuidor de la boleta."
            },
            Foto4: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta4: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor4:{
                required: "Indique el distribuidor de la boleta."
            },
            Foto5: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta5: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor5:{
                required: "Indique el distribuidor de la boleta."
            },
            Foto6: {
                required: "Agregue la foto correspondiente."
            },
            MontoBoleta6: {
                required: "Indique el monto de la boleta."
            },
            Distribuidor6:{
                required: "Indique el distribuidor de la boleta."
            }
        }
    });

    $(".input-file-img").fileinput({
        'showUpload': false
    });
    // $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").click(function() {
    //     var isChecked = false;
    //     $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").each(function() {
    //         if($(this).is(':checked') && $(this).val() == 'Si') {
    //             isChecked = true;
    //         }
    //     });
    //     if (isChecked == true) {
    //         alert('checked');
    //         //$('#MaterialPOP_Si').prop("checked", true);
    //     } else {
    //         alert('not checked');
    //         //$('#MaterialPOP_No').prop("checked", true);
    //     }
    // });
    $("input[name$='MaterialPOP']").click(function() {
        var answer = $(this).val();
        
        if(answer == "Si"){
            $("#divMaterialPOP").show();
        }else{
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