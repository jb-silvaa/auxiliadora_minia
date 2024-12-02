<!DOCTYPE html>
<html>
<?php 
$curso1 = $_GET['curso'];
if($curso1 != ''){
    $result = explode("-",$curso1);
    $nivel = $result[0];
    $letra = $result[1];
    //$orden = "ORDER BY letras.letra_id = '$letra', niveles.nivel_id = '$nivel' DESC, niveles.nivel_nombre ASC";
}else{
    $orden = "";
}

$perfil_archivo = 1;
include('../funciones-sc/conexion.php');

if($nivel != '' && $nivel != '0'){
    $nivel_filtro = "AND ca.nivel_id = '$nivel'";
}else{
    $nivel_filtro = "";
}
if($letra != '' && $letra != '0'){
    $letra_filtro = "AND ca.letra_id = '$letra'";
}else{
    $letra_filtro = "";
}
$sql = "SELECT * FROM evaluaciones as e
        INNER JOIN cursos_asignaturas as ca on ca.curso_asignatura_id = e.curso_asignatura_id
        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
        INNER JOIN letras as l on l.letra_id = ca.letra_id
        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
        INNER JOIN periodos as pe on pe.periodo_periodo = ca.curso_asignatura_periodo
        INNER JOIN profesores as p on p.profesor_id = ca.profesor_id
        INNER JOIN tipos_evaluaciones as te on te.tipo_evaluacion_id = e.tipo_evaluacion_id
        WHERE evaluacion_estado = '1'
        AND pe.periodo_activo = '1'
        AND e.evaluacion_aprobacion <> '-1'
        $nivel_filtro
        $letra_filtro";
$rs = mysql_query($sql, $conexion);

?>
<!DOCTYPE html>
<html>
    <head>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
        <title>Sistema Clases</title>

        
       
        <link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
        <link href='../packages/core/main.css' rel='stylesheet' />
        <link href='../packages/daygrid/main.css' rel='stylesheet'/>

        <script src='../packages/core/locales-all.js'></script>
        <script src='../packages/core/main.js'></script>
        <script src='../packages/interaction/main.js'></script>
        <script src='../packages/daygrid/main.js'></script>  
        <script src="../js-sc/validar_caracteres.js"></script>
        <script src="../js-sc/menu-lateral.js"></script>

        <script>
            /* $(document).ready(function () {
                $.ajax({
                    type: "POST",
                    url: "getEvaluaciones_test.php",
                    data:{ 
                        nivel : <?=$nivel?>,
                        letra : <?=$letra?>
                    },
                        
                    success: function(results) {   
                        console.log("gane: "+ results);    
                        
                    
                    },
                });
            }); */
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    eventClick: function(info) {
                        //console.log(info.event.extendedProps.curso);
                        //alert('Event: ' + info.event.title);
                        //alert('Event: ' + info.event.extendedProps.description);
                        //alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                        //alert('View: ' + info.view.type);
                        //$('#exampleModalCenter').modal('show');
                        var td = document.getElementById("curso-tabla");
                        td.innerHTML = info.event.extendedProps.curso;

                        var td1 = document.getElementById("asig-tabla");
                        td1.innerHTML = info.event.extendedProps.asignatura;

                        var td2 = document.getElementById("dif-tabla");
                        td2.innerHTML = info.event.extendedProps.dificultad;

                        var td3 = document.getElementById("desc-tabla");
                        td3.innerHTML = info.event.extendedProps.description;

                        var td4 = document.getElementById("tipo-tabla");
                        td4.innerHTML = info.event.extendedProps.tipo_evaluacion;

                        $('#boton-ir').click(function() {
                            window.location= info.event.extendedProps.direccion;
                        });

	                    //var elementocode= document.getElementById("code");
                        $('#exampleModalCenter').appendTo("body").modal('show');
                        //$('#exampleModalCenter').modal('show');
                        //$('#exampleModalCenter').modal('show');
                        /* $('#exampleModalCenter').on('shown.bs.modal', function () {
                            $('#myInput').focus();
                        }); */
                        

                        // change the border color just for fun
                        info.el.style.borderColor = 'red';
                    },
                    plugins: ['dayGrid' ],
                    header: {
                        left: 'prevYear,prev,next,nextYear today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,dayGridDay'
                    },
                    locale: 'es',
                    navLinks: true, // can click day/week names to navigate views
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    events: [
                        <?php 
                        while($row = mysql_fetch_array($rs)){
                            $ca_id = $row['curso_asignatura_id'];
                            $id = $row['evaluacion_id'];
                            $curso = $row['nivel_nombre']."-".$row['letra_nombre'];
                            $nombre_completo = $row['asignatura_nombre']." || ".$curso."-".$row['dificultad_nombre']." || Profesor: ".$row['profesor_nombres']." ".$row['profesor_apellidos']." || ".$row['tipo_evaluacion_nombre'];
                            if($row['evaluacion_archivo'] == '' || $row['evaluacion_archivo'] == NULL){
                                $color = "#ff0000";
                            }else if($row['evaluacion_aprobacion'] == '1'){
                                $color = "#008000";
                            }else{
                                $color = "#0000ff";
                            }
                        ?>
                        {
                            id: '<?php echo $evaluacion_id; ?>',
					        title: '<?php echo $nombre_completo; ?>',
                            start: '<?php echo $row['evaluacion_fecha']; ?>',
                            description: '<?php echo $row['evaluacion_nombre']; ?>',
                            curso: '<?php echo $curso; ?>',
                            asignatura: '<?php echo $row['asignatura_nombre']; ?>',
                            tipo_evaluacion: '<?php echo $row['tipo_evaluacion_nombre']; ?>',
                            dificultad: '<?php echo $row['dificultad_nombre']; ?>',
                            direccion: '<?php echo "evaluacion.php?id=$ca_id&id2=$id"; ?>',
					        color: '<?php echo $color; ?>',
                        },

<?php
                        }
                            
                        
                        
                        
                        ?>
                    ]
                });
                calendar.render();

                /* $("#curso").change(function(){
                    var asig = $(this).val();
                    var res = asig.split("-");
                    console.log(res[0]);
                    console.log(res[1]);
                    nivel = res[0];
                    letra = res[1];
                    $.ajax({
                        url: 'getEvaluaciones.php',
                        type: 'post',
                        data: {nivel : nivel, letra: letra},
                        dataType: 'json',
                        success:function(response){
                            calendar.render();
                            
                        }
                    });
                }); */
            });

        </script>
        <style>

            body {
                margin: 40px 10px;
                padding: 0;
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 14px;
            }
            
            #calendar {
                max-width: 100%;
                margin: 0 auto;
            }

        </style>
    </head>
    <body>
        <!------ Include the above in your HEAD tag ---------->

        <div id="wrapper">
        
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
                        <div>
                            <style>
                                .dot {
                                    height: 25px;
                                    width: 25px;
                                    border-radius: 50%;
                                    display: inline-block;
                                    margin-left: 15px;
                                    margin-right: 30px;
                                    margin-top: 10px;
                                    margin-bottom: 10px;
                                }
                            </style>
                            <table>
                            <tr>
                            <td><label> Evaluación Aprobada</label><span class="dot" style="background-color: #008000 !important;"></span></td>
                            <td><label> Evaluación subida sin revisión</label><span class="dot" style="background-color: #0000ff !important;"></span></td>
                            <td><label> Evaluación sin archivo</label><span class="dot" style="background-color: #ff0000 !important;"></span></td>
                            </tr>
                            </table>
                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-11">
                            <div class="filtros">
                                <form method="GET">
                                    <label>Curso</label>       
                                    <select id="curso" name="curso" required class="minimal">
                                        <option value="0-0">TODOS</option>
                                        <?php
                                        $sql_asig = "SELECT DISTINCT *
                                            FROM cursos_asignaturas
                                            INNER JOIN letras on letras.letra_id = cursos_asignaturas.letra_id 
                                            INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id
                                            WHERE curso_asignatura_estado = '1'
                                            GROUP BY nivel_nombre, letra_nombre";
                                        $rs_asig = mysql_query($sql_asig, $conexion);
                                        while($row_asig = mysql_fetch_array($rs_asig))
                                        {
                                            $curso = $row_asig['nivel_nombre']."-".$row_asig['letra_nombre'];
                                            $ids = $row_asig['nivel_id']."-".$row_asig['letra_id'];
                                            if($nivel == $row_asig['nivel_id'] && $letra == $row_asig['letra_id'] ){
                                                echo "<option value='".$ids."' selected>".$curso."</option>";    
                                            }else{
                                                echo "<option value='".$ids."'>".$curso."</option>";
                                            }
                                            //echo "<option value=\"$nid|$lid\">".$nombre."</option>";
                                        }
                                        ?>                                   
                                    </select>
                                    <input type="submit" value="Filtrar">                      
                                </form>
                            </div> 
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-primary btn-nuevo" onclick="window.print();"><i class="fas fa-print"  style="color:#ffffff;" title="Imprimir"></i> Imprimir</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" id='calendar'></div>
                    </div>  

                <div id="calendario-listado">
                    <h1>1 de Marzo</h1>
                    <p>La prueba 1</p>                     
                </div>
            </div>
            </div>
            <!-- /#page-content-wrapper -->

        </div>
        <!-- /#wrapper -->
        <?php 
		    include('../fonts/fonts.php'); 
		    //include('../js-sc/bootstrap.php'); 
        ?> 

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Detalle Evaluación</h5>
                        
                    </div>
                    <div class="modal-body">
                    <!-- <table style="width:100%">
                        <tr>
                            <th>Name:</th>
                            <td>Bill Gates</td>
                        </tr>
                        <tr>
                            <th>Telephone:</th>
                            <td>555 77 854</td>
                        </tr>
                        <tr>
                            <th>Telephone:</th>
                            <td>555 77 855</td>
                        </tr>
                        </table> -->
                        <table id="info" class="table">
                                <tr>
                                    <th scope="col">Curso</th>
                                    <td id="curso-tabla"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Asignatura</th>
                                    <td id="asig-tabla"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Dificultad</th>
                                    <td id="dif-tabla"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Descripción</th>
                                    <td id="desc-tabla"></td>
                                </tr>
                                <tr>
                                    <th scope="col">Tipo Evaluación</th>
                                    <td id="tipo-tabla"></td>
                                </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="boton-ir" type="button" class="btn btn-primary">Ir</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
