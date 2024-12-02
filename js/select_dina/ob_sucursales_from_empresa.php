<?php
	include ('../../funciones/conexion.php');
	
	$id_empresa = $_REQUEST['id_empresa'];

	if($id_empresa != -1){
		$filtro = " AND empresa_id = '$id_empresa' ";
	}
	
	$queryM = "SELECT sucursal_id, sucursal_nombre 
				  FROM sucursales 
				  WHERE sucursal_estado = 1
				  	$filtro
				  ORDER BY sucursal_nombre";
	$resultadoM = mysql_query($queryM, $conexion);
	
	$html = "<option value='-1' selected disabled='disabled'>Seleccione una sucursal</option>";
	
	while($rowM = mysql_fetch_array($resultadoM))
	{
		$sucu_id = $rowM['sucursal_id'];
		$sucu_nombre = $rowM['sucursal_nombre'];
		$html.= "<option value='$sucu_id'>$sucu_nombre</option>";
	}
	
	echo $html;
?>