<?php
	include ('../../funciones/conexion.php');
	
	$empresa_id = $_REQUEST['id_empresa'];

	
	echo $queryM = "SELECT sucursal_id, sucursal_nombre 
				  FROM sucursales 
				  WHERE sucursal_estado = 1
                  AND empresa_id = '$empresa_id'
				  ORDER BY sucursal_nombre";
	$resultadoM = mysql_query($queryM, $conexion);
	
	
	$x=0;
	while($rowM = mysql_fetch_array($resultadoM))
	{
		$sucu_id = $rowM['sucursal_id'];
      $sucu_nombre = $rowM['sucursal_nombre'];
      if($x==0)
         $html.= "<option value='$sucu_id' selected>$sucu_nombre</option>";
      else
         $html.= "<option value='$sucu_id'>$sucu_nombre</option>";
      $x++;
	}
	
	echo $html;
?>

