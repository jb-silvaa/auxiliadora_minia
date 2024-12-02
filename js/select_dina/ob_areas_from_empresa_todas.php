<?php
	
	include ('../../funciones/conexion.php');
	
	$id_emp = $_REQUEST['id_emp'];
	
	$queryM = "	SELECT are.area_id, 
							 are.area_nombre

					FROM areas AS are
               INNER JOIN configuracion_empresas AS confg
						ON are.area_id = confg.area_id
               WHERE confg.empresa_id = '$id_emp' 
						AND are.area_estado = 1
                  AND confg.config_empresa_estado = 1
					ORDER BY are.area_nombre";
	/* Este echo es de prueba. pa q funcione debe tar comentado 
		echo "<br>hola $queryM<br>"; */
	$resultadoM = mysql_query($queryM, $conexion);
	
	$html = "<option value='-1'>Todas</option>";
	
	
	while($rowM = mysql_fetch_array($resultadoM))
	{
		$area_id 	  = $rowM['area_id'];
		$area_nombre = $rowM['area_nombre'];
		$html.= "<option value='$area_id'>$area_nombre</option>";
	}
	
	echo $html;
?>