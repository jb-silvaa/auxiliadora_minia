<?php

session_start();

$user_id = $_SESSION['profesor_id'];

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');



$id = $_GET['id'];

$id_c = $_GET['id_c'];



$sql =	"	UPDATE 	cargas

			SET		carga_archivo = '',

      carga_aprobacion = '0'

			WHERE	carga_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Archivo eliminado correctamente!')

window.location.href='carga.php?id=$id_c';

</SCRIPT>");

mysqli_close($sql);

?>