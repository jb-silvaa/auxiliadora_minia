<?php
session_start();
if(!$_SESSION){
    print '<script language="javascript">
        alert("Error: Usuario No Autenticado"); 
        self.location = "../index_principal.phpp";
        </script>';
}
/*
if($_SESSION['usuario_tipo']!='0'){

    SESSION_DESTROY();
    print '<script language="javascript">
    alert("Error: Usuario No Autenticado"); 
    self.location = "../index_principal.php";
    </script>';

    
 }*/

    include ('../funciones-sc/conexion.php');
    $fecha_creacion = date('Y-m-d');
    $archivo_evaluaciones = $_FILES["files"]['name'];
    $nombre_archivo = "archivo_evaluacion";

     $time             = date('Y_m_d_H_i_s'); // para actualizar pdf
        //sacamos la ext del archivo
     $str              = $_FILES["files"]['name'];
     $ext              = end(explode(".", $str));
     $archivo_eva      = $nombre_archivo."(".$time.").".$ext; // el numero del boleta es el imp pal guardarlo
  
   if(isset($_FILES['files']['type'])){
    /*  if ($_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheett'){ */
       $destino = 'archivos/evaluaciones/'.$archivo_eva;
       //Subimos el fichero al servidor
       move_uploaded_file($_FILES["files"]["tmp_name"], $destino);
     }
     //lectura del valos del archivo

     require_once '../PHPEXCEL/Classes/PHPExcel.php';
     $archivo = $destino;
     $inputFileType = PHPExcel_IOFactory::identify($archivo);
     $objReader = PHPExcel_IOFactory::createReader($inputFileType);
     $objPHPExcel = $objReader->load($archivo);
     $sheet = $objPHPExcel->getSheet(0); 
     $highestRow = $sheet->getHighestRow(); 
     $highestColumn = $sheet->getHighestColumn();
     $row=2;
     $po=false;
     while($po==false){ 
           
        $curso_asignatura_id = $sheet->getCell("K".$row)->getValue();   //EN LA K ESTARA EL ID, LO QUE ME PERMITE BUSCAR TODO         

        if($curso_asignatura_id==""){
        //echo "<br>Rompo<br><br>"; 
        break;
        }

        
        $nombre         =   utf8_encode($sheet->getCell("H".$row)->getValue());
        $tipo           =   $sheet->getCell("I".$row)->getValue();
        //PARA NO CONFUNDIR AL USUARIO DEJAMOS 0,1,2 LOS VALORES PERO EN REALIDAD EN BD ES 1,2,3 ASI QUE AQUI LOS CAMBIO
        if($tipo == 0){ $tipo_real = '1'; }
        if($tipo == 1){ $tipo_real = '2'; }
        if($tipo == 2){ $tipo_real = '3'; }
        $fecha          =   $sheet->getCell("J".$row)->getValue();
        $fecha          =   date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fecha)); 

        if($nombre != '')
        {
             $sql_ca = "INSERT INTO evaluaciones(
                                curso_asignatura_id,
                                evaluacion_nombre,
                                evaluacion_archivo,
                                evaluacion_fecha,
                                evaluacion_copia,
                                tipo_evaluacion_id,
                                evaluacion_aprobacion,
                                evaluacion_estado,
                                evaluacion_fecha_creacion,
                                evaluacion_encargado
                            )VALUES(
                                '$curso_asignatura_id',
                                '$nombre',
                                '',
                                '$fecha',
                                '0',
                                '$tipo_real',
                                '0',
                                '1',
                                '$fecha_creacion',
                                ''
                            )";

            $rs_ca = mysqli_query($conexion, $sql_ca);
            $row++;
        }
        else
        {
            $row++;
        }

        
     }
    if($rs_ca)
    echo "<script>window.location='calendario_pruebas.php?z=add_exi';</script>"; 
    else
    echo "<script>window.location='calendario_pruebas.php?z=add_err';</script>";

?>