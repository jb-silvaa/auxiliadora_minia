<?php
include('../funciones-sc/conexion.php');

$nivel_creado_id = $_GET['id'];

$sql = "SELECT * FROM niveles_creados WHERE nivel_creado_id = '$nivel_creado_id'";
$rs = mysql_query($sql,$conexion);
$row = mysql_fetch_array($rs);

$nivel_id = $row['nivel_id'];
$periodo = $row['nivel_creado_periodo'];

$sql_delete = "DELETE FROM cursos_asignaturas 
                WHERE nivel_id = '$nivel_id' 
                AND curso_asignatura_periodo = '$periodo'";
$rs_delete = mysql_query($sql_delete,$conexion);

$sql_delete1 = "DELETE FROM niveles_creados 
                WHERE nivel_creado_id = '$nivel_creado_id'";
$rs_delete1 = mysql_query($sql_delete1,$conexion);



echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Nivel eliminado correctamente!')
    window.location.href='niveles_generados.php';
    </SCRIPT>");
	mysql_close($sql);
?>