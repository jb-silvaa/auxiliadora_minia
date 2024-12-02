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



function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



	echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}





$curso_id = $_POST['id'];

$evaluacion = $_POST['nivel'];

$fecha = $_POST['inicio'];

$nombre = $_POST['nameprueba'];

$copias = $_POST['copias'];

$observacion = $_POST['obscorreccion1'];



 $sql = "UPDATE evaluaciones 

		SET	tipo_evaluacion_id = '$evaluacion',

		evaluacion_fecha = '$fecha',

		evaluacion_nombre='$nombre',

		evaluacion_copia = '$copias',

		evaluacion_obs_correccion = '$observacion',

		evaluacion_aprobacion = '0',

		evaluacion_encargado = ''

		WHERE evaluacion_id = '$curso_id'";



$rs = mysqli_query($conexion, $sql);



$sql_last = "SELECT MAX(evaluacion_id) as evaluacion_id FROM evaluaciones WHERE curso_asignatura_id = '$curso_id'";

$rs_last = mysqli_query($conexion, $sql_last);

$row_last = mysqli_fetch_array($rs_last);

$carga = $curso_id;



$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);



$hora = date("H_i_s");

$nombre_imagen = "documentos/".$carga."_".$hora.".".$ext;

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='application/octet-stream' || $_FILES['files']['type']=='application/msword' || 

		$_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){

		$destino = 'documentos/'.$carga.'_'.$hora.".".$ext;

		//Subimos el fichero al servidor

		move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

		$_FILES["files"]["tmp_name"];



		$sql_imagen = "UPDATE evaluaciones

			   SET evaluacion_archivo = '$nombre_imagen'

			   WHERE evaluacion_id = $carga";

		$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}else{
		echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Extensión de archivo no válida!')

    window.location.href='evaluaciones.php?id=$id_c';

    </SCRIPT>");
	}

}

debug_to_console($sql_imagen);



$sql2 = "SELECT * 

		FROM evaluaciones

		WHERE evaluacion_id = '$curso_id'";



$rs3 = mysqli_query($conexion, $sql2);



$arreglo = mysqli_fetch_array($rs3);



$id_c = $arreglo['curso_asignatura_id'];



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Evaluacion modificada correctamente!')

    window.location.href='evaluaciones.php?id=$id_c';

    </SCRIPT>");

	mysqli_close($sql);

?>