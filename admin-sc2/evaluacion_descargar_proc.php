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
include('../funciones-sc/conexion.php');

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$nivel = $_POST['nivel'];
if($nivel != ''){ $nivel_filtro = " AND n.nivel_id = '".$nivel."' "; }
else{ $nivel_filtro = ""; }

$letra = $_POST['letra'];
if($letra == '' || $letra == '0'){ $letra_filtro = ""; $letra = '0';}
else{ $letra_filtro = " AND ca.letra_id = ".$letra." "; }

$periodo = $_POST['periodo'];
if($periodo == ''){ $periodo = date('Y'); }
$periodo_filtro = " AND ca.curso_asignatura_periodo = '$periodo' ";

$sql_prof = "SELECT * 
                                FROM asignaturas as a,
                                     cursos_asignaturas as ca,
                                     letras as l,
                                     niveles as n,
                                     profesores as p,
                                     dificultades as d,
                                     evaluaciones as e,
                                     evaluaciones_estado as ee
                                WHERE a.asignatura_estado = '1'
                                AND ca.asignatura_id = a.asignatura_id
                                AND n.nivel_id = ca.nivel_id
                                AND e.curso_asignatura_id = ca.curso_asignatura_id
                                AND l.letra_id = ca.letra_id
                                AND ca.dificultad_id = d.dificultad_id
                                AND ca.profesor_id = p.profesor_id
                                AND e.evaluacion_estado_id = ee.evaluacion_estado_id
                                AND e.evaluacion_estado = '1'
                                $periodo_filtro
                                $nivel_filtro
                                $letra_filtro
                                ORDER BY  p.profesor_apellidos, p.profesor_nombres, ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";
$rs_prof = mysql_query($sql_prof, $conexion);

$zip = new ZIPARCHIVE();
	$zip_ruta = "doc_cursos.zip";
	if(file_exists($zip_ruta))
		unlink($zip_ruta);

	
	//echo "<br><br>hola -  creamos el zip<br><br>";
	// Creamos y abrimos un archivo zip temporal
   if ($zip->open($zip_ruta, ZipArchive::CREATE) === TRUE){
      //echo "<br><br>hola -  abrimos el zip<br><br>";
        while($row_prof = mysql_fetch_array($rs_prof)){
            $zip_nombre =  $row_prof['nivel_nombre']."-".$row_prof['letra_nombre'];
            $eval = $row_prof['evaluacion_archivo'];
            $partes_ruta = pathinfo($row_prof['evaluacion_archivo']);
            $ext = $partes_ruta['extension'];
            debug_to_console($ext);
            debug_to_console($eval);
            $nombres = $row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."-".$row_prof['asignatura_nombre']."-".$row_prof['evaluacion_id'].".".$ext;
            $zip->addFile("../profesor-sc/$eval","$nombres");
            sleep(0.5);
            debug_to_console($nombres);
        }
        $zip->close();
        debug_to_console($zip_nombre);
        //debug_to_console($zip);
    }

   if(file_exists($zip_ruta)){
		
		header('Content-Type: application/octet-stream');
		//header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=$zip_nombre.zip");
		
        // leemos el archivo creado
        debug_to_console("AAAAA");
        readfile($zip_ruta);
        debug_to_console("sdsasdsa");
        // Por último eliminamos el archivo temporal creado
        unlink($zip_ruta);//Destruye el archivo temporal
   }

   header("Location: evaluacion_descargar.php");
/*echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Profesor creado correctamente!, Anote su contraseña= $clave')
    window.location.href='profesores.php';
    </SCRIPT>");
	mysql_close($sql);*/
?>