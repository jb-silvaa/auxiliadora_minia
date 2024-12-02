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

$nombre = $_POST['nombre'];

$codigo = $_POST['codigo'];

$dificultad = $_POST['dificultad'];

if($nombre == '')

{

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('No se puede crear asignatura sin información!')

    window.location.href='asignaturas_nuevo.php';

    </SCRIPT>");

	mysqli_close($sql);

}

else

{

$sql = "INSERT INTO asignaturas 

		(

			asignatura_nombre,

			asignatura_codigo,

			asignatura_dificultad,

			asignatura_estado

		)

		values

		(

			'$nombre',

			'$codigo',

			'$dificultad',

			'1'

		)";

$rs = mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Asignatura creada correctamente!')

    window.location.href='asignaturas.php';

    </SCRIPT>");

	mysqli_close($sql);

}

?>