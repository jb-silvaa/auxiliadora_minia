<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
include('funcionrut.php');
session_start();
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
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
                        <h1>Jefes de area</h1>
                        <table class="header2">
                            <tr>
                                <th class="back-fff"></th>
                                <th class="back-fff"></th>
                                <th colspan="3">Nuevo Jefe de area</th>
                                <th><a href='jefes_area_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>
                            </tr>
                    		<tr>
                    			<th>RUT</th>
                    			<th>NOMBRE DEL ENCARGADO</th>
                                <th>CORREO </th>
                                <th>VER</th>
                                <th>MODIFICAR</th>
                                <th>ELIMINAR</th>
                    		  </tr>
                        <?php 
                        $sql = "SELECT * 
                                FROM jefes_area 
                                WHERE jefe_estado = '1'";
                        $rs = mysql_query($sql, $conexion);
                        while($row = mysql_fetch_array($rs))
                        {
                        	echo "<tr>
                        			<td>".formateo_rut($row['jefe_rut'])."</td>
                        			<td>".$row['jefe_nombre']." ".$row['jefe_apellido']."</td> 
                                    <td>".$row['jefe_correo']."</td>    
                                    <td><a href='jefes_area_profe_modificar.php?id=".$row['jefe_id']."'><i class='fas fa-search fa-lg'></i></a></td>     
                                    <td><a href='jefes_area_modificar.php?id=".$row['jefe_id']."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>
                                    <td><a href='jefes_area_eliminar.php?id=".$row['jefe_id']." onclick = 'javascript: return confirm('Desea Eliminar Este Jefe?');'><i class='far fa-times-circle fa-lg'></i></a></td>
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

</body>
</html>