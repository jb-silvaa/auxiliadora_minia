<?php
	
	include ('../../funciones/conexion.php');
	
	
	
	$id_emp = $_REQUEST['id_emp'];
	//Escojo las areas que le pertenecen a todos y las especificas de esta empresa
	$queryM = "	SELECT tipo_punto_id,tipo_punto_nombre 
					FROM tipos_puntos 
					WHERE tipo_punto_estado = '1'
						AND empresa_id = '0'
						OR empresa_id = '$id_emp'
					ORDER BY tipo_punto_nombre";
	
	$resultadoM = mysql_query($queryM, $conexion);
	
	$html = "<option value='-1' disabled selected>Seleccione TIPO</option>";
	
	
	while($rowM = mysql_fetch_array($resultadoM)){
		$tipo_pto_id     = $rowM['tipo_punto_id'];
		$tipo_pto_nombre = $rowM['tipo_punto_nombre'];
		
		$html.= "<option value='$tipo_pto_id'>$tipo_pto_nombre</option>";
	}
	
	echo $html;
?>