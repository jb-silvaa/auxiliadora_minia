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
                        <h1>AGREGAR ASIGNATURA</h1>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <form method="POST" action="agregar_asignatura_proc.php" onsubmit="return validator();">
                                <label>AÑO</label>                                
                                <select name="periodo" required class="minimal">
                                    <option value="">Seleccione Año</option> 
                                    <?php
                                    $sql_asig = "SELECT * FROM periodos WHERE periodo_activo = '1' ORDER BY periodo_periodo DESC";
                                    $rs_asig = mysql_query($sql_asig, $conexion);
                                    while($row_asig = mysql_fetch_array($rs_asig))
                                    {
                                        echo "<option value='".$row_asig['periodo_periodo']."''>".$row_asig['periodo_periodo']."</option>";
                                    }
                                    ?>                                   
                                </select>
                                <label>ASIGNATURA</label>                                
                                <select name="asignatura" required class="minimal">
                                    <option value="">Seleccione Asignatura</option> 
                                    <?php
                                    $sql_asig = "SELECT * FROM asignaturas WHERE asignatura_estado = '1' ORDER BY asignatura_nombre ASC";
                                    $rs_asig = mysql_query($sql_asig, $conexion);
                                    while($row_asig = mysql_fetch_array($rs_asig))
                                    {
                                        echo "<option value='".$row_asig['asignatura_id']."''>".$row_asig['asignatura_nombre']."</option>";
                                    }
                                    ?>                                   
                                </select>
                            <table class="header2">   
                            <tr>                                                           
                                <?php
                                $cont=1;
                                $sql_nivel = "SELECT * FROM niveles where niveles_estado = '1' order by nivel_id ASC";
                                $rs_nivel = mysql_query($sql_nivel, $conexion);
                                while($row_nivel = mysql_fetch_array($rs_nivel))
                                {
                                ?>
                                    <td><?=$row_nivel['nivel_nombre']?>                      
                                    <input type="checkbox" style="float: right !important; width: 20px;" id="checkbox" name="nivel[]" value="<?=$row_nivel['nivel_id']?>"> 
                                    <input type="hidden" name="id" value="<?=$row_nivel['nivel_id']?>"></td>
                                  
                                <?php
                                if($cont%2 == 0)
                                {
                                    echo "</tr><tr>";
                                }
                                $cont++;
                                }
                                ?>
                            </table>
                            <input type="submit" value="Generar">
                        </div>                        
                        </form>     
                        </div>
                        <div class="col-lg-3">
                        </div>          
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
