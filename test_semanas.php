<?php
session_start();
//include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/httpdocs/superforms/phpformbuilder/Form.php';
include_once 'phpformbuilder/Form.php';
//include 'check_session.php';
include 'dbconnect.php';

$month_number = 7;
$ciudad_id = 'CIU06';
$tipolocal_id = 'TIP01';
$table_name = 'TabExhibMulti';

$s_query = "Select * From FormConfiguration Where TableName = '" . $table_name . "'" 
            . " And Type = 'radio'"
            . " Order By FieldOrder Desc";

$form_fields = mysql_query($s_query);

while ($row = mysql_fetch_assoc($form_fields)) {
    $exhib_fields[] = $row;
}

$s_query = "Select * FROM Semana WHERE month(FechaInicio) = " . $month_number;

$semanas = mysql_query($s_query);

$reporte_html = '<table border="1">';

while ($row = mysql_fetch_assoc($semanas)) {
    $reporte_html .= '<tr><th>' . $row['SemanaMes']  . '</th></tr>';

    $s_query = "Select * From " . $table_name . " tes "
    . "Join Encuesta e on tes.idEncuesta = e.idEncuesta "
    . "Join Local l on l.idLocal = e.idLocal "
    . "Join Semana s on s.idSemana = e.idSemana "
    . "WHERE e.idTipoLocal = '" . $tipolocal_id 
    . "' And e.idSemana = '" . $row['idSemana'] 
    . "' And l.idCiudad = '" . $ciudad_id . "'";

    $result = mysql_query($s_query);
     while ($row = mysql_fetch_assoc($result)) {
         
         $reporte_html .= "<tr><td>" .   $row['Local'] . "</td>";
         $td_bg_color = '#00FF00';

         foreach ($exhib_fields as $field_row) {
            $field_value = $row[$field_row['FieldName']];

            if($field_value != 'Exhibido'){
                $td_bg_color = '#ff0000';
                break;
            }
        }

        $reporte_html .= '<td bgcolor="'. $td_bg_color . '">  </td>';
        $reporte_html .='</tr>';
     }
}

$reporte_html.='</table>';
echo $reporte_html;

?>