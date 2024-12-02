<?php 
session_start();
include('../funciones-sc/conexion.php');
$usuario = $_SESSION['profesor_id'];

$id = intval($_GET['id']);
if($id == '1')
{
$sql="UPDATE solicitudes as s set s.solicitud_leido = '0' WHERE s.receptor_profesor_id = '".$usuario."' AND s.solicitud_estado_id = '0'";
}
if($id == '2')
{
$sql="UPDATE cargas as c, cursos_asignaturas as ca set c.carga_leido = '0' WHERE ca.profesor_id = '".$usuario."' AND c.carga_aprobacion = '1' AND c.curso_asignatura_id = ca.curso_asignatura_id";
}
if($id == '3')
{
$sql="UPDATE cargas as c, cursos_asignaturas as ca set c.carga_leido = '0' WHERE ca.profesor_id = '".$usuario."' AND c.carga_aprobacion = '-1' AND c.curso_asignatura_id = ca.curso_asignatura_id";
}
if($id == '4')
{
$sql="UPDATE evaluaciones as e, cursos_asignaturas as ca set e.evaluacion_leido = '0' WHERE ca.profesor_id = '".$usuario."' AND e.curso_asignatura_id = ca.curso_asignatura_id";
}
$result = mysqli_query($conexion, $sql);
echo $sql;