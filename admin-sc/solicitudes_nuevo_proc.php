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



$tipo = $_POST['tipo'];

$desc = $_POST['desc'];

$fecha = date('Y-m-d');

$usuario = $_SESSION['id'];

$receptor = $_POST['receptor'];

$tipo_usuario = substr($receptor, -1);

if($tipo_usuario == '1')

{

	$rec_usuario = substr($receptor,0, -2);

	$rec_profesor = '0';

}

if($tipo_usuario == '2')

{

	$rec_profesor = substr($receptor,0, -2);

	$rec_usuario = '0';

}

$sql = "INSERT INTO solicitudes 

		(

			solicitud_cuerpo,

			tipo_solicitud_id,

			usuario_id,

			profesor_id,

			receptor_usuario_id,

			receptor_profesor_id,

			solicitud_fecha_creacion,

			solicitud_estado

		)

		values

		(

			'$desc',

			'$tipo',

			'$usuario',

			'0',

			'$rec_usuario',

			'$rec_profesor',

			'$fecha',

			'1'

		)";

$rs = mysqli_query($conexion, $sql);



$sql_last = "SELECT MAX(solicitud_id) as solicitud_id FROM solicitudes WHERE usuario_id = '$usuario' ";

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

	$destino = '../profesor-sc/documentos/'.$id.'_'.$hora.".".$ext;

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



	$sql_curso = "SELECT * FROM usuarios WHERE usuario_id = '$user_id'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);



	$sql_tipo = "SELECT * FROM tipos_solicitudes WHERE tipo_solicitud_id = '$tipo'";

	$rs_tipo = mysqli_query($conexion, $sql_tipo);

	$row_tipo = mysqli_fetch_array($rs_tipo);



	$fecha_esp = date("d-m-Y", strtotime($fecha));



	$mensaje = "Estimado se ha generado una nueva solicitud para su analisis:<br><br>

	<b>Creador: </b>".$row_curso['usuario_nombres']." ".$row_curso['usuario_apellidos']."<br>

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

	$sql_mail = "SELECT profesor_correo FROM profesores WHERE profesor_id = '$receptor'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['profesor_correo'] );

	}	

	$email->Send();

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Solicitud creada correctamente!')

    window.location.href='solicitudes_pendientes.php';

    </SCRIPT>");

	mysqli_close($sql);

?>