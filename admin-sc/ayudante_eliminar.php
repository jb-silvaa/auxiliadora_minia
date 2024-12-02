<?php

session_start();

if ($_SESSION['perfil'] != 1){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'listado_cursos.php'

    </script>";

    die();

}

include('../funciones-sc/conexion.php');



$ayudante_id = $_GET['id'];
$periodo = $_GET['periodo'];



$sql =	"	UPDATE 	ayudantes

			SET		ayudante_estado = '-1'

			WHERE	ayudante_id = '$ayudante_id'";

$rs	 =	mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Curso/Coordinador eliminado correctamente!')

window.location.href='listado_ayudantes.php?periodo=$periodo';

</SCRIPT>");

mysqli_close($sql);

?>