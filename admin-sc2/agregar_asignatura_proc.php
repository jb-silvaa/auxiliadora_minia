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
$asignatura = $_POST['asignatura'];
$nivel_id = $_POST['nivel'];
//CONSULTO TODOS LOS NIVELES CON SUS LETRAS

for ($i=0;$i<count($nivel_id);$i++)    
{     
$nivel = $nivel_id[$i];
$sql_nl = "SELECT * FROM niveles_letras WHERE nivel_id = '$nivel'";
$rs_nl = mysql_query($sql_nl, $conexion);
while ($row_nl = mysql_fetch_array($rs_nl)) {
	$nivel = $row_nl['nivel_id'];
	$letra = $row_nl['letra_id'];

	$sql_asig = "SELECT * FROM asignaturas WHERE asignatura_id = '$asignatura'";
	$rs_asig = mysql_query($sql_asig, $conexion);
	$row_asig = mysql_fetch_array($rs_asig);

		if($row_asig['asignatura_dificultad'] == '1')
		{
			$sql_dif = "SELECT * FROM dificultades WHERE dificultad_estado = '1' AND dificultad_id <> '0'";
			$rs_dif = mysql_query($sql_dif, $conexion);
			while ($row_dif = mysql_fetch_array($rs_dif)) 
			{
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
					  	'10',
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


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Asignatura agregada correctamente!')
    window.location.href='listado_cursos.php?periodo=".date('Y')."&nivel=0&asignatura=".$asignatura."&docente=0';
    </SCRIPT>");
	mysql_close($sql);
?>
