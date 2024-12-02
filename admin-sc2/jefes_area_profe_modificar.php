<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
if (!$_SESSION['id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
    die();
}
if ($_SESSION['perfil'] != 1){
    echo "<script LANGUAGE='JavaScript'>
                  window.alert('Acceso no autorizado');
                  window.location= 'listado_cursos.php'
      </script>";
      die();
  }
$id = $_GET['id'];
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
<!------ Include the above in your HEAD tag ---------->

    <div id="wrapper">
        <div class="overlay"></div>
        <!-- Sidebar -->
        <?php include('menu-lateral.php'); ?>
        <!-- Page Content -->
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
                        <h1>MODIFICAR PROFESORES JEFE AREA</h1>
                        <table class="header2">
                        <form method="POST" action="jefes_area_profe_modificar_proc.php" enctype="multipart/form-data">
                            <?php
                            $sql_profesores = "SELECT * FROM profesores WHERE profesor_estado = '1' ORDER BY profesor_apellidos, profesor_nombres ASC";
                            $rs_profesores = mysql_query($sql_profesores, $conexion);
                            while($row_profesores = mysql_fetch_array($rs_profesores))
                            {            
                            ?>
                            <tr> 
                            <td><?=$row_profesores['profesor_apellidos']." ".$row_profesores['profesor_nombres']?></td>
                            <?php 
                            $profe_id = $row_profesores['profesor_id'];
                            $sql_check = "SELECT profesor_id FROM jefes_area_profe WHERE profesor_id = '$profe_id' AND jefe_id = '$id' AND jefes_area_profe_estado = '1'";
                            $rs_check = mysql_query($sql_check, $conexion);
                            $row_check = mysql_fetch_array($rs_check);
                            ?>                         
                            <td> <input type="checkbox" <?php if($row_check['profesor_id'] != ''){ echo " checked "; } ?> id="checkbox" name="profesor[]" value="<?=$row_profesores['profesor_id']?>"> 
                                 <input type="hidden" name="id" value="<?=$id?>">
                            </td></tr>
                            <?php
                            }
                            ?>
                            <div class="info-100">
                                <input type="submit" name="BtnIngresar" value="INGRESAR">
                            </div> 
                        </form>  
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