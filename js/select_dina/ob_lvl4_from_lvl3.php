<?php
	
	include ('../../funciones/conexion.php');
	
	$id_lvl3 = $_REQUEST['id_lvl3'];
	$id_emp = $_REQUEST['id_emp'];

	$id_suc = $_REQUEST['id_suc'];

	if($id_suc != ""){
		//Piden tb que filtre x sucursal
		$filtro_sucursal = " AND lvl4.sucursal_id = '$id_suc'";
	}else{
		$filtro_sucursal = "";
	}
	
	$sql_area = "SELECT area_id FROM nivel_3 WHERE nivel_tres_id = '$id_lvl3'";
	$rs_area = mysql_query($sql_area, $conexion);
	$row_area = mysql_fetch_array($rs_area);
	$id_are = $row_area['area_id'];

	$queryM = "	SELECT 
							lvl4.nivel_cuatro_id,
							lvl4.nivel_cuatro_nombre

					FROM nivel_cuatro AS lvl4
					INNER JOIN nivel_tres AS lvl3
						ON lvl4.nivel_tres_id = lvl3.nivel_tres_id
					
					WHERE lvl4.nivel_cuatro_estado = 1
						AND lvl4.nivel_tres_id = '$id_lvl3'
						$filtro_sucursal
					ORDER BY lvl4.nivel_cuatro_nombre";
	//echo "<br>hola1: $queryM<br><br>";
	$resultadoM = mysql_query($queryM, $conexion);
	$html = "";
	if(mysql_num_rows($resultadoM) == 0){
		echo "<option value='-2' disabled='disabled' selected> Sin nivel extra </option>";
	}else{
		/* $html = "$lvl_name";
		$html.= "<select class='mdb-select md-form colorful-select dropdown-danger' name='lvl3' id='lvl3-select'>";
		*/
		$html.= "<option value='-1' disabled='disabled' selected>Seleccione uno(a) $lvl_name</option>";
		
		while($rowM = mysql_fetch_array($resultadoM)){	
			
			$nivel_cuatro_id 	  = $rowM['nivel_cuatro_id'];
			$nivel_cuatro_nombre = $rowM['nivel_cuatro_nombre'];

			$html.= "<option value='$nivel_cuatro_id'>$nivel_cuatro_nombre</option>";
		}
		
		//$html.="</select>";
		echo $html;
	
	}
	
	
?>