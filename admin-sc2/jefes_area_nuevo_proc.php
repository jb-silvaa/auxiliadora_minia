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
include('../profesor-sc/SED.php');
$jefe = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rut = $_POST['rut'];
$correo=$_POST['correo'];


/* FUNCION PARA CREAR CLAVE ALEATORIA DE 6 CARACTERES */

$clave = RandomString(6,TRUE,TRUE,TRUE,FALSE);
function RandomString($longitud = 6, $opcLetra = TRUE, $opcNumero = TRUE, $opcMayus = TRUE, $opcEspecial = FALSE){
$letras ="abcdefghijklmnopqrstuvwxyz";
$numeros = "1234567890";
$letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$especiales ="|@#~$%()=^*+[]{}-_";
$listado = "";
$password = "";
if ($opcLetra == TRUE) $listado .= $letras;
if ($opcNumero == TRUE) $listado .= $numeros;
if($opcMayus == TRUE) $listado .= $letrasMayus;
if($opcEspecial == TRUE) $listado .= $especiales;

for( $i=1; $i<=$longitud; $i++) {
$caracter = $listado[rand(0,strlen($listado)-1)];
$password.=$caracter;
$listado = str_shuffle($listado);
}
return $password;
}

$pass_cript=SED::encryption($clave);
/*** FIN FUNCION ***/

 $sql = "INSERT INTO jefes_area (
			jefe_rut ,
			jefe_nombre ,
			jefe_apellido ,
			jefe_correo,
			jefe_clave, 
			jefe_estado,
			jefe_activo
			
		)VALUES(
		'$rut',
		'$nombre',
		'$apellido',
		'$correo',
		'$pass_cript',
		'1',
		'1'
	)";
$rs = mysql_query($sql, $conexion);

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Jefe agregado correctamente!, Anote su contraseña= $clave')
    window.location.href='jefes_area.php';
    </SCRIPT>");
	mysql_close($sql);
?>