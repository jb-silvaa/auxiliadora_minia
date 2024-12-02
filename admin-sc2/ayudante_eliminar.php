<?php
session_start();
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
}
include('../funciones-sc/conexion.php');

$ayudante_id = $_GET['id'];

$sql =	"	UPDATE 	ayudantes
			SET		ayudante_estado = '-1'
			WHERE	ayudante_id = '$ayudante_id'";
$rs	 =	mysql_query($sql,$conexion);

echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Archivo eliminado correctamente!')
window.location.href='listado_ayudantes.php';
</SCRIPT>");
mysql_close($sql);
?>