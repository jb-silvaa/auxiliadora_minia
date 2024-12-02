<?php

include('../funciones-sc/conexion.php');



$nivel_creado_id = $_GET['id'];



$sql = "SELECT * FROM niveles_creados WHERE nivel_creado_id = '$nivel_creado_id'";

$rs = mysqli_query($conexion, $sql);

$row = mysqli_fetch_array($rs);



$nivel_id = $row['nivel_id'];

$periodo = $row['nivel_creado_periodo'];



$sql_delete = "DELETE FROM cursos_asignaturas 

                WHERE nivel_id = '$nivel_id' 

                AND curso_asignatura_periodo = '$periodo'";

$rs_delete = mysqli_query($conexion, $sql_delete);



$sql_delete1 = "DELETE FROM niveles_creados 

                WHERE nivel_creado_id = '$nivel_creado_id'";

$rs_delete1 = mysqli_query($conexion, $sql_delete1);







echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Nivel eliminado correctamente!')

    window.location.href='niveles_generados.php';

    </SCRIPT>");

	mysqli_close($sql);

?>