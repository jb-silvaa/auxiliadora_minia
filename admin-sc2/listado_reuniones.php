<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
$periodo = $_GET['periodo'];
if($periodo == ''){ $periodo = date('Y'); }
$periodo_filtro = " AND pj.profesor_jefe_periodo = '$periodo' ";

$docente = $_GET['docente'];
if($docente == '' || $docente == '0'){ $docente_filtro = ""; $docente = '0';}
else{ $docente_filtro = " AND pjr.profesor_id = ".$docente." "; }

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
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
                        <div class="col-lg-12">
                            <h1 style="text-align:left;float:left;">LISTADO REUNIONES</h1> 
                            
                            <div class="filtros4">
                                <form method="GET">
                                    <label>Año</label>
                                    <select name="periodo" class="minimal">
                                        <?php 
                                        $sql_anos = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo' DESC, periodo_periodo ASC";
                                        $rs_anos = mysql_query($sql_anos, $conexion);
                                        while ($row_anos = mysql_fetch_array($rs_anos)) {
                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Profesor</label>
                                    <select name="docente" class="minimal">
                                        <?php 
                                        if($docente == '0'){ echo "<option value='0'>TODOS</option>"; }
                                        $sql_profe = "SELECT * 
                                                    FROM profesores_jefes as pj,
                                                        profesores as p
                                                    WHERE p.profesor_id = pj.profesor_id
                                                    AND pj.profesor_jefe_estado = '1'
                                                    ORDER BY p.profesor_id = '$docente' DESC, p.profesor_apellidos, p.profesor_nombres ASC";
                                        $rs_profe = mysql_query($sql_profe, $conexion);
                                        while ($row_profe = mysql_fetch_array($rs_profe)) {
                                            echo "<option value=".$row_profe['profesor_id'].">".$row_profe['profesor_apellidos']." ".$row_profe['profesor_nombres']."</option>";
                                        }
                                        if($docente != '0'){ echo "<option value='0'>TODOS</option>"; }
                                        ?>
                                    </select>
                                    <input type="submit" value="filtrar">
                                </form>
                                
                            </div>
                            <!-- <table class="header2" style="width: 100% !important; margin: 10px 0px !important; "> -->
                            <table class="header2">
                                <tr>
                                    <th>Curso</th>
                                    <th>Profesor</th>
                                    <th>Tipo</th>
                                    <th>Asunto</th>
                                    <th>Resumen</th>
                                    <th>Archivo</th>
                                </tr>
                                <?php 
                                $sql_prof = "SELECT * 
                                FROM profesor_jefe_reuniones as pjr,
                                    niveles as n,
                                    letras as l,
                                    profesores as p,
                                    profesores_jefes as pj
                                WHERE n.nivel_id = pjr.nivel_id
                                AND l.letra_id = pjr.letra_id
                                AND p.profesor_id = pjr.profesor_id
                                AND n.nivel_id = pj.nivel_id
                                AND l.letra_id = pj.letra_id
                                $periodo_filtro
                                $docente_filtro";
                                $rs_prof = mysql_query($sql_prof, $conexion);
                                $contador = '0';
                                while($row_prof = mysql_fetch_array($rs_prof))
                                {                         
                                    echo "<tr>
                                    <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>
                                    <td>".$row_prof['profesor_nombres']."-".$row_prof['profesor_apellidos']."</td>
                                    <td>".$row_prof['reunion_tipo']."</td>
                                    <td>".$row_prof['reunion_asunto']."</td>
                                    <td>".$row_prof['reunion_resumen']."</td>";
                                    if($row_prof['reunion_archivo'] == ''){
                                        echo "<td>--</td>";
                                    }else{
                                        ?>
                                        <td><a href="../profesor-sc/<?=$row_prof['reunion_archivo']?>"><i class='fas fa-search fa-lg'></i></a></td>
                                    <?php
                                    }
                                    echo "
                                    
                        		  </tr>";
                                    $dificultad = '';
                                    $contador++;
                                }
                                if($contador == '0'){ echo "<td colspan='8'>No hay resultados para los filtros escogidos</td>"; }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
