<?php 
//$perfil_archivo = 1;//adm = 1 , docente = 2;

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
                      $sql_profe = "SELECT * FROM jefes_area_profe JP
                        where jefes_area_profe_estado = '1' and jefe_id = '$user_id'";
                        $rs_profe = mysqli_query($conexion, $sql_profe);
                        while($row_profe = mysqli_fetch_array($rs_profe))
                        {

                            $profesor_id = $row_profe['profesor_id'];  

                            $sql_soli = "SELECT * FROM profesores as P, solicitudes as S ,
                            tipos_solicitudes as TP
                            WHERE P.profesor_id = S.receptor_profesor_id and S.receptor_profesor_id = '$profesor_id' and TP.tipo_solicitud_id = S.tipo_solicitud_id ";
                            $rs_soli = mysqli_query($conexion, $sql_soli);


                            while ($row_soli = mysqli_fetch_array($rs_soli)) {
                                 $solicitante = $row_soli['profesor_nombres']." ".$row_soli['profesor_apellidos'];
                                    echo "<tr>
                                    <td>".$solicitante."</td>
                                    <td>".$row_soli['solicitud_fecha_creacion']."</td>          
                                    <td>".$row_soli['tipo_solicitud_nombre']."</td>
                                    <td><a href='solicitudes_ver.php?id=".$row_soli['solicitud_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";
                        }
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
</body>
</html>