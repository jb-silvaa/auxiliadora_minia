<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');

$data = $_GET['data'];
if($data == ''){ $filtro_profesor = ' '; }
else{ $filtro_profesor = " AND (profesor_nombres LIKE '%".$data."%' OR profesor_apellidos LIKE '%".$data."%') "; }
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
                        <h1>MENSAJES</h1>
                         <div class="filtros">
                            <form action="" method="GET">
                                <input type="text" name="data" placeholder="Ingrese Nombre o Apellido" onkeyup="reemplazar(this);">
                                <input type="submit" value="BUSCAR">
                            </form>                            
                        </div>
                        <table class="header2">
                             <tr>
                                <th class="back-fff"></th>
                                <th class="back-fff"></th>
                                <th colspan="2">Redactar nuevo mensaje</th>
                                <th><a href='mensaje_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>
                            </tr>
                            <tr>  
                                <th>DESTINATARIO</th>
                                <th>FECHA</th>
                                <th>CONTENIDO</th> 
                                <th>ARCHIVO</th>
                                <th>VER</th>
                              </tr>
                         <?php 
                       $sql = "SELECT * 
                                FROM mensajes as m 
                                join profesores as p on m.profesor_id = p.profesor_id
                                WHERE m.mensaje_estado = '1' AND m.profesor_id <> '0' $filtro_profesor ";

                        $rs = mysql_query($sql, $conexion);
                        while($row = mysql_fetch_array($rs))
                        {
                                   echo "<tr>
                                    <td>".$row['profesor_nombres']." ".$row['profesor_apellidos']."</td> 
                                    <td>".$row['mensaje_fecha']."</td>   
                                    <td style='width:250px;'>".$row['mensaje_cuerpo']."</td>";
                                    if($row['mensaje_archivo']=="") {
                                        echo "<td> -- </td>";
                                    }else{
                                        echo "<td><a href='../profesor-sc/".$row['mensaje_archivo']."'><i class='fas fa-search fa-lg'></i></a></td>";
                                    }
                                    echo "<td><a href='mensaje_responder.php?id=".$row['mensaje_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";
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