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

include('../funciones-sc/conexion.php');



$id = $_GET['id'];

$cur_asig = $_GET['cur_asi'];



$sql =	"	UPDATE 	cargas

			SET		carga_aprobacion = '-1'

			WHERE	carga_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Carga reprobada correctamente!')

window.location.href='carga.php?id=$cur_asig';

</SCRIPT>");

mysqli_close($sql);

?>