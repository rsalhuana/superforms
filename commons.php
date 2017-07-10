<?php
function TildesHtml($cadena) 
{ 
    return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                        array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), 
                        $cadena);     
}
?>