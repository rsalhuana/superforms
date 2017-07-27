<?php
use phpformbuilder\Form;
use phpformbuilder\database\Mysql;

session_start();
//include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/httpdocs/superforms/phpformbuilder/Form.php';
include_once 'phpformbuilder/Form.php';
include 'dbconnect.php'; 
if(isset($_SESSION['current_step']) && $_SESSION['current_step'] != null && $_SESSION['current_step'] != '')
{
    header("Location: " . $_SESSION['current_step']);
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('sm-login-form') === true) {
        $user = $_POST['smuser'];
        $password = $_POST['smpassword'];

        $s_query = "Select * From Usuario Where usuario = '" . $user . "'";

        $result = mysql_query($s_query);
        $db_user = null;

        while ($fila = mysql_fetch_assoc($result)) {
            $db_user = $fila;
        }

        //if(password_verify($password, $db_user['password'])) {
        if($password == $db_user['password']) {
            if($db_user['Activo'] != 'Y')
            {
                echo '<p class="alert alert-danger"> No puede acceder al sistema. Contacte con un Administrador para mayor información.</p>' . "\n";
            }
            else
            {
                $_SESSION['userid'] = $db_user['idUsuario']; 
                $_SESSION['username'] = $db_user['usuario']; 
                $_SESSION['userfullname'] = $db_user['Nombre'] . ' ' . $db_user['Apellido']; 

                $rol = $db_user['idRol'];
                if($rol == 'ROL01'){
                    header("Location: encuesta_nueva.php");
                    exit();
                }
                else if($rol == 'ROL02'){
                    header("Location: reporte_semanal.php");
                    //header("Location: adm-encuestas.php");
                    exit();
                }
            }
        }
        else
        {
            echo '<p class="alert alert-danger"> Usuario o contraseña incorrecta.</p>' . "\n";
        }
        
        mysql_close($link);
}

$form = new Form('sm-login-form', 'horizontal', 'novalidate');

$form->startFieldset('');

$form->addInput('text', 'smuser', '', 'Usuario', 'required');

$form->addInput('password', 'smpassword', '', 'Password', 'required');

//$form->addPlugin('icheck', 'input', 'default', array('%theme%'=> 'square', '%color%'=> 'yellow'));

$form->addBtn('submit', 'submit-btn', 1, 'Ingresar', 'class=btn btn-success');
//$form->addBtn('', 'submit-btn', 1, 'Siguiente', 'class=btn btn-success');
$form->endFieldset();
// jQuery validation
$form->addPlugin('formvalidation', '#sm-login-form');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MERCADIA LOGIN</title>
<link rel="icon" 
      type="image/x-icon" 
      href="img/enc_clip.ico">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <?php $form->printIncludes('css'); ?>
</head>
<body>
    <br/>
    <center> <img src="LOGO_VERTICAL.png" width="300px"> 
    <br/>
    <h3 class="text-center">LOGIN</h3>
     </center>
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