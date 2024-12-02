<?php
session_start();
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
}
include '../profesor-sc/SED.php';
$id = $_SESSION['id'];
include('../funciones-sc/conexion.php');
$clave=$_POST['clavenuevabox1'];
if(!$clave == null ){
	$pass_cifrado = SED::encryption($clave);//funcion para Encriptar
} //cambiar contraseña y guardar la nueva encriptada
$sql = "UPDATE usuarios 
SET usuario_clave = '$pass_cifrado',
	usuario_activo = '0'
where usuario_id = '$id'";
$rs = mysql_query($sql, $conexion);
echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Cambio de clave exitoso , BIENVENIDO!')
    window.location.href='../admin-sc/main.php';
    </SCRIPT>");


	mysql_close($sql);

?>