<?php
use phpformbuilder\Form;
use phpformbuilder\Validator\Validator;
use phpformbuilder\database\Mysql;

session_start();
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';
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
    $element = '<input name="' . $name . '" type="file"  class="input-file-img" required ' . $attr . ' />';
    return $label . '<div>' . $element . '</div>';
}

function addInput($type, $name, $value = '', $label = '', $attr = '')
{
    return '<div class="col-sm-8"><input name="' . $name . '" type="' . $type . '" value="' . $value . '" ' . $attr . '></div>';
}

function addSubmitBtn($type, $value = 'Enviar', $attr = '', $next_page = '')
{
    return '<button class="button" type="submit"> '.$value.' </button>';
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

function addOption($name, $value = '', $label = '', $attr = '')
{
    return '<option name="' . $name . '" value="' . $value . '" ' . $attr . ' >' . $label . '</option>';
}

function addInputText($name, $value = '', $label = '', $attr = '')
{
    return '<input type="text" class="form-control" name="' . $name . '" value="' . $value . '" ' . $attr . ' />';
}

function addInputCurrency($name, $value = '', $label = '', $attr = '', $required = 0)
{
    $required_html = '';
    if ($required == 1) {
        $required_html = 'required';
    }
    return '<input type="text" class="form-control onlyDecimal" name="' . $name . '" value="' . $value . '" ' . $attr . ' ' . $required_html . ' />';
}

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $idFormulario = $_GET['fid'];    
    $idEncuesta = $_GET['ecid'] ;    
    $_SESSION['current_step'] = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $idFormulario;
}else{
    $idFormulario = $_POST['idFormulario'];    
    $idEncuesta = $_POST['idEncuesta'] ;    
}

$s_query = "Select * FROM Encuesta e join Local l on e.idLocal = l.idLocal WHERE idEncuesta = " . $idEncuesta ;
$result = mysql_query($s_query);
$info_encuesta = null;
while ($row = mysql_fetch_assoc($result)) {
    $info_encuesta = $row;
}

$s_query = "Select * From Distribuidora Where idCiudad = '" . $info_encuesta["idCiudad"] . "'";
//$s_query = "Select * From Formulario Where idForm = '" . $idFormulario . "'";
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
    $previous_page = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $info_formulario['PreviousFormId'];
}

$next_page = '';
if($info_formulario['NextFormId'] != null && $info_formulario['NextFormId'] != ''){
    $next_page = 'encuesta.php?ecid=' . $idEncuesta . '&fid=' . $info_formulario['NextFormId'];
}

$page_title = $info_formulario['Descripcion'] . " - Local: " . $info_encuesta["Local"];

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
    $target_dir = "uploads/";
    $uploads_img = array();
    if (is_array($_FILES) && count($_FILES) > 0) {
        foreach ($_FILES as $n => $f) {
            if ($f["name"] != "") {
                $name_file = time() . "-" . basename($f["name"]);
                $name_file = str_replace(" ", "_", $name_file);
                $target_file = $target_dir . $name_file;
                if (move_uploaded_file($f["tmp_name"], $target_file)) {
                    $uploads_img[$n] = $name_file;
                } else {
                    echo "Hubo un problema al cargar la foto.";
                }
            }
        }
    }
    require_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/database/Mysql.php';
    $db = new Mysql();

    $update['idEncuesta'] = Mysql::SQLValue($_POST['idEncuesta']);

    /*if($idFormulario == 'F004'){
        foreach($all_fields as $row)
        {
            if($row['Type'] == 'file-image'){

            }
        }    
    }*/

    foreach($all_fields as $row)
    {
        if($row['Type'] == 'file-image')
        {
            $uploaded_images = json_encode($_POST[$row['FieldName']]);
            if (array_key_exists($row['FieldName'], $uploads_img)) {
                $update[$row['FieldName']] = Mysql::SQLValue($uploads_img[$row['FieldName']]);
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
$form_html = '<form enctype="multipart/form-data" action="" method="post">';
$form_html .= addInput('hidden', 'idEncuesta', $idEncuesta, '', '');
$form_html .= addInput('hidden', 'idFormulario', $idFormulario, '', '');

if($existing_info != null)
{
    $form_html .= addInput('hidden', 'Id', $existing_info['Id'], '', '');
}

$group_name = '';
$requiredIf = '';

foreach($all_fields as $row){

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

    // if($row['RequiredIf'] != null && $row['RequiredIf'] != '')
    // {
    //     //$isRequired = '';
    //     if($requiredIf != $row['RequiredIf'])
    //     {
    //         $requiredIf = $row['RequiredIf'];
    //         $form_html .= startDependantFields($requiredIf, 'Si');
    //     }
    // }

    $the_value = '';

    if($existing_info != null)
    {
        $the_value = $existing_info[$row['FieldName']];
    }

    if($row['FieldName'] == 'NOBLE_PAPEL_HIGIENICO_INSTITUCIONAL' && $info_encuesta["idCiudad"] != 'CIU11'){
        // hacer nada
    }else if($row['Type'] == 'radio')
    {
        $form_html .= '<div class="form-group">';
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
            $form_html .= addOption($row['FieldName'], $option["idDistribuidora"], $option["Nombre"], '');
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
        $form_html .= addFileImage($row['FieldName'], $the_value, $row['Description'], '');
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
    else if($row['Type'] == 'pictures')
    {
        $images_list = $the_value;

/*        if($_POST['UploadedImages']){
            $images_list = json_encode($_POST[$row['FieldName']]);
        }
*/
        if($images_list != null && $images_list != ''){
            $images_list = $the_value;
            $images = explode(',',$images_list);

            foreach($images as $img){
                $text_only = str_replace('"', '', $img);
                $text_only = str_replace('[', '', $text_only);
                $text_only = str_replace(']', '', $text_only);

                $form_html .= addHtml('<img class="text-center" width="510" src="phpformbuilder/images/uploads/' . $text_only . '"></br>' );
                
                $form_html .= addInput('hidden', $row['FieldName'].'[]', $text_only, '', '');
            }
        }

        $fileUpload_config = array(
            'xml'                 => 'images-with-captions',
            'uploader'            => 'imageFileUpload.php',
            'btn-text'            => 'Explorar ...',
            'max-number-of-files' => $row['MaxValue']
        );
        $form_html .= addHtml('<span class="help-block">' . $row['MaxValue'] . 'fotos max. (.jp[e]g, .png, .gif)</span>', 'uploaded-images', 'after');
        //$form->addFileUpload('file',  $row['FieldName'].'[]', '', $row['Description'], '', $fileUpload_config);
    }
    else
    {
        $form_html .= addInput('text', $row['FieldName'], $the_value, $row['Description'], $isRequired);
    }
}

$form_html .= addHtml('      </div>');
$form_html .= addHtml('    </div>');
$form_html .= addHtml('  </div>');

$form_html .= addSubmitBtn('submit', 'Siguiente', 'class=btn btn-success', $next_page);

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
    <link href="/menu.css" rel="stylesheet">
    <script src="/menu.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/fileinput-rtl.min.css" rel="stylesheet">
    <link href="./assets/css/fileinput.min.css" rel="stylesheet">
    <style> 
        .none { display:none; }
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
    <?php include "enc-menu.php"; ?>
    <?php 
    if($validation_messages != ''){
        echo '<p class="alert alert-danger"><b>Debe completar la siguiente información: </b></br>' . $validation_messages . '</p>';
    }

    if($error_msg != ''){
        echo '<p class="alert alert-danger">' . $error_msg . '</p>';
    }

    ?>
    <center> <img src="LOGO_HORIZONTAL.png" width="300px"> </center>
    <h3 class="text-center"><?php echo $page_title; ?></h3>
    <div class="container">
            <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <?php echo $form_html; ?>
            
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="./assets/js/fileinput.min.js"></script>
    <script src="./assets/js/locales/es.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
 
            $(".input-file-img").fileinput({
                'showUpload': false
            });
            $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").click(function() {
                var isChecked = false;
                $("input[name='Colgante'],input[name='Jalavista'],input[name='TachoDispensador'],input[name='BannerToldo']").each(function() {
                    if($(this).is(':checked') && $(this).val() == 'Si') {
                        isChecked = true;
                    }
                });
                if (isChecked == true) {
                    $('#MaterialPOP_Si').prop("checked", true);
                } else {
                    $('#MaterialPOP_No').prop("checked", true);
                }
            });
            /*$("input[name$='MaterialPOP']").click(function() {
                var answer = $(this).val();
                
                if(answer == "Si"){
                    $("#divMaterialPOP").show();
                }else{
                    $("#divMaterialPOP").hide();
                }
                
            });*/

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
    </script>

</body>
</html>