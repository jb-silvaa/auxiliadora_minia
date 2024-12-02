<?php
include('../funciones-sc/conexion.php');

$id = $_GET['id'];

$sql =	"	UPDATE 	profesores
			SET		profesor_clave = 'RDJCRWV4bGZOY2ZlQVRLK2tBQWdFZz09',
					profesor_activo = '1',
					profesor_clave_activa = '0'
			WHERE	profesor_id = '$id'
		";
$rs	 =	mysql_query($sql,$conexion);
echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Clave re-seteada correctamente! Nueva clave = 12345')
window.location.href='profesores.php?';
</SCRIPT>");
mysql_close($sql);
?>