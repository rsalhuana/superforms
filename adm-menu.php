<?php
$the_menu =  '<div class="topnav" id="myTopnav">';
//$the_menu .= '<a href="/adm-encuestas.php">Detalle Encuestas</a>';
//$the_menu .= '<a href="/adm-preguntas.php">Preguntas Específicas</a>';
$the_menu .= '<a href="encuesta_nueva.php">Nueva Encuesta</a>';
$the_menu .= '<a href="reporte_semanal.php">Reporte Detalle Semana</a>';
$the_menu .= '<a href="logout.php">Cerrar Sesión</a>';
$the_menu .= '<a href="javascript:void(0);" class="icon" onclick="displayMenu()">&#9776;</a>';
$the_menu .= '</div>';
echo $the_menu;
?>

