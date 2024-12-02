<?php 
//error_reporting(E_ALL); ini_set('display_errors', '1');
include('../funciones-sc/conexion.php');

$columnas = array("D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");





require_once dirname(__FILE__) . '/../PHPEXCEL/Classes/PHPExcel.php';

//ini_set('max_execution_time', 300); //300 seconds = 5 minutes

//require_once 'PHPExcel/IOFactory.php';



// Create new PHPExcel object

$objPHPExcel = new PHPExcel();

//$objPHPExcel->setActiveSheetIndex(0);

$style = array(

    'alignment' => array(

        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,

    )

);



$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);

// Create a first sheet, representing sales data



// Rename sheet

//$objPHPExcel->getActiveSheet()->setTitle('Name of Sheet 1');



// Create a new worksheet, after the default sheet

//$objPHPExcel->createSheet();



// Add some data to the second sheet, resembling some different data types

//$objPHPExcel->setActiveSheetIndex(1);

//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');



// Rename 2nd sheet

//$objPHPExcel->getActiveSheet()->setTitle('Second sheet');



$sql = "SELECT * FROM cursos_asignaturas as ca

        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id

        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id

        INNER JOIN letras as l on l.letra_id = ca.letra_id

        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id

        INNER JOIN profesores as p on p.profesor_id = ca.profesor_id

        INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo

        WHERE ca.nivel_id > 8 AND ca.nivel_id <= 12

        AND pe.periodo_activo = '1'

        AND ca.profesor_id > 0

        GROUP BY ca.profesor_id";

$rs = mysqli_query($conexion, $sql);

$cont = 0;



while($row = mysqli_fetch_array($rs)){

    $objPHPExcel->setActiveSheetIndex($cont);

    $cont_cursos=2;

    $cont_asig=2;

    $cont_cargas=2;

    $objPHPExcel->getActiveSheet()->getRowDimension("$cont+1")->setRowHeight(20);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'PROFESOR');

    $objPHPExcel->getActiveSheet()->setCellValue('B1','CURSO');

    $objPHPExcel->getActiveSheet()->setCellValue('C1','ASIGNATURA');

    $objPHPExcel->getActiveSheet()->setCellValue('D1','ANUAL');

    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'MARZO');

    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'ABRIL');

    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'MAYO');

    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'JUNIO');

    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'JULIO');

    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'AGOSTO');

    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'SEPTIEMBRE');

    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'OCTUBRE');

    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'NOVIEMBRE');

    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'DICIEMBRE');



    foreach(range('A','N') as $columnID) {

        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);

        

        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth(20);

        $objPHPExcel->getActiveSheet()->getStyle( $columnID."1" )->getFont()->setBold( true );

    }

    //$nombre2 = $row['profesor_nombres']." ".$row['profesor_apellidos'];

    $nombre = $row['profesor_nombres'];

    $apellido = $row['profesor_apellidos'];

    $prof_id = $row['profesor_id'];

    $nombre = strtok($nombre, " ");

    $apellido = strtok($apellido, " ");

    $nombre_apellido = $nombre." ".$apellido;

    $objPHPExcel->getActiveSheet()->setTitle("$nombre_apellido");

    $objPHPExcel->getActiveSheet()->setCellValue('A2', "$nombre_apellido");

    $rs_cursos = mysqli_query($conexion, $sql);

    $sql_cursos = "SELECT * FROM cursos_asignaturas as ca

        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id

        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id

        INNER JOIN letras as l on l.letra_id = ca.letra_id

        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id

        INNER JOIN profesores as p on p.profesor_id = ca.profesor_id

        INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo

        WHERE ca.nivel_id > 8 AND ca.nivel_id <= 12 

        AND pe.periodo_activo = '1'

        AND ca.profesor_id = '$prof_id'

        GROUP BY ca.nivel_id, ca.letra_id, ca.dificultad_id

        ORDER BY nivel_nombre, letra_nombre";

    $rs_cursos = mysqli_query($conexion, $sql_cursos);

    while($row_cursos = mysqli_fetch_array($rs_cursos)){

            $nombre_curso = $row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."-".$row_cursos['dificultad_nombre'];
            $nombre_curso = str_replace('&Aacute;', 'Á', $nombre_curso);
            $objPHPExcel->getActiveSheet()->setCellValue("B$cont_cursos", "$nombre_curso");    

            $nid = $row_cursos['nivel_id'];

            $lid = $row_cursos['letra_id'];

            $did = $row_cursos['dificultad_id'];

            $sql_asig = "SELECT * FROM cursos_asignaturas as ca

                INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id

                INNER JOIN niveles as n on n.nivel_id = ca.nivel_id

                INNER JOIN letras as l on l.letra_id = ca.letra_id

                INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id

                INNER JOIN profesores as p on p.profesor_id = ca.profesor_id

                INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo

                WHERE ca.nivel_id > 8 AND ca.nivel_id <= 12

                AND pe.periodo_activo = '1'

                AND ca.profesor_id = '$prof_id'

                AND ca.nivel_id = '$nid'

                AND ca.letra_id = '$lid'

                AND ca.dificultad_id = '$did'

                GROUP BY ca.asignatura_id";

            $rs_asig = mysqli_query($conexion, $sql_asig);

            while($row_asig = mysqli_fetch_array($rs_asig)){

                $nombre_asig = $row_asig['asignatura_nombre'];

                $aid = $row_asig['asignatura_id'];

                $objPHPExcel->getActiveSheet()->setCellValue("C$cont_asig", "$nombre_asig");



                $sql_carga = "SELECT * FROM cursos_asignaturas as ca

                    INNER JOIN cargas as c on c.curso_asignatura_id = ca.curso_asignatura_id

                    INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id

                    INNER JOIN niveles as n on n.nivel_id = ca.nivel_id

                    INNER JOIN letras as l on l.letra_id = ca.letra_id

                    INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id

                    INNER JOIN profesores as p on p.profesor_id = ca.profesor_id

                    INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo

                    WHERE ca.nivel_id > 8 AND ca.nivel_id <= 12

                    AND pe.periodo_activo = '1'

                    AND ca.profesor_id = '$prof_id'

                    AND ca.nivel_id = '$nid'

                    AND ca.letra_id = '$lid'

                    AND ca.dificultad_id = '$did'

                    AND ca.asignatura_id = '$aid'

                    AND ca.curso_asignatura_estado = '1'

                    ORDER BY c.tipo_carga_unidad";

                $rs_carga = mysqli_query($conexion, $sql_carga);

                $columna='D';

                while($row_carga = mysqli_fetch_array($rs_carga)){

                    $archivo = $row_carga['carga_archivo'];

                    $aprobacion = $row_carga['carga_aprobacion'];

                    $unidad = $row_carga['tipo_carga_unidad'];

                    if($archivo != '' && $aprobacion == 1){

                        $objPHPExcel->getActiveSheet()->setCellValue($columnas[$unidad].$cont_asig, "OK!");

                        $objPHPExcel->getActiveSheet()->getStyle($columnas[$unidad].$cont_asig)->applyFromArray(

                            array(

                                'fill' => array(

                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,

                                    'color' => array('rgb' => '008000')

                                )

                            )

                        );



                    }else if($aprobacion == -1){

                        $objPHPExcel->getActiveSheet()->setCellValue($columnas[$unidad].$cont_asig, "NO");

                        $objPHPExcel->getActiveSheet()->getStyle($columnas[$unidad].$cont_asig)->applyFromArray(

                            array(

                                'fill' => array(

                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,

                                    'color' => array('rgb' => 'ff0000')

                                )

                            )

                        );

                    }else if($archivo != '' && $aprobacion == 0 && $nid != '14' && $nid != '13'  && is_file('../profesor-sc/'.$archivo)){
                        $objPHPExcel->getActiveSheet()->setCellValue($columnas[$unidad].$cont_asig, "RV");

                        $objPHPExcel->getActiveSheet()->getStyle($columnas[$unidad].$cont_asig)->applyFromArray(

                            array(

                                'fill' => array(

                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,

                                    'color' => array('rgb' => 'ffff12')

                                )

                            )

                        );
                    }

                    $columna++;

                }

                $cont_asig++;

                $cont_cursos=$cont_asig-1;

            }

            $cont_cursos++;

    }

    for ($fila = 2; $fila <$cont_asig ; $fila++) {

        foreach(range('D','N') as $columnID) {

            $isEmpty = $objPHPExcel->getActiveSheet()->getCell($columnID.$fila)->getValue();

            if($isEmpty == '' || $isEmpty == NULL){

                $objPHPExcel->getActiveSheet()->setCellValue($columnID.$fila, "--");

            }

            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth(10);

        }

    }

    //OTRA HOJA

    $objPHPExcel->createSheet();

    $cont++;

}

$objPHPExcel->removeSheetByIndex(

    $objPHPExcel->getIndex(

        $objPHPExcel->getSheetByName('Worksheet')

    )

);

// Redirect output to a client’s web browser (Excel5)

header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition: attachment;filename="Planificacion_Media.xls"');

header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');







?>