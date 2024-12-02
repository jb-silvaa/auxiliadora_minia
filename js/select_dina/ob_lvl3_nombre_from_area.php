<?php
	
	include ('../../funciones/conexion.php');
	
	$id_emp = $_REQUEST['id_emp'];
	$id_are = $_REQUEST['id_are'];
	$id_suc = $_REQUEST['id_suc'];
	
	if($id_suc != ""){
		//ME piden que sea x area y sucursal
		$filtro_sucursal = " AND lvl3.sucursal_id = '$id_suc'";
	}else{
		$filtro_sucursal = "";
	}

	$sql_name_lvl = "	SELECT 
							
							lvl.nivel_nombre

					FROM niveles AS lvl
					INNER JOIN nivel_tres as lvl3
						ON lvl.nivel_id = lvl3.nivel_id
					
						
					WHERE  lvl3.area_id = '$id_are'
						AND lvl3.nivel_tres_estado = 1
						$filtro_sucursal
					LIMIT 1";
					//echo "<br>hola1: $sql_name_lvl<br><br>";
	$rs_name_lvl = mysql_query($sql_name_lvl, $conexion);
	if(mysql_num_rows($rs_name_lvl) == 0){
		//NO hay lvl3
		echo "Area sin nivel extra";
	}else{
		$row_name_lvl = mysql_fetch_array($rs_name_lvl);
		$lvl_name = $row_name_lvl['nivel_nombre'];
		echo $lvl_name;
	}
	
	
	
?>