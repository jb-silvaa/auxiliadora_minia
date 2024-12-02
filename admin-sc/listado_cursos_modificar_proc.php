<?php

session_start();

$user_id = $_SESSION['id'];

if (!$_SESSION){

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



$nota = $_POST['nota'];

$nota_actual = $_POST['nota_actual'];

$unidad = $_POST['unidad'];

$profesor = $_POST['profesor'];

$letra = $_POST['letra'];

$id = $_POST['id'];



$sql = "UPDATE cursos_asignaturas 

		SET	curso_asignatura_notas_total = '$nota',

			curso_asignatura_notas_actual = '$nota_actual',

			profesor_id = '$profesor',

			curso_asignatura_unidades = '$unidad'

		WHERE curso_asignatura_id = '$id'";

$rs = mysqli_query($conexion, $sql);

$docente = $_POST['docente'];
$nivel = $_POST['nivel'];
$asignatura = $_POST['asignatura'];



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Curso modificado correctamente!')

    window.location.href='listado_cursos.php?asignatura=$asignatura&nivel=$nivel&docente=$docente&letra=$letra';

    </SCRIPT>");

	mysqli_close($sql);

?>