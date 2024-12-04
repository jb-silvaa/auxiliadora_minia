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



$tipo = $_POST['tipo'];

$desc = $_POST['desc'];

$fecha = date('Y-m-d');

$usuario = $_SESSION['profesor_id'];

$receptor = $_POST['receptor'];

$sql = "INSERT INTO solicitudes 

		(

			solicitud_cuerpo,

			tipo_solicitud_id,

			usuario_id,

			profesor_id,

			receptor_usuario_id,

			solicitud_fecha_creacion,

			solicitud_estado

		)

		values

		(

			'$desc',

			'$tipo',

			'0',

			'$usuario',

			'$receptor',

			'$fecha',

			'0'

		)";

$rs = mysqli_query($conexion, $sql);



$sql_last = "SELECT MAX(solicitud_id) as solicitud_id FROM solicitudes WHERE profesor_id = '$usuario' ";

$rs_last = mysqli_query($conexion, $sql_last);

$row_last = mysqli_fetch_array($rs_last);



$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);



$id = $row_last['solicitud_id'];

$hora = date("H_i_s");

$nombre_imagen = "documentos/".$id."_".$hora.".".$ext;

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='application/msword' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['files']['type']=='application/pdf' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.presentationml.presentation' || $_FILES['files']['type']=='image/jpeg' || $_FILES['files']['type']=='image/jpg' || $_FILES['files']['type']=='application/vnd.ms-excel' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){

	$destino = 'documentos/'.$id.'_'.$hora.".".$ext;

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

	$_FILES["files"]["tmp_name"];

	$sql_imagen = "UPDATE solicitudes

			   SET solicitud_archivo = '$nombre_imagen'

			   WHERE solicitud_id = $id";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}

}





require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

	require '../PHPMailer-master/PHPMailerAutoload.php';

	$email = new PHPMailer();



	$sql_curso = "SELECT * FROM profesores WHERE profesor_id = '$user_id'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);



	$sql_tipo = "SELECT * FROM tipos_solicitudes WHERE tipo_solicitud_id = '$tipo'";

	$rs_tipo = mysqli_query($conexion, $sql_tipo);

	$row_tipo = mysqli_fetch_array($rs_tipo);



	$fecha_esp = date("d-m-Y", strtotime($fecha));



	$mensaje = "Estimado se ha generado una nueva solicitud para su analisis:<br><br>

	<b>Creador: </b>".$row_curso['profesor_nombres']." ".$row_curso['profesor_apellidos']."<br>

	<b>Tipo Solicitud: </b>".$row_tipo['tipo_solicitud_nombre']."<br>	

	<b>Mensaje: </b>".$desc."<br>

	<br><br>

	Para revisar esta u otras solicitudes diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/admin-sc/'>link</a>.<br>

	Atentamente.<br><br>

	Sistema Colegio María Auxiliadora.

	";



	//	aqui agrego la validacion smtp auth

	



	$email->CharSet = 'UTF-8';

	$email->From      = 'no-reply@colegiomariaauxiliadora.cl';

	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';

	$email->Subject   = 'Creación de nueva solicitud.';

	$email->Body      = $mensaje;

	$email->IsHTML(true);

	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO

	$sql_mail = "SELECT usuario_mail FROM usuarios WHERE usuario_estado = '1' AND perfil_id = '1'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['usuario_mail'] );

	}	

	$sql_mail = "SELECT jefe_correo FROM jefes_area as ja, jefes_area_profe as jap WHERE jefe_estado = '1' AND ja.jefe_id = jap.jefe_id AND jap.profesor_id = '$usuario'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['jefe_correo'] );

	}

	// $email->Send();



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Solicitud creada correctamente!')

    window.location.href='index.php';

    </SCRIPT>");

	mysqli_close($sql);

?>