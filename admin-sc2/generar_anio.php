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
$sql_periodo = "SELECT * FROM periodos WHERE periodo_estado = '1'";
$rs_periodo = mysql_query($sql_periodo,$conexion);
$row_periodo = mysql_fetch_array($rs_periodo);
$periodo = $row_periodo['periodo_periodo'];
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
                        <h1>GENERAR AÑO ACADÉMICO</h1>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-12" style="text-align: center;">
                            <form method="POST" action="generar_anio_proc.php" onsubmit="return validator();">
                                <label>AÑO</label>                                
                                <select id="periodo" name="periodo" required class="minimal">
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
                                <label>CURSO</label>
                                <select id="nivel" name="nivel" required class="minimal">
                                    <option value="">Seleccione Curso</option>
                                    <?php
                                    $sql = "SELECT n.nivel_id, n.nivel_nombre, niveles_creados.nivel_creado_id 
                                    FROM niveles as n
                                    LEFT JOIN niveles_creados on niveles_creados.nivel_id = n.nivel_id
                                    AND niveles_creados.nivel_creado_periodo = '$periodo'
                                    WHERE niveles_estado = '1'
                                    GROUP BY nivel_nombre";
                                    
                                    $rs = mysql_query($sql, $conexion);
                                    while($row = mysql_fetch_array($rs))
                                    {
                                        $curso = $row['nivel_nombre'];
                                        $id = $row['nivel_id'];
                                        $id_c = $row['nivel_creado_id'];
                                        if($row['nivel_creado_id'] == '' || $row['nivel_creado_id'] == NULL){
                                            echo "<option value='".$id."''>".$curso."</option>";    
                                        }
                                    }
                                    ?> 
                                </select>
                                <label>ASIGNATURA</label>
                                <table style="border:1px solid black;">
                                <?php
                                    $sql = "SELECT * FROM asignaturas
                                    WHERE asignatura_estado = '1'";
                                    
                                    $rs = mysql_query($sql, $conexion);
                                    $x = 0;
                                    while($row = mysql_fetch_array($rs))
                                    {
                                        $asignatura = $row['asignatura_nombre'];
                                        $id = $row['asignatura_id'];
                                        if($x == 0){
                                            echo "<tr>";
                                        }
                                        echo "<th style='border:1px solid black; text-align: center;'><label style='text-align: center;'>$asignatura</label>
                                                <select style='width:160px;' name='asignatura[]' required class='minimal'>
                                                <option value='$id-0'>No considerar</option>
                                                <option value='$id-1'>Considerar con dificultad</option>
                                                <option value='$id-2'>Considerar sin dificultad</option>      
                                            </select></th>";
                                        $x++;
                                        if($x==3){
                                            $x = 0;
                                            echo "</tr>";
                                        }
                                    }
                                    if($x!=3 || $x!=0){
                                        echo "</tr>";
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
