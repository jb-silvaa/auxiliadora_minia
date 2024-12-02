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



$sql =	"	UPDATE 	cursos_asignaturas

			SET		curso_asignatura_estado = '-1'

			WHERE	curso_asignatura_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

$docente = $_GET['docente'];
$nivel = $_GET['nivel'];
$asignatura = $_GET['asignatura'];

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Curso eliminado correctamente!')

window.location.href='listado_cursos.php?asignatura=$asignatura&nivel=$nivel&docente=$docente';

</SCRIPT>");

mysqli_close($sql);

?>