<?php
session_start();
include 'profesor-sc/SED.php';//llamando al archivo con las funciones
$usuario=$_POST['usuario']; //tomando los datos de los input
$clave=$_POST['clave'];
$_SESSION['usuario'] = $usuario;
include('funciones-sc/conexion.php');

$claveE=SED::encryption($clave);//encriptamos la clave ingresada por el usuario en el input de texto

$consulta = "SELECT * FROM profesores where profesor_correo='$usuario' and profesor_clave='$claveE'"; //$claveE , esta comparando con alguna clave = de la BD , por eso se cambio de $clave el cual tomaba el valor del input , por la claveE la cual es la encriptacion del input.
$resultado=mysqli_query($conexion, $consulta);//devuelve un valor de coincidencias
$row_profesor = mysqli_fetch_array($resultado);
$_SESSION['profesor_id'] = $row_profesor['profesor_id'];
$activa = $row_profesor['profesor_activo'];
$encript = $row_profesor['profesor_clave']; //tomamos la clave encriptada de la base de datos (cambiar_clave_proc)

if($row_profesor['profesor_id'] != ''){
	if($activa == '1'){  
		header("location:profesor-sc/cambiar_clave.php");
	}else{ 
		
		if($encript == $claveE){
			header("location:profesor-sc/index.php");
			}
		}
}else{
       
	     echo "<script LANGUAGE='JavaScript'>
                window.alert('Contraseña erronea');
                window.location= 'index.php'
    </script>";
} 
mysqli_free_result($resultado);
mysqli_close($conexion);
?>