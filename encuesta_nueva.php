<?php
use phpformbuilder\Form;
use phpformbuilder\database\Mysql;

session_start();
$_SESSION['current_step'] = '';
include 'check_session.php';
include 'dbconnect.php';
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';

$msg_welcome = 'Bienvenido: ' . $_SESSION['userfullname'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('sm-encuesta-nueva-form') === true) 
{
    require_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/database/Mysql.php';

    $semana_id = $_POST['semana'];
    $local_id = $_POST['local'];
    $tipolocal_id = $_POST['tipolocal'];

    $ciudad_id = $_POST['ciudad'];
    $distrito_id = $_POST['distrito'];

    $msg = '';

    if($ciudad_id == null || $ciudad_id == ''){
        $msg .= "Debe seleccionar la Ciudad</br>";
    }

    if($distrito_id == null || $distrito_id == ''){
        $msg .= "Debe seleccionar el Distrito</br>";
    }

    if($local_id == null || $local_id == ''){
        $msg .= "Debe seleccionar un Local</br>";
    }

    if($tipolocal_id == null || $tipolocal_id == ''){
        $msg .= "Debe seleccionar el Tipo de Local</br>";
    }

    if($semana_id == null || $semana_id == ''){
        $msg .= "Debe seleccionar una Semana</br>";
    }

    if($msg == ''){
    
        $db = new Mysql();

        $s_query = "Select * From Encuesta Where idSemana = '" . $semana_id 
                    . "' AND idLocal = '" . $local_id . "'";

        $encuesta = mysql_query($s_query);

        $msg_it_already_exists = '';
        while ($fila = mysql_fetch_assoc($encuesta)) {
            if($fila['HoraFin'] == null || $fila['HoraFin'] == ''){

                $next_form = 'F001';
                $tipolocal_id = $fila['idTipoLocal'];
                if($tipolocal_id == 'TIP01'){ //Multicategoria
                    $next_form = 'F006';
                } else if($tipolocal_id == 'TIP02'){ //Envase Descartable
                    $next_form = 'F008';
                } else if($tipolocal_id == 'TIP03'){// Sanitario
                    $next_form = 'F001';
                }

                header("Location: encuesta.php?ecid=" . $fila['idEncuesta'] . '&fid=' . $next_form);
                exit();
            }
            //$msg_it_already_exists = '<p class="alert alert-danger">Ya se realizó una encuesta para ese local.</p>';
            $msg_it_already_exists = 'Ya se realizó una encuesta para ese local.';
        }

        if($msg_it_already_exists == ''){
            date_default_timezone_set("America/Lima");
            
            $new_row['Fecha'] = Mysql::SQLValue(date("Y-m-d H:i:s"));
            $new_row['HoraInicio'] = Mysql::SQLValue(date("h:i:sa"));
            $new_row['idSemana'] = Mysql::SQLValue($semana_id);
            $new_row['idTipoLocal'] = Mysql::SQLValue($tipolocal_id);
            $new_row['idLocal'] = Mysql::SQLValue($local_id);
            $new_row['user_insert'] = Mysql::SQLValue($_SESSION['username']);

            $sql = $db->buildSQLInsert('Encuesta', $new_row);
            
            if(!mysql_query( $sql, $link ))
            {
                $msg .= mysql_error(). "\n";
                //echo $msg;
            }
            else
            {
                $last_id = mysql_insert_id();
                $next_form = 'F001';

                if($tipolocal_id == 'TIP01'){ //Multicategoria
                    $next_form = 'F006';
                } else if($tipolocal_id == 'TIP02'){ //Envase Descartable
                    $next_form = 'F008';
                } else if($tipolocal_id == 'TIP03'){// Sanitario
                    $next_form = 'F001';
                }

                header("Location: encuesta.php?ecid=" . $last_id . '&fid=' . $next_form);
                exit();
            }
        }
    }
}

$form = new Form('sm-encuesta-nueva-form', 'horizontal', 'novalidate');

$form->startFieldset('');
$form->addHtml('<center>');

$form->addHtml('<p><select name="ciudad" id="ciudad">');
$form->addHtml('<option value="">Seleccione Ciudad</option>');

$departamentos = mysql_query("Select * From Ciudad Where Activo = 'Y'");
while ($row = mysql_fetch_assoc($departamentos)) {
    $selected_att = '';
    if($ciudad_id == $row['idCiudad']){
        $selected_att = 'selected';
    }

    $form->addHtml('<option value="'.$row['idCiudad'].'"' . $selected_att . '>'.$row['Ciudad'].'</option>');
}

$form->addHtml('</select></p>');

$form->addHtml('<p><select name="distrito" id="distrito">');
$form->addHtml('<option value="">Seleccione Distrito</option>');
$form->addHtml('</select></p>');

$fecha_hoy = date("Y-m-d H:i:s");

$form->addHtml('<p><select name="semana" id="semana">');

$semanas = mysql_query("Select * From Semana Where FechaInicio < '" . $fecha_hoy . "' and FechaFin > '" . $fecha_hoy . "'");
while ($row = mysql_fetch_assoc($semanas)) {
    $form->addHtml('<option value="'.$row['idSemana'].'">'.$row['SemanaMes'].'</option>');
}

$form->addHtml('</select></p>');

$form->addHtml('<p><select name="local" id="local">');
$form->addHtml('<option value="">Seleccione Local</option>');
$form->addHtml('</select></p>');

$form->addHtml('<p><select name="tipolocal" id="tipolocal">');
$form->addHtml('<option value="">Seleccione Tipo Local</option>');
$form->addHtml('<option value="TIP01">Multicategoria</option>');
$form->addHtml('<option value="TIP02">Envase Descartable</option>');
$form->addHtml('<option value="TIP03">Sanitario</option>');
$form->addHtml('</select></p>');

$form->addHtml('</center>');
$form->addBtn('submit', 'submit-btn', 1, 'Empezar encuesta', 'class=btn btn-success');
$form->endFieldset();
mysql_close($link);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NUEVA ENCUESTA</title>
     <link href="select.css" rel="stylesheet">
     <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
        <link href="/menu.css" rel="stylesheet">
    <script src="/menu.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="/ubigeochange.js"></script>
    <?php $form->printIncludes('css'); ?>

    <script type="text/javascript">
        
        </script>
</head>
<body>
    <?php 
    include "enc-menu.php"; 

    echo '<p class="alert alert-info">' . $msg_welcome . '</p>';
    
    if($msg_it_already_exists != '') {
        echo '<p class="alert alert-info">' . $msg_it_already_exists . '</p>';
    }
    if($msg != ''){ 
        echo '<p class="alert alert-danger">' . $msg . '</p>'; 
    } 
    ?> 
    
    <center> 
        <img src="LOGO_HORIZONTAL.png" width="300px"> 
        <h3>Nueva Encuesta</h3>
    </center>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <?php
                $form->render();
            ?>
            </div>
        </div>
    </div>
   
    <?php
        $form->printIncludes('js');
        $form->printJsCode();
    ?>
    <script type="text/javascript">
        $(document).ready(function () {

            /* open panel where we found the first error */

            if($('.has-error')[0]) {
                var errorFound = false;
                if(!$('#collapseOne .has-error')[0]) {
                    $('#collapseOne').removeClass('in');
                }

                // open first error panel
                $('.panel-collapse').each(function() {
                    if($(this).find('.has-error')[0] && errorFound === false) {
                        $(this).addClass('in');
                        errorFound = true;
                    }
                });
            }
        });
    </script>
</body>
</html>