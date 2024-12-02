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



$id = $_GET['id'];



$sql =	"	UPDATE 	cursos

			SET		curso_estado = '-1'

			WHERE	curso_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Curso eliminado correctamente!')

window.location.href='cursos.php?';

</SCRIPT>");

mysqli_close($sql);

?>