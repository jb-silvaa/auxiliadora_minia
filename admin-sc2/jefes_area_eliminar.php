<?php
session_start();
$user_id = $_SESSION['id'];
if (!$_SESSION['id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
    die();
}
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
}
include('../funciones-sc/conexion.php');

$id = $_GET['id'];

$sql =	"	UPDATE 	jefes_area
			SET		jefe_estado = '-1'
			WHERE	jefe_id = '$id'
		";
$rs	 =	mysql_query($sql,$conexion);
echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Jefe eliminado correctamente!')
window.location.href='jefes_area.php?';
</SCRIPT>");
mysql_close($sql);
?>