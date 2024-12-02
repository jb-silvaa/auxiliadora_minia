<!DOCTYPE html>
<html>
<?php 
$curso1 = $_GET['curso'];
if($curso1 != ''){
    $result = explode("-",$curso1);
    $nivel = $result[0];
    $letra = $result[1];
    //$orden = "ORDER BY letras.letra_id = '$letra', niveles.nivel_id = '$nivel' DESC, niveles.nivel_nombre ASC";
}else{
    $orden = "";
}

$perfil_archivo = 1;
include('../funciones-sc/conexion.php');

if($nivel != '' && $nivel != '0'){
    $nivel_filtro = "AND ca.nivel_id = '$nivel'";
}else{
    $nivel_filtro = "";
}
if($letra != '' && $letra != '0'){
    $letra_filtro = "AND ca.letra_id = '$letra'";
}else{
    $letra_filtro = "";
}

$mes = $_GET['mes'];
if($mes == '' || $mes == '0'){ $mes_filtro = " "; }
else{ $mes_filtro = " AND month(e.evaluacion_fecha) = ".$mes." "; }

$profesor = $_GET['profesor'];
if($profesor == '' || $profesor == '0'){ $profesor_filtro = " "; }
else{ $profesor_filtro = " AND p.profesor_id = ".$profesor." "; }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
        <title>Sistema Clases</title>

        
       
        <link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
        <link href='../packages/core/main.css' rel='stylesheet' />
        <link href='../packages/daygrid/main.css' rel='stylesheet'/>
        <?php 

            include('../fonts/fonts.php');
            include('../js-sc/bootstrap.php');  
            
        ?>
        
    </head>
    <body>
        <!------ Include the above in your HEAD tag ---------->

        <div id="wrapper">
        
            <!-- Sidebar -->
            <?php include('menu-lateral.php'); ?>
            
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                    <span class="hamb-top"></span>
                    <span class="hamb-middle"></span>
                    <span class="hamb-bottom"></span>
                </button>
                <div class="container">
                    <div class="row">
                        <div>
                            <style>
                                .dot {
                                    height: 25px;
                                    width: 25px;
                                    border-radius: 50%;
                                    display: inline-block;
                                    margin-left: 15px;
                                    margin-right: 30px;
                                    margin-top: 10px;
                                    margin-bottom: 10px;
                                }

                                table {
                                  border-collapse: collapse;
                                }

                                table, th, td {
                                  border: 1px solid black;
                                }
                            </style>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-11">
                            <div class="filtros4">
                                <form method="GET">
                                    <label>Curso</label>       
                                    <select id="curso" name="curso" required class="minimal">
                                        <option value="0-0">TODOS</option>
                                        <?php
                                        $sql_asig = "SELECT DISTINCT *
                                            FROM cursos_asignaturas
                                            INNER JOIN letras on letras.letra_id = cursos_asignaturas.letra_id 
                                            INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id
                                            WHERE curso_asignatura_estado = '1'
                                            GROUP BY nivel_nombre, letra_nombre";
                                        $rs_asig = mysqli_query($conexion, $sql_asig);
                                        while($row_asig = mysqli_fetch_array($rs_asig))
                                        {
                                            $curso = $row_asig['nivel_nombre']."-".$row_asig['letra_nombre'];
                                            $ids = $row_asig['nivel_id']."-".$row_asig['letra_id'];
                                            if($nivel == $row_asig['nivel_id'] && $letra == $row_asig['letra_id'] ){
                                                echo "<option value='".$ids."' selected>".$curso."</option>";    
                                            }else{
                                                echo "<option value='".$ids."'>".$curso."</option>";
                                            }
                                        }
                                        ?>                                   
                                    </select>
                                    <label>Profesor</label>       
                                    <select id="profesor" name="profesor" required class="minimal">
                                        <option value="0">TODOS</option>
                                        <?php
                                        $sql_prof = "SELECT DISTINCT *
                                            FROM cursos_asignaturas as ca, profesores as p, periodos as pe
                                            WHERE ca.curso_asignatura_estado = '1'
                                            AND ca.profesor_id = p.profesor_id
                                            ANd pe.periodo_activo = '1'
                                            AND pe.periodo_periodo = ca.curso_asignatura_periodo
                                            AND ca.profesor_id <> '0'
                                            GROUP by p.profesor_id
                                            ORDER BY p.profesor_id = '$profesor' DESC, p.profesor_apellidos, p.profesor_nombres ASC";
                                        $rs_prof = mysqli_query($conexion, $sql_prof);
                                        while($row_prof = mysqli_fetch_array($rs_prof))
                                        {
                                            $profe = $row_prof['profesor_apellidos']." ".$row_prof['profesor_nombres'];
                                            $ids = $row_prof['profesor_id'];
                                            if($nivel == $row_prof['nivel_id'] && $letra == $row_prof['letra_id'] ){
                                                echo "<option value='".$ids."' selected>".$profe."</option>";    
                                            }else{
                                                echo "<option value='".$ids."'>".$profe."</option>";
                                            }
                                        }
                                        ?>                                   
                                    </select>
                                    <label>Mes</label>       
                                    <select id="mes" name="mes" required class="minimal">
                                        <option value="0">TODOS</option>
                                        <?php
                                        $sql_mes = "SELECT *
                                            FROM meses
                                            ORDER BY mes_id = '$mes' DESC, mes_id ASC";
                                        $rs_mes = mysqli_query($conexion, $sql_mes);
                                        while($row_mes = mysqli_fetch_array($rs_mes))
                                        {
                                            $nombre = $row_mes['mes_nombre'];
                                            $ids = $row_mes['mes_id'];
                                            if($mes == $row_mes['mes_id']){
                                                echo "<option value='".$ids."' selected>".$nombre."</option>";    
                                            }else{
                                                echo "<option value='".$ids."'>".$nombre."</option>";
                                            }
                                        }
                                        ?>                                   
                                    </select>
                                    <input type="submit" value="Filtrar">                      
                                </form>
                            </div> 
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-primary btn-nuevo" onclick="window.print();"><i class="fas fa-print"  style="color:#ffffff;" title="Imprimir"></i> Imprimir</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" id='calendar'></div>
                    </div>  

                <div id="calendario-listado">
                <?php
                    $html = '';
                    $sql = "SELECT * FROM evaluaciones as e
                            INNER JOIN cursos_asignaturas as ca on ca.curso_asignatura_id = e.curso_asignatura_id
                            INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
                            INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
                            INNER JOIN letras as l on l.letra_id = ca.letra_id
                            INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
                            INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo
                            INNER JOIN profesores as p on p.profesor_id = ca.profesor_id
                            INNER JOIN tipos_evaluaciones as te on te.tipo_evaluacion_id = e.tipo_evaluacion_id
                            WHERE evaluacion_estado = '1'
                            AND pe.periodo_activo = '1'
                            AND e.evaluacion_aprobacion <> '-1'
                            AND e.evaluacion_fecha <> '0000-00-00'
                            $nivel_filtro
                            $letra_filtro
                            $profesor_filtro
                            $mes_filtro
                            ORDER BY e.evaluacion_fecha ASC";
                    $rs = mysqli_query($conexion, $sql);
                    $fecha_que_paso = '0000-00-00';
                    while ($row = mysqli_fetch_array($rs)) {  
                        if($row['evaluacion_fecha'] != $fecha_que_paso)
                        {    
                        setlocale (LC_TIME, "es_ES");       
                        $fecha_larga = utf8_encode( strftime("%A, %d de %B de %Y", strtotime($row['evaluacion_fecha'])) ); 
                        $healthy = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        $yummy   = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado", "Domingo");

                        $fecha_larga = str_replace($healthy, $yummy, $fecha_larga); 

                        $healthy = array("March", "April", "May", "June", "July","August", "September", "October", "November", "December");
                        $yummy   = array("Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

                        $fecha_larga = str_replace($healthy, $yummy, $fecha_larga);           
                        echo '</table><h1>'.$fecha_larga.'</h1>
                        <table class="header2">
                                <tr>
                                    <th>Asignatura</th>
                                    <th>Profesor</th>
                                    <th>Evaluación</th>
                                </tr>';
                        }
                        $fecha_que_paso =  $row['evaluacion_fecha'];
                        echo '<tr><td>'.$row["asignatura_nombre"].' '.$row["dificultad_nombre"].'</td><td>'.$row["profesor_nombres"].' '.$row["profesor_apellidos"].'</td><td>'.$row["evaluacion_nombre"].' / '.$row["tipo_evaluacion_nombre"].'</td></tr>';
                    }
                    //echo $html;
                    ?>
                                

                </div>
            </div>
            </div>
            <!-- /#page-content-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php  
            /*include("../mpdf-development/src/mpdf.php");
            $mpdf=new mPDF();

            // La variable $html es vuestro código que queréis pasar a PDF
            //$html = utf8_encode($html);

            $mpdf->WriteHTML($html);

            // Genera el fichero y fuerza la descarga
            $mpdf->Output('nombre.pdf','D'); exit;*/
        ?> 
    </body>
</html>
