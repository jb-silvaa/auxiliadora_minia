<?php

header('Content-Type: application/json');



include('../funciones-sc/conexion.php');



$nivel_tipo = $_POST['nivel_tipo'];

$periodo = $_POST['periodo'];

$contador = '1';



//Consulto todos los cursos que contengan la asignatura enviada, asociada al periodo enviado

$sql_cursos = "SELECT DISTINCT asignatura_nombre, asignaturas.asignatura_id

FROM cursos_asignaturas

INNER JOIN asignaturas on asignaturas.asignatura_id = cursos_asignaturas.asignatura_id 

INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id

INNER JOIN dificultades on dificultades.dificultad_id = cursos_asignaturas.dificultad_id

WHERE curso_asignatura_periodo = '$periodo'

AND nivel_tipo = '$nivel_tipo'

GROUP BY asignaturas.asignatura_id

ORDER BY asignaturas.asignatura_nombre ASC";

$rs_cursos = mysqli_query($conexion, $sql_cursos);   

while($row_cursos = mysqli_fetch_array($rs_cursos))

{

    $id_asig = $row_cursos['asignatura_id'];

    $nombre_asig = $row_cursos['asignatura_nombre'];

    $asig_arr[] = array("asig_id" => $id_asig, "nombre_asig" => $nombre_asig);

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