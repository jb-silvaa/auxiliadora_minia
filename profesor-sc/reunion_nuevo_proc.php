<?php

session_start();

$user_id = $_SESSION['profesor_id'];

function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');



$curso = $_POST['curso'];

            $result_explode = explode('|', $curso);

            echo "Model: ". $result_explode[0]."<br />";

            echo "Colour: ". $result_explode[1]."<br />";



$profesor_id = $_POST['id'];

$nid = $result_explode[0];

$lid = $result_explode[1];

$descripcion = $_POST['descripcion'];

$fecha = $_POST['inicio'];

$asunto = $_POST['asunto'];

$tipo = $_POST['tipo'];



$sql = "INSERT INTO profesor_jefe_reuniones

		(   

			profesor_id,

			reunion_tipo,

			reunion_asunto,

			reunion_resumen,

            nivel_id,

            letra_id,

			reunion_fecha

		)

		values

		(

			'$profesor_id',

            '$tipo',

			'$asunto',

			'$descripcion',

            '$nid',

            '$lid',

			'$fecha'

		)";

		

$rs = mysqli_query($conexion, $sql);



$sql_last = "SELECT MAX(reunion_id) as reunion_id FROM profesor_jefe_reuniones WHERE profesor_id = '$profesor_id'";

$rs_last = mysqli_query($conexion, $sql_last);

$row_last = mysqli_fetch_array($rs_last);

$carga = $row_last['reunion_id'];



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



$sql_imagen = "UPDATE profesor_jefe_reuniones

			   SET reunion_archivo = '$nombre_imagen'

			   WHERE reunion_id = $carga";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}

}



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Evaluacion cargada correctamente!')

    window.location.href='listado_reuniones.php';

    </SCRIPT>");

?>