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



//Consulta para saber quien aceptó/rechazó una planificación

$sql_user = "SELECT * FROM usuarios

			 WHERE usuario_id = '$user_id'

";

$rs_user = mysqli_query($conexion, $sql_user);

$row_user = mysqli_fetch_array($rs_user);

$user_nombre = $row_user['usuario_nombres'];

$user_apellidos = $row_user['usuario_apellidos'];

$encargado = $user_nombre." ".$user_apellidos;



//Filtro docente tabla

$docente = $_GET['docente'];

if($docente == ''){

    if($_SESSION['filtro_docente'] != '' && $_SESSION['filtro_docente'] != '0'){

        $docente = $_SESSION['filtro_docente'];

        $docente_filtro = " AND ca.profesor_id = ".$docente." ";

    }else{

        $docente_filtro = ""; 

        $docente = '0';

    }

}else if($docente == '0'){

    $docente_filtro = ""; 

    $_SESSION['filtro_docente'] = $docente;

}else{

    $_SESSION['filtro_docente'] = $docente;

    $docente_filtro = " AND ca.profesor_id = ".$docente." ";

}



//Filtro periodo grafico de barras

$periodo = $_GET['periodo'];

if($periodo == ''){ 

    if($_SESSION['filtro_periodo'] != ''){

        $periodo = $_SESSION['filtro_periodo'];

    }else{

        $periodo = /*date('Y')*/'2024'; 

        $_SESSION['filtro_periodo'] = $periodo;

    }

    $periodo_filtro = " AND ca.curso_asignatura_periodo = '2024' ";

}else{

    $_SESSION['filtro_periodo'] = $periodo;

    $periodo_filtro = " AND ca.curso_asignatura_periodo = '$periodo' ";

}



//Filtro periodo de la tabla

$periodo_tabla = $_GET['periodo_tabla'];

if($periodo_tabla == ''){ 
    $periodo_tabla = /*date('Y')*/'2024'; 
    if($_SESSION['filtro_periodo_tabla'] != ''){

        $periodo_tabla = $_SESSION['filtro_periodo_tabla'];

    }else{

        $periodo_tabla = /*date('Y')*/'2024'; 

        $_SESSION['filtro_periodo_tabla'] = $periodo_tabla;

    }

    $periodo_filtro_tabla = " AND ca.curso_asignatura_periodo = '$periodo_tabla' ";

}else{

    $_SESSION['filtro_periodo_tabla'] = $periodo_tabla;

    $periodo_filtro_tabla = " AND ca.curso_asignatura_periodo = '$periodo_tabla' ";

}



//Filtro nivel grafico de barras

$nivel = $_GET['nivel'];

if($nivel == '' || $nivel == '0'){ 

    if($_SESSION['filtro_nivel'] != ''){

        $nivel = $_SESSION['filtro_nivel'];

    }else{

        $nivel = '12'; 

        $_SESSION['filtro_nivel'] = $nivel;

    }

}else{

    $_SESSION['filtro_nivel'] = $nivel;

}



//Filtro periodo del gráfico de pie

$periodo_pie = $_GET['periodo_pie'];

if($periodo_pie == ''){ 

    if($_SESSION['filtro_periodo_pie'] != ''){

        $periodo_pie = $_SESSION['filtro_periodo_pie'];

    }else{

        $periodo_pie = /*date('Y')*/'2024'; 

        $_SESSION['filtro_periodo_pie'] = $periodo_pie;

    }

    $periodo_filtro_pie = " AND ca.curso_asignatura_periodo = '$periodo_pie' ";

}else{

    $_SESSION['filtro_periodo_pie'] = $periodo_pie;

    $periodo_filtro_pie = " AND ca.curso_asignatura_periodo = '$periodo_pie' ";

}



//Filtro asignatura gráfico de barras

$asignatura = $_GET['asignatura'];

if($asignatura == ''){ 

    if($_SESSION['filtro_asignatura'] != '' && $_SESSION['filtro_asignatura'] != '0'){

        $asignatura = $_SESSION['filtro_asignatura'];

        $asignatura_filtro = " AND ca.asignatura_id = '$asignatura' ";

    }else{

        $asignatura_filtro = "";

        $asignatura = '0';

    }

}else if($asignatura == '0'){

    $asignatura_filtro = "";

    $_SESSION['filtro_asignatura'] = $asignatura;

}else{

    $_SESSION['filtro_asignatura'] = $asignatura;

    $asignatura_filtro = " AND ca.asignatura_id = '$asignatura' ";

}



//Filtro asignatura gráfico pie

$asignatura_pie = $_GET['asignatura_pie'];

if($asignatura_pie == ''){ 

    if($_SESSION['filtro_asignatura_pie'] != '' && $_SESSION['filtro_asignatura_pie'] != '0'){

        $asignatura_pie = $_SESSION['filtro_asignatura_pie'];

        $asignatura_filtro_pie = " AND ca.asignatura_id = '$asignatura_pie' ";

    }else{

        $asignatura_filtro_pie = "";

        $asignatura_pie = '0';

    }

}else if($asignatura_pie == '0'){

    $asignatura_filtro_pie = "";

    $_SESSION['filtro_asignatura_pie'] = $asignatura_pie;

}else{

    $_SESSION['filtro_asignatura_pie'] = $asignatura_pie;

    $asignatura_filtro_pie = " AND ca.asignatura_id = '$asignatura_pie' ";

}



$tipo_carga = $_GET['tipo'];

if($tipo_carga == '-1'){ 

    $tipo_filtro = " AND c.carga_aprobacion = '-1' "; 

    $_SESSION['filtro_carga'] = $tipo_carga;

}else if($tipo_carga == '1'){ 

    $tipo_filtro = " AND c.carga_aprobacion = '1' "; 

    $_SESSION['filtro_carga'] = $tipo_carga;

}else if($tipo_carga == '0'){ 

    $tipo_filtro = " AND c.carga_aprobacion = '0' "; 

    $_SESSION['filtro_carga'] = $tipo_carga;

}else if($tipo_carga == ''){ 

    if($_SESSION['filtro_carga'] != ''){

        $tipo_carga = $_SESSION['filtro_carga'];

        $tipo_filtro = " AND c.carga_aprobacion = '$tipo_carga' "; 

    }else{

        $tipo_carga = '2';

        $tipo_filtro = ""; 

    }

}else if($tipo_carga == '2'){

    $tipo_filtro = ""; 

    $_SESSION['filtro_carga'] = $tipo_carga-2;

}



//FILTRO PARA LIMITAR VISTA DE CURSOS A AYUDANTES

$sql_ay = "SELECT *

        FROM ayudantes

        WHERE usuario_id = '$user_id'

        AND ayudante_periodo = '$periodo'";

$rs_ay = mysqli_query($conexion, $sql_ay);

$cant = mysqli_num_rows($rs_ay);

$ay_filtro = "";

$ayudante_filtro = "";

if($cant>0){

    $ay_filtro = ", ayudantes as ay";

    $ayudante_filtro = "AND ca.curso_asignatura_id = ay.curso_asignatura_id

                        AND ay.usuario_id = '$user_id'
                        AND ay.ayudante_estado = '1'";

}else{

    $ay_filtro = "";

    $ayudante_filtro = "";

}



?>

<!DOCTYPE html>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

                        <h1>RESUMEN GENERAL</h1>

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

                        $rs_anual = mysqli_query($conexion, $sql_anual);

                        $row_anual = mysqli_fetch_array($rs_anual);

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

                        $rs_anual_a = mysqli_query($conexion, $sql_anual_a);

                        $row_anual_a = mysqli_fetch_array($rs_anual_a);



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

                        $rs_anual_r = mysqli_query($conexion, $sql_anual_r);

                        $row_anual_r = mysqli_fetch_array($rs_anual_r);

                        ?>

                        <div class="col-lg-6" style="border:1px solid black; height:500px;">

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

                                    <th>CARGAS ANUALES SIN PLANIF. O SIN REVISAR</th><td><?=$diff?></td>

                                </tr>                                

                            </table>

                        </div>

                        <div class="col-lg-6" style="border:1px solid black; height:500px;">

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

                        </div>

                        <div class="col-lg-6" style="border:1px solid black;height:500px;">

                            <div class="filtros4">

                                <form>

                                    <label>Nivel</label>

                                    <select name="nivel" class="minimal">

                                        <option value="0">Seleccionar</option>

                                        <?php 

                                        $sql_nivel = "SELECT * FROM cursos_asignaturas,niveles

                                        WHERE cursos_asignaturas.nivel_id = niveles.nivel_id

                                        GROUP BY nivel_nombre";

                                        $rs_nivel = mysqli_query($conexion, $sql_nivel);

                                        while ($row_nivel = mysqli_fetch_array($rs_nivel)) {

                                            echo "<option value=".$row_nivel['nivel_id'].">".$row_nivel['nivel_nombre']."</option>";

                                        }

                                        ?>

                                    </select><br><br><br>

                                    <label>Asignatura</label>

                                    <select name="asignatura" class="minimal">

                                        <option value="0">Seleccionar</option>

                                        <?php 

                                        $sql_asig = "SELECT * FROM asignaturas

                                        GROUP BY asignatura_nombre";

                                        $rs_asig = mysqli_query($conexion, $sql_asig);

                                        while ($row_asig = mysqli_fetch_array($rs_asig)) {

                                            echo "<option value=".$row_asig['asignatura_id'].">".$row_asig['asignatura_nombre']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <br><br><br>

                                    <label>Año</label>

                                    <select name="periodo" class="minimal">

                                        <?php 

                                        $sql_anos = "SELECT * FROM periodos WHERE periodo_estado = '1'";

                                        $rs_anos = mysqli_query($conexion, $sql_anos);

                                        while ($row_anos = mysqli_fetch_array($rs_anos)) {

                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <input type="hidden" name="periodo_tabla" value="<?=$periodo_tabla?>">

                                    <input type="hidden" name="periodo_pie" value="<?=$periodo_pie?>">

                                    <input type="hidden" name="docente" value="<?=$docente?>">

                                    <input type="hidden" name="asignatura_pie" value="<?=$asignatura_pie?>">

                                    <input type="hidden" name="tipo" value="<?=$tipo_carga?>">

                                    <input type="submit" value="filtrar">

                                </form>    

                            </div>

                            <canvas id="graphCanvas"></canvas>

                            <script>

                                $(document).ready(function () {

                                    showGraph();

                                });





                                function showGraph()

                                {

                                    {

                                        //Envío de datos hacia otra ventana para realizar las consultas correspondientes

                                        $.post("getBarChart.php?p=<?=$periodo?>&n=<?=$nivel?>&a=<?=$asignatura?>",

                                        function (data)

                                        {

                                            console.log(data);

                                            var name = [];

                                            var marks = [];

                                            var marks2 = [];



                                            for (var i in data) {

                                                name.push(data[i]['todo']);

                                                marks.push(data[i]['carga']);

                                                marks2.push(data[i]['cargarest']);

                                            }

                                            var chartdata = {

                                                labels: name,

                                                datasets: [

                                                    {

                                                        label: 'Cargas subidas',

                                                        backgroundColor: '#000000',

                                                        borderColor: '#46d5f1',

                                                        hoverBackgroundColor: '#CCCCCC',

                                                        hoverBorderColor: '#666666',

                                                        data: marks

                                                    },

                                                    {

                                                        label: 'Cargas Restantes',

                                                        backgroundColor: '#aaaaaa',

                                                        borderColor: '#46d5f1',

                                                        hoverBackgroundColor: '#CCCCCC',

                                                        hoverBorderColor: '#666666',

                                                        data: marks2

                                                    }

                                                ]

                                            };



                                            var graphTarget = $("#graphCanvas");



                                            var barGraph = new Chart(graphTarget, {

                                                type: 'bar',

                                                data: chartdata

                                                ,

                                                options: {

                                                    scales: {

                                                        xAxes: [{ 

                                                            stacked: true 

                                                            }],

                                                        yAxes: [{

                                                            scaleLabel: {

                                                                display: true,

                                                                labelString: 'Total Cargas'

                                                            },

                                                            stacked: true,

                                                            ticks: {

                                                                min: 0,

                                                                suggestedMax: 10

                                                            }

                                                        }]

                                                    }

                                                }

                                            });

                                        });

                                    }

                                }

                                </script>

                        </div>

                        <div class="col-lg-6" style="border:1px solid black; height:500px;">

                        <div class="filtros4">

                                <form>

                                    <label>Asignatura</label>

                                    <select name="asignatura_pie" class="minimal">

                                        <option value="0">TODOS</option>

                                        <?php 

                                        $sql_asig = "SELECT * FROM asignaturas

                                        GROUP BY asignatura_nombre";

                                        $rs_asig = mysqli_query($conexion, $sql_asig);

                                        while ($row_asig = mysqli_fetch_array($rs_asig)) {

                                            echo "<option value=".$row_asig['asignatura_id'].">".$row_asig['asignatura_nombre']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <br><br><br>

                                    <label>Año</label>

                                    <select name="periodo_pie" class="minimal">

                                        <?php 

                                        $sql_anos = "SELECT * FROM periodos WHERE periodo_estado = '1'";

                                        $rs_anos = mysqli_query($conexion, $sql_anos);

                                        while ($row_anos = mysqli_fetch_array($rs_anos)) {

                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <input type="hidden" name="periodo_tabla" value="<?=$periodo_tabla?>">

                                    <input type="hidden" name="periodo" value="<?=$periodo?>">

                                    <input type="hidden" name="docente" value="<?=$docente?>">

                                    <input type="hidden" name="asignatura" value="<?=$asignatura?>">

                                    <input type="hidden" name="tipo" value="<?=$tipo_carga?>">

                                    <input type="hidden" name="nivel" value="<?=$nivel?>">

                                    <input type="submit" value="filtrar">

                                </form>    

                            </div>

                            <?php 



                            //total cargas subidas sin revisar

                            $sql_pie = "SELECT count(carga_id) as total_anual 

                                        FROM cargas as c,

                                            cursos_asignaturas as ca,

                                            periodos as p

                                        WHERE tipo_carga_id = '2' 

                                        AND ca.profesor_id <> '0'

                                        AND p.periodo_periodo = ca.curso_asignatura_periodo

                                        AND ca.curso_asignatura_id = c.curso_asignatura_id

                                        AND c.carga_estado = '1'

                                        AND ca.curso_asignatura_estado = '1'

                                        AND c.carga_aprobacion = '0'

                                        AND c.carga_archivo != ''

                                        $periodo_filtro_pie

                                        $asignatura_filtro_pie

                                        ";

                            $rs_pie = mysqli_query($conexion, $sql_pie);

                            $row_pie = mysqli_fetch_array($rs_pie);

                            

                            //cargas faltantes por subir

                            /* $sql_pie_f = "SELECT count(carga_id) as total_anual_f

                                        FROM cargas as c,

                                            cursos_asignaturas as ca,

                                            periodos as p

                                        WHERE tipo_carga_id = '2' 

                                        AND ca.profesor_id <> '0'

                                        AND p.periodo_periodo = ca.curso_asignatura_periodo

                                        AND ca.curso_asignatura_id = c.curso_asignatura_id

                                        AND c.carga_estado = '1'

                                        AND ca.curso_asignatura_estado = '1'

                                        $periodo_filtro_pie

                                        $asignatura_filtro_pie

                                        "; */



                            //total de cursos 

                            $sql_pie_f = "SELECT COUNT(curso_asignatura_id) as total_anual_f

                            FROM cursos_asignaturas as ca

                            WHERE curso_asignatura_estado = '1'

                            $periodo_filtro_pie

                            $asignatura_filtro_pie

                            ";

                            $rs_pie_f = mysqli_query($conexion, $sql_pie_f);

                            $row_pie_f = mysqli_fetch_array($rs_pie_f);



                            //cargas aprobadas

                            $sql_pie_a = "SELECT count(carga_id) as total_aceptada 

                                        FROM cargas as c,

                                            cursos_asignaturas as ca,

                                            periodos as p

                                        WHERE tipo_carga_id = '2' 

                                        AND ca.profesor_id <> '0'

                                        AND p.periodo_periodo = ca.curso_asignatura_periodo

                                        AND ca.curso_asignatura_id = c.curso_asignatura_id

                                        AND c.carga_estado = '1'

                                        AND ca.curso_asignatura_estado = '1'

                                        AND c.carga_archivo != ''

                                        AND c.carga_aprobacion = '1'

                                        $periodo_filtro_pie

                                        $asignatura_filtro_pie

                                        ";

                            $rs_pie_a = mysqli_query($conexion, $sql_pie_a);

                            $row_pie_a = mysqli_fetch_array($rs_pie_a);

                            

                            //cargas rechazadas

                            $sql_pie_r = "SELECT count(carga_id) as total_rechazada

                                        FROM cargas as c,

                                            cursos_asignaturas as ca,

                                            periodos as p

                                        WHERE tipo_carga_id = '2' 

                                        AND ca.profesor_id <> '0'

                                        AND p.periodo_periodo = ca.curso_asignatura_periodo

                                        AND ca.curso_asignatura_id = c.curso_asignatura_id

                                        AND c.carga_estado = '1'

                                        AND ca.curso_asignatura_estado = '1'

                                        AND c.carga_archivo = ''

                                        AND c.carga_aprobacion= '-1'

                                        $periodo_filtro_pie

                                        $asignatura_filtro_pie

                                        ";

                            $rs_pie_r = mysqli_query($conexion, $sql_pie_r);

                            $row_pie_r = mysqli_fetch_array($rs_pie_r);



                            $diff2 = $row_pie_f['total_anual_f']-$row_pie_a['total_aceptada']-$row_pie['total_anual']-$row_pie_r['total_rechazada'];

                            ?>

                            <canvas id="pie-chart1" width="800" height="450"></canvas>

                            <script type="text/javascript">

                                new Chart(document.getElementById("pie-chart1"), {

                                type: 'pie',

                                data: {

                                  labels: ["Sin revisar", "Aprobadas","Rechazadas" , "Faltantes por subir"],

                                  datasets: [{

                                    label: "Cantidad en unidades",

                                    backgroundColor: ["#aaa","rgb(46, 204, 113)","rgb(207, 0, 15)","#000"],

                                    data: [<?=$row_pie['total_anual']?>,<?=$row_pie_a['total_aceptada']?>,

                                    <?=$row_pie_r['total_rechazada']?>, <?=$diff2?> ]

                                  }]

                                },

                                options: {

                                  title: {

                                    display: true,

                                    text: 'Resumen Planificaciones Anuales Por Asignatura'

                                  }

                                }

                            });



                            </script>

                        </div>

                        <div class="col-lg-12">

                            <h1>ESTADO PLANIFICACIONES ANUALES</h1>

                            <?php

                            if($_SESSION['perfil'] == '1'){

                            ?>

                            <a href="download_avance_media.php" class="btn btn-default btn-lg active" role="button" style="text-align:right;float:right;">Avance Planificaciones Media</a>

                            <a href="download_avance_basica.php" class="btn btn-default btn-lg active" role="button" style="text-align:right;float:right;margin-right:5px">Avance Planificaciones Básica</a><br>

                            <?php

                            }

                            ?>

                            <div class="filtros4">

                                <form method="GET">

                                    <label>Estado Planif.</label>

                                    <select name="tipo" class="minimal">

                                        <option value="1" <?php if($tipo_carga == '1'){ echo ' selected '; } ?>>Aceptadas</option>

                                        <option value="-1" <?php if($tipo_carga == '-1'){ echo ' selected '; } ?>>Rechazadas</option>

                                        <option value="0" <?php if($tipo_carga == '0'){ echo ' selected '; } ?>>Sin Carga</option>

                                        <option value="2" <?php if($tipo_carga == '2'){ echo ' selected '; } ?>>Todas</option>

                                    </select>

                                    <label>Año</label>

                                    <select name="periodo_tabla" class="minimal">

                                        <?php 

                                        $sql_anos = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo_tabla' DESC, periodo_periodo ASC";

                                        $rs_anos = mysqli_query($conexion, $sql_anos);

                                        while ($row_anos = mysqli_fetch_array($rs_anos)) {

                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <label>Profesor</label>

                                    <select name="docente" class="minimal">

                                        <?php 

                                        if($docente == '0'){ echo "<option value='0'>TODOS</option>"; }

                                        $sql_profe = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0' ORDER BY profesor_id = '$docente' DESC, profesor_apellidos, profesor_nombres ASC";

                                        $rs_profe = mysqli_query($conexion, $sql_profe);

                                        while ($row_profe = mysqli_fetch_array($rs_profe)) {

                                            echo "<option value=".$row_profe['profesor_id'].">".$row_profe['profesor_apellidos']." ".$row_profe['profesor_nombres']."</option>";

                                        }

                                        if($docente != '0'){ echo "<option value='0'>TODOS</option>"; }

                                        ?>

                                    </select>

                                    <input type="hidden" name="periodo" value="<?=$periodo?>">

                                    <input type="hidden" name="periodo_pie" value="<?=$periodo_pie?>">

                                    <input type="hidden" name="nivel" value="<?=$nivel?>">

                                    <input type="hidden" name="asignatura_pie" value="<?=$asignatura_pie?>">

                                    <input type="hidden" name="asignatura" value="<?=$asignatura?>">

                                    <input type="submit" value="filtrar">

                                </form>

                                

                            </div>

                            <table class="header2" style="width: 100% !important; margin: 10px 0px !important; ">

                                <tr>

                                    <th>Profesor</th>

                                    <th>Curso</th>

                                    <th>Asignatura</th>

                                    <th>Avance Mensual</th>

                                    <th>Avance Anual</th>

                                    <th>Nivel</th>

                                    <th>Estado</th>

                                    <th>Ver</th>

                                    <th>Ir</th>

                                    <?php

                                    if($_SESSION['perfil'] == '1'){

                                    ?>

                                    <th>Borrar Archivo</th>

                                    <th>Enviar Avance</th>

                                    <?php

                                    }

                                    ?>

                                    

                                </tr>

                                <?php 

                                //Consulta para desplegar lista de cursos

                                $sql_prof = "SELECT DISTINCT * 

                                FROM asignaturas as a,

                                     cursos_asignaturas as ca,

                                     letras as l,

                                     niveles as n,

                                     profesores as p,

                                     dificultades as d,

                                     cargas as c

                                     $ay_filtro

                                WHERE a.asignatura_estado = '1'

                                AND ca.asignatura_id = a.asignatura_id

                                AND n.nivel_id = ca.nivel_id

                                AND c.curso_asignatura_id = ca.curso_asignatura_id

                                AND l.letra_id = ca.letra_id

                                AND ca.dificultad_id = d.dificultad_id

                                AND ca.profesor_id = p.profesor_id

                                AND c.tipo_carga_id = '2'

                                AND ca.curso_asignatura_estado = '1'

                                $periodo_filtro_tabla

                                $tipo_filtro

                                $docente_filtro

                                $ayudante_filtro

                                ORDER BY  p.profesor_apellidos, p.profesor_nombres, ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";

                                //print $sql_prof;

                                $rs_prof = mysqli_query($conexion, $sql_prof);

                                $contador = '0';

                                while($row_prof = mysqli_fetch_array($rs_prof)){
                                    
                                    $path = $row_prof['carga_archivo'];
                                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                                    $cargaanual = "ANUAL ".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."-".$row_prof['asignatura_nombre'].".".$extension;

                                    $asig = $row_prof['asignatura_id'];

                                    $niv = $row_prof['nivel_id'];

                                    $let = $row_prof['letra_id'];

                                    $dif = $row_prof['dificultad_id'];

                                    



                                    //Consulta para saber cantidad de cargas mensuales rechazadas

                                    $sql_avance_r = "SELECT COUNT(carga_archivo)

                                    FROM cargas as c 

                                    INNER JOIN cursos_asignaturas as ca on c.curso_asignatura_id = ca.curso_asignatura_id 

                                    WHERE ca.nivel_id = '$niv' 

                                    AND ca.asignatura_id = '$asig' 

                                    AND c.tipo_carga_id = '1'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_archivo = '' 

                                    AND c.carga_aprobacion = '-1'

                                    $periodo_filtro_tabla";



                                    $rs_avance_r = mysqli_query($conexion, $sql_avance_r);

                                    $row_avance_r = mysqli_fetch_array($rs_avance_r);



                                    //Consulta para saber cantidad de cargas mensuales aprobadas

                                    $sql_avance = "SELECT COUNT(carga_archivo)

                                    FROM cargas as c 

                                    INNER JOIN cursos_asignaturas as ca on c.curso_asignatura_id = ca.curso_asignatura_id 

                                    WHERE ca.nivel_id = '$niv' 

                                    AND ca.asignatura_id = '$asig' 

                                    AND c.tipo_carga_id = '1'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_archivo != '' 

                                    AND c.carga_aprobacion = '1'

                                    $periodo_filtro_tabla";



                                    $rs_avance = mysqli_query($conexion, $sql_avance);

                                    $row_avance = mysqli_fetch_array($rs_avance);



                                    //Consulta para saber cuantas cargas mensuales han sido subidas

                                    $sql_avance_sa = "SELECT COUNT(carga_archivo)

                                    FROM cargas as c 

                                    INNER JOIN cursos_asignaturas as ca on c.curso_asignatura_id = ca.curso_asignatura_id 

                                    WHERE ca.nivel_id = '$niv' 

                                    AND ca.asignatura_id = '$asig' 

                                    AND c.tipo_carga_id = '1'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_archivo != '' 

                                    AND c.carga_aprobacion = '0'

                                    $periodo_filtro_tabla";

                                    

                                    $rs_avance_sa = mysqli_query($conexion, $sql_avance_sa);

                                    $row_avance_sa = mysqli_fetch_array($rs_avance_sa);



                                    //Consulta para saber el total de cargas mensuales que deberian ser subidas

                                    $sql_avance2 = "SELECT curso_asignatura_unidades

                                    FROM cursos_asignaturas as ca

                                    WHERE ca.asignatura_id = '$asig'

                                    AND ca.nivel_id = '$niv'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    $periodo_filtro_tabla";

                                    $rs_avance2 = mysqli_query($conexion, $sql_avance2);

                                    $row_avance2 = mysqli_fetch_array($rs_avance2);



                                    //Consulta para saber si la carga anual fue aprobada

                                    $sql_avance_a = "SELECT count(carga_id) as carga_anual

                                    FROM cargas as c,

                                        cursos_asignaturas as ca,

                                        periodos as p

                                    WHERE tipo_carga_id = '2' 

                                    AND ca.profesor_id <> '0'

                                    AND p.periodo_periodo = ca.curso_asignatura_periodo

                                    AND ca.curso_asignatura_id = c.curso_asignatura_id

                                    AND c.carga_estado = '1'

                                    AND ca.curso_asignatura_estado = '1'

                                    AND ca.asignatura_id = '$asig'

                                    AND ca.nivel_id = '$niv'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_aprobacion = '1'

                                    $periodo_filtro_tabla";



                                    $rs_avance_a = mysqli_query($conexion, $sql_avance_a);

                                    $row_avance_a = mysqli_fetch_array($rs_avance_a);

                                    

                                    //Consulta para saber si la carga anual esta subida

                                    $sql_avance_a2 = "SELECT count(carga_id) as carga_anual

                                    FROM cargas as c,

                                        cursos_asignaturas as ca,

                                        periodos as p

                                    WHERE tipo_carga_id = '2' 

                                    AND ca.profesor_id <> '0'

                                    AND p.periodo_periodo = ca.curso_asignatura_periodo

                                    AND ca.curso_asignatura_id = c.curso_asignatura_id

                                    AND c.carga_estado = '1'

                                    AND ca.curso_asignatura_estado = '1'

                                    AND ca.asignatura_id = '$asig'

                                    AND ca.nivel_id = '$niv'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_aprobacion = '0'

                                    AND (c.carga_archivo <> null OR c.carga_archivo <> '')

                                    $periodo_filtro_tabla";



                                    $rs_avance_a2 = mysqli_query($conexion, $sql_avance_a2);

                                    $row_avance_a2 = mysqli_fetch_array($rs_avance_a2);



                                    //Consulta para saber si la carga anual fue rechazada

                                    $sql_avance_ra = "SELECT count(carga_id) as carga_anual

                                    FROM cargas as c,

                                        cursos_asignaturas as ca,

                                        periodos as p

                                    WHERE tipo_carga_id = '2' 

                                    AND ca.profesor_id <> '0'

                                    AND p.periodo_periodo = ca.curso_asignatura_periodo

                                    AND ca.curso_asignatura_id = c.curso_asignatura_id

                                    AND c.carga_estado = '1'

                                    AND ca.curso_asignatura_estado = '1'

                                    AND ca.asignatura_id = '$asig'

                                    AND ca.nivel_id = '$niv'

                                    AND ca.letra_id = '$let'

                                    AND ca.dificultad_id = '$dif'

                                    AND c.carga_aprobacion = '-1'

                                    $periodo_filtro_tabla";



                                    $rs_avance_ra = mysqli_query($conexion, $sql_avance_ra);

                                    $row_avance_ra = mysqli_fetch_array($rs_avance_ra);



                                    $total_subidas_mensual = $row_avance_sa[0]+$row_avance[0];

                                    $total_subidas_anual = $row_avance_a[0]+$row_avance_a2[0];

                                    

                                    echo "<tr>                                                          

                                            <td>".$row_prof['profesor_apellidos']." ".$row_prof['profesor_nombres']."</td>

                                            <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>

                                            <td>".$row_prof['asignatura_nombre']."</td>  

                                            <td>".$total_subidas_mensual."/".$row_avance2[0]." (<font color='green'><b> ".$row_avance[0]."</b></font>,<font color='red'><b> ".$row_avance_r[0]."</b></font>)"."</td>

                                            <td>".$total_subidas_anual."/1"." (<font color='green'><b> ".$row_avance_a[0]."</b></font>,<font color='red'><b> ".$row_avance_ra[0]."</b></font>)"."</td>

                                            <td>".$row_prof['dificultad_nombre']."</td>";

                                    if($row_prof['carga_aprobacion'] == '-1'){

                                        echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></td>";             

                                    }

                                    if($row_prof['carga_aprobacion'] == '0'){

                                        echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";

                                    }

                                    if($row_prof['carga_aprobacion'] == '1'){

                                        echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";

                                    }



                                    if($row_prof['carga_archivo'] == '')

                                    {

                                      echo "<td><i class='fas fa-minus-circle fa-lg' title='No disponible'></i></td>";

                                    }

                                    else

                                    {
                                    	if(is_file('../profesor-sc/'.$row_prof['carga_archivo'])) { ?>
										  <td><a download="<?=$cargaanual?>" href="../profesor-sc/<?=$row_prof['carga_archivo']?>"><i class="fas fa-search fa-lg"></i></a></td>
									<?php	} else { ?>
										  <td>archivo corrupto</td> 
									<?php	}
                                        ?>       

                                    

                                    <?php } ?> 

                                    <td><a href="carga.php?id=<?=$row_prof['curso_asignatura_id']?>"><i class="fas fa-search-plus fa-lg"></i></a></td>  

                                    <?php

                                    if($_SESSION['perfil'] == '1'){

                                    ?>

                                    <td><a href="eliminar_archivo_carga.php?id=<?=$row_prof['carga_id']?>" onclick = "javascript: return confirm('¿Desea Eliminar Este Archivo?');"><i class="fas fa-times fa-lg"></i></a></td>        

                                    <td><a><i class="fas fa-paper-plane" id='open<?=$row_prof['curso_asignatura_id']?>'></i></a></td>

                                    <input type="hidden" name="id_profesor<?=$row_prof['profesor_id']?>" id="id_profesor<?=$row_prof['profesor_id']?>" value="<?=$row_prof['profesor_id']?>">

                                    <input type="hidden" name="periodo<?=$row_prof['curso_asignatura_periodo']?>" id="periodo<?=$row_prof['curso_asignatura_id']?>" value="<?=$row_prof['curso_asignatura_periodo']?>">

                                    <script>

										$(document).ready(function(){

										    $('#open<?=$row_prof['curso_asignatura_id']?>').on('click', function(){

										        $('#popup').fadeIn('slow');

										        $('.popup-overlay').fadeIn('slow');

										        $('.popup-overlay').height($(window).height());

										        //$("#anchor")[0].click();

										        var id = $('#id_profesor<?=$row_prof['profesor_id']?>').attr('value');

										        document.getElementById('id').value = id;

                                                var periodo = $('#periodo<?=$row_prof['curso_asignatura_id']?>').attr('value');

										        document.getElementById('periodo').value = periodo;

										        return false;

										    });

										 

										    $('#close').on('click', function(){

										        $('#popup').fadeOut('slow');

										        $('.popup-overlay').fadeOut('slow');

										        return false;

										    });

										});

									</script>

                                    <?php

                                    }

                                    ?>

                                          </tr>

                                <?php

                                    $dificultad = '';

                                    $contador++;

                                }

                                if($contador == '0'){ echo "<td colspan='11'>No hay resultados para los filtros escogidos</td>"; }

                                ?>

                            </table>

                        </div>

                    </div>

                </div>
                <?php 
                //if($_GET['sql'] == '1'){ echo $sql_prof; }
                ?>
            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

    <div class="popup-contenedor" style="display: none;" id="popup">

        <div class="popup-form">

            <form method="post" action="enviar_estado_avance.php">

                <a href="" id="close"><i class="far fa-times-circle"></i></a><br>

                <!-- <label>Asunto</label>

                    <input type="text" id="asunto" name="asunto"  style="border-radius: 20px;border: 1px solid;" onkeyup="reemplazar(this);"><br><br><br> -->

                <label>Observación Planificación</label>

                    <textarea placeholder="Ingrese Observación" name="observacion" onkeyup="reemplazar(this);"></textarea>

                <input type="hidden" name="id" id="id">

                <input type="hidden" name="periodo" id="periodo">

                <input type="hidden" name="encargado" value="<?=$encargado?>">

                <input type="submit" value="Enviar">

            </form>

        </div>

    </div>

</body>

</html>

