<?php
session_start();
$user_id = $_SESSION['id'];
if (!$_SESSION['id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
    die();
}
if ($_SESSION['perfil'] != 1){
	echo "<script LANGUAGE='JavaScript'>
				  window.alert('Acceso no autorizado');
				  window.location= 'listado_cursos.php'
	  </script>";
	  die();
  }
include('../funciones-sc/conexion.php');

$periodo = $_POST['periodo'];
 $sql_existe = "SELECT periodo_periodo FROM periodos where periodo_periodo = '$periodo'";
$rs_existe = mysql_query($sql_existe,$conexion);
 $row_existe = mysql_fetch_array($rs_existe);

if($periodo == $row_existe['periodo_periodo']){
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo ya existe, ingrese otro año')
    window.location.href='generar_periodo.php';
    </SCRIPT>");
}else{


 $sql_anio = "INSERT INTO periodos 
		              ( periodo_periodo,
	                  	periodo_activo
			
		                 )
		                  values
	                    	(
			              '$periodo'
			                ,'0'
			
		                     )";
  $rs_anio = mysql_query($sql_anio, $conexion);
//CONSULTO TODOS LOS NIVELES CON SUS LETRAS
$sql_nl = "SELECT * FROM niveles_letras WHERE nivel_letra_estado = '1'";
$rs_nl = mysql_query($sql_nl, $conexion);
while ($row_nl = mysql_fetch_array($rs_nl)) {
	$nivel = $row_nl['nivel_id'];
	$letra = $row_nl['letra_id'];
	$sql_na = "SELECT * FROM niveles_asignaturas WHERE nivel_id = '$nivel' AND nivel_asignatura_estado = '1'";
	$rs_na = mysql_query($sql_na, $conexion);
	while ($row_na = mysql_fetch_array($rs_na)) {
		if($row_na['asignatura_dificultad'] == '1')
		{
			$asignatura = $row_na['asignatura_id'];
			$sql_dif = "SELECT * FROM dificultades WHERE dificultad_estado = '1' AND dificultad_id <> '0'";
			$rs_dif = mysql_query($sql_dif, $conexion);
			while ($row_dif = mysql_fetch_array($rs_dif)) {
				$dificultad = $row_dif['dificultad_id'];
				$sql_curso = "INSERT INTO cursos_asignaturas 
					  (
					  	nivel_id,
					  	letra_id,
					  	asignatura_id,
					  	curso_asignatura_periodo,
					  	profesor_id,
					  	dificultad_id,
					  	curso_asignatura_unidades,
					  	curso_asignatura_estado
					  )
					  VALUES
					  (
					  	'$nivel',
					  	'$letra',
					  	'$asignatura',
					  	'$periodo',
					  	'0',
					  	'$dificultad',
					  	'5',
					  	'1'
					  )
					  ";	
				$rs_curso = mysql_query($sql_curso, $conexion);
				//insertamos el nuevo periodo a tabla periodos para mostrarlo en el listado de años

             

				$sql_last = "SELECT curso_asignatura_id 
							 FROM cursos_asignaturas 
							 WHERE nivel_id = '$nivel'
							 AND 	letra_id = '$letra'
							 AND 	asignatura_id = '$asignatura'
							 AND 	curso_asignatura_periodo = '$periodo'
							 AND 	profesor_id = '0'
							 AND 	dificultad_id = '$dificultad'
							 AND 	curso_asignatura_estado = '1'
							";
				$rs_last = mysql_query($sql_last, $conexion);
				$row_last = mysql_fetch_array($rs_last);

				$cur_asig = $row_last['curso_asignatura_id'];
				//CREO LAS CARGAS ANUALES PARA CADA CURSO
				$sql_carga = "INSERT INTO cargas 
				(
					curso_asignatura_id,
					tipo_carga_id,
					tipo_carga_unidad,
					carga_aprobacion,
					carga_estado
				)
				VALUES 
				(
					$cur_asig,
					'2',
					'0',
					'0',
					'1'
				)
				";
				$rs_carga = mysql_query($sql_carga, $conexion);
			}
		}
		else
		{
			$asignatura = $row_na['asignatura_id'];
			$sql_curso = "INSERT INTO cursos_asignaturas 
					  (
					  	nivel_id,
					  	letra_id,
					  	asignatura_id,
					  	curso_asignatura_periodo,
					  	curso_asignatura_unidades,
					  	profesor_id,
					  	dificultad_id,
					  	curso_asignatura_estado
					  )
					  VALUES
					  (
					  	'$nivel',
					  	'$letra',
					  	'$asignatura',
					  	'$periodo',
					  	'5',
					  	'0',
					  	'0',
					  	'1'
					  )
					  ";
			$rs_curso = mysql_query($sql_curso, $conexion);

			$sql_last = "SELECT curso_asignatura_id 
							 FROM cursos_asignaturas 
							 WHERE nivel_id = '$nivel'
							 AND  	letra_id = '$letra'
							 AND 	asignatura_id = '$asignatura'
							 AND 	curso_asignatura_periodo = '$periodo'
							 AND 	profesor_id = '0'
							 AND 	dificultad_id = '0'
							 AND 	curso_asignatura_estado = '1'
							";
				$rs_last = mysql_query($sql_last, $conexion);
				$row_last = mysql_fetch_array($rs_last);

				$cur_asig = $row_last['curso_asignatura_id'];
				//CREO LAS CARGAS ANUALES PARA CADA CURSO
				$sql_carga = "INSERT INTO cargas 
				(
					curso_asignatura_id,
					tipo_carga_id,
					tipo_carga_unidad,
					carga_aprobacion,
					carga_estado
				)
				VALUES 
				(
					$cur_asig,
					'2',
					'0',
					'0',
					'1'
				)
				";
				$rs_carga = mysql_query($sql_carga, $conexion);
		}
	}
}
}

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo generado correctamente!')
    window.location.href='generar_periodo.php';
    </SCRIPT>");
	mysql_close($sql);
?>
