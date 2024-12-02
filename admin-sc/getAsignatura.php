<?php

header('Content-Type: application/json');



include('../funciones-sc/conexion.php');



$asignatura = $_POST['asignatura'];

$periodo = $_POST['periodo'];

$contador = '1';



//Consulto todos los cursos que contengan la asignatura enviada, asociada al periodo enviado

$sql_cursos = "SELECT DISTINCT *

FROM cursos_asignaturas

INNER JOIN letras on letras.letra_id = cursos_asignaturas.letra_id 

INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id

INNER JOIN dificultades on dificultades.dificultad_id = cursos_asignaturas.dificultad_id

WHERE curso_asignatura_periodo = '$periodo'

AND asignatura_id = '$asignatura'
AND curso_asignatura_estado = '1'

GROUP BY nivel_nombre, letra_nombre, dificultad_nombre

ORDER BY niveles.nivel_id ASC";

$rs_cursos = mysqli_query($conexion, $sql_cursos);   

while($row_cursos = mysqli_fetch_array($rs_cursos))

{

    $id_asig = $row_cursos['curso_asignatura_id'];

    $curso = $row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."-".$row_cursos['dificultad_nombre'];

    $asig_arr[] = array("asig_id" => $id_asig, "curso" => $curso);

}

echo json_encode($asig_arr); //Devuelvo las coincidencias encontradas como objeto JSON

?>



<?php 



/* $asignatura = $_POST['asignatura'];

$periodo = $_POST['periodo'];

$x="SELECT * FROM srv_tbl WHERE ind_id=$ind_id";

$res=query($conexion, $x);



$returnString = '';   

while ($y=mysqli_fetch_array($res)) {



    $returnString .= "<div><label><input type='checkbox' value='.$y['srv_id'].'>.$y['srv_name'].</label></div>";

}



echo $returnString; */

?>