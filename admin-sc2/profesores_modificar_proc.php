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
$profesor = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rut = $_POST['rut'];
$fecha = $_POST['fecha'];
$correo = $_POST['correo'];
$correo_p = $_POST['correo_p'];
$fono = $_POST['fono'];

$sql = "UPDATE profesores 
		SET	profesor_rut = '$rut',
			profesor_nombres = '$nombre',
			profesor_apellidos = '$apellido',
			profesor_fecha_nacimiento = '$fecha',
			profesor_correo = '$correo',
			profesor_correo_personal = '$correo_p',
			profesor_fono = '$fono'
		WHERE profesor_id = '$profesor'";
$rs = mysql_query($sql, $conexion);

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
    window.alert('Profesor modificado correctamente!')
    window.location.href='profesores.php';
    </SCRIPT>");
	mysql_close($sql);
?>