<?php
    include ('../funciones-sc/conexion.php');
    include ('../funciones/sesion_admin.php');
    include ('../funciones/color.php'); 
    include ('../funciones/funciones.php');  
    //include ('../funciones/romano.php'); 
    session_start();
    if(!$_SESSION){
        print '<script language="javascript">
            alert("Error: Usuario No Autenticado"); 
            self.location = "../index_principal.php";
            </script>';
    }

    $profesor_id = $_SESSION['profesor_id'];
    require_once ('../TCPDF-master/tcpdf.php');

    //clase para crear header y footer personalizado

    class mipdf extends TCPDF{  

      //Header personalizado

      public function Header() {

      if ($this->page == 1) {
            //imagen en header
            $logo = '../images-sc/logo_footer.png';
            $this->Image($logo, 10, 10, 50, '', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', 'B', 20);
            $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        } else {
            //imagen en header
            $logo = '../images-sc/logo_footer2222.png';
            $this->Image($logo, 10, 10, 50, '', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', 'B', 20);
            $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

      }

      

      //footer personalizado

      public function Footer() {

      // posicion

      //$this->SetY(-15); original

      $this->SetY(-25);

      // fuente

      $this->SetFont('helvetica', 'I', 8);

      // numero de pagina

      //$logo = '../images/logo_footer.png';

      

      $logoFileName = "../images-sc/logo_footer2.png";

      $logoWidth = 50; // 15mm

      $this->Image($logoFileName, $logoX, $this->GetY()+2, $logoWidth);



      

      

      //$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

      }

    }
    $width = 216;  
    $height = 273; 
    $pageLayout = array($width, $height);
    //iniciando un nuevo pdf
    $pdf = new mipdf(PDF_PAGE_ORIENTATION, 'mm', $pageLayout, true, 'UTF-8', false);
 
    //establecer margenes

    $pdf->SetMargins(20, 15, 20); //PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT

    $pdf->SetHeaderMargin(15); // PDF_MARGIN_HEADER

    $pdf->SetFooterMargin(15); //PDF_MARGIN_FOOTER

    

    //informacion del pdf

    $pdf->SetCreator('Colegio María Auxiliadora');

    $pdf->SetAuthor('Colegio María Auxiliadora');

    $pdf->SetTitle('Calendario Evaluaciones');

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    

    // set header and footer fonts

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



    // set default monospaced font

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



    //tipo de fuente y tamanio

    $pdf->SetFont('helvetica', '', 11);

    

    

    $pdf->AddPage(); 



    $pag = "Pagina 1";



    $item = 0;



    $par  = 'bgcolor="#bfbfbf"';

    $impar = 'bgcolor="#ffffff"';

    

    $html = '';

    

    //Primera pagina:



    $html = '';

    //busco año activo
    $sql_activo = "SELECT periodo_periodo FROM periodos WHERE periodo_activo = 1";
    $rs_activo = mysqli_i_query($conexion, $sql_activo);
    $row_activo = mysqli_i_fetch_array($rs_activo);
    $periodo_activo = $row_activo['periodo_periodo'];
    //busco nombre profesor
    $sql_profesor = "SELECT profesor_nombres, profesor_apellidos FROM profesores WHERE profesor_id = '$profesor_id'";
    $rs_profesor = mysqli_i_query($conexion, $sql_profesor);
    $row_profesor = mysqli_i_fetch_array($rs_profesor);
    $profesor_nombre = $row_profesor['profesor_nombres']." ".$row_profesor['profesor_apellidos'];
    $sql_datos = "SELECT ca.curso_asignatura_id, 
                        evaluacion_fecha, 
                        evaluacion_nombre, 
                        tipo_evaluacion_nombre, 
                        month(e.evaluacion_fecha) as mes_id,
                        asignatura_nombre,
                        letra_nombre,
                        nivel_nombre 
                        FROM evaluaciones as e, tipos_evaluaciones as te, cursos_asignaturas as ca, asignaturas as a,
                        niveles as n, letras as l
                        WHERE ca.profesor_id = '$profesor_id' 
                        AND YEAR(e.evaluacion_fecha) = '$periodo_activo'
                        AND te.tipo_evaluacion_id = e.tipo_evaluacion_id
                        AND ca.curso_asignatura_id = e.curso_asignatura_id
                        AND YEAR(e.evaluacion_fecha) = ca.curso_asignatura_periodo
                        AND n.nivel_id = ca.nivel_id
                        AND l.letra_id = ca.letra_id
                        AND a.asignatura_id = ca.asignatura_id
                        AND e.evaluacion_estado <> '-1'
                        AND e.evaluacion_aprobacion <> '-1'
                        ORDER BY e.evaluacion_fecha ASC";

    $rs_datos = mysqli_i_query($conexion, $sql_datos);

    $html.='
    <br><br><br><br>
        <table cellpadding="10" style="border: 1px solid black;">
        <tr>
            <th style="width:480px;font-size:100%;text-align:center;"><b>CALENDARIO EVALUACIONES '.$periodo_activo.'</b></th>
        </tr>
        <tr>
            <th style="width:480px;font-size:80%;text-align:center;"><b>PROFESOR(A): '.$profesor_nombre.'</b></th>
        </tr>
        </table>';

    $mes_actual = '0';
    while($row_datos = mysqli_i_fetch_array($rs_datos))
    {
        $mes_id = $row_datos['mes_id'];
        //reviso el mes para ordenarlo bonito
        $sql_mes = "SELECT mes_nombre FROM meses WHERE mes_id = '$mes_id'";
        $rs_mes = mysqli_i_query($conexion, $sql_mes);
        $row_mes = mysqli_i_fetch_array($rs_mes);
        $mes_nombre = $row_mes['mes_nombre'];
        if($mes_actual != $mes_nombre)
        {
        $html.='<table cellpadding="2" style="border: 1px solid black;">
        <tr>
            <th style="width:480px;font-size:75%;text-align:center;background-color:#027d00;color:white;"><b>'.$mes_nombre.'</b></th>
        </tr>
        </table>
        <table cellpadding="2" style="border: 1px solid black;" border="1">
            <tr style="background-color:#027d00;color:white;">
                <th style="width:50px;font-size:75%;text-align:center;"><b>Fecha</b></th>
                <th style="width:70px;font-size:75%;text-align:center;"><b>Curso</b></th>
                <th style="width:150px;font-size:75%;text-align:center;"><b>Asignatura</b></th>
                <th style="width:140px;font-size:75%;text-align:center;"><b>Nombre Evaluación</b></th>
                <th style="width:70px;font-size:75%;text-align:center;"><b>Tipo Evaluación</b></th>
            </tr>
        </table>';
        }
        $mes_actual = $mes_nombre;
        $html.='<table cellpadding="2" style="border: 1px solid black;" border="1">
                <tr>
                    <th style="width:50px;font-size:75%">'.$row_datos['evaluacion_fecha'].'</th>
                    <th style="width:70px;font-size:75%">'.$row_datos['nivel_nombre'].' '.$row_datos['letra_nombre'].'</th>
                    <th style="width:150px;font-size:75%">'.$row_datos['asignatura_nombre'].'</th>
                    <th style="width:140px;font-size:75%">'.$row_datos['evaluacion_nombre'].'</th>
                    <th style="width:70px;font-size:75%">'.$row_datos['tipo_evaluacion_nombre'].'</th>
                </tr>
            </table>';
    }
        



    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $time = date('Y_m_d_H_i'); // para actualizar pdf

    $filename = $profesor_id."_".$time.".pdf";

    ob_end_clean();

    $pdf->Output($filename, 'I');



    //$pdf->Output(__DIR__ . '/archivos/informes_finales/'.$filename, 'F');

    //$fileNL = $filelocation."/".$filename; 

    //Guardandp la direccion del pdf
  ?>


