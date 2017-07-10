<?php
use phpformbuilder\Form;
use phpformbuilder\database\Mysql;

session_start();
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';

include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('sm-user-form') === true) {
        require_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/database/Mysql.php';

        //var_dump($_POST);

        $db = new Mysql();

        $new_row['usuario'] = Mysql::SQLValue($_POST['smuser']);
        //$new_row['password'] = Mysql::SQLValue(password_hash($_POST['smpassword'], PASSWORD_DEFAULT));
        $new_row['password'] = Mysql::SQLValue($_POST['smpassword']);
        $new_row['Nombre'] = Mysql::SQLValue($_POST['nombre']);
        $new_row['Apellido'] = Mysql::SQLValue($_POST['apellido']);
        $new_row['idRol'] = Mysql::SQLValue($_POST['rol']);
        //$new_row['Activo'] = 'Y';
        //$new_row['HoraFin'] = Mysql::SQLValue(date("H:i:s"));

        $sql = $db->buildSQLInsert('Usuario', $new_row);
        $msg = '<p class="alert alert-success">¡Usuario creado con éxito!</p>' . "\n";

        if(!mysql_query( $sql, $link ))
        {
            $msg = '<p class="alert alert-danger"> Hubo un problema en la BD: <br>' . mysql_error(). '</p>' . "\n";
            var_dump($_POST);
        }
        //else
        //{
          //  $last_id = mysql_insert_id();

            //echo 'last id: ' . $last_id;
            //header("Location: enc-seguridad.php?ecid=" . $last_id);
            //exit();
        //}

        echo $msg;
        Form::clear('sm-user-form');
        mysql_close($link);
}

$form = new Form('sm-user-form', 'horizontal', 'novalidate');

$form->startFieldset('');

$form->addInput('text', 'nombre', '', 'Nombre', 'required');

$form->addInput('text', 'apellido', '', 'Apellido', 'required');

$form->addInput('text', 'smuser', '', 'Usuario', 'required');

$form->addInput('password', 'smpassword', '', 'Password', 'required');

$form->addOption('rol', 'ROL01', 'Encuestador');    
$form->addOption('rol', 'ROL02', 'Administrador');    
$form->addSelect('rol', 'Local', 'class=selectpicker, required');

//$form->addPlugin('icheck', 'input', 'default', array('%theme%'=> 'square', '%color%'=> 'yellow'));

$form->addBtn('submit', 'submit-btn', 1, 'Crear usuario', 'class=btn btn-success');
//$form->addBtn('', 'submit-btn', 1, 'Siguiente', 'class=btn btn-success');
$form->endFieldset();
// jQuery validation
$form->addPlugin('formvalidation', '#sm-user-form');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STORE CHECK - CREAR USUARIO</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <?php $form->printIncludes('css'); ?>
</head>
<body>
    <center><img class="text-center" src="img/suma-logo-upd.png" width="200" height="70"></center>
    <h3 class="text-center">CREAR USUARIO</h3>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <?php
            if (isset($sent_message)) {
                echo $sent_message;
            }
            $form->render();
            ?>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <?php
        $form->printIncludes('js');
        $form->printJsCode();
    ?>
    <script type="text/javascript">
        $(document).ready(function () {

            /* open panel where we found the first error */

            if($('.has-error')[0]) {
                var errorFound = false;
                if(!$('#collapseLimpiezaMod .has-error')[0]) {
                    $('#collapseLimpiezaMod').removeClass('in');
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