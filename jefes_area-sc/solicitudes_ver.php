<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
$solicitud_ID = $_GET['id'];
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
                        <h1>Visualizar Solicitud</h1>
            <input type="hidden" name="id" value="<?=$row['solicitud_id']?>">
                        <table class="header2">
                            
                        <?php 
                        //colspan=2 en el primer td
                       $sql = "SELECT * FROM solicitudes S
                        JOIN tipos_solicitudes TS ON S.tipo_solicitud_id = TS.tipo_solicitud_id
                        JOIN solicitudes_estados SE ON S.solicitud_estado_id = SE.solicitud_estado_id
                        JOIN profesores P ON S.profesor_id = P.profesor_id
                        JOIN usuarios U on S.usuario_id = U.usuario_id
                        where S.solicitud_id = '$solicitud_ID'";
                        $rs = mysqli_query($conexion, sql);
                        $row = mysql_fetch_array($rs);
                            if($row['usuario_id'] == '0'){
                                $solicitante = $row['profesor_nombres']." ".$row['profesor_apellidos'];
                            }else{
                            $solicitante = $row['usuario_nombres']." ".$row['usuario_apellidos'];
                        }
                            echo " <tr>
                                <th>Solicitante</th>
                                    <td>".$solicitante."</td>
                                    </tr>

                                    <tr>
                                <th>Fecha de Creacion</th>
                                    <td>".$row['solicitud_fecha_creacion']."</td>
                                    </tr>

                                      <tr>
                                <th>Tipo de Solicitud</th>        
                                    <td>".$row['tipo_solicitud_nombre']."</td>
                                    </tr>
                                         <tr>
                                <th>Nombre de Archivo</th>";
                                if($row['solicitud_archivo']==""){
                                  alert("No hay archivo para mostrar.");
                                 header("location:solicitudes_ver.php");
                                } else{
                                echo " 
                                    <td><a href='../profesor-sc/".$row['solicitud_archivo']."'><i class='fas fa-search fa-lg'></i></a></td>
                                    </tr>
                                    ";     
                                    } 
                                    echo
                                    "
                                         <tr>
                                <th>Observacion</th>        
                                    <td >".$row['solicitud_cuerpo']."</td>
                                    </tr>
                                  ";
                        ?>
                        </table> 
                         <h1>Historial de Solicitud</h1>
                         <table class="header2">
                            <tr>
                            <th>Usuario</th>
                           <th>Fecha</th>
                           <th>Comentario</th>
                           <th>Ver Archivo</th>
                    </tr>
                            <?php // Se evita llamar a todo ya que hay un usuario_id y profesor_id 1 y 0 siempre llamando datos incorrectos
                      $sql_s = "SELECT P.profesor_nombres, P.profesor_apellidos,U.usuario_nombres,U.usuario_apellidos,
                       SH.*,S.solicitud_id
                       FROM solicitudes_historial SH 
                        join profesores P on SH.profesor_id = P.profesor_id 
                        join usuarios U on SH.usuario_id = U.usuario_id 
                        join solicitudes S on SH.solicitud_id = S.solicitud_id 
                        where S.solicitud_id = '$solicitud_ID'" ;
                         $res = mysqli_query($conexion, sql_s);
                          while($fila = mysql_fetch_array($res))
                         {
                            if($fila['usuario_id'] == '0')
                            {
                                $solicitud_h = $fila['profesor_nombres']." ".$fila['profesor_apellidos'];
                            }
                            else
                            {
                               $solicitud_h = $fila['usuario_nombres']." ".$fila['usuario_apellidos'];
                            }

                       echo" <tr>
                                 <td>".$solicitud_h."</td>
                                 <td>".$fila['solicitud_historial_fecha']."</td>
                                 <td>".$fila['solicitud_historial_comentario']."</td>";
                                    if($fila['solicitud_historial_archivo'] != ''){
                                      echo "<td><a href='../profesor-sc/".$fila['solicitud_historial_archivo']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";
                                    } else {
                                      echo "<td>--</td></tr>";
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