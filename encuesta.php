<?php
use phpformbuilder\Form;
use phpformbuilder\Validator\Validator;
use phpformbuilder\database\Mysql;

session_start();
//include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/httpdocs/superforms/phpformbuilder/Form.php';
include_once 'phpformbuilder/Form.php';

include 'check_session.php';
include 'dbconnect.php';

function startDependantFields($requiredIf, $showIfValue)
{
    return '<div id="div' . $requiredIf . '" style="display: none;" data-parent="' . $requiredIf . '" data-show-value="' . $showIfValue . '">';
}

function endDependantFields()
{
    return '</div>';
}

function addThumbnailPicture($pic_name){
    return '<tr class="template-download fade in">
                <td>
                    <span class="preview">
                            <img src="/phpformbuilder/images/uploads/thumbnail/' . $pic_name . '">
		            </span>
                    <input type="hidden" name="UploadedImages[]" value="' . $pic_name . '">
                </td>
                <td>
                </td>
                <td>
                        <button class="btn btn-danger delete" data-type="DELETE" data-url="/superforms/phpformbuilder/plugins/jQuery-File-Upload/server/php/imageFileUpload.php?file=' . $pic_name . '" onclick="deleteCaption(this);">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Eliminar</span>
                        </button>
                </td>
            </tr>';
}

function addTextarea($name, $value = '', $label = '', $attr = '', $required = 0)
{
    $required_html = '';
    if ($required == 1) {
        $required_html = 'required';
    }
    $label = '<label for="' . $name . '">' . $label . '</label>';
    $textarea = '<textarea name="' . $name . '" ' . $attr . ' ' . $required_html . '>' . $value . '</textarea>';
    return $label . '<div>' . $textarea . '</div>';
}

function addFileImage($name, $value = '', $label = '', $attr = '')
{
    $label = '<label for="' . $name . '">' . $label . '</label>';

    $element = '';
    if($value != null && $value != ''){
        $element = '<img src="uploads/' . $value . '" alt="" height="82">';
        $attr = '';
    }

    $element .= '<input id="' . $name . '" name="' . $name . '" type="file"  class="input-file-img" ' . $attr . ' />';
    return $label . '<div>' . $element . '</div>';
}

function addFileUpload($name, $value = '', $label = '', $attr = '')
{
    $label = '<label for="' . $name . '"  class="col-sm-4 control-label fileinput-label">' . $label . '</label>';

    $element = '';
    if($value != null && $value != ''){
        $element = '<img src="uploads/' . $value . '" alt="" height="82">';
        $attr = '';
    }

     $element .= '<div class="col-sm-8">
                <div id="UploadedImages[]-upload" class="fileupload">
                    <div class="row fileupload-buttonbar">
                        <div class="col-lg-7">
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Elegir foto</span>
                                <input type="file" name="UploadedImages[]" multiple />
                            </span>
                            <span class="fileupload-process"></span>
                        </div>
                        <div class="col-lg-5 fileupload-progress fade">
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    
                </div>
            </div>';
            // <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
        return $label .  $element ;
}

function addInput($type, $name, $value = '', $label = '', $attr = '')
{
    return '<div class="col-sm-8"><input id = "' . $name . '" name="' . $name . '" type="' . $type . '" value="' . $value . '" ' . $attr . '></div>';
}

function addSubmitBtn($type, $value = 'Enviar', $attr = '', $next_page = '')
{
    return  '<button id="btn-submit-form" type="submit" href="#" class="btn-page next">'.$value.'</button>';
}
function addBtn($type, $value = 'Enviar', $attr = '', $next_page = '')
{
    return  '<a href="'.$next_page.'" class="btn-page previous">' . $value . '</a>';
}

function addHtml($html)
{
    return $html;
}

function addRadio($name, $value = '', $label = '', $attr = '', $required = 0)
{
    $required_html = '';
    if ($required == 1) {
        $required_html = 'required';
    }
    return '<input type="radio" id="' . $name .'_'.$value . '" name="' . $name . '" value="' . $value . '" ' . $attr . ' ' . $required_html . ' >' . $value . ' ';
}

/*function addRadio($name, $value = '', $label = '', $attr = '', $required = 0)
{
    $required_html = '';
    if ($required == 1) {
        $required_html = 'required';
    }
    return '<input type="radio" id="' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . ' ' . $required_html . ' >' . $value . ' ';
}*/

function addOption($name, $value = '', $label = '', $attr = '')
{
    return '<option name="' . $name . '" value="' . $value . '" ' . $attr . ' >' . $label . '</option>';
}

function addInputText($name, $value = '', $label = '', $attr = '')
{
    return '<input type="text" class="form-control" id = "' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . ' />';
}

function addInputCurrency($name, $value = '', $label = '', $attr = '', $required = 0)
{
    $required_html = '';
    if ($required == 1) {
        $required_html = 'required';
    }
    return '<input type="text" class="form-control onlyDecimal" id = "' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . ' ' . $required_html . ' />';
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $idFormulario = $_GET['fid'];    
    $idEncuesta = $_GET['ecid'] ;    
    $_SESSION['current_step'] = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $idFormulario;
}else{
    $idFormulario = $_POST['idFormulario'];    
    $idEncuesta = $_POST['idEncuesta'] ;    
}

$s_query = "Select *, e.idTipoLocal as 'TipoLocal' FROM Encuesta e join Local l on e.idLocal = l.idLocal WHERE idEncuesta = " . $idEncuesta ;
$result = mysql_query($s_query);
$info_encuesta = null;
while ($row = mysql_fetch_assoc($result)) {
    $info_encuesta = $row;
}

$s_query = "Select * From Distribuidora Where idCiudad = '" . $info_encuesta["idCiudad"] . "'";
$result = mysql_query($s_query);
$distribuidoras = null;
while ($row = mysql_fetch_assoc($result)) {
    $distribuidoras[] = $row;
}


$s_query = "Select * From Formulario Where idForm = '" . $idFormulario . "'";
$result = mysql_query($s_query);
$info_formulario = null;
while ($row = mysql_fetch_assoc($result)) {
    $info_formulario = $row;
}

$table_name = $info_formulario['TableName'];

$previous_page = '';
if($info_formulario['PreviousFormId'] != null && $info_formulario['PreviousFormId'] != ''){
    $prev_form_id= $info_formulario['PreviousFormId'];
    if($idFormulario == 'F002'){
        $tipolocal_id = $info_encuesta["TipoLocal"];
        if($tipolocal_id == 'TIP01'){ //Multicategoria
            $prev_form_id = 'F006';
        } else if($tipolocal_id == 'TIP02'){ //Envase Descartable
            $prev_form_id = 'F008';
        } else if($tipolocal_id == 'TIP03'){// Sanitario
            $prev_form_id = 'F001';
        }
    }
    $previous_page = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $prev_form_id;
}

$next_page = '';
if($info_formulario['NextFormId'] != null && $info_formulario['NextFormId'] != ''){
    $next_page = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $info_formulario['NextFormId'];
}

$local_name = "<b>Local:</b>" . $info_encuesta["Local"];
$page_title = $info_formulario['Descripcion'];

$form_id = 'sm-form-'.$table_name;

/* =================================================
    Get Fields and Validations for the Form
================================================== */

$all_fields = array();
$required_fields = array();

$s_query = "Select * From FormConfiguration Where TableName = '" . $table_name . "' Order By FieldOrder Desc";
$form_fields = mysql_query($s_query);

while ($row = mysql_fetch_assoc($form_fields)) {
    $all_fields[] = $row;
    if($row['IsRequired'] || ($row['RequiredIf'] != null && $row['RequiredIf'] != '')){
        $required_fields[] = $row;
    }
}

/* =============================================
    validation if posted
============================================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $target_dir = "uploads/";
    // $uploads_img = array();
    // if (is_array($_FILES) && count($_FILES) > 0) {
    //     foreach ($_FILES as $n => $f) {
    //         if ($f["name"] != "") {
    //             $name_file = time() . "-" . basename($f["name"]);
    //             $name_file = str_replace(" ", "_", $name_file);
    //             $target_file = $target_dir . $name_file;

    //             if (move_uploaded_file($f["tmp_name"], $target_file)) {
    //                 $uploads_img[$n] = $name_file;
    //             } else {
    //                 echo "Hubo un problema al cargar la foto.";
    //             }
    //         }
    //     }
    // }
    require_once 'phpformbuilder/database/Mysql.php';
    $db = new Mysql();

    $update['idEncuesta'] = Mysql::SQLValue($_POST['idEncuesta']);

    foreach($all_fields as $row)
    {
        // if($row['Type'] == 'file-image')
        // {
        //     $uploaded_images = json_encode($_POST[$row['FieldName']]);
        //     if (array_key_exists($row['FieldName'], $uploads_img)) {
        //         $update[$row['FieldName']] = Mysql::SQLValue($uploads_img[$row['FieldName']]);
        //     }
        // }
        if($row['FieldName'] == 'UploadedImages')
        {
            //$uploaded_images = json_encode($_POST[$row['FieldName']]);
            $uploaded_images = $_POST[$row['FieldName']];
            $i = 0;
            foreach($uploaded_images as $img){
                if($i == 3){ $i = 0;}
                $i++;
                $update['FotoExhib' . $i] = Mysql::SQLValue($img);
                
                //$caption = str_replace(".","_",$img) . '-caption';
                //echo "<p>comentario: " . $_POST[$caption] . "</p>";
            }
        }
        else
        {
            $update[$row['FieldName']] = Mysql::SQLValue($_POST[$row['FieldName']]);
        }
    }

    // Build Insert or Update sentence
    $table_id = $_POST['Id'];
    if($table_id == null || $table_id == '')
    {
        $sql = $db->buildSQLInsert($table_name, $update);
    }
    else
    {
        $where['Id'] = Mysql::SQLValue($table_id);
        $sql = $db->buildSQLUpdate($table_name, $update, $where);
    }

    $error_msg = '';

    // Execute Insert or Update sentence
    if(!mysql_query( $sql, $link )) {
        $error_msg = 'Mensaje: </br>' . mysql_error();
    } else if($next_page != '') {
        header("Location: " . $next_page);
        exit();
    } else {
        date_default_timezone_set("America/Lima");

        $filter['idEncuesta'] = Mysql::sqlValue($_POST['idEncuesta'], Mysql::SQLVALUE_NUMBER);
        $update_encuesta['HoraFin'] = Mysql::SQLValue(date("h:i:sa"));

        $sql = $db->buildSQLUpdate('Encuesta', $update_encuesta, $filter);
        
        if(!mysql_query( $sql, $link )){
            $error_msg = 'Mensaje: </br>' . mysql_error();
        } else {
            $_SESSION['current_step'] = '';
            header('Location: success.php?ecid=' . $idEncuesta);
            exit();
        }
    }
}

/* =================================================
    Check if it already exists
================================================== */
$result = mysql_query('Select * From ' . $table_name . ' Where idEncuesta = ' . $idEncuesta);
$existing_info = null;

while ($r = mysql_fetch_assoc($result)) {
    $existing_info = $r;
}

/* ==================================================
    The Form
================================================== */
$form_html = '<form id="frmenc" enctype="multipart/form-data" action="" method="post">';
$form_html .= addInput('hidden', 'idEncuesta', $idEncuesta, '', '');
$form_html .= addInput('hidden', 'idFormulario', $idFormulario, '', '');

if($existing_info != null)
{
    $form_html .= addInput('hidden', 'Id', $existing_info['Id'], '', '');
}

$group_name = '';
$requiredIf = '';

foreach($all_fields as $row){

    if($row['FieldName'] == 'NOBLE_PAPEL_HIGIENICO_INSTITUCIONAL' && $info_encuesta["idCiudad"] != 'CIU11'){
        continue;
    }

    //$form_html .= addHtml('<label for="' . $row['FieldName'] . '" class="error">Please select at least two types of spam.</label>');

    if($requiredIf != '' && ($requiredIf != $row['RequiredIf'] || $row['RequiredIf'] == null))
    {
        $requiredIf = '';
        $form_html .= endDependantFields();
    }

    if($group_name != $row['GroupName'])
    {
        if($group_name != '')
        {
            $form_html .= addHtml('      </div>');
            $form_html .= addHtml('    </div>');
            $form_html .= addHtml('  </div>');
        }

        $group_name = $row['GroupName'];        
        $group_id = 'g-id-' . rand(10,2102);

        $form_html .= addHtml('  <div class="panel panel-primary">');
        $form_html .= addHtml('    <div class="panel-heading" role="tab" id="heading' . $group_id . '">');
        $form_html .= addHtml('      <h4 class="panel-title">');
        $form_html .= addHtml('        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $group_id . '" aria-expanded="false" aria-controls="collapse' . $group_id . '">');
        $form_html .= addHtml( $group_name);
        $form_html .= addHtml('          <span class="caret"></span>');
        $form_html .= addHtml('        </a>');
        $form_html .= addHtml('      </h4>');
        $form_html .= addHtml('    </div>');
        $form_html .= addHtml('    <div id="collapse' . $group_id . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' . $group_id . '">');
        $form_html .= addHtml('      <div class="panel-body">');
    }

    $isRequired = $row['IsRequired'] == 1 ? 'required' : '';        

    if($row['RequiredIf'] != null && $row['RequiredIf'] != '')
    {
        //$isRequired = '';
        if($requiredIf != $row['RequiredIf'])
        {
            $requiredIf = $row['RequiredIf'];
            $form_html .= startDependantFields($requiredIf, 'Si');
        }
    }

    $the_value = '';

    if($existing_info != null)
    {
        $the_value = $existing_info[$row['FieldName']];
    }

    if($row['Type'] == 'radio')
    {
        $form_html .= '<div id="div-id-' . $row['FieldName'] . '" class="form-group div-class-' . $row['FieldName'] . '">';
        $form_html .= '<label for="' . $row['FieldName'] . '">' . $row['Description'] . '</label>';
        $form_html .= '<div>';
        
        $rb_options = explode(',' , $row['Options']);

        foreach ($rb_options as $option) {
            $checked = '';
            if($the_value == $option){
                $checked = 'checked=checked';
            }
            $form_html .= addRadio($row['FieldName'], $option, $option, $checked, $row['IsRequired']);
        }

        $form_html .= '</div></div>';
    }
    else if($row['Type'] == 'select')
    {
        $form_html .= '<div class="form-group">';
        $form_html .= '<label for="' . $row['FieldName'] . '">' . $row['Description'] . '</label>';
        $form_html .= '<div>';
        $form_html .= '<select class="form-control" name="' . $row['FieldName'] . '">';
        $rb_options = explode(',' , $row['Options']);

        foreach ($rb_options as $option) {
            $form_html .= addOption($row['FieldName'], $option, $option, '');
        }
        $form_html .= '</select>';
        $form_html .= '</div></div>';
    }
    else if($row['Type'] == 'select_distribuidor')
    {
        $form_html .= '<div class="form-group">';
        $form_html .= '<label for="' . $row['FieldName'] . '">' . $row['Description'] . '</label>';
        $form_html .= '<div>';
        $form_html .= '<select class="form-control" name="' . $row['FieldName'] . '">';
        $form_html .= addOption($row['FieldName'], "", "Selecciona una Distribuidora", '');
        foreach ($distribuidoras as $option) {
            $attr = '';
            if($option["idDistribuidora"] == $the_value){
                $attr = 'selected';
            }
            $form_html .= addOption($row['FieldName'], $option["idDistribuidora"], $option["Nombre"], $attr);
        }

        $form_html .= '</select>';
        $form_html .= '</div></div>';
    }
    else if($row['Type'] == 'textarea')
    {
        $form_html .= addTextarea($row['FieldName'], $the_value, $row['Description'], 'cols=30, rows=4', $row['IsRequired']);
    }
    else if($row['Type'] == 'file-image')
    {
        $attr = $row['IsRequired'] == 1 ? 'required' : '';
        //$form_html .= addFileImage('UploadedImages', $the_value, $row['Description'], $attr);
         $form_html .= addFileImage($row['FieldName'], $the_value, $row['Description'], $attr);
    }
    else if($row['Type'] == 'input-text')
    {
        $form_html .= '<div class="form-group">';
        $form_html .= '<label for="' . $row['FieldName'] . '">' . $row['Description'] . '</label>';
        $form_html .= '<div>';
        $form_html .= addInputText($row['FieldName'], $the_value, $row['Description'], '');
        $form_html .= '</div></div>';
    }
    else if($row['Type'] == 'input-currency')
    {
        $form_html .= '<div class="form-group">';
        $form_html .= '<label for="' . $row['FieldName'] . '">' . $row['Description'] . '</label>';
        $form_html .= '<div>';
        $form_html .= addInputCurrency($row['FieldName'], $the_value, $row['Description'], '', $row['IsRequired']);
        $form_html .= '</div></div>';
    }
    else if($row['Type'] == 'number')
    {
        $min_value = $row['MinValue'];
        if($row['MinValue'] == 'ThisYear'){
            $min_value = date("Y");
        }

        $attributes = 'min=' . $min_value;

        if($row['MaxValue'] != null && $row['MaxValue'] != ''){
            $attributes .= ', max=' . $row['MaxValue'];
        }

        //$attributes = $min_max;

        if($isRequired != ''){
            $attributes.= ',' . $isRequired;
        }
        
        $form_html .= addInput('number', $row['FieldName'], $the_value, $row['Description'], $attributes);
    }
    else if($row['Type'] == 'UploadedImages')
    {
        $form_html .= addInput('hidden', 'UploadedImagesMinValue', $row['MinValue'], '', '');
        $form_html .= addInput('hidden', 'UploadedImagesMaxValue', $row['MaxValue'], '', '');

        $form_html .= addHtml('<table role="presentation" class="table table-striped"><tbody class="files">');
        
        $attr = $row['IsRequired'] == 1 ? 'required' : '';
        $form_html .= addFileUpload('UploadedImages', $the_value, $row['Description'], $attr);
        
        if($idFormulario == 'F003'){
             $form_html .= addHtml('<span class="help-block">Elija ' . $row['MaxValue'] . ' fotos.</span>');
        }
        
        if($existing_info != null && $idFormulario == 'F003'){
            
            if($existing_info['FotoExhib1'] != null && $existing_info['FotoExhib1'] != ''){
                $form_html .= addThumbnailPicture($existing_info['FotoExhib1']);
            }
            if($existing_info['FotoExhib2'] != null && $existing_info['FotoExhib2'] != ''){
                $form_html .= addThumbnailPicture($existing_info['FotoExhib2']);
            }
            if($existing_info['FotoExhib3'] != null && $existing_info['FotoExhib3'] != ''){
                $form_html .= addThumbnailPicture($existing_info['FotoExhib3']);
            }
        }
        $form_html .= addHtml('</tbody></table>');
    }
    else
    {
        $form_html .= addInput('text', $row['FieldName'], $the_value, $row['Description'], $isRequired);
    }
}

$form_html .= addHtml('      </div>');
$form_html .= addHtml('    </div>');
$form_html .= addHtml('  </div>');


if ($previous_page != '') {
    $form_html .= addBtn('submit', '&laquo; Volver', 'class=btn btn-success', $previous_page);
}

$form_html .= addSubmitBtn('submit', 'Siguiente &raquo;', 'class=btn btn-success', $next_page);

$form_html .= addHtml('  </div>') . "\n";

$form_html .= '</form >';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <link href="menu.css" rel="stylesheet">
    <script src="menu.js"></script>
    <!-- Bootstrap CSS -->
<script src="//code.jquery.com/jquery.js"></script>
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    

  
    <style> 
        form{
            margin-bottom:15px;
        }
        .error {
            display: list-item;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            color:red;
        }
        .none { display:none; }
        .button {
            display: inline-block;
            width: 115px;
            height: 35px;
            background: #4E9CAF;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            margin-bottom:5px;
            margin-right: 5px;
        } 
        a.btn-page {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
        }

        a:hover.btn-page {
            background-color: #ddd;
            color: black;
        }
        button.btn-page {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border-style: none;
        }
        .previous {
            background-color: #f1f1f1;
            color: black;
        }

        .next {
            background-color: #4CAF50;
            color: white;
        }

        .round {
            border-radius: 50%;
        }
       
    </style>
</head>
<body>
    <?php include "enc-menu.php"; ?>
    <?php 
    if($validation_messages != ''){
        echo '<p class="alert alert-danger"><b>Debe completar la siguiente información: </b></br>' . $validation_messages . '</p>';
    }

    if($error_msg != ''){
        echo '<p class="alert alert-danger">' . $error_msg . '</p>';
    }

    ?>
    <p id="div-msg-error" class="alert alert-danger" style="display:none"></p>
    <center> <img src="LOGO_HORIZONTAL.png" width="300px"> </center>
    <h3 class="text-center"><?php echo $local_name; ?> </br></br> <?php echo $page_title; ?> </h3>
    
    <div class="container">
   
            <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <?php echo $form_html; ?>
            </div>
        </div>
    </div>
   
    <script src="encuesta_script.js?v=219"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>



<!-- Inicio file upload -->
    <link href="phpformbuilder/plugins/jQuery-File-Upload/css/jquery.fileupload.min.css" rel="stylesheet" media="screen">
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/tmpl.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/vendor/jquery.ui.widget.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/load-image.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/canvas-to-blob.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.iframe-transport.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.fileupload.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.fileupload-ui.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.fileupload-process.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.fileupload-validate.min.js"></script>
    <script src="phpformbuilder/plugins/jQuery-File-Upload/js/jquery.fileupload-image.min.js"></script>

    <script id="template-upload-UploadedImages" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
            <td>
                <span class="preview"></span>
                <p class="name">{%=file.name%}</p>
                <strong class="error text-danger"></strong>
            </td>
            <td>
                <p class="size">Procesando...</p>
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            </td>
            <td>
                {% if (!i && !o.options.autoUpload) { %}
                    <button class="btn btn-primary start" disabled>
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Subir</span>
                    </button>
                {% } %}
                {% if (!i) { %}
                    <button class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancelar</span>
                    </button>
                {% } %}
            </td>
        </tr>
    {% } %}
    </script>
    <!-- The template to display files available for download -->
    <script id="template-download-UploadedImages" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade">
                <td>
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                        {% } %}
                    </span>
                    <input type="hidden" name="UploadedImages[]" value="{%=file.name%}" />
                </td>
                {% if (file.error) { %}
                    <td>
                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                    </td>
                {% } %}
                <td>
                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                </td>
                <td>
                    {% if (file.deleteUrl) { %}
                        <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" onclick="deleteCaption(this);"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Eliminar</span>
                        </button>
                    {% } else { %}
                        <button class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancelar</span>
                        </button>
                    {% } %}
                </td>
            </tr>
            <?php if($idFormulario == 'F004'){ ?>
                // Distribuidor
                <tr class="template-download fade">
                    <td colspan="2">
                        <div class="form-group">
                            <label for="{%=file.name%}-Distribuidor" class="col-sm-4 control-label">
                                Distribuidor
                            </label>
                            <div class="col-sm-8">

                                <select class="form-control" name="{%=file.name%}-Distribuidor">
                                <?php 
                                    $form_html = addOption("{%=file.name%}-Distribuidor", "", "Selecciona una Distribuidora", '');
                                    foreach ($distribuidoras as $option) {
                                        $form_html .= addOption("{%=file.name%}-Distribuidor", $option["idDistribuidora"], $option["Nombre"], $attr);
                                    }
                                    echo $form_html;
                                ?>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td></td>
                    <td >
                        <div class="form-group">
                            <label for="{%=file.name%}-monto" class="col-sm-4 control-label">
                                Monto Boleta S/.
                            </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control onlyDecimal" name="{%=file.name%}-monto" value="">
                            </div>
                        </div>
                    </td>
                </tr>
                
            <?php }?>
        {% } %}
    </script>
    <script>
        var deleteCaption = function(btn) {
             <?php if($idFormulario == 'F004'){ ?>
                $(btn).closest('tr.template-download').next('tr.template-download').remove();
            <?php } else { ?>
                $(btn).closest('tr.template-download').remove();
            <?php } ?>
        };
        $(function () {
            'use strict';
            // Initialize the jQuery File Upload widget:
            $('#frmenc').fileupload({
                downloadTemplateId: 'template-download-UploadedImages',
                uploadTemplateId: 'template-upload-UploadedImages',
                paramName: 'files[]',
                url: 'http://mercadia.com.pe/superforms/phpformbuilder/plugins/jQuery-File-Upload/server/php/imageFileUpload.php',
                dataType: 'json',
                autoUpload: true,
                maxNumberOfFiles: 1,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator && navigator.userAgent),
                imageMaxWidth: 800,
                imageMaxHeight: 800,
                imageCrop: true // Force cropped images
            });
        });
    </script>
<!-- Fin file upload-->

</body>
</html>