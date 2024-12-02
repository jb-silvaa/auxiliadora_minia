<!DOCTYPE html>
<html>
<?php 
session_start();
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
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM usuarios WHERE usuario_id = '$user_id'";
$rs = mysqli_query($conexion, $sql);
$row = mysqli_fetch_array($rs);
$perfil = $row['perfil_id'];

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
$rs = mysqli_query($conexion, $sql);

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
                        if(info.event.extendedProps.direccion != "no")
                        {
                            $('#boton-ir').click(function() {
                                window.location= info.event.extendedProps.direccion;
                            });
                        }
                        if(info.event.extendedProps.direccion == "no")
                        {
                            $("#boton-ir").attr("disabled", false);
                        }
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
                        while($row = mysqli_fetch_array($rs)){
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
                            //REVISO SI EL CURSO PERTENECE AL COORD
                            $evaluacion_revisada = "evaluacion.php?id=$ca_id&id2=$id";
                            if($perfil == '3')
                            {
                                $sql_pertenece = "SELECT curso_asignatura_id FROM ayudantes WHERE usuario_id = '$user_id' AND ayudante_estado = '1' AND curso_asignatura_id = '$ca_id'";
                                $rs_pertenece = mysqli_query($conexion, $sql_pertenece);
                                $row_pertenece = mysqli_fetch_array($rs_pertenece);

                                if($row_pertenece['curso_asignatura_id'] != "" )
                                {
                                    $evaluacion_revisada = "evaluacion.php?id=$ca_id&id2=$id";
                                }
                                else
                                {
                                    $evaluacion_revisada = "no";
                                }
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
                            direccion: '<?php echo $evaluacion_revisada; ?>',
					        color: '<?php echo $color; ?>',
                        },

<?php
                        }
                            
                        
                        
                        
                        ?>
                    ],
    eventDrop: function(event, delta, revertFunc) {

    alert(event.title + " se cambio la fecha a " + event.start.format());
    var id = event.id;
    var fecha = event.fecha_actual;
    var fecha_nueva = event.start.format();
    if (!confirm("¿Está seguro que desea cambiar la fecha?")) {
      revertFunc();
    }else{
      //alert(fecha);
      $.ajax({
          type: "POST",
          url: "calendario_mod_fecha.php",
          data:{ 
             id : id,
             fecha : fecha,
             fecha_nueva : fecha_nueva 
          },
             
          success: function(results) {   
             console.log("gane: "+ results);    
             //var  formolo = document.getElementById("modalx");
             //console.log("holax: formo: "+ formolo);
             //formolo.innerHTML = results;
          },
       });
    }

  }
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
                    <a aria-hidden='true' data-toggle='modal' data-target='#nueva_eva'><button class='btn btn-primary btn-nuevo'>Agregar Evaluaciones</button></a>
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
                                        $rs_asig = mysqli_query($conexion, $sql_asig);
                                        while($row_asig = mysqli_fetch_array($rs_asig))
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

        <!-- cod del modal modifi -->
          <div class="modal fade " id="nueva_eva" tabindex="-1" role="dialog" aria-labelledby="nueva_eva" aria-hidden="true">
            <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
            <div id="modalx" class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <form action='listado_cursos_agregar_evaluacion.php' method='post' enctype='multipart/form-data'>
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Agregar Evaluación(es)</h5>
                      
                  </div>
                  <div class="modal-body">
                    
                    <table id="info" class="table">                  
                      <tr>
                        <th scope="col" style='width: 170px;'>Periodo</th>
                        <td id="curso-tabla">
                          <?php
                            $sql_periodo = "SELECT periodo_periodo 
                                            FROM periodos 
                                            WHERE periodo_estado = '1'
                                              AND periodo_activo = '1'
                                            LIMIT 1";
                            $rs_periodo = mysqli_query($conexion, $sql_periodo);
                            $row_periodo = mysqli_fetch_array($rs_periodo);
                            $eval_periodo = $row_periodo['periodo_periodo'];
                          
                          ?>
                          <input type='number' style='width: 300px;' id='eva_periodo' name='eva_periodo'  class='form-control validate' onkeyup='reemplazar(this)' placeholder='Periodo'  value='<?=$eval_periodo?>' required min='1' onchange="buscar_asigna();">
                        </td>
                      </tr>
                      <tr>
                        <th scope="col" >Nivel</th>     
                          <?php
                            $sql_eva_nivel = "SELECT nivel_id, nivel_nombre
                                              FROM niveles 
                                              WHERE niveles_estado = '1'
                                              ORDER BY nivel_orden ASC";
                            $rs_eva_nivel = mysqli_query($conexion, $sql_eva_nivel);
                            //echo "<br>hola - sql_eva_nivel:$sql_eva_nivel<br>";
                          ?>
                          <td id="curso-tabla">
                            <select id="eva_nivel" name="eva_nivel" class="minimal" style='width: 300px;height:30px;' onchange="buscar_asigna();" required>
                              <option value="" disabled selected >Seleccione Nivel</option>
                              <?php
                                while($row_eva_nivel = mysqli_fetch_array($rs_eva_nivel)){
                                  $eva_nivel_id = $row_eva_nivel['nivel_id']; 
                                  $eva_nivel_nombre = $row_eva_nivel['nivel_nombre']; 
                                  echo "<option value='$eva_nivel_id'>$eva_nivel_nombre</option>";                                 
                                }
                              ?>
                                      
                                      
                            </select> 
                          </td>
                      </tr>
                      <tr>
                      
                        <th scope="col">Letra</th>     
                          <?php
                            $sql_eva_letra = "SELECT letra_id, letra_nombre
                                              FROM letras 
                                              WHERE letra_estado = '1'
                                              ORDER BY letra_nombre ASC";
                            $rs_eva_letra = mysqli_query($conexion, $sql_eva_letra);
                            //echo "<br>hola - sql_eva_nivel:$sql_eva_nivel<br>";
                          ?>
                        <td id="curso-tabla">
                          <select id="eva_letra" name="eva_letra" onchange="buscar_asigna();" class="minimal" style='width: 300px;height:30px;' required>
                            <option value="" disabled selected>Seleccione Letra</option>
                            <?php
                              while($row_eva_letra = mysqli_fetch_array($rs_eva_letra)){
                                $eva_letra_id     = $row_eva_letra['letra_id']; 
                                $eva_letra_nombre = $row_eva_letra['letra_nombre']; 
                                echo "<option value='$eva_letra_id'>$eva_letra_nombre</option>";                                 
                              }
                            ?>
                                    
                                    
                          </select> 
                        
                        </td>
                      </tr>

                      <tr>
                        
                        <th scope="col">Asignatura</th>
                        <td id="curso-tabla">     
                            <select id="eva_asigna" name="id" class="minimal" style='width:300px;height:30px;' required>
                              <option value="" disabled selected>Seleccione Asignatura</option>
                            </select> 
                        </td>
                      </tr>

                      <tr>
                        <th scope="col">Cantidad de evaluaciones</th>
                        <td id="curso-tabla">  
                          <input type='number' style='width: 300px;' id='cantidad_ev' name='cantidad_ev'  class='form-control validate' onkeyup='reemplazar(this)' placeholder='Ingrese cantidad de evaluaciones a agregar' required min='1' >
                        </td>
                      </tr>
                      
                    </table>
                    
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary">Siguiente</button>
                  </div>
                </form>
              </div>   
            </div>
          </div>   
        <!-- FIN cod del modal modifi -->
    </body>
</html>

<script>
  function ajax_modal_modifi(id_curso_asignatura){//FUNCION PARA GUARDAR EL ARCHIVO
         
    document.getElementById("modalx").innerHTML = "";
    console.log("hola id_tipo_arreglo: "+id_curso_asignatura);
         
    $.ajax({
      type: "POST",
      url: "modal_cantidad_evaluaciones.php",
      data:{ 
        id_curso_asignatura : id_curso_asignatura
        
      },
            
      success: function(results) {   
        console.log("gane: "+ results);    
        var  formolo = document.getElementById("modalx");
        //console.log("holax: formo: "+ formolo);
        formolo.innerHTML = results;
        $(document).ready(function () {
          $('#tabla_modal').dataTable();
          $('.dataTables_length').addClass('bs-select');
        });
        
      },
    });
  }
  function buscar_letra(eva_nivel){//FUNCION PARA GUARDAR EL ARCHIVO
    var  eva_letra = document.getElementById("eva_letra");    
    
    console.log("hola eva_nivel: "+eva_nivel);
    eva_letra.innerHTML = "<option value='' disabled selected>Seleccione Letra</option>";
    $.ajax({
      type: "POST",
      url: "calendario_pruebas_buscar_letra.php",
      data:{ 
        eva_nivel : eva_nivel.value
        
      },
            
      success: function(results) {   
        console.log("gane: "+ results);    
        
        
        eva_letra.innerHTML = results;
        
        
      },
    });
  }
  function buscar_asigna(){//FUNCION PARA GUARDAR EL ARCHIVO
    var  eva_asigna = document.getElementById("eva_asigna");
    var eva_nivel = document.getElementById("eva_nivel");
    var eva_letra = document.getElementById("eva_letra");
    var eva_periodo = document.getElementById("eva_periodo");
    console.log("hola eva_nivel: "+eva_nivel);
    
    eva_asigna.innerHTML = "<option value='' disabled selected>Seleccione Asignatura</option>";
    $.ajax({
      type: "POST",
      url: "calendario_pruebas_buscar_asigna.php",
      data:{ 
        eva_nivel : eva_nivel.value,
        eva_letra : eva_letra.value,
        eva_periodo : eva_periodo.value
        
      },
            
      success: function(results) {   
        console.log("gane: "+ results);    
        
        
        eva_asigna.innerHTML = results;
        
        
      },
    });
  }
</script>