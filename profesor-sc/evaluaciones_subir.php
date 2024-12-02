<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();
require '/var/www/html/vendor/autoload.php';

$user_id = $_SESSION['profesor_id'];

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');

include('../funciones-sc/notificacion.php');



$carga = $_GET['id'];
$curso_id = $_GET['curso_id'];
$copias = $_POST['cantidad'];

//Se suben notis para todos los usuarios

$sql_todos = "SELECT * FROM usuarios WHERE usuario_estado = '1'";

$rs_todos = mysqli_query($conexion, $sql_todos);

while($row_todos = mysqli_fetch_array($rs_todos)){

    $user = $row_todos['usuario_id'];

    $sql_ayudante = "SELECT * 

                    FROM ayudantes 

                    INNER JOIN usuarios on usuarios.usuario_id = ayudantes.usuario_id

                    WHERE ayudantes.usuario_id = '$user'";

    $rs_ayudante = mysqli_query($conexion, $sql_ayudante);

    $row_ayudante = mysqli_fetch_array($rs_ayudante);

    if(mysqli_num_rows($rs_ayudante)>0){

        if($row_ayudante['curso_asignatura_id'] == $curso_id){

            notify("evaluacion", $row_todos['usuario_id'], $carga, $user_id, 2);

        }

        continue;



    }

    notify("evaluacion", $row_todos['usuario_id'], $carga, $user_id, 2);

}



$filename = $_FILES['files'.$carga]['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);
if($ext == ''){ $ext = "docx"; }



$hora = date("H_i_s");

$nombre_imagen = "documentos/".$carga."_".$hora.".".$ext;

if (isset($_FILES['files'.$carga])){	

	//Comprobamos si el fichero es una imagen

	if ($ext == 'doc' || $ext == 'docx' || $_FILES['files'.$carga]['type']=='application/octet-stream' || $_FILES['files'.$carga]['type']=='application/msword' || $_FILES['files'.$carga]['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){

	$destino = 'documentos/'.$carga.'_'.$hora.".".$ext;

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files".$carga]["tmp_name"], $destino);

	//$_FILES["files".$carga]["tmp_name"];

	if(is_file($nombre_imagen))
	{
		$sql_imagen = "UPDATE evaluaciones

			   SET evaluacion_archivo = '$nombre_imagen',
			   	   evaluacion_copia = '$copias'

			   WHERE evaluacion_id = '$carga'";

		$rs_imagen = mysqli_query($conexion, $sql_imagen);
	}
	else 
	{
		echo ("<SCRIPT LANGUAGE='JavaScript'>

	window.alert('Archivo no se pudo subir!')

	window.location.href='evaluaciones.php?id=$curso_id';

	</SCRIPT>");
	}

	

	}else{
		echo ("<SCRIPT LANGUAGE='JavaScript'>

	window.alert('Extensión de archivo no válida!')

	window.location.href='evaluaciones.php?id=$curso_id';

	</SCRIPT>");
	}

}



require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

	require '../PHPMailer-master/PHPMailerAutoload.php';

	$email = new PHPMailer();

	$sql_eva = "SELECT * FROM evaluaciones WHERE evaluacion_id = '$carga'";
	$rs_eva = mysqli_query($conexion, $sql_eva);
	$row_eva = mysqli_fetch_array($rs_eva);


	$sql_curso = "SELECT *

				  FROM niveles as n,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca,

			   		   letras as l,

			   		   profesores as p

			   	  WHERE n.nivel_id = ca.nivel_id

			  	  AND l.letra_id = ca.letra_id

			  	  AND a.asignatura_id = ca.asignatura_id

			  	  AND p.profesor_id = ca.profesor_id

			 	  AND ca.curso_asignatura_id = '$curso_id'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);

	$fecha_esp = date("d-m-Y", strtotime($fecha));

	$mensaje = "Estimado se ha generado una nueva evaluación para su aprobación o rechazo:<br><br>

	<b>Profesor: </b>".$row_curso['profesor_nombres']." ".$row_curso['profesor_apellidos']."<br>

	<b>Curso: </b>".$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']."<br>	

	<b>Asignatura: </b>".$row_curso['asignatura_nombre']."<br>

	<b>Evaluación: </b>".$row_eva['evaluacion_nombre']."<br>

	<b>Fecha: </b>".$row_eva['evaluacion_fecha']."<br>

	<b>N° Copias: </b>".$copias."<br>

	<br><br>

	Para revisar esta u otras evaluaciones diríjase al siguiente <a href='https://mauxiliadora.cdgo-chile.cl/admin-sc/'>link</a>.<br>

	Atentamente.<br><br>

	Sistema Colegio María Auxiliadora.

	";



	//	aqui agrego la validacion smtp auth

	


	$email->IsSMTP();
	$email->Host       = 'blue102.dnsmisitio.net';
	//El puerto será el 587 ya que usamos encriptación TLS
	$email->Port       = 465;
	//Definmos la seguridad como TLS
	$email->SMTPSecure = 'ssl';
	//Tenemos que usar gmail autenticados, así que esto a TRUE
	$email->SMTPAuth   = true;
	//Definimos la cuenta que vamos a usar. Dirección completa de la misma
	$email->Username   = "mauxiliadoravina@cdgochile.cl";
	//Introducimos nuestra contraseña de gmail
	$email->Password   = "-.cdgo.05311.-";

	$email->CharSet = 'UTF-8';
	$email->From      = 'mauxiliadoravina@cdgochile.cl';

	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';

	$email->Subject   = 'Creación de nueva evaluación.';

	$email->Body      = $mensaje;

	$email->IsHTML(true);

	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO

	$sql_mail = "SELECT usuario_mail FROM usuarios WHERE usuario_estado = '1' AND perfil_id = '1'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['usuario_mail'] );

	}	

	//$email->Send();

/*

	if(!$email->Send())

	{

	   echo "Error sending: " . $email->ErrorInfo;

	}

	else

	{

	   echo "E-mail sent";

	}*/



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Evaluacion cargada correctamente!')

    window.location.href='evaluaciones.php?id=$curso_id';

    </SCRIPT>"); 

?>