<?php

session_start();

$usuario=$_POST['usuario'];

$clave=$_POST['clave'];

include('../funciones-sc/conexion.php');

$consulta="SELECT * FROM usuarios where usuario='$usuario' and clave='$clave'";

$resultado=mysqli_query($conexion, $conexion,$consulta);

$row=mysqli_num_rows($resultado);

if($filas>0){

	header("location:../admin-sc/main.php");

}else{

	echo "error en la autenticacion";

}

mysqli_close($conexion);

?>