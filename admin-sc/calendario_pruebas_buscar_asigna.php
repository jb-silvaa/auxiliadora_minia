<?php
session_start();
include('../funciones-sc/conexion.php');

  $nivel_id                 = trim($_POST['eva_nivel']);
  $letra_id                 = trim($_POST['eva_letra']);
  $curso_asignatura_periodo = trim($_POST['eva_periodo']);
 

  $sql_tabla = "SELECT
                  ca.curso_asignatura_id,
                  asig.asignatura_id,
                  asig.asignatura_nombre
                FROM cursos_asignaturas as ca
                  INNER JOIN asignaturas as asig ON asig.asignatura_id = ca.asignatura_id
                WHERE ca.curso_asignatura_estado = '1'
                  AND ca.curso_asignatura_periodo = '$curso_asignatura_periodo'
                  AND ca.nivel_id = '$nivel_id'
                  AND ca.letra_id = '$letra_id'
                ORDER BY asig.asignatura_nombre
                  ";
  $rs_tabla = mysqli_query($conexion, $sql_tabla);
  $html = "";
  $html.= "<option value='' disabled selected>Seleccione Asignatura</option>";
  while($row_tabla = mysqli_fetch_array($rs_tabla)){
    $curso_asignatura_id  = $row_tabla['curso_asignatura_id'];
    $asignatura_id        = $row_tabla['asignatura_id'];
    $asignatura_nombre    = $row_tabla['asignatura_nombre'];


    
    $html.="<option value='$curso_asignatura_id'>$asignatura_nombre</option>"; 
  }

  echo $html;

?>
