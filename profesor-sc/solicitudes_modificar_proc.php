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



$new = $_POST['usuario'];

$tipo = substr($new, 0, 1);

if($tipo == '1')

{

	$new_usuario = substr($new, 2);

	$new_profesor = '0';

}

else

{

	$new_profesor = substr($new, 2);

	$new_usuario = '0';

}

$desc = $_POST['desc'];

$fecha = date('Y-m-d');

$usuario = $_SESSION['profesor_id'];

$id = $_POST['id'];



$sql = "INSERT INTO solicitudes_historial 

		(

			solicitud_historial_comentario,

			solicitud_id,

			usuario_id,

			profesor_id,

			solicitud_historial_fecha

		)

		values

		(

			'$desc',

			'$id',

			'0',

			'$usuario',

			'$fecha'

		)";

$rs = mysqli_query($conexion, $sql);



$sql_receptor = "UPDATE solicitudes

			   	 SET receptor_usuario_id = '$new_usuario',

			   	 	 receptor_profesor_id = '$new_profesor'

			   	 WHERE solicitud_id = $id";

$rs_receptor = mysqli_query($conexion, $sql_receptor);



$sql_last = "SELECT MAX(solicitud_historial_id) as solicitud_historial_id FROM solicitudes_historial WHERE profesor_id = '$usuario' ";

$rs_last = mysqli_query($conexion, $sql_last);

$row_last = mysqli_fetch_array($rs_last);



$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);



$id = $row_last['solicitud_historial_id'];

$hora = date("H_i_s");

$nombre_imagen = "documentos/".$id."_".$hora.".".$ext;

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='application/msword' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['files']['type']=='application/pdf' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.presentationml.presentation' || $_FILES['files']['type']=='image/jpeg' || $_FILES['files']['type']=='image/jpg' || $_FILES['files']['type']=='application/vnd.ms-excel' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){

	$destino = '../profesor-sc/documentos/'.$id.'_'.$hora.".".$ext;

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

	$_FILES["files"]["tmp_name"];

	$sql_imagen = "UPDATE solicitudes_historial

			   SET solicitud_historial_archivo = '$nombre_imagen'

			   WHERE solicitud_historial_id = $id";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}

}

require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

	require '../PHPMailer-master/PHPMailerAutoload.php';

	$email = new PHPMailer();



	$sql_info = "SELECT * FROM solicitudes WHERE solicitud_id = '$id'";

	$rs_info = mysqli_query($conexion, $sql_info);

	$row_info = mysqli_fetch_array($rs_info);



	if($row_info['usuario_id'] == '0')

	{

		$sql_curso = "SELECT * FROM profesores WHERE profesor_id = ".$row_info['profesor_id'];

		$rs_curso = mysqli_query($conexion, $sql_curso);

		$row_curso = mysqli_fetch_array($rs_curso);



		$nombre = $row_curso['profesor_nombres']." ".$row_curso['profeosr_apellidos'];

	}



	if($row_info['profesor_id'] == '0')

	{

		$sql_curso = "SELECT * FROM usuarios WHERE usuario_id = ".$row_info['usuario_id'];

		$rs_curso = mysqli_query($conexion, $sql_curso);

		$row_curso = mysqli_fetch_array($rs_curso);



		$nombre = $row_curso['usuario_nombres']." ".$row_curso['usuario_apellidos'];

	}	



	$sql_tipo = "SELECT * FROM tipos_solicitudes WHERE tipo_solicitud_id = ".$row_info['tipo_solicitud_id'];

	$rs_tipo = mysqli_query($conexion, $sql_tipo);

	$row_tipo = mysqli_fetch_array($rs_tipo);



	$fecha_esp = date("d-m-Y", strtotime($fecha));



	$mensaje = "Estimado se ha re-enviado una solicitud para su analisis:<br><br>

	<b>Creador: </b>".$nombre."<br>

	<b>Tipo Solicitud: </b>".$row_tipo['tipo_solicitud_nombre']."<br>	

	<b>Mensaje: </b>".$row_info['solicitud_cuerpo']."<br>

	<br><br>

	Para revisar esta u otras solicitudes diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/admin-sc/'>link</a>.<br>

	Atentamente.<br><br>

	Sistema Colegio María Auxiliadora.

	";



	//	aqui agrego la validacion smtp auth

	



	$email->CharSet = 'UTF-8';

	$email->From      = 'no-reply@colegiomariaauxiliadora.cl';

	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';

	$email->Subject   = 'Solicitud re-enviada.';

	$email->Body      = $mensaje;

	$email->IsHTML(true);

	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO

	if($new_profesor != '0')

	{

		$sql_mail = "SELECT profesor_correo FROM profesores WHERE profesor_id = ".$new_profesor;

		$rs_mail = mysqli_query($conexion, $sql_mail);

		while($row_mail = mysqli_fetch_array($rs_mail))

		{

			$email->AddAddress( $row_mail['profesor_correo'] );

		}

	}

	if($new_usuario != '0')

	{

		$sql_mail = "SELECT usuario_mail FROM usuarios WHERE usuario_id = ".$new_usuario;

		$rs_mail = mysqli_query($conexion, $sql_mail);

		while($row_mail = mysqli_fetch_array($rs_mail))

		{

			$email->AddAddress( $row_mail['usuario_mail'] );

		}

	}	

	$email->Send();

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Solicitud reenviada correctamente!')

    window.location.href='solicitudes_pendientes.php';

    </SCRIPT>");

	mysqli_close($sql);

?>