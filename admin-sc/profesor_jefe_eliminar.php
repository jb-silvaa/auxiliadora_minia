<?php

session_start();

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

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



$sql_data = "SELECT * FROM profesores_jefes WHERE profesor_jefe_id = '$id'";

$rs_data = mysqli_query($conexion, $sql_data);

$row_data = mysqli_fetch_array($rs_data);

$profesor_id = $row_data['profesor_id'];

$nivel_id = $row_data['nivel_id'];

$letra_id = $row_data['letra_id'];

$periodo = $row_data['profesor_jefe_periodo'];



$sql =	"	UPDATE 	profesores_jefes

			SET		profesor_jefe_estado = '-1'

			WHERE	profesor_jefe_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);



$sql =	"	UPDATE 	cursos_asignaturas

			SET		curso_asignatura_jefatura = '0'

			WHERE	nivel_id = '$nivel_id'

            AND letra_id = '$letra_id'

            AND curso_asignatura_periodo = '$periodo'

        ";

$rs = mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Archivo eliminado correctamente!')

window.location.href='listado_profesores_jefes.php';

</SCRIPT>");

mysqli_close($sql);

?>