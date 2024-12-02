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
/*
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
}*/
$estado = $_POST['estado'];
$obs = $_POST['obs'];
$fecha = date('Y-m-d');
$usuario = $_SESSION['id'];
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
			'$obs',
			'$id',
			'$usuario',
			'0',
			'$fecha'
		)";
$rs = mysql_query($sql, $conexion);

$sql_receptor = "UPDATE solicitudes
			   	 SET solicitud_estado_id = '$estado', solicitud_archivo=''
			   	 WHERE solicitud_id = $id";
$rs_receptor = mysql_query($sql_receptor, $conexion);

/*
$sql_receptor = "UPDATE solicitudes
			   	 SET receptor_usuario_id = '$new_usuario',
			   	 	 receptor_profesor_id = '$new_profesor'
			   	 WHERE solicitud_id = $id";
$rs_receptor = mysql_query($sql_receptor, $conexion);

$sql_last = "SELECT MAX(solicitud_historial_id) as solicitud_historial_id FROM solicitudes_historial WHERE usuario_id = '$usuario' ";
$rs_last = mysql_query($sql_last, $conexion);
$row_last = mysql_fetch_array($rs_last);

$filename = $_FILES['files']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);

$id = $row_last['solicitud_historial_id'];
$hora = date("H_i_s");
$nombre_imagen = "documentos/".$id."_".$hora.".".$ext;
if (isset($_FILES['files'])){	
	//Comprobamos si el fichero es una imagen
	if ($_FILES['files']['type']=='application/msword' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
	$destino = '../profesor-sc/documentos/'.$id.'_'.$hora.".".$ext;
	//Subimos el fichero al servidor
	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);
	$_FILES["files"]["tmp_name"];
	$sql_imagen = "UPDATE solicitudes_historial
			   SET solicitud_historial_archivo = '$nombre_imagen'
			   WHERE solicitud_historial_id = $id";
	$rs_imagen = mysql_query($sql_imagen, $conexion);
	}
}*/

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Solicitud reenviada correctamente!')
    window.location.href='solicitudes_pendientes.php';
    </SCRIPT>");
	mysql_close($sql);
?>