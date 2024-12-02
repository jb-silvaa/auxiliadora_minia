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
$id = $_POST['id'];

$sql = "UPDATE cursos 
		SET	curso_nombre = '$nombre',
			curso_codigo = '$codigo'
		WHERE curso_id = '$id'";
$rs = mysql_query($sql, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Curso modificado correctamente!')
    window.location.href='cursos.php';
    </SCRIPT>");
	mysql_close($sql);
?>