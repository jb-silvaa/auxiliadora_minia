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



$nombre = $_POST['nombre'];

$apellido = $_POST['apellido'];

$rut = $_POST['rut'];



$sql = "INSERT INTO alumnos 

		(

			alumno_rut,

			alumno_nombres,

			alumno_apellidos,

			alumno_estado

		)

		values

		(

			'$rut',

			'$nombre',

			'$apellido',

			'1'

		)";

$rs = mysqli_query($conexion, $sql);





echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Alumno creado correctamente!')

    window.location.href='alumnos.php';

    </SCRIPT>");

	mysqli_close($sql);

?>