<?php 
session_start();
include('../funciones-sc/conexion.php');
$usuariomsg = $_SESSION['profesor_id'];

$sql="UPDATE mensajes set mensaje_leido = '1' WHERE
	profesor_id = '".$usuariomsg."' AND mensaje_estado = '1'";

$result = mysql_query($sql,$conexion);
echo $sql;							