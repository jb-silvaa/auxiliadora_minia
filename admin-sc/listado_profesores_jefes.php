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

if($periodo == ''){ $periodo = date('Y'); }

$periodo_filtro = " profesores_jefes.profesor_jefe_periodo = '$periodo' ";



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

                            <h1 style="text-align:left;float:left;">LISTADO PROFESORES JEFES</h1> 

                            <a href="asignar_profesor_jefe.php" class="btn btn-default btn-lg active" role="button" style="text-align:right;float:right;margin-right:5px">Asignar Profesor Jefe</a>

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

                                    <input type="submit" value="filtrar">

                                </form>

                                

                            </div>

                            <!-- <table class="header2" style="width: 100% !important; margin: 10px 0px !important; "> -->

                            <table class="header2">

                                <tr>

                                    <th>Curso</th>

                                    <th>Profesor Jefe</th>

                                    <th>Periodo</th>

                                    <th>Modificar</th>

                                    <th>Eliminar</th>

                                </tr>

                                <?php 

                                $sql_prof = "SELECT DISTINCT *

                                FROM profesores_jefes

                                INNER JOIN letras on letras.letra_id = profesores_jefes.letra_id 

                                INNER JOIN niveles on niveles.nivel_id = profesores_jefes.nivel_id

                                INNER JOIN profesores on profesores.profesor_id = profesores_jefes.profesor_id

                                WHERE

                                $periodo_filtro

                                AND profesores_jefes.profesor_jefe_estado = '1'

                                GROUP BY nivel_nombre, letra_nombre

                                ";

                                $rs_prof = mysqli_query($conexion, $sql_prof);

                                $contador = '0';

                                while($row_prof = mysqli_fetch_array($rs_prof)){                         

                                    echo "<tr>

                                    <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>

                                    <td>".$row_prof['profesor_nombres']."-".$row_prof['profesor_apellidos']."</td>

                                    <td>".$row_prof['profesor_jefe_periodo']."</td>";

                                    ?>

                                    <td><a href='profesor_jefe_modificar.php?id=<?=$row_prof['profesor_jefe_id']?>'><i class='fas fa-pencil-alt fa-lg'></i></a></td>

                                    <td><a href='profesor_jefe_eliminar.php?id=<?=$row_prof['profesor_jefe_id']?>' onclick ="javascript: return confirm('Desea Eliminar Este Profesor Jefe?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                                    <?php

                        		  echo "</tr>";

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



    </div>

</body>

</html>

