<?php
use phpformbuilder\Form;

include_once 'phpformbuilder/Form.php';

$form = new Form('frm89', 'horizontal', 'novalidate');

$fileUpload_config = array(
    'xml'                 => 'images-with-captions',
    'uploader'            => 'imageFileUpload.php',
    'btn-text'            => 'Elegir una foto',
    'max-number-of-files' => '1'
);
$form->addHtml('<span class="help-block"> 5 fotos max. (.jpg, .png, .gif)</span>', 'UploadedImages', 'after');
$form->addFileUpload('file',  'UploadedImages[]', '', 'la descripción', '', $fileUpload_config);
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> test image upload </title>
     <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
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

</head>
<body>
    <?php
        $form->render();
        
        $form->printJsCode();
?>

<script>

    $(function () {
        //'use strict';
        // Initialize the jQuery File Upload widget:
        $('#frm89').fileupload({
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator && navigator.userAgent),
            imageMaxWidth: 800,
            imageMaxHeight: 800,
            imageCrop: true // Force cropped images
        });
    });
</script>


</body>
</html>