<?php
session_start();
include 'check_session.php';
include 'dbconnect.php';

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $idEncuesta = $_GET['ecid'] ;    
}

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
    
        <link href="menu.css" rel="stylesheet">
    <script src="menu.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="commons.css"></script>
    <script src="commons.js"></script>
    
</head>
<body onload="nobackbutton();">
     <?php include "enc-menu.php"; ?>


    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <center> <img src="LOGO_HORIZONTAL.png" width="300px" class="logo_top"> </center>
            <h3>
               <center> <?php echo 'La encuesta fue grabada satisfactoriamente para el local: </br><b>' . $nombre_local . '</b>'; ?></center>
            </h3>
            </div>
        </div>
    </div>
   </center>

</body>
</html>