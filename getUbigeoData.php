<?php

include 'dbconnect.php';

if(isset($_POST["ciudad_id"]) && !empty($_POST["ciudad_id"]) && $_POST["ciudad_id"] != 'CIU11'){
    $locales = mysql_query("select * from Local where idCiudad = '" .  $_POST['ciudad_id'] . "' Order by Local ASC");
    echo '<option value="">Seleccione Local</option>';
    while ($row = mysql_fetch_assoc($locales)) {
        echo '<option value="'.$row['idLocal'].'">'.$row['Local'].'</option>';
    }
}

if(isset($_POST["ciudad_id"]) && !empty($_POST["ciudad_id"]) && $_POST["ciudad_id"] == 'CIU11'){
    $Distritos = mysql_query("select * from Distrito where idCiudad = '" .  $_POST['ciudad_id'] . "' Order by Distrito ASC");
    echo '<option value="">Seleccione Distrito</option>';
    while ($row = mysql_fetch_assoc($Distritos)) {
        $selected_att = '';
        if($distrito_id == $row['idDistrito']){
            $selected_att = 'selected';
        }
        echo '<option value="'.$row['idDistrito'].'">'.$row['Distrito'].'</option>';
    }
}
if(isset($_POST["distrito_id"]) && !empty($_POST["distrito_id"])){
    $locales = mysql_query("Select * FROM Local WHERE idDistrito = '" . $_POST['distrito_id'] . "' Order by Local ASC");
    echo '<option value="">Seleccione Local</option>';
    while ($row = mysql_fetch_assoc($locales)) {
        echo '<option value="'.$row['idLocal'].'">'.$row['Local'].'</option>';
    }
}
?>
