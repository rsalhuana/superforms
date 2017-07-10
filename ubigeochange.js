$(document).ready(function(){
            $('#ciudad').on('change',function(){
                var ciudadID = $(this).val();
                if(ciudadID){
                    $.ajax({
                        type:'POST',
                        url:'getUbigeoData.php',
                        data:'ciudad_id='+ciudadID,
                        success:function(html){
                            if(ciudadID != 'CIU11'){
                                //$('#distrito').html('<option value=""> - </option>');                         
                                $('#distrito').hide();
                                $('#local').show();
                                $('#local').html(html);
                            }else{
                                $('#local').hide();
                                $('#distrito').show();
                                $('#distrito').html(html); 
                                //$('#local').html('<option value="">Seleccione distrito</option>'); 
                            }
                        }
                    }); 
                }else{
                    $('#distrito').hide();
                    $('#local').hide();
                    //$('#distrito').html('<option value="">Seleccione ciudad</option>');
                    //$('#local').html('<option value="">Seleccione ciudad</option>'); 
                }
            });
            
            $('#distrito').on('change',function(){
                var distritoID = $(this).val();
                if(distritoID){
                    $.ajax({
                        type:'POST',
                        url:'getUbigeoData.php',
                        data:'distrito_id='+distritoID,
                        success:function(html){
                            $('#local').show();
                            $('#local').html(html);
                        }
                    }); 
                }else{
                    //$('#local').html('<option value="">Seleccione distrito</option>'); 
                    $('#local').hide();
                }
            });
        });