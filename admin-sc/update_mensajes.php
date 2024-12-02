<?php 

session_start();

include('../funciones-sc/conexion.php');

//$usuariomsg = $_SESSION['profesor_id'];

$data = $_POST['data'];

$tipo_noti = array_shift($data);

$destinatario = array_shift($data);



//var_dump($data);

foreach($data as $d){

	//echo $d;

	$sql="UPDATE notificaciones set notificacion_leido = '1' 

	WHERE notificacion_tipo_id = '$d' 

    AND notificacion_tipo = '$tipo_noti' 

    AND notificacion_destinatario_id = '$destinatario'";



	$result = mysqli_query($conexion, $sql);

	//echo $sql;

}



//$sql="UPDATE notificaciones set notificacion_leido = '1' WHERE

//	notificacion_tipo_id = '".$data[0]."' AND mensaje_estado = '1'";

//echo $sql;							