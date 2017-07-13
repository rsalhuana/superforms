<?php
$nombre_local = '';
$s_query = "Select * FROM Encuesta e join Local l on e.idLocal = l.idLocal WHERE idEncuesta = " . $idEncuesta ;
$result = mysql_query($s_query);
$info_encuesta = null;
while ($row = mysql_fetch_assoc($result)) {
    $info_encuesta = $row;
}
$nombre_local = $info_encuesta["Local"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ENCUESTA GRABADA</title>
     <link href="select.css" rel="stylesheet">
     <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
        <link href="/menu.css" rel="stylesheet">
    <script src="/menu.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    

    <script type="text/javascript">
        
        </script>
</head>
<body>
     <?php include "enc-menu.php"; ?>



    <center><img class="text-center" src="img/suma-logo-upd.png" width="200" height="70">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <h1>
                <?php echo 'La encuesta fue grabada satisfactoriamente para el local ' . $nombre_local; ?>
            </h1>
            </div>
        </div>
    </div>
   </center>

</body>
</html>