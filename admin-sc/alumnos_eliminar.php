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



$id = $_GET['id'];



$sql =	"	UPDATE 	alumnos

			SET		alumno_estado = '-1'

			WHERE	alumno_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Alumno eliminado correctamente!')

window.location.href='alumnos.php?';

</SCRIPT>");

mysqli_close($sql);

?>