<?php

session_start();

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



$id = $_GET['id'];



$sql =	"	UPDATE 	profesores

			SET		profesor_estado = '-1'

			WHERE	profesor_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Profesor eliminado correctamente!')

window.location.href='profesores.php?';

</SCRIPT>");

mysqli_close($sql);

?>