<?php 
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
  session_start();
  /*if(!$_SESSION){
      print '<script language="javascript">
          alert("Error: Usuario No Autenticado"); 
          self.location = "index.php";
          </script>';
  }*/
  include ('../funciones-sc/conexion.php');
$curso1 = $_GET['curso'];
if($curso1 != ''){
    $result = explode("-",$curso1);
    $nivel = $result[0];
    $letra = $result[1];
    //$orden = "ORDER BY letras.letra_id = '$letra', niveles.nivel_id = '$nivel' DESC, niveles.nivel_nombre ASC";
}else{
    $orden = "";
}

  $sql_periodo = "SELECT periodo_periodo 
                                            FROM periodos 
                                            WHERE periodo_estado = '1'
                                              AND periodo_activo = '1'
                                            LIMIT 1";
                            $rs_periodo = mysqli_query($conexion, $sql_periodo);
                            $row_periodo = mysqli_fetch_array($rs_periodo);
                            $eval_periodo = $row_periodo['periodo_periodo'];


  //BUSCO LOS MANTENIMIENTOS
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
$sql_evas = "SELECT * FROM evaluaciones as e
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
        /*AND e.evaluacion_aprobacion <> '-1'*/
        $nivel_filtro
        $letra_filtro";
$rs_evas = mysqli_query($conexion, $sql_evas);

  $pagina_titulo = " | Calendarios";
  $hoy = "'".date('Y-m-d')."'";
  //alerta
  switch ($_GET['z']) {
    case 'add_exi':
      $alerta_tipo = "success";
      $alerta_texto = "Calendario agregado con éxito";
      $alerta = true;
      break;
    case 'add_err':
      $alerta_tipo = "error";
      $alerta_texto = "Error al agregar Calendario";
      $alerta = true;
      break;
    case 'modi_exi':
      $alerta_tipo = "success";
      $alerta_texto = "Calendario modificado con éxito";
      $alerta = true;
      break;
    case 'modi_err':
      $alerta_tipo = "error";
      $alerta_texto = "Error al modificar Calendario";
      $alerta = true;
      break;
    case 'elim_exi':
      $alerta_tipo = "success";
      $alerta_texto = "Calendario eliminado con éxito";
      $alerta = true;
      break;
    case 'elim_err':
      $alerta_tipo = "error";
      $alerta_texto = "Error al eliminar Calendario";
      $alerta = true;
      break;
    default:
      $alerta = false;
  }
//alerta
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags always come first -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Listado Calendarios</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  
  <!-- stylos propios -->
  <link href="../css/style.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="../css/mdb.min.css?version=2" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="../css/style.css" rel="stylesheet">
  <!-- MDBootstrap Datatables  -->
  <link href="../css/addons/datatables.min.css" rel="stylesheet">
  <link href="../css/calendario_css.css" rel="stylesheet" type="text/css">
  <!-- JQuery -->
  <script type="text/javascript" src="../js/jquery-3.4.1.min.js"></script>

  <script type="text/javascript" src="../js/qrcode/jquery-ui.js"></script>
  <script type="text/javascript" src="../js/qrcode/qrcode.js"></script>
  <script type="text/javascript" src="../js/qrcode/download.js"></script>
  <link href="../css/jquery-ui.css" rel="stylesheet">
  <style type="text/css">
    .ui-widget-header{
      background: #243a51;
      color: #fff; 
      font-family: Roboto, Arial;
    }

    .ui-dialog-titlebar-close{
      background-image: url("../css/images/cross.png");
    }
  </style>
  <!-- mensajes de alerta de estado al cargar la pagina -->
  <script type='text/javascript'>
    $(document).ready(function () {
      var alerta = "<?php echo $alerta;?>";
      var alerta_texto = "<?php echo $alerta_texto;?>";
      var alerta_tipo = "<?php echo $alerta_tipo;?>";
      //console.log("hola alerta = "+alerta);
        if(alerta == true){ 
          if(alerta_tipo == "info"){
            toastr.info(alerta_texto);
          }else if(alerta_tipo == "warning"){
            toastr.warning(alerta_texto);
          }else if(alerta_tipo == "success"){
            toastr.success(alerta_texto);
          }else if(alerta_tipo == "error"){
            toastr.error(alerta_texto);
          }
        }
    });
   </script>
   <script type='text/javascript'>
    function ajax_modal_modifi(id_area){//FUNCION PARA GUARDAR EL ARCHIVO
        
      document.getElementById("modalx").innerHTML = "";
      //console.log("hola id_area: "+id_area);
      
      $.ajax({
        type: "POST",
        url: "archivos/areas_modal_modificar.php",
        data:{ 
          id_area : id_area
        },
          
        success: function(results) {   
          //console.log("gane: "+ results);    
          var  formolo = document.getElementById("modalx");
          //console.log("holax: formo: "+ formolo);
          formolo.innerHTML = results;
        
        },
      });
    
    }
     
    function modifi_submit(){
      var cloroformo = document.getElementById('modifi_formo');
      //x alguna razon el submit del modal no funciona. aunq el boton sea submit
      cloroformo.submit();
    }    
  </script>
</head>

<body class="hidden-sn black-skin">
  <!--Main Layout-->
  <main>
    <div class="container-fluid">
    <form method="GET" action="">
      <div class="row">
          <div class="col-4"></div>
          <div class="col-3">
              <!--Blue select-->
              <select class="mdb-select md-form colorful-select dropdown-ins" name="curso">
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
              <label>Seleccione Curso</label>                
              <!--/Blue select-->
            
          </div>
          <div class="col-1">
            <button type="submit" class="btn btn-amber">Filtrar</button>
          </div>
          <div class="col-1">
          </div>
          <div class="col-3">
            <button class="btn btn-primary btn-nuevo" onclick="window.print();"><i class="fas fa-print"  style="color:#ffffff;" title="Imprimir"></i> Imprimir</button>
        </div>  
      </div>
    </form>
          <div id="calendar"></div> 
        </div>
      </div>      
    </div>
  </main>
  
  <!-- SCRIPTS -->
  
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="../js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="../js/mdb.min.js"></script>
  <!-- MDBootstrap Datatables  -->
  <script type="text/javascript" src="../js/addons/datatables.min.js"></script>
  
  <!-- script que inicia cosas. como el select y los js de tablas -->
  <script src="../js-sc/moment.min.js"></script>
  <script src="../js-sc/fullcalendar.min.js"></script>
  <!--archivos para calendario -->
  <script type="text/javascript">
    $('#calendar').fullCalendar({    
    plugins: ['dayGrid' ],
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'year,month,agendaWeek,agendaDay,listWeek'
    },
    validRange: {
    start: '<?=$eval_periodo?>-01-01',
    end: '<?=$eval_periodo?>-12-31'
    },
    firstDay: 1,
    defaultDate: <?=$hoy?>,
    navLinks: true,
    <?php
    if($_SESSION['perfil'] != '2')
    {
    ?>
    editable: true,
    <?php
    }
    else
    {
    ?>
    editable: false,
    <?php 
    }
    ?>
    eventLimit: true,
    eventRender: function (eventObj, $el) {
        $el.popover({
            title: eventObj.title,
            content: eventObj.description,
            trigger: 'hover',
            placement: 'top',
            container: 'body',
            html: true,
        });
    },
    events: [
        <?php 
        while($row = mysqli_fetch_array($rs_evas)){
        $impreso = $row['evaluacion_estado_id'];
        $ca_id = $row['curso_asignatura_id'];
        $evaluacion_archivo = $row['evaluacion_archivo'];
        $id = $row['evaluacion_id'];
        $curso = $row['nivel_nombre']."-".$row['letra_nombre'];
        $nombre_completo = $row['asignatura_nombre']." || ".$curso."-".$row['dificultad_nombre']." || Profesor: ".$row['profesor_nombres']." ".$row['profesor_apellidos']." || ".$row['tipo_evaluacion_nombre'];
        if($row['evaluacion_archivo'] == '' || $row['evaluacion_archivo'] == NULL){
            $color = "#ff0000";
            $font = "#000000";
        }else if($row['evaluacion_aprobacion'] == '1'){
            $color = "#008000";
            $font = "#000000";
        }else{
            $color = "#0000ff";
            $font = "#000000";
        }
        //APARTE REVISO SI ESTA IMPRESA, DE SER ASI PASO DE VERDE A NARANJO 
        if($impreso == '3')
        {
          $color = "#6c7a89";
          $font = "#000000";
        }
        //Y SI ESTA RECHAZADA OTRO COLOR
        if($row['evaluacion_aprobacion'] == '-1'){
            $color = "#000000";
            $font = "#ffffff";
        }
        //REVISO SI EL CURSO PERTENECE AL COORD
        //$evaluacion_revisada = "evaluacion.php?id=$ca_id&id2=$id";
        //asi estaba ahora la cambiare a que descargue la prueba directamente en caso de estar revisada o mas
        if($_SESSION['perfil'] != '2')
          $evaluacion_revisada = "evaluacion.php?id=$ca_id&id2=$id";
        else
          $evaluacion_revisada = "../profesor-sc/$evaluacion_archivo";
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
        } ?>
        { 
            id: '<?php echo $id; ?>',
            title: '<?php echo $nombre_completo; ?>',
            start: '<?php echo $row['evaluacion_fecha']; ?>',
            description: '<?php echo $row['evaluacion_nombre']; ?>',
            curso: '<?=$curso?>',
            asignatura: '<?php echo $row['asignatura_nombre']; ?>',
            dificultad: '<?php echo $row['dificultad_nombre']; ?>',
            color: '<?php echo $color; ?>',
            textColor: '<?php echo $font; ?>'
            <?php
            if($_SESSION['perfil'] != '2')
            {
            ?>,    
            url: '<?=$evaluacion_revisada?>'
            <?php }else{ 
              if(is_file($evaluacion_revisada)){
            ?>,
              url: '<?=$evaluacion_revisada?>'
            <?php } } ?>
        },

      <?php
    }    
    ?>

    ],
eventClick: function(event) {
    if (event.url) {
        window.open(event.url, "_blank");
        return false;
    }
},
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
    //script del datatables //
    $(document).ready(function () {
      $('#dtBasicExample').dataTable();
      $('.dataTables_length').addClass('bs-select');
      
    });

    

    $('.toast').toast('show');

    // MDB Lightbox Init
   /* $(function () {
    $("#mdb-lightbox-ui").load("mdb-addons/mdb-lightbox-ui.html");
    });*/

    // SideNav Initialization
    $(".button-collapse").sideNav();

    // Material Select Initialization
    $(document).ready(function() {
    $('.mdb-select').materialSelect();
    });

    $('#input_starttime').pickatime({
    // 12 or 24 hour
    twelvehour: true,
    });

    // Data Picker Initialization
    $('.datepicker').pickadate();


  
    $(function () {
      $('.min-chart#chart-sales').easyPieChart({
        barColor: "#4caf50", size: 300,
        onStep: function (from, to, percent) {
          $(this.el).find('.percent').text(Math.round(percent));
        }
      });
      $('.min-chart#chart-sales2').easyPieChart({
        barColor: "#0d47a1", size: 300,
        onStep: function (from, to, percent) {
          $(this.el).find('.percent').text(Math.round(percent));
        }
      });
      $('.min-chart#chart-sales3').easyPieChart({
        barColor: "#d50000", size: 300,
        onStep: function (from, to, percent) {
          $(this.el).find('.percent').text(Math.round(percent));
        }
      });
    });    
  </script>
</body>
</html>