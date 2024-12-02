<?php  

//conecto con la base de datos 	

session_start();

include('../funciones-sc/conexion.php');

if ($_SESSION['perfil'] != 1){

	echo "<script LANGUAGE='JavaScript'>

				  window.alert('Acceso no autorizado');

				  window.location= 'listado_cursos.php'

	  </script>";

	  die();

  }







$id	= trim($_GET["id"]);



$sql =	"

		UPDATE cargas

		SET carga_archivo = ''

		WHERE carga_id = '$id'		

		";

$rs	 =	mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Archivo eliminado correctamente!')

    window.location.href='reportes.php';

    </SCRIPT>");

	mysqli_close($sql);

?>