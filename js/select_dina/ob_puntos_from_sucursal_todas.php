<?php
	
	include ('../../funciones/conexion.php');
	
	$id_area = $_REQUEST['id_area'];
	if($id_area != "")
		$filtro_area = "AND area_id = '$id_area'";
	$id_sucu = $_REQUEST['id_sucu'];
	
	$queryM = "	SELECT punto_id, 
							punto_nombre 
					FROM puntos 
					WHERE sucursal_id = '$id_sucu'  $filtro_area AND punto_estado = 1 ORDER BY punto_nombre";
	$resultadoM = mysql_query($queryM, $conexion);
	
	
	$html = "<option value='-1'>Todos</option>";
	
	while($rowM = mysql_fetch_array($resultadoM))
	{
		$punto_id 	  = $rowM['punto_id'];
		$punto_nombre = $rowM['punto_nombre'];
		$html.= "<option value='$punto_id'>$punto_nombre</option>";
	}
	
	echo $html;
?>