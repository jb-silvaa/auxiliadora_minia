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
include '../profesor-sc/SED.php';
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rut = $_POST['rut'];
$fecha = $_POST['fecha'];
$correo = $_POST['correo'];
$correo_p = $_POST['correo_p'];
$fono = $_POST['fono'];


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

$sql = "INSERT INTO profesores 
		(
			profesor_rut,
			profesor_clave,
			profesor_nombres,
			profesor_apellidos,
			profesor_fecha_nacimiento,
			profesor_correo,
			profesor_correo_personal,
			profesor_fono,
			profesor_estado,
			profesor_activo
		)
		values
		(
			'$rut',
			'$pass_cript',
			'$nombre',
			'$apellido',
			'$fecha',
			'$correo',
			'$correo_p',
			'$fono',
			'1',
			'1'
		)";
$rs = mysql_query($sql, $conexion);

$sql_last = "SELECT MAX(profesor_id) as profesor_id FROM profesores WHERE profesor_correo_personal = '$correo_p' ";
$rs_last = mysql_query($sql_last, $conexion);
$row_last = mysql_fetch_array($rs_last);
$profesor = $row_last['profesor_id'];
$hora = date("H_i_s");
$nombre_imagen = "foto-perfil/".$profesor."_".$hora.".jpg";
if (isset($_FILES['files'])){	
	//Comprobamos si el fichero es una imagen
	if ($_FILES['files']['type']=='image/jpeg'){
	$destino = '../profesor-sc/foto-perfil/'.$profesor.'_'.$hora.'.jpg';
	//Subimos el fichero al servidor
	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);
	$_FILES["files"]["tmp_name"];
	$sql_imagen = "UPDATE profesores
			   SET profesor_imagen = '$nombre_imagen'
			   WHERE profesor_id = $profesor";
	$rs_imagen = mysql_query($sql_imagen, $conexion);
	}
}

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Profesor creado correctamente!, Anote su contraseña= $clave')
    window.location.href='profesores.php';
    </SCRIPT>");
	mysql_close($sql);
?>