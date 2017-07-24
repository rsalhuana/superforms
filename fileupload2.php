<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $uploaded_images = $_POST['UploadedImages'];
    foreach($uploaded_images as $img){
        echo "<p>Imagen: " . $img . "</p>";
        $caption = str_replace(".","_",$img) . '-caption';
        echo "<p>comentario: " . $_POST[$caption] . "</p>";
    }
    print_r($_POST);
}
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
    <form id="frm89" action="fileupload2.php" method="POST" enctype="multipart/form-data" 
    novalidate class="form-horizontal">
        <div>
            <input name="token" type="hidden" value="127779455259753ef9a53209.30788029" class="form-control">
            <input name="frm89" type="hidden" value="1"  class="form-control">
        </div>
        <div class="form-group">
            <label for="UploadedImages[]" class="col-sm-4 control-label fileinput-label">
                la descripción
            </label>
            <div class="col-sm-8">
                <!-- The container for the uploaded files -->
                <div id="UploadedImages[]-upload" class="fileupload">
                    <div class="row fileupload-buttonbar">
                        <div class="col-lg-7 col-md-6 col-sm-8">
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Elegir una foto</span>
                                <input type="file" name="UploadedImages[]" multiple />
                            </span>
                            <span class="fileupload-process"></span>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-4 fileupload-progress fade">
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                            </div>
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                </div>
            </div>
        </div>
        <input type="submit" value="Enviar" class="btn-success">
    </form>
    <script type="text/javascript">
        $(document).ready(function () {

            if ($(".has-error")[0]) {
                $("html, body").animate({
                    scrollTop: $($(".has-error")[0]).offset().top - 400
                }, 800);
            }
        });
    </script>

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
            // captions
            <tr class="template-download fade">
                <td colspan="4">
                    <div class="form-group">
                        <label for="{%=file.name%}-caption" class="col-sm-4 control-label">
                            Comentarios
                        </label>
                        <div class="col-sm-8">
                            <input name="{%=file.name%}-caption" type="text" value="" class="form-control" />
                        </div>
                    </div>
                </td>
            </tr>
            // monto
            <tr>
            <tr class="template-download fade">
                <td colspan="4">
                    <div class="form-group">
                        <label for="{%=file.name%}-monto" class="col-sm-4 control-label">
                            Monto
                        </label>
                        <div class="col-sm-8">
                            <input name="{%=file.name%}-monto" type="text" value="" class="form-control" />
                        </div>
                    </div>
                </td>
            </tr>
        {% } %}
    </script>
    <script>
        var deleteCaption = function(btn) {
            $(btn).closest('tr.template-download').next('tr.template-download').remove();
        };
        $(function () {
            'use strict';
            // Initialize the jQuery File Upload widget:
            $('#frm89').fileupload({
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
        
</body>
</html>