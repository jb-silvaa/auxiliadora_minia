<?php 

session_start();

include('../funciones-sc/conexion.php');

$usuario = $_SESSION['id'];



$id = intval($_GET['id']);

if($id == '1')

{

$sql="UPDATE solicitudes as s set s.solicitud_leido_admin = '1' WHERE s.receptor_usuario_id = '".$usuario."' AND s.solicitud_estado_id = '0'";

}

if($id == '2')

{

$sql="UPDATE cargas as c, cursos_asignaturas as ca set c.carga_leido_admin = '1' WHERE c.carga_aprobacion = '0' AND c.curso_asignatura_id = ca.curso_asignatura_id";

}/*

if($id == '3')

{

$sql="UPDATE cargas as c, cursos_asignaturas as ca set c.carga_leido_admin = '0' WHERE ca.profesor_id = '".$usuario."' AND c.carga_aprobacion = '-1' AND c.curso_asignatura_id = ca.curso_asignatura_id";

}*/

if($id == '3')

{

$sql="UPDATE evaluaciones as e, cursos_asignaturas as ca set e.evaluacion_leido_admin = '1' WHERE e.curso_asignatura_id = ca.curso_asignatura_id";

}

$result = mysqli_query($conexion, $sql);

echo $sql;