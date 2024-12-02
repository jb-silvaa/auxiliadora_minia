<?php

session_start();

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'index.php'

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

$rs = mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Cambio de clave exitoso , BIENVENIDO!')

    window.location.href='../admin-sc/listado_cursos.php';

    </SCRIPT>");





	mysqli_close($sql);



?>