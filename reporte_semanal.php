<?php
session_start();
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';
//include 'check_session.php';
include 'dbconnect.php';
//include 'FormBuilder.php';
$reporte_html = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

$table_name = 'TabExhibSanitario';
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

$s_query = "Select * 
From TabExhibSanitario tes
Join Encuesta e on tes.idEncuesta = e.idEncuesta
Join Local l on l.idLocal = e.idLocal
WHERE e.idTipoLocal = 'TIP03'";

$result = mysql_query($s_query);
//$info_formulario = null;

$reporte_html .= '<tr><th> </th>';

foreach ($exhib_fields as $field_row) {
    $reporte_html .= "<th>" . $field_row['Description']. "</th>";
}

$reporte_html .= '</tr>';

while ($row = mysql_fetch_assoc($result)) {
    $reporte_html .= "<tr><td>" .   $row['Local'] . "</td>";
    //echo "<p><b>Semana: </b>" .   $row['idSemana'] . "</p>";
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
       <link href="/menu.css" rel="stylesheet">
    <script src="/menu.js"></script>
<link href="select.css" rel="stylesheet">
<style type="text/css">
  table{
    border-collapse: separate;
    border-spacing: 5px 0;
  }
  table td{
    border: 1px solid black;
  }
   table th{
    border: 1px solid black;
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
 <center> <img src="LOGO_HORIZONTAL.png" width="300px"> 
<h3>Reporte Detalle Semana</h3>

<form action="reporte_semanal.php" method="post">
<p><select name="mes" id="mes">
<option value="">Seleccione Mes</option>
<option value="7">Julio</option>
<!--<option value="8">Agosto</option>
<option value="9">Setiembre</option>
<option value="10">Octubre</option>
<option value="11">Noviembre</option>
<option value="12">Diciembre</option>-->
</select></p>

<p><select name="semana" id="semana">
<option value="">Seleccione Semana</option>
<?php
$fecha_hoy = date("Y-m-d H:i:s");
$semanas = mysql_query("Select * From Semana Where FechaInicio < '" . $fecha_hoy . "' and FechaFin > '" . $fecha_hoy . "'");
while ($row = mysql_fetch_assoc($semanas)) {
    echo '<option value="'.$row['idSemana'].'">'.$row['SemanaMes'].'</option>';
}
?>

</select></p>

<p><select name="tipolocal" id="tipolocal">
<option value="">Seleccione Tipo Local</option>
<option value="TIP01">Multicategoria</option>
<option value="TIP02">Envase Descartable</option>
<option value="TIP03">Sanitario</option>
</select></p>

<p><select name="ciudad" id="ciudad">
<option value="">Seleccione Ciudad</option>
<?php
$departamentos = mysql_query("Select * From Ciudad Where Activo = 'Y'");
while ($row = mysql_fetch_assoc($departamentos)) {
   echo '<option value="'.$row['idCiudad'].'">'.$row['Ciudad'].'</option>';
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