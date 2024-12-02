<?php

session_start();

if (!$_SESSION['id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');



$profesor_id = $_POST['profesor'];

$nivel_id = $_POST['nivel'];

$letra_id = $_POST['letra'];

$periodo = $_POST['periodo'];



$sql =	"	UPDATE 	profesores_jefes

			SET		profesor_id = '$profesor_id'

			WHERE	 nivel_id = '$nivel_id'

      AND letra_id = '$letra_id'

      AND profesor_jefe_periodo = '$periodo'

      AND profesor_jefe_estado = '1'

		";

$rs	 =	mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Profesor jefe modificado correctamente!')

window.location.href='listado_profesores_jefes.php';

</SCRIPT>");

mysqli_close($sql);

?>