<?php

session_start();

$user_id = $_SESSION['profesor_id'];

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');



$carga = $_POST['id_carga'];

$inicio = $_POST['inicio'];

$termino = $_POST['termino'];

$tipo = $_POST['tipo'];



$sql = "UPDATE cargas

		SET tipo_carga_id = '$tipo',

			carga_fecha_inicio = '$inicio',

			carga_fecha_termino = '$termino'

		WHERE carga_id = '$carga'

	   ";



$rs = mysqli_query($conexion, $sql);



$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);



$hora = date("H_i_s");

$nombre_imagen = "documentos/".$carga."_".$hora.".".$ext;

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='application/msword' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){

	$destino = 'documentos/'.$carga.'_'.$hora.".".$ext;

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

	$_FILES["files"]["tmp_name"];

	$sql_imagen = "UPDATE cargas

			   SET carga_archivo = '$nombre_imagen'

			   WHERE carga_id = $carga";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}

}



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Carga modificada correctamente!')

    window.location.href='index.php';

    </SCRIPT>");

?>