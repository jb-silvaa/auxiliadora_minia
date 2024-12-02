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
$curso_id 		= $_POST['id_curso'];
$evaluacion 	= $_POST['evaluacion'];
$fecha 			= $_POST['inicio'];
$fechacreacion 	= $_POST['fechacreacion'];
$nombre 		= $_POST['nameprueba'];
//print_r($nombre);
if(!empty($_POST['nameprueba'])){
    // Loop to store and display values of individual checked checkbox.
    $i=0;
    foreach($_POST['nameprueba'] as $j){
        
    $sql = "INSERT INTO evaluaciones 
		(   
			curso_asignatura_id,
			evaluacion_nombre,
			evaluacion_fecha,
			tipo_evaluacion_id,
			evaluacion_aprobacion,
			evaluacion_estado,
			evaluacion_fecha_creacion,
			evaluacion_encargado,
			evaluacion_estado_id
		)
		values
		(
			'$curso_id',
			'$nombre[$i]',
			'$fecha[$i]',
			'$evaluacion[$i]',
			'0',
			'1',
			'$fechacreacion',
			'',
			'1'
		)";
     $rs = mysqli_query($conexion, $sql);
$i++;
    }
}

/*require_once('../PHPMailer-master/class.phpmailer.php');

//CON ADJUNTO
	require '../PHPMailer-master/PHPMailerAutoload.php';
	$email = new PHPMailer();

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
	<b>Evaluación: </b>".$nombre."<br>
	<b>Fecha: </b>".$fecha_esp."<br>
	<b>N° Copias: </b>".$copias."<br>
	<br><br>
	Para revisar esta u otras evaluaciones diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/admin-sc/'>link</a>.<br>
	Atentamente.<br><br>
	Sistema Colegio María Auxiliadora.
	";

	//	aqui agrego la validacion smtp auth
	

	$email->CharSet = 'UTF-8';
	$email->From      = 'no-reply@colegiomariaauxiliadora.cl';
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

	if(!$email->Send())
	{
	   echo "Error sending: " . $email->ErrorInfo;
	}
	else
	{
	   echo "E-mail sent";
	}*/

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Evaluacion(es) cargada(s) correctamente!')
    window.location.href='listado_cursos.php';
    </SCRIPT>"); 
?>