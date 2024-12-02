<?php 
session_start();
include('../funciones-sc/conexion.php');
//$usuariomsg = $_SESSION['profesor_id'];
$data = $_POST['data'];

$id = $_POST['id'];
$tipo = $_POST['tipo'];

//var_dump($data);
if($data){
    $destinatario = array_shift($data);
    $autor = $_POST['autor'];
    foreach($data as $d){
        //echo $d;
        $sql="UPDATE notificaciones set notificacion_leido = '1' 
        WHERE notificacion_tipo_id = '$d' 
        AND notificacion_tipo = 'mensaje' 
        AND notificacion_destinatario_id = '$destinatario'
        AND notificacion_autor_tipo = '$autor'";
    
        $result = mysqli_query($conexion, sql);
        //echo $sql;
    }
}else if($id != '' && $tipo != ''){
    $sql="UPDATE notificaciones set notificacion_leido = '1' 
	WHERE notificacion_tipo = '$tipo' 
    AND notificacion_destinatario_id = '$id'";
    echo $sql;
	$result = mysqli_query($conexion, sql);
}

//$sql="UPDATE notificaciones set notificacion_leido = '1' WHERE
//	notificacion_tipo_id = '".$data[0]."' AND mensaje_estado = '1'";
//echo $sql;							