
<!-- ESTE EJEMPLO TOMA LAS DIFICULTADES Y LAS LISTA CON UN COMBOBOX -->
<!--ESTO IRIA EN EL FORMULARIO PARA CREAR (OBVIO QUE CON ASIGNATURAS)-->
<h1>Niveles de Dificultad</h1>            
<?php
  $sql_dificultades = "SELECT * FROM dificultades WHERE dificultad_estado = '1'";
  $rs_dificultades = mysql_query($sql_dificultades, $conexion);
  while($row_dificultades = mysql_fetch_array($rs_dificultades))
  {            
  ?>
      <label><?=$row_dificultades['dificultad_nombre']?></label>
      <input type="checkbox" name="dificultad[]" value="<?=$row_dificultades['dificultad_id']?>">
  <?php
  }
  ?>
<!-- FIN EJEMPLO COMBOBOX -->

<?php
//EJEMPLO PARA CREAR LAS ASIGNATURAS QUE RECIBA DESDE EL FORMULARIO
$dificultad = $_POST["dificultad"];
 
for ($i=0;$i<count($dificultad);$i++)    
{     
	$id_dif = $dificultad[$i];
 	$sql = "INSERT INTO asignaturas_dificultades
				(	
					asignatura_id,
					dificultad_id,
					asignatura_dificultad_estado
				)
				VALUES 
				(
					'$id',
					'$id_dif',
					'1'
				)
		";
	$rs	 =	mysql_query($sql,$conexion);
}

/////////////////////////////


//ESTE SERIA EL EJEMPLO PARA MODIFICAR LA SELECCION DE ASIGNATURAS, DEJA EN -1 TODAS Y LAS VUELVE A CREAR LAS NUEVAS
$id = $_POST["id"]; //RECIBO EL ID DE LA ASIGNATURA
$dificultad = $_POST["dificultad"];

//UPDATE TODOS LOS PLANES A -1 PARA INSERTARLOS TODOS DE NUEVO CON ESTADO 1
$borrar = "UPDATE asignaturas_dificultades SET asignatura_dificultad_estado = '-1' WHERE asignatura_id = '$id'";
$rs_borrar = mysql_query($borrar, $conexion); 
for ($i=0;$i<count($dificultad);$i++)    
{     
	$id_dif = $dificultad[$i];
 	$sql = "INSERT INTO asignaturas_dificultades
				(	
					asignatura_id,
					dificultad_id,
					asignatura_dificultad_estado
				)
				VALUES 
				(
					'$id',
					'$id_dif',
					'1'
				)
		";
	$rs	 =	mysql_query($sql,$conexion);
}
/////////////////////////////
?>
