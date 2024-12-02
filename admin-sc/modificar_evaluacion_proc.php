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

$sql = "SELECT *
        FROM evaluaciones
        WHERE evaluacion_estado = '1'
        AND curso_asignatura_id = '$curso_id'
        ORDER BY evaluacion_fecha ASC";

$rs = mysqli_query($conexion, $sql);

while($row = mysqli_fetch_array($rs))
{ 
    $ev_id = $row['evaluacion_id'];
    $evaluacion 	= $_POST['evaluacion'.$ev_id];
	$fecha 			= $_POST['inicio'.$ev_id];
	$nombre 		= $_POST['nameprueba'.$ev_id];    
    $sql_ev = "UPDATE evaluaciones 
			SET   
			evaluacion_nombre = '$nombre',
			evaluacion_fecha = '$fecha',
			tipo_evaluacion_id = '$evaluacion'			
		WHERE evaluacion_id = $ev_id";
     $rs_ev = mysqli_query($conexion, $sql_ev);    
}

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Evaluacion(es) cargada(s) correctamente!')
    window.location.href='listado_cursos.php';
    </SCRIPT>"); 
?>