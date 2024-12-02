<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$user_id = $_SESSION['id'];

$periodo = $_GET['periodo'];
if($periodo == ''){ 
    if($_SESSION['filtro_periodo'] != ''){
        $periodo = $_SESSION['filtro_periodo'];
    }else{
        $periodo = date('Y'); 
        $_SESSION['filtro_periodo'] = $periodo;
    }
    $periodo_filtro = " AND ca.curso_asignatura_periodo = '$periodo' ";
}else{
    $_SESSION['filtro_periodo'] = $periodo;
    $periodo_filtro = " AND ca.curso_asignatura_periodo = '$periodo' ";
}

$docente = $_GET['docente'];
if($docente == ''){
    if($_SESSION['filtro_docente'] != '' && $_SESSION['filtro_docente'] != '0' ){
        $docente = $_SESSION['filtro_docente'];
        $docente_filtro = " AND ca.profesor_id = ".$docente." ";
        debug_to_console(1);
    }else{
        $docente_filtro = ""; 
        $docente = '0';
        debug_to_console(2);
    }
}else if($docente == '0'){
    $docente_filtro = ""; 
    $_SESSION['filtro_docente'] = $docente;
    debug_to_console(3);
}else{
    $_SESSION['filtro_docente'] = $docente;
    $docente_filtro = " AND ca.profesor_id = ".$docente." ";
    debug_to_console(4);
}

$tipo_carga = $_GET['tipo'];
if($tipo_carga != ''){
    $tipo_filtro = " AND e.evaluacion_estado_id = '".$tipo_carga."' ";
    $_SESSION['filtro_carga1'] = $tipo_carga;
}else{
    if($_SESSION['filtro_carga1'] != ''){
        $tipo_carga = $_SESSION['filtro_carga1'];
        $tipo_filtro = " AND e.evaluacion_estado_id = '".$tipo_carga."' ";
    }else{
        $tipo_filtro = ""; 
    }
}

//FILTRO PARA LIMITAR VISTA DE CURSOS A AYUDANTES
$sql_ay = "SELECT *
        FROM ayudantes
        WHERE usuario_id = '$user_id'";
$rs_ay = mysql_query($sql_ay,$conexion);
$cant = mysql_num_rows($rs_ay);
debug_to_console($user_id);
$ay_filtro = "";
$ayudante_filtro = "";
if($cant>0){
    $ay_filtro = ", ayudantes as ay";
    $ayudante_filtro = "AND ca.curso_asignatura_id = ay.curso_asignatura_id
                        AND ay.usuario_id = '$user_id'";
}else{
    $ay_filtro = "";
    $ayudante_filtro = "";
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
    <title>Sistema Clases</title>
    <link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
    <link 
    href="../css-sc/iphone.css"
    media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
    rel="stylesheet">
    <?php 
        include('../fonts/fonts.php'); 
        include('../js-sc/bootstrap.php'); 
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
     <script src="validar_periodo.js"></script>
     <script src="../js-sc/validar_caracteres.js"></script>
</head>
<body>
    <div id="wrapper">
        <div class="overlay"></div>
    
        <!-- Sidebar -->
        <?php include('menu-lateral.php'); 

        ?>

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
                    <div class="col-lg-12">
                    <!--    <h1>RESUMEN GENERAL</h1>
                        <?php 
                        $sql_anual = "SELECT count(carga_id) as total_anual 
                                      FROM cargas as c,
                                           cursos_asignaturas as ca,
                                           periodos as p
                                      WHERE tipo_carga_id = '2' 
                                      AND ca.profesor_id <> '0'
                                      AND p.periodo_periodo = ca.curso_asignatura_periodo
                                      AND ca.curso_asignatura_id = c.curso_asignatura_id
                                      AND c.carga_estado = '1'
                                      AND ca.curso_asignatura_estado = '1'
                                      $periodo_filtro
                                      $docente_filtro
                                      ";
                        $rs_anual = mysql_query($sql_anual, $conexion);
                        $row_anual = mysql_fetch_array($rs_anual);
                        $sql_anual_a = "SELECT count(carga_id) as total_aceptada 
                                      FROM cargas as c,
                                           cursos_asignaturas as ca,
                                           periodos as p
                                      WHERE tipo_carga_id = '2' 
                                      AND ca.profesor_id <> '0'
                                      AND p.periodo_periodo = ca.curso_asignatura_periodo
                                      AND ca.curso_asignatura_id = c.curso_asignatura_id
                                      AND c.carga_estado = '1'
                                      AND ca.curso_asignatura_estado = '1'
                                      AND c.carga_aprobacion = '1'
                                      $periodo_filtro
                                      $docente_filtro
                                      ";
                        $rs_anual_a = mysql_query($sql_anual_a, $conexion);
                        $row_anual_a = mysql_fetch_array($rs_anual_a);

                        $sql_anual_r = "SELECT count(carga_id) as total_rechazada 
                                      FROM cargas as c,
                                           cursos_asignaturas as ca,
                                           periodos as p
                                      WHERE tipo_carga_id = '2' 
                                      AND ca.profesor_id <> '0'
                                      AND p.periodo_periodo = ca.curso_asignatura_periodo
                                      AND ca.curso_asignatura_id = c.curso_asignatura_id
                                      AND c.carga_estado = '1'
                                      AND ca.curso_asignatura_estado = '1'
                                      AND c.carga_aprobacion = '-1'
                                      $periodo_filtro
                                      $docente_filtro
                                      ";
                        $rs_anual_r = mysql_query($sql_anual_r, $conexion);
                        $row_anual_r = mysql_fetch_array($rs_anual_r);
                        ?>
                        <div class="col-lg-6">
                            <h1>RESUMEN PLANIFICACIONES ANUALES</h1>
                            <table class="header2">
                                <tr>
                                    <th>CARGAS ANUALES CON PROFESOR</th><td><?=$row_anual['total_anual']?></td>
                                </tr>
                                <tr>
                                    <th>CARGAS ANUALES APROBADAS</th><td><?=$row_anual_a['total_aceptada']?></td>
                                </tr>
                                <tr> 
                                    <th>CARGAS ANUALES RECHAZADAS</th><td><?=$row_anual_r['total_rechazada']?></td>
                                </tr>
                                <?php 
                                $diff = $row_anual['total_anual']-$row_anual_a['total_aceptada']-$row_anual_r['total_rechazada'];
                                ?>
                                <tr>
                                    <th>CARGAS ANUALES SIN PLANIF.</th><td><?=$diff?></td>
                                </tr>                                
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <canvas id="pie-chart" width="800" height="450"></canvas>
                            <script type="text/javascript">
                                new Chart(document.getElementById("pie-chart"), {
                                type: 'pie',
                                data: {
                                  labels: ["Sin Planificación", "Aceptada", "Rechazada"],
                                  datasets: [{
                                    label: "Cantidad en unidades",
                                    backgroundColor: ["#000", "rgb(46, 204, 113)","rgb(207, 0, 15)"],
                                    data: [<?=$diff?>,<?=$row_anual_a['total_aceptada']?>,<?=$row_anual_r['total_rechazada']?>]
                                  }]
                                },
                                options: {
                                  title: {
                                    display: true,
                                    text: 'Resumen Planificaciones Anuales'
                                  }
                                }
                            });

                            </script>
                        </div>-->
                        <?php 
                        $sql_desc = "SELECT count(carga_id) as total_anual 
                                      FROM cargas as c,
                                           cursos_asignaturas as ca,
                                           periodos as p
                                      WHERE tipo_carga_id = '2' 
                                      AND ca.profesor_id <> '0'
                                      AND p.periodo_periodo = ca.curso_asignatura_periodo
                                      AND ca.curso_asignatura_id = c.curso_asignatura_id
                                      AND c.carga_estado = '1'
                                      AND ca.curso_asignatura_estado = '1'
                                      $periodo_filtro
                                      $docente_filtro
                                      ";
                        $rs_desc = mysql_query($sql_desc, $conexion);
                        $row_desc = mysql_fetch_array($rs_desc);
                        ?>
                        <div class="col-lg-12">
                            <h1 style="text-align:left;float:left;">ESTADO EVALUACIONES</h1> 
                            <!--<h2 style="text-align:right;float:right;"><label>DESCARGAR Eval.</label><a class="btn btn-info" href="evaluacion_descargar.php">DESCARGAR</a></h2> -->
                            
                            <div class="filtros4">
                                <form>
                                    <label>Estado Eval.</label>
                                    <select name="tipo" class="minimal">                                        
                                        <?php
                                        if($tipo_carga == ''){ echo "<option value=''>TODOS</option>"; }
                                        $sql_tipo = "SELECT * FROM evaluaciones_estado ORDER BY evaluacion_estado_id = '$tipo_carga' DESC, evaluacion_estado_nombre ASC";
                                        $rs_tipo = mysql_query($sql_tipo);
                                        while($row_tipo = mysql_fetch_array($rs_tipo))
                                        {
                                          echo "<option value='".$row_tipo['evaluacion_estado_id']."'>".$row_tipo['evaluacion_estado_nombre']."</option>";
                                        }
                                        if($tipo_carga != ''){ echo "<option value=''>TODOS</option>"; }
                                        ?>
                                    </select>
                                    <label>Año</label>
                                    <select name="periodo" class="minimal">
                                        <?php 
                                        $sql_anos = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo' DESC, periodo_periodo ASC";
                                        $rs_anos = mysql_query($sql_anos, $conexion);
                                        while ($row_anos = mysql_fetch_array($rs_anos)) {
                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Profesor</label>
                                    <select name="docente" class="minimal">
                                        <?php 
                                        if($docente == '0'){ echo "<option value='0'>TODOS</option>"; }
                                        $sql_profe = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0' ORDER BY profesor_id = '$docente' DESC, profesor_apellidos, profesor_nombres ASC";
                                        $rs_profe = mysql_query($sql_profe, $conexion);
                                        while ($row_profe = mysql_fetch_array($rs_profe)) {
                                            echo "<option value=".$row_profe['profesor_id'].">".$row_profe['profesor_apellidos']." ".$row_profe['profesor_nombres']."</option>";
                                        }
                                        if($docente != '0'){ echo "<option value='0'>TODOS</option>"; }
                                        ?>
                                    </select>
                                    <input type="submit" value="filtrar">
                                </form>
                                
                            </div>
                            <table class="header2" style="width: 100% !important; margin: 10px 0px !important; ">
                                <tr>
                                    <th>Profesor</th>
                                    <th>Curso</th>
                                    <th>Asignatura</th>
                                    <th>Nivel</th>
                                    <th>Evaluación</th>
                                    <th>Estado</th>
                                    <th>Ir</th>
                                </tr>
                                <?php 
                                $sql_prof = "SELECT * 
                                FROM asignaturas as a,
                                     cursos_asignaturas as ca,
                                     letras as l,
                                     niveles as n,
                                     profesores as p,
                                     dificultades as d,
                                     evaluaciones as e,
                                     evaluaciones_estado as ee
                                     $ay_filtro
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
                                $tipo_filtro
                                $docente_filtro
                                $ayudante_filtro
                                ORDER BY  p.profesor_apellidos, p.profesor_nombres, ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";
                                $rs_prof = mysql_query($sql_prof, $conexion);
                                $contador = '0';
                                while($row_prof = mysql_fetch_array($rs_prof))
                                {                         
                                    echo "<tr>                                                          
                                            <td>".$row_prof['profesor_apellidos']." ".$row_prof['profesor_nombres']."</td>
                                            <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>
                                            <td>".$row_prof['asignatura_nombre']."</td>  
                                            <td>".$row_prof['dificultad_nombre']."</td>
                                            <td>".$row_prof['evaluacion_nombre']."</td>
                                            <td>".$row_prof['evaluacion_estado_nombre']."</td>";
                                    ?> 
                                    <td><a href="evaluacion.php?id=<?=$row_prof['curso_asignatura_id']?>"><i class="fas fa-search-plus fa-lg"></i></a></td>                                   
                                          </tr>
                                <?php
                                    $dificultad = '';
                                    $contador++;
                                }
                                if($contador == '0'){ echo "<td colspan='8'>No hay resultados para los filtros escogidos</td>"; }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
