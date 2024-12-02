<?php 

$perfil_archivo = 2;//adm = 1 , docente = 2;

include('../funciones-sc/conexion.php');



$profe_data = $_GET['profesor'];

$estado_data = $_GET['estado'];

$mes_data = $_GET['mes'];

if($profe_data == ''){ $profe_filtro = ''; }

else{ $profe_filtro = " AND (profesor_nombres LIKE '%".$profe_data."%' OR profesor_apellidos LIKE '%".$profe_data."%' OR profesor_rut LIKE '%".$profe_data."%') "; }

if($mes_data == '' || $mes_data == '0'){ $mes_filtro = ''; }

else{ $mes_filtro = " AND month(ev.evaluacion_fecha) = ".$mes_data." "; }

if($estado_data == ''){ $estado_filtro = ''; }

else{ $estado_filtro = " AND ev.evaluacion_estado_id = ".$estado_data." "; }

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Sistema Clases</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

	<link 

	href="../css-sc/iphone.css"

	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

	rel="stylesheet">

	<?php 

		include('../fonts/fonts.php'); 

		include('../js-sc/bootstrap.php'); 

	?>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

<!------ Include the above in your HEAD tag ---------->



    <div id="wrapper">

        <div class="overlay"></div>

    

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

                    <div class="col-lg-12">

                        <h1>Evaluaciones a imprimir</h1>

                        <div class="filtros4">

                            <form method="GET">

                                <label>Profesor</label>

                                <input type="text" name="profesor" placeholder="RUT/NOMBRE" onkeyup="reemplazar(this);" value="<?=$profe_data?>">

                                <label>Estados</label>

                                <select name="estado" class="minimal">



                                    <?php 

                                    if($estado_data == ''){ echo '<option value="">Seleccione Estado</option>'; }

                                    $sql_est = "SELECT * FROM evaluaciones_estado WHERE evaluacion_estado_estado = '1' ORDER BY evaluacion_estado_id = '$estado_data' DESC, evaluacion_estado_id ASC";

                                    $rs_est = mysqli_query($conexion, $sql_est);

                                    while($row_est = mysqli_fetch_array($rs_est))

                                    {

                                        echo "<option value='".$row_est['evaluacion_estado_id']."'>".$row_est['evaluacion_estado_nombre']."</option>";

                                    }

                                    ?>

                                </select>
                                <label>Estados</label>

                                <select name="mes" class="minimal">



                                    <?php 

                                    if($mes_data == '' || $mes_data == '0'){ $selected_0 = ' selected '; }else{ $selected_0 = ''; }

                                    $sql_mes = "SELECT * FROM meses ";

                                    $rs_mes = mysqli_query($conexion, $sql_mes);
                                    echo '<option value="0" $selected_0>Todos los meses</option>';
                                    while($row_mes = mysqli_fetch_array($rs_mes))

                                    {
                                        if($mes_data == $row_mes['mes_id']){ $selected = ' selected '; }else{ $selected = ''; }
                                        echo "<option value='".$row_mes['mes_id']."' $selected>".$row_mes['mes_nombre']."</option>";

                                    }

                                    ?>

                                </select>
                                <input type="submit" value="FILTRAR">

                            </form>

                        </div>

                        <table class="header2">

                        <tr>

                            <th>Profesor</th>

                            <th>Fecha Entrega</th>

                            <th>Copias</th>

                            <th>Curso</th>

                            <th>Asignatura</th>

                            <th>Tipo Ev.</th>                            

                            <th>Archivo</th>

                            <th>Estado</th>

                        </tr>

                        <?php 

                        $sql_ev = "SELECT * 

                                   FROM evaluaciones as ev,
                                        cursos_asignaturas as ca,
                                        asignaturas as a,
                                        letras as l,
                                        niveles as n,
                                        tipos_evaluaciones as te,
                                        profesores as p,
                                        evaluaciones_estado as ee,
                                        periodos as pe 
                                   WHERE ev.evaluacion_estado = '1'
                                   AND ev.evaluacion_aprobacion = '1'
                                   AND ev.curso_asignatura_id = ca.curso_asignatura_id
                                   AND a.asignatura_id = ca.asignatura_id
                                   AND l.letra_id = ca.letra_id
                                   AND n.nivel_id = ca.nivel_id
                                   AND pe.periodo_periodo = ca.curso_asignatura_periodo
                                   AND pe.periodo_activo = '1'
                                   AND te.tipo_evaluacion_id = ev.tipo_evaluacion_id
                                   AND p.profesor_id = ca.profesor_id
                                   AND ee.evaluacion_estado_id = ev.evaluacion_estado_id
                                   $profe_filtro
                                   $estado_filtro
                                   $mes_filtro
                                   ORDER BY evaluacion_fecha ASC";

                        $rs_ev = mysqli_query($conexion, $sql_ev);

                        while($row_ev = mysqli_fetch_array($rs_ev))

                        {

                            echo "<tr>

                                    <td>".$row_ev['profesor_nombres']." ".$row_ev['profesor_apellidos']."</td>

                                    <td>".$row_ev['evaluacion_fecha']."</td>

                                    <td>".$row_ev['evaluacion_copia']."</td>

                                    <td>".$row_ev['nivel_nombre']."-".$row_ev['letra_nombre']."</td>

                                    <td>".$row_ev['asignatura_nombre']."</td>

                                    <td>".$row_ev['tipo_evaluacion_nombre']."</td>

                                    ";

                        ?>

                                    <td><a href="../profesor-sc/<?=$row_ev['evaluacion_archivo']?>" ><i class='fas fa-search fa-lg' id='open<?=$row_ev['evaluacion_id']?>'></i></a></td>

                                    <input type="hidden" name="id_curso<?=$row_ev['evaluacion_id']?>" id="id_curso<?=$row_ev['evaluacion_id']?>" value="<?=$row_ev['evaluacion_id']?>">

                                        <script>

                                        $(document).ready(function(){

                                            $('#open<?=$row_ev['evaluacion_id']?>').on('click', function(){

                                                $('#popup').fadeIn('slow');

                                                $('.popup-overlay').fadeIn('slow');

                                                $('.popup-overlay').height($(window).height());

                                                if(<?=$row_ev['evaluacion_estado_id']?> == '1')

                                                {

                                                window.location.href = "../profesor-sc/<?=$row_ev['evaluacion_archivo']?>";

                                                }

                                                var id = $('#id_curso<?=$row_ev['evaluacion_id']?>').attr('value');

                                                document.getElementById('id').value = id;

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

                               

                                        echo "<td>".$row_ev['evaluacion_estado_nombre']."</td>

                                        </tr>";

                        }

                        ?>

                        </table>                   

                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

<div class="popup-contenedor" style="display: none;" id="popup">

    <div class="popup-form">

        <form method="post" action="docencia_evaluacion_aprobar.php">

            <a href="" id="close"><i class="far fa-times-circle"></i></a>

            <h1>Seleccione Nuevo Estado</h1>

            <?php 

            $sql_estado = "SELECT * FROM evaluaciones_estado WHERE evaluacion_estado_estado = '1'";

            $rs_estado = mysqli_query($conexion, $sql_estado);

            while($row_estado = mysqli_fetch_array($rs_estado))

            {

                echo "<label>".$row_estado['evaluacion_estado_nombre']."</label>

                      <input type='radio' name='decision' value='".$row_estado['evaluacion_estado_id']."'>";

            }

            ?>

            

            <input type="hidden" name="id" id="id">

            <input type="hidden" name="cur_asig" value="<?=$cur_asig?>">

            <input type="submit" value="Enviar">

        </form>

    </div>

</div>

</body>

</html>