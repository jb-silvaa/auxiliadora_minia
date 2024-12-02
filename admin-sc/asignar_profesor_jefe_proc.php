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



$periodo = $_POST['periodo'];

$curso_id = $_POST['curso'];

$profesor_id = $_POST['profesor'];



$sql_nl = "SELECT * FROM cursos_asignaturas WHERE curso_asignatura_id = '$curso_id'";

$rs_nl = mysqli_query($conexion, $sql_nl);

$row_nl = mysqli_fetch_array($rs_nl);

$letra_id = $row_nl['letra_id'];

$nivel_id = $row_nl['nivel_id'];



$sql_curso = "INSERT INTO profesores_jefes

        (

        nivel_id,

        letra_id,

        profesor_jefe_periodo,

        profesor_id,

        profesor_jefe_estado

        )

        VALUES

        (

        '$nivel_id',

        '$letra_id',

        '$periodo',

        '$profesor_id',

        '1'

        )

        ";	

$rs_curso = mysqli_query($conexion, $sql_curso);



$sql =	"	UPDATE 	cursos_asignaturas

			SET		curso_asignatura_jefatura = '1'

			WHERE	nivel_id = '$nivel_id'

            AND letra_id = '$letra_id'

            AND curso_asignatura_periodo = '$periodo'

        ";

$rs = mysqli_query($conexion, $sql);

//insertamos el nuevo periodo a tabla periodos para mostrarlo en el listado de años



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Asignatura agregada correctamente!')

    window.location.href='listado_profesores_jefes.php';

    </SCRIPT>");

	mysqli_close($sql);

?>

