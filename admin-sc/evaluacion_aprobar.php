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

include('../funciones-sc/notificacion.php');



$id = $_POST['id'];

$cur_asig = $_POST['cur_asig'];

$decision = $_POST['decision'];

$observacion = $_POST['observacion'];

$encargado = $_POST['encargado'];



if($decision == '1')

{

	$estado = 'aprobado';

	$sql =	"	UPDATE 	evaluaciones

			SET		evaluacion_aprobacion = '$decision',

			        evaluacion_leido = '$decision',

					evaluacion_observacion = '$observacion',

					evaluacion_estado_id = '1',

					evaluacion_bool = '0',

					evaluacion_encargado = '$encargado'

			WHERE	evaluacion_id = '$id'

		";

}

else

{

	$estado = 'rechazado';

	$sql =	"	UPDATE 	evaluaciones

			SET		evaluacion_aprobacion = '$decision',

			        evaluacion_leido = '$decision',

					evaluacion_observacion = '$observacion',

					evaluacion_archivo = '',

					evaluacion_bool = '-1',

					evaluacion_encargado = '$encargado'

			WHERE	evaluacion_id = '$id'

		";

}



$rs	 =	mysqli_query($conexion, $sql);



require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

	require '../PHPMailer-master/PHPMailerAutoload.php';

	$email = new PHPMailer();



	$sql_curso = "SELECT *

				  FROM niveles as n,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca,

			   		   letras as l,

			   		   profesores as p,

			   		   evaluaciones as e

			   	  WHERE n.nivel_id = ca.nivel_id

			  	  AND l.letra_id = ca.letra_id

			  	  AND e.evaluacion_id = '$id'

			  	  AND e.curso_asignatura_id = ca.curso_asignatura_id

			  	  AND a.asignatura_id = ca.asignatura_id

			  	  AND p.profesor_id = ca.profesor_id

			 	  AND ca.curso_asignatura_id = '$cur_asig'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);

	$profe = $row_curso['profesor_id'];

	$mensaje = "Estimado se ha ".$estado." una evaluación enviada por usted:<br><br>

	<b>Profesor: </b>".$row_curso['profesor_nombres']." ".$row_curso['profesor_apellidos']."<br>

	<b>Curso: </b>".$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']."<br>	

	<b>Asignatura: </b>".$row_curso['asignatura_nombre']."<br>

	<b>Evaluación: </b>".$row_curso['evaluacion_nombre']."<br>

	<b>Fecha: </b>".date("d-m-Y", strtotime($row_curso['evaluacion_fecha']))."<br>

	<b>N° Copias: </b>".$row_curso['evaluacion_copia']."<br>

	<b>Observación: </b>".$observacion."<br><br>

	Para revisar esta u otras planificaciones diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/'>link</a>.<br>

	Atentamente.<br><br>

	Sistema Colegio María Auxiliadora.

	";

    $forUser = $profe;

    notify("evaluacion", $forUser, $id, $user_id, '1');



	//	aqui agrego la validacion smtp auth

	



	$email->CharSet = 'UTF-8';

	$email->From      = 'no-reply@colegiomariaauxiliadora.cl';

	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';

	$email->Subject   = 'Se ha '.$estado.' una evaluación.';

	$email->Body      = $mensaje;

	$email->IsHTML(true);

	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO

	$sql_mail = "SELECT profesor_correo FROM profesores WHERE profesor_id = '$profe'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['profesor_correo'] );

	}

	$email->AddAddress( 'cmaplanif@gmail.com' );//CORREO COPIA DE LAS PLANIFICACIONES 

	// $email->Send();

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

window.alert('Evaluacion corregida correctamente!')

window.location.href='evaluacion.php?id=$cur_asig';

</SCRIPT>");

mysqli_close($sql);

?>