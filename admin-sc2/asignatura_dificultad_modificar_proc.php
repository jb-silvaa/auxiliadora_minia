<?php
include('../funciones-sc/conexion.php');
session_start();
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
}


$dificultad = $_POST['dificultad'];
$id = $_POST['id'];

$sql = "UPDATE asignaturas 
		SET	
			asignatura_dificultad = '$dificultad'
		WHERE asignatura_id = '$id'";
$rs = mysql_query($sql, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Asignatura modificada correctamente!')
    window.location.href='asignatura_dificultad.php';
    </SCRIPT>");
	mysql_close($sql);
?>