<?php
	
	include ('../../funciones/conexion.php');
	
	$id_sucu = $_REQUEST['id_sucu'];
	
	$queryM = "	SELECT are.area_id, 
							 are.area_nombre

					FROM areas AS are
               INNER JOIN configuracion_empresas AS confg
						ON are.area_id = confg.area_id
               INNER JOIN sucursales AS sucu
                  ON confg.empresa_id = sucu.empresa_id 
					WHERE sucu.sucursal_id = '$id_sucu' 
						AND are.area_estado = 1
                  AND confg.config_empresa_estado = 1
					ORDER BY are.area_nombre";
	
	$resultadoM = mysql_query($queryM, $conexion);
	
	$html = "<option value='-1' disabled selected>Seleccione Area</option>";
	
	
	while($rowM = mysql_fetch_array($resultadoM))
	{
		$area_id 	  = $rowM['area_id'];
		$area_nombre = $rowM['area_nombre'];
		$html.= "<option value='$area_id'>$area_nombre</option>";
	}
	
	echo $html;
?>