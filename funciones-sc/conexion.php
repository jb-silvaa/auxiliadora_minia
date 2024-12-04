<?php
/* CONECTO A LA BASE DE DATOS */
//$conexion = mysqli_connect("localhost", "cma_clases", "2020.cma.cdgo");
//$conexion = mysqli_connect("cdgo_db", "root", "ClaveCDGO.2017");
//$db = mysqli_select_db("sistema_clases");
// $conexion = mysqli_connect("192.168.185.111", "root", "ClaveCDGO.2017", "sistema_clases");
$conexion = mysqli_connect("localhost", "root", "", "sistema_clases");
ini_set('display_errors',0);
mysqli_set_charset($conexion,"latin1");
mysqli_query($conexion,"SET NAMES latin1"); 
/* FIN CONEXION A LA BD */
?>