<?php
session_start();
//include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/httpdocs/superforms/phpformbuilder/Form.php';
include_once 'phpformbuilder/Form.php';
//include 'check_session.php';
include 'dbconnect.php';
//include 'FormBuilder.php';
$reporte_html = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $mes_id = $_POST["mes"];
    $semana_id = $_POST["semana"];
    $ciudad_id = $_POST["ciudad"];
    $tipolocal_id = $_POST["tipolocal"];

    $table_name = 'TabExhibSanitario';

    if($tipolocal_id == 'TIP01'){ //Multicategoria
        $table_name = 'TabExhibMulti';
    } else if($tipolocal_id == 'TIP02'){ //Envase Descartable
        $table_name = 'TabExhibDescartable';
    } else if($tipolocal_id == 'TIP03'){// Sanitario
        $table_name = 'TabExhibSanitario';
    }

    $exhib_fields = array();

    $s_query = "Select GroupName, count(*) as Colspan From FormConfiguration Where TableName = '" . $table_name . "'" 
                ." And Type = 'radio' Group by GroupName";

    $categories = mysql_query($s_query);

    $reporte_html = '<table ><tr><th></th>';

    while ($row = mysql_fetch_assoc($categories)) {
        $reporte_html .= '<th colspan="' . $row['Colspan'] . '">' . $row['GroupName'] . '</th>';
    }

    $reporte_html .= '</tr>';

    $s_query = "Select * From FormConfiguration Where TableName = '" . $table_name . "'" 
            . " And Type = 'radio'"
            . " Order By FieldOrder Desc";

    $form_fields = mysql_query($s_query);

    while ($row = mysql_fetch_assoc($form_fields)) {
        $exhib_fields[] = $row;
    }

    $s_query = "Select * From TabExhibSanitario tes "
    . "Join Encuesta e on tes.idEncuesta = e.idEncuesta "
    . "Join Local l on l.idLocal = e.idLocal "
    . "Join Semana s on s.idSemana = e.idSemana "
    . "WHERE e.idTipoLocal = '" . $tipolocal_id . "' And e.idSemana = 'SEM01' And l.idCiudad = '" . $ciudad_id . "'";

    //echo $s_query;

    // $s_query = "Select s.SemanaMes, tes.* 
    // From TabExhibSanitario tes
    // Join Encuesta e on tes.idEncuesta = e.idEncuesta
    // Join Local l on l.idLocal = e.idLocal
    // Join Semana s on s.idSemana = e.idSemana
    // WHERE e.idTipoLocal = 'TIP03' 
    // And e.idSemana = 'SEM01'";

    $result = mysql_query($s_query);
    //$info_formulario = null;

    $reporte_html .= '<tr><th> </th>';

    foreach ($exhib_fields as $field_row) {
        $reporte_html .= "<th>" . $field_row['Description']. "</th>";
    }

    $reporte_html .= '</tr>';

    while ($row = mysql_fetch_assoc($result)) {
        $reporte_html .= "<tr><td>" .   $row['Local'] . "</td>";
        
        foreach ($exhib_fields as $field_row) {
            $field_value = $row[$field_row['FieldName']];
            $td_bg_color = '#FFFFFF';
            if($field_value == 'Exhibido'){
                $td_bg_color = '#00FF00';
            }else if($field_value == 'No exhibido'){
                $td_bg_color = '#FFFF00';
            }else {
                $td_bg_color = '#FF0000';
            }

            $reporte_html .= '<td bgcolor="'. $td_bg_color . '">  </td>';
        }
        $reporte_html .= "</tr>";
    }

    $reporte_html .= '</table>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
     <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    
    <title>REPORTE SEMANAL</title>
    <link href="menu.css" rel="stylesheet">
    <script src="menu.js"></script>
    <link href="select.css" rel="stylesheet">
    <link href="common.css" rel="stylesheet">
    <style type="text/css">
    table{
        border-collapse: separate;
        
    }
    table td{
        border: 1px solid black;
    }
    table th{
        border: 1px solid black;
        min-width: 150px;
        text-align: center;
    }
    .button {
        display: block;
        width: 115px;
        height: 35px;
        background: #4E9CAF;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        color: white;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <?php include "adm-menu.php"; ?>

    <?php echo $msg_welcome; ?>
<center>
 <center> <img src="LOGO_HORIZONTAL.png" width="300px" class="logo_top"> 
<h3>Reporte Detalle Semana</h3>

<form action="reporte_semanal.php" method="post">
<p><select name="mes" id="mes">
<option value="">Seleccione Mes</option>
<option value="1" <?php if(isset($_POST['mes']) && $_POST['mes'] == '1'){ echo 'selected'; } ?>>Enero</option>
<option value="2" <?php if(isset($_POST['mes']) && $_POST['mes'] == '2'){ echo 'selected'; } ?>>Febrero</option>
<option value="3" <?php if(isset($_POST['mes']) && $_POST['mes'] == '3'){ echo 'selected'; } ?>>Marzo</option>
<option value="4" <?php if(isset($_POST['mes']) && $_POST['mes'] == '4'){ echo 'selected'; } ?>>Abril</option>
<option value="5" <?php if(isset($_POST['mes']) && $_POST['mes'] == '5'){ echo 'selected'; } ?>>Mayo</option>
<option value="6" <?php if(isset($_POST['mes']) && $_POST['mes'] == '6'){ echo 'selected'; } ?>>Junio</option>
<option value="7" <?php if(isset($_POST['mes']) && $_POST['mes'] == '7'){ echo 'selected'; } ?>>Julio</option>
<option value="8" <?php if(isset($_POST['mes']) && $_POST['mes'] == '8'){ echo 'selected'; } ?>>Agosto</option>
<option value="9" <?php if(isset($_POST['mes']) && $_POST['mes'] == '9'){ echo 'selected'; } ?>>Setiembre</option>
<option value="10" <?php if(isset($_POST['mes']) && $_POST['mes'] == '10'){ echo 'selected'; } ?>>Octubre</option>
<option value="11" <?php if(isset($_POST['mes']) && $_POST['mes'] == '11'){ echo 'selected'; } ?>>Noviembre</option>
<option value="12" <?php if(isset($_POST['mes']) && $_POST['mes'] == '12'){ echo 'selected'; } ?>>Diciembre</option>
</select></p>

<p><select name="semana" id="semana">
<option value="">Seleccione Semana</option>
<?php
$fecha_hoy = date("Y-m-d H:i:s");
$semanas = mysql_query("Select * From Semana Where FechaInicio < '" . $fecha_hoy . "' and FechaFin > '" . $fecha_hoy . "'");
while ($row = mysql_fetch_assoc($semanas)) {
    $attr = '';
    if(isset($_POST['semana']) && $row['idSemana'] == $_POST['semana']){
        $attr = 'selected';
    }

    echo '<option value="'.$row['idSemana'].'" ' . $attr . '>'.$row['SemanaMes'].'</option>';
}
?>

</select></p>

<p><select name="tipolocal" id="tipolocal">
<option value="">Seleccione Tipo Local</option>
<option value="TIP01" <?php if(isset($_POST['tipolocal']) && $_POST['tipolocal'] == 'TIP01'){ echo 'selected'; } ?> >Multicategoria</option>
<option value="TIP02" <?php if(isset($_POST['tipolocal']) && $_POST['tipolocal'] == 'TIP02'){ echo 'selected'; } ?>>Envase Descartable</option>
<option value="TIP03" <?php if(isset($_POST['tipolocal']) && $_POST['tipolocal'] == 'TIP03'){ echo 'selected'; } ?>>Sanitario</option>
</select></p>

<p><select name="ciudad" id="ciudad">
<option value="">Seleccione Ciudad</option>
<?php
$departamentos = mysql_query("Select * From Ciudad Where Activo = 'Y'");
while ($row = mysql_fetch_assoc($departamentos)) {
    $attr = '';
    if(isset($_POST['ciudad']) && $row['idCiudad'] == $_POST['ciudad']){
        $attr = 'selected';
    }
    echo '<option value="'.$row['idCiudad'].'"' . $attr . '>'.$row['Ciudad'].'</option>';
}
?>
</select></p>
<p>
<input type="submit" class="button" value="Ver Reporte"/>
</p>
</form>
</center>

<?php echo $reporte_html; ?>
</body>
</html>