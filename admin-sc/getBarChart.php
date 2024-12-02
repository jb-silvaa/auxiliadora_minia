<?php

header('Content-Type: application/json');



include('../funciones-sc/conexion.php');



$asignatura = $_GET['a'];

$periodo = $_GET['p'];

$nivel = $_GET['n'];



//Obtengo todos los cursos correspondientes a la asignatura, periodo y nivel enviados

$sqlQuery = "SELECT nivel_nombre, letra_nombre, dificultad_nombre, nivel_id, letra_id, dificultad_id

FROM niveles, letras, dificultades

WHERE nivel_id IN 

     (SELECT nivel_id

      FROM cursos_asignaturas 

      WHERE nivel_id = '$nivel' 

      AND curso_asignatura_periodo = '$periodo' 

      AND asignatura_id = '$asignatura') 

AND letra_id IN

       (SELECT letra_id

      FROM cursos_asignaturas 

      WHERE nivel_id = '$nivel' 

      AND curso_asignatura_periodo = '$periodo' 

      AND asignatura_id = '$asignatura') 

AND dificultad_id IN

       (SELECT dificultad_id

      FROM cursos_asignaturas 

      WHERE nivel_id = '$nivel' 

      AND curso_asignatura_periodo = '$periodo' 

      AND asignatura_id = '$asignatura') ";



$result = mysqli_query($conexion, $sqlQuery);



while($row_asig = mysqli_fetch_array($result)){

    $nivel = $row_asig['nivel_nombre'];

    $letra = $row_asig['letra_nombre'];

    $dificultad = $row_asig['dificultad_nombre'];

    $todo = $nivel."-".$letra." ".$dificultad;



    $nid = $row_asig['nivel_id'];

    $lid = $row_asig['letra_id'];

    $did = $row_asig['dificultad_id'];



    $sql = "SELECT COUNT(carga_archivo)

            FROM cargas as c 

            INNER JOIN cursos_asignaturas as ca on c.curso_asignatura_id = ca.curso_asignatura_id 

            WHERE ca.nivel_id = '$nid' 

            AND ca.curso_asignatura_periodo = '$periodo' 

            AND ca.asignatura_id = '$asignatura' 

            AND c.tipo_carga_id = '1'

            AND ca.letra_id = '$lid'

            AND ca.dificultad_id = '$did'

            AND c.carga_archivo != '' ";



    $result1 = mysqli_query($conexion, $sql);

    $row_asig1 = mysqli_fetch_array($result1);

    $carga = $row_asig1[0];



    $sql1 = "SELECT curso_asignatura_unidades

            FROM cursos_asignaturas

            WHERE asignatura_id = '$asignatura'

            AND nivel_id = '$nid'

            AND letra_id = '$lid'

            AND dificultad_id = '$did'

            AND curso_asignatura_periodo = '$periodo' ";



    $result2 = mysqli_query($conexion, $sql1);

    $row_asig2 = mysqli_fetch_array($result2);

    $cargarest = $row_asig2[0]-$row_asig1[0];



    $users_arr[] = array("todo" => $todo,"carga" => $carga,"cargarest" => $cargarest);

}



// encoding array to json format

echo json_encode($users_arr);



?>