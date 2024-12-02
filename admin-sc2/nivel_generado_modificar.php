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

$nivel_creado_id = $_GET['id'];

$sql_nivel_creado = "SELECT * 
                    FROM niveles_creados as nc 
                    INNER JOIN niveles as n on n.nivel_id = nc.nivel_id
                    WHERE nivel_creado_id = '$nivel_creado_id'";
$rs_nivel_creado = mysql_query($sql_nivel_creado,$conexion);
$row_nivel_creado = mysql_fetch_array($rs_nivel_creado);

$periodo = $row_nivel_creado['nivel_creado_periodo'];
$nivel_nombre = $row_nivel_creado['nivel_nombre'];
$nivel_id = $row_nivel_creado['nivel_id'];
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
                            <form method="POST" action="nivel_generado_modificar_proc.php" onsubmit="return validator();">
                                <label>AÑO</label>                                
                                <input type="text" name="periodo" readonly="" value="<?=$periodo?>" >
                                <input type="hidden" name="nivel_id" value="<?=$nivel_id?>" >
                                <input type="hidden" name="nivel_creado_id" value="<?=$nivel_creado_id?>" >
                                <label>NIVEL</label>
                                <input type="text" name="nivel" readonly="" value="<?=$nivel_nombre?>" >
                                <label>ASIGNATURA</label>
                                <table style="border:1px solid black;">
                                <?php
                                    $sql = "SELECT * FROM asignaturas
                                    WHERE asignatura_estado = '1'";
                                    
                                    $rs = mysql_query($sql, $conexion);
                                    $x = 0;
                                    while($row = mysql_fetch_array($rs))
                                    {
                                        $existe = 0;
                                        $dificultad = 0;
                                        $sql_asig_ca = "SELECT * FROM cursos_asignaturas
                                        WHERE curso_asignatura_periodo = '2021'
                                        AND nivel_id = '$nivel_id'
                                        GROUP BY asignatura_id";
                                        $rs_asig_ca = mysql_query($sql_asig_ca,$conexion);
                                        //Recorro cursos asignaturas para ver si existe la asignatura
                                        while($row_asig_ca = mysql_fetch_array($rs_asig_ca)){
                                            if($row['asignatura_id'] == $row_asig_ca['asignatura_id']){
                                                $existe = 1;
                                                //verifico si tiene dificultad o no
                                                if($row_asig_ca['dificultad_id'] > 0){
                                                    $dificultad = 1;
                                                    break;
                                                }
                                            }
                                        }
                                        $asignatura = $row['asignatura_nombre'];
                                        $id = $row['asignatura_id'];
                                        if($x == 0){
                                            echo "<tr>";
                                        }
                                        ?>
                                        <th style='border:1px solid black; text-align: center;'><label style='text-align: center;'><?=$asignatura?></label>
                                                <select style='width:160px;' name='asignatura[]' required class='minimal'>
                                                <option value='<?=$id?>-0' <?php if($existe == 0 && $dificultad == 0){ echo ' selected '; } ?>>No considerar</option>
                                                <option value='<?=$id?>-1' <?php if($existe == 1 && $dificultad == 1){ echo ' selected '; } ?>>Considerar con dificultad</option>
                                                <option value='<?=$id?>-2' <?php if($existe == 1 && $dificultad == 0){ echo ' selected '; } ?>>Considerar sin dificultad</option>      
                                            </select></th>
                                        <?php
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
                        <a href="#" onclick="agregar()" class="btn btn-primary active" role="button" style="text-align:right;float:right;">Agregar</a>
                            <script>
                            function agregar(){
                                var $select = $('[name="asignatura[]"]');
                                    var output = [];
                                    for (var i = 0, len = $select.length; i < len; i++) {
                                        //output.push($select.eq(i).val());
                                        console.log($select.eq(i).val());
                                    }
                                    //$('#output').text('Added Together = ' + output);
                            }
                            </script>
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
