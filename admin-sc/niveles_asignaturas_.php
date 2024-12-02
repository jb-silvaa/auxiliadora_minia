<?php 

$perfil_archivo = 1;//adm = 1 , docente = 2;

include('../funciones-sc/conexion.php');

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

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

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

                        <h1>NIVELES ASIGNATURAS</h1>

                        <table class="header2">

                            <!--<tr>

                                <th class="back-fff"></th>

                                <th>GENERAR PLANTILLA ANUAL</th>

                                <th><a href='plantilla_anual_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>-->

                            <tr>  

                                <th>NIVEL</th>

                                <th>ASIGNATURA</th>

                                <th>VER</th>

                              </tr>

                        <?php 

                        /*$sql = "SELECT * FROM niveles_asignaturas N 

                        JOIN niveles NI ON N.nivel_id = NI.nivel_id 

                        JOIN asignaturas A ON N.asignatura_id = A.asignatura_id 

                        WHERE NI.niveles_estado = 1 and A.asignatura_estado= 1 

                        ORDER BY NI.nivel_id ASC ";*/

                        $sql = "SELECT * FROM niveles WHERE niveles_estado = '1'";

                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                            $nivel_id = $row['nivel_id'];

                            $sql_contar = "SELECT count(asignatura_id) AS total FROM niveles_asignaturas WHERE nivel_asignatura_estado = '1' AND nivel_id = '$nivel_id'";

                            $rs_contar = mysqli_query($conexion, $sql_contar);

                            $row_contar = mysqli_fetch_array($rs_contar);



                            $rowspan = $row_contar['total'];

                            if($rowspan == '0'){ $rowspan = '1'; }

                            echo "<tr>

                                    <td rowspan=".$rowspan.">".$row['nivel_nombre']."</td>";    

                            $sql_asig = "SELECT * FROM asignaturas as a, niveles_asignaturas as na WHERE a.asignatura_id = na.asignatura_id AND na.nivel_id = '$nivel_id' AND nivel_asignatura_estado = '1'";

                            $rs_asig = mysqli_query($conexion, $sql_asig);

                            $contador = '1';

                            while ($row_asig = mysqli_fetch_array($rs_asig)) {

                                if($contador == '1')

                                {

                                    echo "<td>".$row_asig['asignatura_nombre']."</td>";

                                    echo "<td rowspan=".$rowspan."><a href='niveles_asignaturas_modificar.php?id=".$row['nivel_id']."' style='height: 20px;'><i class='fas fa-search fa-lg'></i></a></td></tr>";

                                }

                                else

                                {

                                    echo "<tr><td>".$row_asig['asignatura_nombre']."</td></tr>"; 

                                }

                               $contador++; 

                            }

                            if($contador == '1'){ echo "<td>--</td><td><a href='niveles_asignaturas_modificar.php?id=".$row['nivel_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>"; } 

                        }

                        ?>



                        </table>

                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

</body>

</html>

