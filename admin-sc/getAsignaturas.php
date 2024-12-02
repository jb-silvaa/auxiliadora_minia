<?php

header('Content-Type: application/json');



include('../funciones-sc/conexion.php');



$sql_cursos = "SELECT * 

                FROM asignaturas";

$rs_cursos = mysqli_query($conexion, $sql_cursos);   

while($row_cursos = mysqli_fetch_array($rs_cursos))

{

    $id_asig = $row_cursos['asignatura_id'];

    $asig = $row_cursos['asignatura_nombre'];

    $asig_arr[] = array("asig_id" => $id_asig, "asig" => $asig);

}

echo json_encode($asig_arr); //Devuelvo las coincidencias encontradas como objeto JSON



?>