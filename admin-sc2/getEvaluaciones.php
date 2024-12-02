<?php 
include('../funciones-sc/conexion.php');
header('Content-Type: application/json');
$nivel = $_GET['n'];
$letra = $_GET['l'];

if($nivel != '' && $nivel != '0'){
    $nivel_filtro = "AND ca.nivel_id = '$nivel'";
}else{
    $nivel_filtro = "";
}
if($letra != '' && $letra != '0'){
    $letra_filtro = "AND ca.letra_id = '$letra'";
}else{
    $letra_filtro = "";
}
$sql = "SELECT * FROM evaluaciones as e
        INNER JOIN cursos_asignaturas as ca on ca.curso_asignatura_id = e.curso_asignatura_id
        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
        INNER JOIN letras as l on l.letra_id = ca.letra_id
        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
        INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo
        WHERE evaluacion_estado = '1'
        AND pe.periodo_activo = '1'
        AND e.evaluacion_aprobacion <> '-1'
        $nivel_filtro
        $letra_filtro";
$rs = mysql_query($sql, $conexion);


while($row = mysql_fetch_array($rs))
{
    $ca_id = $row['curso_asignatura_id'];
    $id = $row['evaluacion_id'];
    $curso = $row['nivel_nombre']."-".$row['letra_nombre'];
 $data[] = array(
  'id'   => $row["evaluacion_id"],
  'ca_id' => $ca_id,
  'title'   => $row["asignatura_nombre"],
  'description' => $row["evaluacion_nombre"],
  'start'   => $row["evaluacion_fecha"],
  'direccion' => "evaluacion.php?id=$ca_id&id2=$id",
  'curso' => $curso,
  'dificultad' => $row['dificultad_nombre']
 );
}

echo json_encode($data);









?>