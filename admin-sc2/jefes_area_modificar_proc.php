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
$jefe = $_POST['id'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$rut = $_POST['rut'];


$sql = "UPDATE jefes_area 
		SET	jefe_rut = '$rut',
			jefe_nombre = '$nombre',
			jefe_apellido = '$apellido'
		WHERE jefe_id = '$jefe'";
$rs = mysql_query($sql, $conexion);

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Jefe modificado correctamente!')
    window.location.href='jefes_area.php';
    </SCRIPT>");
	mysql_close($sql);
?>