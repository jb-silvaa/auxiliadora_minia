<?php

session_start();

include('../funciones-sc/conexion.php');

$user_id = $_SESSION['id'];

if($user_id==null || $user_id== ''){

   echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../admin-sc/index.php'

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



$id = $_GET['id'];

if($id == '')

{

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Valor vacío de asignatura, nada se eliminó!')

    window.location.href='asignaturas.php';

    </SCRIPT>");

}

else

{

$sql =	"	UPDATE 	asignaturas

			SET		asignatura_estado = '-1'

			WHERE	asignatura_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Asignatura eliminada correctamente!')

window.location.href='asignaturas.php?';

</SCRIPT>");

mysqli_close($sql);

}

?>