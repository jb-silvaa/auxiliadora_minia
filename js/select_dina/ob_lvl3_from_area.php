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
	$queryM = "	SELECT 
							lvl3.nivel_tres_id,
							lvl3.nivel_tres_nombre

					FROM nivel_tres as lvl3
					
					WHERE  lvl3.area_id = '$id_are'
						AND lvl3.nivel_tres_estado = 1
						$filtro_sucursal
					ORDER BY lvl3.nivel_tres_nombre";
	//echo "<br>hola1: $queryM<br><br>";
	$resultadoM = mysql_query($queryM, $conexion);
	
	if(mysql_num_rows($resultadoM) == 0){
		echo "<option value='-2' disabled='disabled' selected> Sin nivel extra </option>";
	}else{
		/* $html = "$lvl_name";
		$html.= "<select class='mdb-select md-form colorful-select dropdown-danger' name='lvl3' id='lvl3-select'>";
		*/
		$html.= "<option value='-1' disabled='disabled' selected>Seleccione uno(a) $lvl_name</option>";
		
		while($rowM = mysql_fetch_array($resultadoM)){	
			
			$nivel_tres_id 	  = $rowM['nivel_tres_id'];
			
			$nivel_tres_nombre = $rowM['nivel_tres_nombre'];
			
			$html.= "<option value='$nivel_tres_id'>$nivel_tres_nombre</option>";
		}
		
		//$html.="</select>";
		echo $html;
	
	}
	
	
?>