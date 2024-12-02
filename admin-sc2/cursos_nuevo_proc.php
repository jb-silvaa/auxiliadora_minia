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

$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];

$sql = "INSERT INTO cursos 
		(
			curso_nombre,
			curso_codigo,
			curso_estado
		)
		values
		(
			'$nombre',
			'$codigo',
			'1'
		)";
$rs = mysql_query($sql, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Curso creado correctamente!')
    window.location.href='cursos.php';
    </SCRIPT>");
	mysql_close($sql);
?>