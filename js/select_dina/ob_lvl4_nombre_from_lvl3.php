<?php
	
	include ('../../funciones/conexion.php');
	
	$id_emp = $_REQUEST['id_emp'];
	 
	$id_suc = $_REQUEST['id_suc'];
	
	if($id_suc != ""){
		//Piden tb que filtre x sucursal
		$filtro_sucursal = " AND lvl4.sucursal_id = '$id_suc'";
	}else{
		$filtro_sucursal = "";
	}
	
	$id_lvl3 = $_REQUEST['id_lvl3'];
	$sql_area = "SELECT area_id FROM nivel_3 WHERE nivel_tres_id = '$id_lvl3'";
	$rs_area = mysql_query($sql_area, $conexion);
	$row_area = mysql_fetch_array($rs_area);
	$id_are = $row_area['area_id'];

	
	$sql_name_lvl = "	SELECT 
						lvl.nivel_nombre
					FROM niveles AS lvl
					INNER JOIN nivel_cuatro as lvl4
						ON lvl.nivel_id = lvl4.nivel_id
					WHERE lvl4.nivel_cuatro_estado = 1
						AND lvl4.nivel_tres_id = '$id_lvl3'
						$filtro_sucursal
						
					LIMIT 1";
					//echo "<br>hola1: $sql_name_lvl<br><br>";
	$rs_name_lvl = mysql_query($sql_name_lvl, $conexion);
	if(mysql_num_rows($rs_name_lvl) == 0){
		//NO hay lvl4
		echo "Area sin nivel 4 extra";
	}else{
		$row_name_lvl = mysql_fetch_array($rs_name_lvl);
		$lvl_name = $row_name_lvl['nivel_nombre'];
		echo $lvl_name;
	}
	
	
	
?>