<?php 

$perfil_archivo = 1;//adm = 1 , docente = 2;

include('../funciones-sc/conexion.php');

session_start();

if ($_SESSION['perfil'] != 1){

	echo "<script LANGUAGE='JavaScript'>

				  window.alert('Acceso no autorizado');

				  window.location= 'listado_cursos.php'

	  </script>";

	  die();

  }

function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

$periodo = $_GET['periodo'];

if($periodo == ''){ $periodo = /*date('Y')*/2024; }

$periodo_filtro = " ayudantes.ayudante_periodo = '$periodo' ";

$coord = $_GET['coord'];

if($coord == ''){ $coord_filtro = ' '; }
else{ $coord_filtro = " AND ayudantes.profesor_ayudante_id = '$coord' "; }



$docente = $_GET['docente'];

if($docente == '' || $docente == '0'){ $docente_filtro = ""; $docente = '0';}

else{ $docente_filtro = " AND pjr.profesor_id = ".$docente." "; }



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

                        <div class="col-lg-12">

                            <h1 style="text-align:left;float:left;">LISTADO COORDINADORES</h1> 

                            <a href="asignar_ayudante.php" class="btn btn-default btn-lg active" role="button" style="text-align:right;float:right;margin-right:5px">Asignar Coordinador</a>

                            <div class="filtros4">

                                <form method="GET">

                                    <label>Año</label>

                                    <select name="periodo" class="minimal">

                                        <?php 

                                        $sql_anos = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo' DESC, periodo_periodo ASC";

                                        $rs_anos = mysqli_query($conexion, $sql_anos);

                                        while ($row_anos = mysqli_fetch_array($rs_anos)) {

                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";

                                        }

                                        ?>

                                    </select>

                                    <label>Coordinador</label>

                                    <select name="coord" class="minimal">

                                        <?php 
                                        if($coord == ''){ echo "<option value=''>Todos</option>"; }
                                        $sql_anos = "SELECT * FROM ayudantes as a, usuarios as u WHERE ayudante_periodo = '$periodo' AND u.usuario_id = a.usuario_id GROUP BY profesor_ayudante_id ORDER BY profesor_ayudante_id = '$coord' DESC, usuario_nombres, usuario_apellidos ASC";

                                        $rs_anos = mysqli_query($conexion, $sql_anos);

                                        while ($row_anos = mysqli_fetch_array($rs_anos)) {

                                            echo "<option value='".$row_anos['profesor_ayudante_id']."'>".$row_anos['usuario_nombres']." ".$row_anos['usuario_apellidos']."</option>";

                                        }
                                        if($coord != ''){ echo "<option value=''>Todos</option>"; }
                                        ?>

                                    </select>

                                    <input type="submit" value="filtrar">

                                </form>

                                

                            </div>

                            <!-- <table class="header2" style="width: 100% !important; margin: 10px 0px !important; "> -->

                            <table class="header2">

                                <tr>

                                    <th>Curso</th>
                                    <th>Asignatura</th>
                                    <th>Coordinador</th>

                                    <th>Periodo</th>

                                    <th>Modificar</th>

                                    <th>Eliminar</th>

                                </tr>

                                <?php 

                                $sql_prof = "SELECT DISTINCT *

                                FROM ayudantes

                                INNER JOIN cursos_asignaturas on cursos_asignaturas.curso_asignatura_id = ayudantes.curso_asignatura_id

                                INNER JOIN niveles as n on n.nivel_id = cursos_asignaturas.nivel_id

                                INNER JOIN letras as l on l.letra_id = cursos_asignaturas.letra_id

                                INNER JOIN usuarios on ayudantes.usuario_id = usuarios.usuario_id

                                INNER JOIN asignaturas on asignaturas.asignatura_id = cursos_asignaturas.asignatura_id

                                WHERE

                                $periodo_filtro
                                $coord_filtro
                                AND ayudantes.ayudante_estado = '1'
                                /*AND ayudantes.profesor_ayudante_id = '40'*/
                                /*GROUP BY cursos_asignaturas.curso_asignatura_id*/
                                ORDER BY usuarios.usuario_nombres, usuarios.usuario_apellidos, asignaturas.asignatura_nombre, n.nivel_nombre  ASC
                                ";

                                $rs_prof = mysqli_query($conexion, $sql_prof);

                                $contador = '0';

                                while($row_prof = mysqli_fetch_array($rs_prof)){                         

                                    echo "<tr>

                                    <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>
                                    <td>".$row_prof['asignatura_nombre']."</td>
                                    <td>".$row_prof['usuario_nombres']." ".$row_prof['usuario_apellidos']."</td>

                                    <td>".$row_prof['ayudante_periodo']."</td>";

                                    ?>

                                    <td><a href='ayudante_modificar.php?id=<?=$row_prof['ayudante_id']?>'><i class='fas fa-pencil-alt fa-lg'></i></a></td>

                                    <td><a href='ayudante_eliminar.php?id=<?=$row_prof['ayudante_id']?>&periodo=<?=$periodo?>' onclick ="javascript: return confirm('Desea Eliminar Este Curso/Coordinador?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                                    <?php

                        		  echo "</tr>";

                                    $dificultad = '';

                                    $contador++;

                                }
                                //print $contador;
                                if($contador == '0'){ echo "<td colspan='8'>No hay resultados para los filtros escogidos</td>"; }

                                ?>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>



    </div>

</body>

</html>

