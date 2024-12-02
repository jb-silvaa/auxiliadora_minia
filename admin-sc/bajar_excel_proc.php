<?php

  session_start();
  include ('../funciones-sc/conexion.php');
  require_once '../PHPEXCEL/Classes/PHPExcel.php';

  //recibo los tickeados, para crear el resto de la consulta
  if(!empty($_POST['nombre_asig'])){

    // Loop to store and display values of individual checked checkbox.
    $filtro_asignatura = " AND (";
    foreach($_POST['nombre_asig'] as $nombre_asig){
      //echo "<br>".$nombre_asig;
      $filtro_asignatura .= " asi.asignatura_id = ".$nombre_asig." OR ";
    }
    $filtro_asignatura = substr($filtro_asignatura, 0, -3);
    $filtro_asignatura .= " )";
}

  $periodo = trim($_POST['periodo']);
  $q_copias = trim($_POST['cantidad']); // La cantidad de copias que quiero x evaluacion
  $nivel_tipo = trim($_POST['nivel_tipo']); // La cantidad de copias que quiero x evaluacion
  $timespam = date("Y_m_d__H_i_s");
  $fichero = 'archivos/base_archivo_sistema_clases.xlsx';
  $programa_ruta = "Subir_Evaluacion_".$timespam.".xlsx";
  $nuevo_fichero = "archivos/$programa_ruta";
  copy($fichero, $nuevo_fichero);
  $archivo = $nuevo_fichero; // 
  //echo "<br>hola - el archivo: $archivo<br><br>";
  $inputFileType = PHPExcel_IOFactory::identify($archivo);
  $inputFileType = PHPExcel_IOFactory::identify($archivo);
  $objReader     = PHPExcel_IOFactory::createReader($inputFileType); 
  $objPHPExcel   = $objReader->load($archivo); // Change the file 
  $sheet         = $objPHPExcel->getSheet(0);

  //Abajo está el arreglo que define el estilo de la celda (bordes y color);

  $styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '766f6e')
        )
    )
  );

  $row = 1;
  $sql_dato = "SELECT  
                cas.curso_asignatura_id,
                niv.nivel_nombre,
                leta.letra_nombre,
                asi.asignatura_nombre,
                pro.profesor_apellidos, 
                pro.profesor_nombres, 
                dif.dificultad_nombre,
                cas.nivel_id,
                cas.letra_id,
                cas.asignatura_id,
                cas.profesor_id,
                cas.curso_asignatura_periodo,
                cas.dificultad_id
                
              FROM cursos_asignaturas as cas 
                INNER JOIN niveles as niv ON niv.nivel_id = cas.nivel_id
                INNER JOIN letras as leta ON leta.letra_id = cas.letra_id
                INNER JOIN asignaturas as asi ON asi.asignatura_id = cas.asignatura_id
                INNER JOIN profesores as pro ON pro.profesor_id = cas.profesor_id
                INNER JOIN dificultades as dif ON dif.dificultad_id = cas.dificultad_id 
              WHERE cas.curso_asignatura_estado = '1'
                AND niv.nivel_tipo = '$nivel_tipo'
                AND cas.curso_asignatura_periodo = '$periodo'
                $filtro_asignatura
              ORDER BY 
                niv.nivel_orden ASC, 
                leta.letra_nombre ASC, 
                asi.asignatura_nombre ASC, 
                pro.profesor_apellidos ASC, 
                dif.dificultad_nombre asc";

  $rs_dato = mysqli_query($conexion, $sql_dato);
  while($row_dato = mysqli_fetch_array($rs_dato)) { 
    $curso_asignatura_id      = $row_dato['curso_asignatura_id'];
    $nivel_nombre             = $row_dato['nivel_nombre'];
    $letra_nombre             = $row_dato['letra_nombre'];
    $asignatura_nombre        = $row_dato['asignatura_nombre'];
    $profesor_apellidos       = $row_dato['profesor_apellidos'];
    $profesor_nombres         = $row_dato['profesor_nombres'];
    $dificultad_nombre        = $row_dato['dificultad_nombre'];
    $nivel_id                 = $row_dato['nivel_id'];
    $letra_id                 = $row_dato['letra_id'];
    $asignatura_id            = $row_dato['asignatura_id'];
    $profesor_id              = $row_dato['profesor_id'];
    $curso_asignatura_periodo = $row_dato['curso_asignatura_periodo'];
    $dificultad_id            = $row_dato['dificultad_id'];
    for($x = 0; $x<$q_copias;$x++){
      $row++;
      
      /* echo "<br>hola - nivel_nombre:$nivel_nombre<br>";
      echo "<br>hola - letra_nombre:$letra_nombre<br>";
      echo "<br>hola - asignatura_nombre:$asignatura_nombre<br>";
      echo "<br>hola - profe:$profesor_nombres $profesor_apellidos<br>";
      echo "<br>hola - periodo:$periodo<br>";
      echo "<br>hola - dificultad_nombre:$dificultad_nombre<br>"; */
      $sheet ->setCellValue("A$row", $nivel_nombre); // 
      $sheet ->setCellValue("B$row", $letra_nombre); // 
      $sheet ->setCellValue("C$row", $asignatura_nombre); // 
      $sheet ->setCellValue("D$row", $profesor_nombres); // 
      $sheet ->setCellValue("E$row", $profesor_apellidos); // 
      $sheet ->setCellValue("F$row", $periodo); // 
      $sheet ->setCellValue("G$row", $dificultad_nombre); // 
      //$sheet ->setCellValue("H$row", "Q$curso_asignatura_id - Q$x"); // 
      $sheet ->setCellValue("K$row", $curso_asignatura_id); // 
      
      
      foreach(range('A','J') as $columnID) {  // le ponemos borde completo a la ultima fila entre A y M
      
        $sheet->getStyle($columnID.''.$row)->applyFromArray($styleArray);
      }
    }
  }
  $sheet->getProtection()->setSheet(true);
  $sheet->getStyle('A1:J'.$row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
  //$sheet->getStyle('K1:K'.$row)->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );

  //COsos FInales pa generar el excel
    //Coloque un nombre de archivo aleatorio para evitar ficheros repetidos.
    
    $fecha= date("Y-m-d H:i:s");
    $name="archivo_base_ev_".$fecha;
    $extencion=".xlsx";
 
    $nombre_archivo=$name.$extencion;
    // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
 
    //Aqui es donde esta la magia para imprimir el archivo de Excel todo lo anterior es la configuración del archivo.
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$nombre_archivo.'"');
    header('Cache-Control: max-age=0');
    

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean(); // Limpiar (eliminar) el búfer de salida y deshabilitar el almacenamiento en el mismo
    $objWriter->save('php://output');

    unlink($nuevo_fichero);
    exit;

  echo "<br><br><br>FINITO";

?>