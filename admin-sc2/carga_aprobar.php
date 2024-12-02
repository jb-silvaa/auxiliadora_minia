<?php
include('../funciones-sc/conexion.php');
include('../funciones-sc/notificacion.php');
session_start();
$user_id = $_SESSION['id'];
$id = $_POST['id'];
$cur_asig = $_POST['cur_asig'];
$decision = $_POST['decision'];
$observacion = $_POST['observacion'];
$encargado = $_POST['encargado'];

if($decision == '1'){
	$estado = 'aprobado';
	$sql =	"	UPDATE 	cargas
			SET		carga_aprobacion = '$decision',
			        carga_leido = '$decision',
					carga_observacion = '$observacion',
					carga_bool= '0',
					carga_encargado= '$encargado'
			WHERE	carga_id = '$id'
		";
}else{
	$estado = 'rechazado';
	$sql =	"	UPDATE 	cargas
			SET		carga_aprobacion = '-1',
			        carga_leido = '$decision',
					carga_observacion = '$observacion',
					carga_archivo = '',
					carga_bool='-1',
					carga_encargado='$encargado'
			WHERE	carga_id = '$id'
		";
}


$rs	 =	mysql_query($sql,$conexion);

$sql_data = "SELECT * FROM cargas WHERE carga_id = '$id'";
$rs_data = mysql_query($sql_data, $conexion);
$row_data = mysql_fetch_array($rs_data);


require_once('../PHPMailer-master/class.phpmailer.php');

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
			 	  AND ca.curso_asignatura_id = '$cur_asig'";
	$rs_curso = mysql_query($sql_curso, $conexion);
	$row_curso = mysql_fetch_array($rs_curso);
	$profe = $row_curso['profesor_id'];
	$mensaje = "Estimado se ha ".$estado." una planificación enviada por usted:<br><br>
	<b>Profesor: </b>".$row_curso['profesor_nombres']." ".$row_curso['profesor_apellidos']."<br>
	<b>Curso: </b>".$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']."<br>	
	<b>Asignatura: </b>".$row_curso['asignatura_nombre']."<br>
	<b>Observación: </b>".$observacion."<br>
	<br>
	Para revisar esta u otras planificaciones diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/'>link</a>.<br>
	Si usted es Jefe de Departamento puede descargar el archivo desde este <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/profesor-sc/".$row_data['carga_archivo']."'>link</a>.<br>
	Atentamente.<br><br>
	Sistema Colegio María Auxiliadora.
	";
	//NOTIFICACION
	$forUser = $profe;
    
    //Creamos la notificación dentro de la BD
	notify("planificacion", $forUser, $id, $user_id, '1');
	
	//	aqui agrego la validacion smtp auth
	

	$email->CharSet = 'UTF-8';
	$email->From      = 'no-reply@colegiomariaauxiliadora.cl';
	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';
	$email->Subject   = 'Se ha '.$estado.' una planificación.';
	$email->Body      = $mensaje;
	$email->IsHTML(true);
	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO
	$sql_mail = "SELECT profesor_correo FROM profesores WHERE profesor_id = '$profe'";
	$rs_mail = mysql_query($sql_mail, $conexion);
	while($row_mail = mysql_fetch_array($rs_mail))
	{
		$email->AddAddress( $row_mail['profesor_correo'] );
	}
	$sql_mail = "SELECT jefe_correo FROM jefes_area as ja, jefes_area_profe as jap WHERE jefe_estado = '1' AND ja.jefe_id = jap.jefe_id AND jap.profesor_id = '$profe'";
	$rs_mail = mysql_query($sql_mail, $conexion);
	while($row_mail = mysql_fetch_array($rs_mail))
	{
		$email->AddAddress( $row_mail['jefe_correo'] );
	}
	$email->AddAddress( '' );//CORREO COPIA DE LAS PLANIFICACIONES 
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
window.alert('Carga corregida correctamente!')
window.location.href='carga.php?id=$cur_asig';
</SCRIPT>");
mysql_close($sql);
?>