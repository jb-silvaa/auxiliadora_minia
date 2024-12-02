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
include('../funciones-sc/conexion.php');

$id = $_POST['id'];
$cur_asig = $_POST['cur_asig'];
$decision = $_POST['decision'];

$sql =	"	UPDATE 	evaluaciones
			SET		evaluacion_estado_id = '$decision'
			WHERE	evaluacion_id = '$id'
		";
$rs	 =	mysql_query($sql,$conexion);
echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Estado de evaluacion modificado correctamente!')
window.location.href='evaluacion.php?id=$cur_asig';
</SCRIPT>");
mysql_close($sql);
?>