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

$profesor = $_SESSION['profesor_id'];

$fecha = $_POST['fecha'];

$correo_p = $_POST['correo_p'];

$fono = $_POST['fono'];



$sql = "UPDATE profesores 

		SET	

			profesor_fecha_nacimiento = '$fecha',

			profesor_correo_personal = '$correo_p',

			profesor_fono = '$fono'

		WHERE profesor_id = '$profesor'";

$rs = mysqli_query($conexion, $sql);



$hora = date("H_i_s");

$nombre_imagen = "foto-perfil/".$profesor."_".$hora.".jpg";

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='image/jpeg'){

	$destino = 'foto-perfil/'.$profesor.'_'.$hora.'.jpg';

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

	$_FILES["files"]["tmp_name"];

	$sql_imagen = "UPDATE profesores

			   SET profesor_imagen = '$nombre_imagen'

			   WHERE profesor_id = $profesor";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}else{
		echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('No se pudo grabar la imagen ya que no es jpg!')

    window.location.href='index.php';

    </SCRIPT>");

	mysqli_close($sql);
	}

}



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Datos modificados correctamente!')

    window.location.href='index.php';

    </SCRIPT>");

	mysqli_close($sql);

?>