<?php
session_start();
include '../profesor-sc/SED.php';//llamando al archivo con las funciones
$usuario=$_POST['usuario']; //tomando los datos de los input
$clave=$_POST['clave'];
$_SESSION['usuario'] = $usuario;
include('../funciones-sc/conexion.php');

$claveE=SED::encryption($clave);//encriptamos la clave ingresada por el usuario en el input de texto

$consulta = "SELECT * FROM jefes_area where jefe_correo='$usuario' and jefe_clave='$claveE'"; //$claveE , esta comparando con alguna clave = de la BD , por eso se cambio de $clave el cual tomaba el valor del input , por la claveE la cual es la encriptacion del input.
$resultado=mysqli_query($conexion, $consulta);//devuelve un valor de coincidencias
$row_jefe = mysqli_fetch_array($resultado);
$_SESSION['jefe_id'] = $row_jefe['jefe_id'];
$activa = $row_jefe['jefe_activo'];
$encript = $row_jefe['jefe_clave']; //tomamos la clave encriptada de la base de datos (cambiar_clave_proc)

if($row_jefe['jefe_id'] != ''){
	if($activa == '1'){  
		header("location:cambiar_clave.php");
	}else{ 
		
		if($encript == $claveE){
			header("location:solicitudes_pendientes.php");
			}
		}
}else{
       
	     echo "<script LANGUAGE='JavaScript'>
                window.alert('Contraseña erronea');
                window.location= '../jefes_area-sc/index.php'
    </script>";
} 
mysqli_free_result($resultado);
mysqli_close($conexion);
?>