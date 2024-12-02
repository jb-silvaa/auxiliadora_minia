<?php
session_start();
$user_id = $_SESSION['jefe_id'];
if (!$_SESSION['jefe_id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
  die();
}
include '../profesor-sc/SED.php';
$id = $_SESSION['jefe_id'];
include('../funciones-sc/conexion.php');
$clave=$_POST['clavenuevabox1'];
if(!$clave == null ){
	$pass_cifrado = SED::encryption($clave);//funcion para Encriptar
} //cambiar contraseña y guardar la nueva encriptada
$sql = "UPDATE jefes_area
SET jefe_clave = '$pass_cifrado',
	jefe_activo = '0'
where jefe_id = '$id'";
$rs = mysqli_query($conexion, $sql);
echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Cambio de clave exitoso , BIENVENIDO!')
    window.location.href='solicitudes_pendientes.php';
    </SCRIPT>");
	mysql_close($sql);

?>