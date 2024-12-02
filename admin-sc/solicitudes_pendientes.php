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

     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

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

                        <h1>Solicitudes Pendientes</h1>

                        <table class="header2">



                            <tr>

                                <th>Solicitante</th>

                                <th>Fecha</th>

                                <th>Tipo de solicitud</th>

                                <th>Ver</th>

                              </tr>

                        <?php 

                        $i=0;

                        $sql = "SELECT * FROM solicitudes as S

                        JOIN tipos_solicitudes as TS ON S.tipo_solicitud_id = TS.tipo_solicitud_id

                        JOIN solicitudes_estados as SE ON S.solicitud_estado_id = SE.solicitud_estado_id

                        JOIN profesores as P ON S.profesor_id = P.profesor_id

                        JOIN usuarios as U on S.usuario_id = U.usuario_id

                        where S.solicitud_estado_id = '0'

                        AND S.receptor_usuario_id = ".$_SESSION['id'];

                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                            if($row['usuario_id'] == '0'){

                                $solicitante = $row['profesor_nombres']." ".$row['profesor_apellidos'];

                            }else{

                                $solicitante = $row['usuario_nombres']." ".$row['usuario_apellidos'];

                            }

                            echo "<tr>

                                    <td>".$solicitante."</td>

                                    <td>".$row['solicitud_fecha_creacion']."</td>          

                                    <td>".$row['tipo_solicitud_nombre']."</td>

                                    <td><a href='solicitudes_modificar.php?id=".$row['solicitud_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";

                        $i++;

                        }

                        if($i == 0){ echo "<td colspan='4'>NO TIENE SOLICITUDES PENDIENTES</td>"; }

                        ?>

                        </table>                      

                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

</body>

</html>