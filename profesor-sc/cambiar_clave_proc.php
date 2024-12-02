<?php

session_start();

$user_id = $_SESSION['profesor_id'];

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include 'SED.php';

$id = $_SESSION['profesor_id'];

include('../funciones-sc/conexion.php');

$clave=$_POST['clavenuevabox1'];

if(!$clave == null ){

	$pass_cifrado = SED::encryption($clave);//funcion para Encriptar

} //cambiar contraseña y guardar la nueva encriptada

$sql = "UPDATE profesores 

SET profesor_clave = '$pass_cifrado',

	profesor_activo = '0'

where profesor_id = '$id'";

$rs = mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Cambio de clave exitoso , BIENVENIDO!')

    window.location.href='../profesor-sc/index.php';

    </SCRIPT>");





	mysqli_close($sql);



?>