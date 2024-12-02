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

$alumno = $_POST['id'];

$nombre = $_POST['nombre'];

$apellido = $_POST['apellido'];

$rut = $_POST['rut'];





$sql = "UPDATE alumnos 

		SET	alumno_rut = '$rut',

			alumno_nombres = '$nombre',

			alumno_apellidos = '$apellido'

		WHERE alumno_id = '$alumno'";

$rs = mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Alumno modificado correctamente!')

    window.location.href='alumnos.php';

    </SCRIPT>");

	mysqli_close($sql);

?>