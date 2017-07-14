<?php

include 'dbconnect.php';

if(isset($_POST["ciudad"]) && !empty($_POST["ciudad"]) && $_POST["ciudad"] != 'CIU11'){
    $locales = mysql_query("select * from Local where idCiudad = '" .  $_POST['ciudad'] . "' Order by Local ASC");
    echo '<option value="">Seleccione Local</option>';
    while ($row = mysql_fetch_assoc($locales)) {
        echo '<option value="'.$row['idLocal'].'">'.$row['Local'].'</option>';
    }
}

if(isset($_POST["ciudad"]) && !empty($_POST["ciudad"]) && $_POST["ciudad"] == 'CIU11'){
    $Distritos = mysql_query("select * from Distrito where idCiudad = '" .  $_POST['ciudad'] . "' Order by Distrito ASC");
    echo '<option value="">Seleccione Distrito</option>';
    while ($row = mysql_fetch_assoc($Distritos)) {
        $selected_att = '';
        if($distrito == $row['idDistrito']){
            $selected_att = 'selected';
        }
        echo '<option value="'.$row['idDistrito'].'" ' . selected_att . '>'.$row['Distrito'].'</option>';
    }
}
if(isset($_POST["distrito"]) && !empty($_POST["distrito"])){
    $locales = mysql_query("Select * FROM Local WHERE idDistrito = '" . $_POST['distrito'] . "' Order by Local ASC");
    echo '<option value="">Seleccione Local</option>';
    while ($row = mysql_fetch_assoc($locales)) {
        echo '<option value="'.$row['idLocal'].'">'.$row['Local'].'</option>';
    }
}
?>
