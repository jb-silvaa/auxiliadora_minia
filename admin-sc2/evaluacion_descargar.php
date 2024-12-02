<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
//phpinfo();
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$periodo = $_GET['periodo'];
if($periodo == ''){ $periodo = date('Y'); }
$periodo_filtro = " AND ca.curso_asignatura_periodo = '$periodo' ";

$docente = $_GET['letra'];
if($docente == '' || $docente == '0'){ $docente_filtro = ""; $docente = '0';}
else{ $docente_filtro = " AND ca.letra_id = ".$docente." "; }

$tipo_carga = $_GET['nivel'];
if($tipo_carga != ''){ $tipo_filtro = " AND n.nivel_id = '".$tipo_carga."' "; }
else{ $tipo_filtro = ""; }
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
                            <h1 style="text-align:left;float:left;">descarga de evaluaciones</h1> 
                            <div class="filtros4">
                                <form>
                                    <label>Seleccionar curso: </label>
                                    <select name="nivel" class="minimal">                                        
                                        <?php
                                        if($tipo_carga == ''){ echo "<option value=''>TODOS</option>"; }
                                        $sql_nivel = "SELECT * FROM niveles ORDER BY nivel_nombre ASC";
                                        $rs_nivel = mysql_query($sql_nivel);
                                        while($row_nivel = mysql_fetch_array($rs_nivel))
                                        {
                                          echo "<option value='".$row_nivel['nivel_id']."'>".$row_nivel['nivel_nombre']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label>Letra</label>
                                    <select name="letra" class="minimal">                                        
                                        <?php
                                        if($docente == '0'){ echo "<option value='0'>TODOS</option>"; }
                                        $sql_letra = "SELECT * FROM letras ORDER BY letra_nombre ASC";
                                        $rs_letra = mysql_query($sql_letra);
                                        while($row_letra = mysql_fetch_array($rs_letra))
                                        {
                                          echo "<option value='".$row_letra['letra_id']."'>".$row_letra['letra_nombre']."</option>";
                                          if($row_letra['letra_id'] == '2') break;
                                        }
                                        ?>
                                    </select>
                                    <label>Periodo</label>
                                    <select name="periodo" class="minimal">
                                        <?php 
                                        $sql_anos = "SELECT * FROM periodos ";
                                        $rs_anos = mysql_query($sql_anos, $conexion);
                                        while ($row_anos = mysql_fetch_array($rs_anos)) {
                                            echo "<option value='".$row_anos['periodo_periodo']."'>".$row_anos['periodo_periodo']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="submit" value="filtrar">
                                </form>
                                <table class="header2" style="width: 100% !important; margin: 10px 0px !important; ">
                                <tr>
                                    <th>Profesor</th>
                                    <th>Curso</th>
                                    <th>Asignatura</th>
                                    <th>Nivel</th>
                                    <th>Evaluación</th>
                                </tr>
                                <?php 
                                $sql_prof = "SELECT * 
                                FROM asignaturas as a,
                                     cursos_asignaturas as ca,
                                     letras as l,
                                     niveles as n,
                                     profesores as p,
                                     dificultades as d,
                                     evaluaciones as e,
                                     evaluaciones_estado as ee
                                WHERE a.asignatura_estado = '1'
                                AND ca.asignatura_id = a.asignatura_id
                                AND n.nivel_id = ca.nivel_id
                                AND e.curso_asignatura_id = ca.curso_asignatura_id
                                AND l.letra_id = ca.letra_id
                                AND ca.dificultad_id = d.dificultad_id
                                AND ca.profesor_id = p.profesor_id
                                AND e.evaluacion_estado_id = ee.evaluacion_estado_id
                                AND e.evaluacion_estado = '1'
                                $periodo_filtro
                                $tipo_filtro
                                $docente_filtro
                                ORDER BY  p.profesor_apellidos, p.profesor_nombres, ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";
                                $rs_prof = mysql_query($sql_prof, $conexion);
                                $contador = '0';
                                $eval = array();
                                $nombres = array();
                                while($row_prof = mysql_fetch_array($rs_prof))
                                {                         
                                    echo "<tr>                                                          
                                            <td>".$row_prof['profesor_apellidos']." ".$row_prof['profesor_nombres']."</td>
                                            <td>".$row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."</td>
                                            <td>".$row_prof['asignatura_nombre']."</td>  
                                            <td>".$row_prof['dificultad_nombre']."</td>
                                            <td>".$row_prof['evaluacion_nombre']."</td>";
                                    ?>                                    
                                          </tr>
                                <?php
                                    $eval[] = $row_prof['evaluacion_archivo'];
                                    $nombres[] = $row_prof['nivel_nombre']."-".$row_prof['letra_nombre']."-".$row_prof['asignatura_nombre']."-".$row_prof['evaluacion_id'];
                                    $dificultad = '';
                                    $contador++;
                                    
                                }
                                if($contador == '0'){ echo "<td colspan='8'>No hay resultados para los filtros escogidos</td>"; }
                                //debug_to_console($nombres[0])
                                ?>
                            </table>
                            <form method="POST" action="evaluacion_descargar_proc.php" enctype="multipart/form-data">
                                
                                <input type="hidden" name="periodo" value="<?=$periodo?>">
                                <input type="hidden" name="nivel" value="<?=$tipo_carga?>">
                                <input type="hidden" name="letra" value="<?=$docente?>">
                                
                                <div class="info-100">
                                <input type="submit" value="DESCARGAR">                                
                                </div>   
                            </form>
                            <button type="button" onclick="downloadAll()">Click Me!</button>
                            <script>
                                function downloadAll(){
                                    var link = document.createElement('a');
                                    link.setAttribute('download', null);
                                    link.style.display = 'none';
                                    document.body.appendChild(link);
                                    var jArray = <?php echo json_encode($eval); ?>;
                                    var name = <?php echo json_encode($nombres); ?>;
                                    
                                    //while(jArray.length>0){
                                    for(var i=0; i<jArray.length; i++){
                                        link.setAttribute('href', "../profesor-sc/"+jArray[0]);
                                        link.setAttribute('download',name[0]);
                                        link.click();   
                                        console.log(jArray.length);
                                        console.log(name.length);         
                                        //jArray.splice(0,1);
                                        //name.splice(0,1);    
                                        //downloadAll();                        
                                    }
                                    document.body.removeChild(link);
                                }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
