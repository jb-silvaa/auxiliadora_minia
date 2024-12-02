<!--

<script src="http://code.jquery.com/jquery-latest.js"></script>

<script>

$(document).ready(function() {

      var refreshId =  setInterval( function(){

    $('#noti_act').load('menu-lateral.php');//actualizas el div

   }, 1000 );

});



</script>-->

<script src="../js-sc/getNotification.js"></script>

<?php

error_reporting(0);

session_start();

include('../funciones-sc/conexion.php');



$user_id = $_SESSION['id'];

if($user_id==null || $user_id== ''){

   echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../admin-sc/index.php'

    </script>";

    die();

}



$sql = "SELECT * FROM usuarios WHERE usuario_id = '$user_id'";

$rs = mysqli_query($conexion, $sql);

$row = mysqli_fetch_array($rs);

$perfil = $row['perfil_id'];
$acceso_ev = $row['usuario_acceso_ev'];
/*

if($perfil_archivo != $perfil && $perfil_archivo != '3'){



     echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Acceso no autorizado');

   window.location='../admin-sc/index.php'

    </SCRIPT>");

        mysqli_close($sql);

        session_destroy();

}*/



//BUSCO PERIODO ACTIVO

$sql_periodo = "SELECT * FROM periodos WHERE periodo_activo = '1'";

$rs_periodo = mysqli_query($conexion, $sql_periodo);

$row_periodo = mysqli_fetch_array($rs_periodo);



$periodo_activo = $row_periodo['periodo_periodo'];

?>

<script>

function goBack() {

    window.history.back();

}

</script> 

<?php

/*****************************************/



$sql_so = "SELECT count(solicitud_id) as so FROM solicitudes WHERE solicitud_estado = '1' AND solicitud_estado_id = '0' ANd solicitud_leido_admin = '0' AND receptor_usuario_id = ".$user_id;

$rs_so = mysqli_query($conexion, $sql_so);

$row_so = mysqli_fetch_array($rs_so);



$aprobado = "SELECT COUNT(carga_id) as ap FROM cargas car 

JOIN cursos_asignaturas ca on car.curso_asignatura_id = ca.curso_asignatura_id where car.carga_aprobacion = 0 AND carga_estado = '1' AND ca.curso_asignatura_periodo = '$periodo_activo' AND car.carga_leido_admin = '0'";

$rs_aprobado = mysqli_query($conexion, $aprobado);

$row_aprobado = mysqli_fetch_array($rs_aprobado);



$sql_eva = "SELECT count(evaluacion_id) as ev FROM evaluaciones as ev, cursos_asignaturas as ca WHERE ev.evaluacion_estado = '1' AND ev.curso_asignatura_id = ca.curso_asignatura_id ANd ev.evaluacion_leido_admin = '0' AND ca.curso_asignatura_periodo = '$periodo_activo'";

$rs_eva = mysqli_query($conexion, $sql_eva);

$row_eva = mysqli_fetch_array($rs_eva);



$evaluacion = $row_eva['ev'];

?>

<script>

function displayNotis(notis){

  $('#popup1').fadeIn('slow');

  $('.popup-overlay').fadeIn('slow');

  $('.popup-overlay').height($(window).height());

  console.log(notis);

  $.ajax({

    type: "POST",

    url: "../funciones-sc/update_notificacion.php",

    data: {data : notis, autor : 2},

    success: function(response, textStatus, XMLHttpRequest){

    //alert(response);

      },

    });

  return false;



  $('#cerrar1').on('click', function(){

    $('#popup1').fadeOut('slow');

    $('.popup-overlay').fadeOut('slow');

    return false;

  });

}

</script>

<script>

window.onload = getNotifications(<?=$user_id?>, 1);

</script>

<div class="popup-contenedor" style="display: none;" id="popup1">

  <div id="popupIndex" class="popup-form popup-largo scroll">

    <a href="" id="cerrar1"><i class="far fa-times-circle"></i></a>

    <h1>MENSAJE RECIBIDO</h1>

    <table id="tabla-notis" class="header2">

      <tr>

        <th>Remitente</th>

        <th>Fecha</th> 

        <th>Contenido</th>

        <th>Archivo</th>

        <th>Archivo</th>

      </tr>

		</table>

  </div>

</div>

<div class="popup-contenedor" style="display: none;" id="popup2">

  <div id="popupIndex" class="popup-form popup-largo scroll">

    <a href="" id="cerrar2"><i class="far fa-times-circle"></i></a>

    <h1>PLANIFICACIONES NUEVAS</h1>

    <table id="tabla-planis-r" class="header2">

      <tr>

        <th>Curso</th>

        <th>Asignatura</th> 

        <th>Unidad</th>

        <th>Fecha</th>

        <th>Ver</th>

      </tr>

	</table>

  </div>

</div>

<div class="popup-contenedor" style="display: none;" id="popup3">

  <div id="popupIndex" class="popup-form popup-largo scroll">

    <a href="" id="cerrar3"><i class="far fa-times-circle"></i></a>

    <h1>EVALUACIONES NUEVAS</h1>

    <table id="tabla-eval" class="header2">

      <tr>

        <th>Curso</th>

        <th>Asignatura</th> 

        <th>Nombre</th>

        <th>Fecha</th>

        <th>Ver</th>

      </tr>

	</table>

  </div>

</div>



<script>

$(document).ready(function(){

    $('#abrir1').on('click', function(){

        $('#popup1').fadeIn('slow');

        $('.popup-overlay').fadeIn('slow');

        $('.popup-overlay').height($(window).height());

        return false;

    });

 

    $('#cerrar1').on('click', function(){

        $('#popup1').fadeOut('slow');

        $('.popup-overlay').fadeOut('slow');

        return false;

    });

});

</script>



<script>

$(document).ready(function(){

    $('#abrir2').on('click', function(){

        $('#popup2').fadeIn('slow');

        $('.popup-overlay').fadeIn('slow');

        $('.popup-overlay').height($(window).height());

        document.getElementById("noti_3").style.visibility = "hidden";

        $.ajax({

            type: "POST",

            url: "../funciones-sc/update_notificacion.php",

            data: {id : <?=$user_id?>, tipo : 'planificacion' },

            success: function(response, textStatus, XMLHttpRequest){

                //alert(response);

            },

            error:function (){

                alert("mal");

            }

        });

        return false;

    });

 

    $('#cerrar2').on('click', function(){

        $('#popup2').fadeOut('slow');

        $('.popup-overlay').fadeOut('slow');

        return false;

    });

});

</script>



<script>

$(document).ready(function(){

    $('#abrir3').on('click', function(){

        $('#popup3').fadeIn('slow');

        $('.popup-overlay').fadeIn('slow');

        $('.popup-overlay').height($(window).height());

        document.getElementById("noti_4").style.visibility = "hidden";

        $.ajax({

            type: "POST",

            url: "../funciones-sc/update_notificacion.php",

            data: {id : <?=$user_id?>, tipo : 'evaluacion' },

            success: function(response, textStatus, XMLHttpRequest){

                //alert(response);

            },

            error:function (){

                alert("mal");

            }

        });

        return false;

    });

 

    $('#cerrar3').on('click', function(){

        $('#popup3').fadeOut('slow');

        $('.popup-overlay').fadeOut('slow');

        return false;

    });

});

</script>











<!-- CODIGO PARA ACTUALIZAR LA BD MEDIANTE AJAX -->



<script>

 function accion(tipo)

{

  tipo_get = tipo;

  //alert(tipo_get);

$.ajax({

        type:'POST', 

        url: 'update_notificacion_admin.php?id='+tipo_get,

        success:function (response, textStatus, XMLHttpRequest){

          $("#noti_"+tipo_get).css("color", "rgba(0,0,0,0)");

          $("#noti_"+tipo_get).css("background", "rgba(0,0,0,0)");

          //alert(response);

       },

       error:function (){

        alert("mal");

       }

     });



}

</script>

<!-- FIN -->

<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">-->
<link rel="stylesheet" href="../fontawesome/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/683d4d51cc.js" crossorigin="anonymous"></script>
<?php if($user_id != 49 && $user_id != 50){ ?>
<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">

            <ul class="nav sidebar-nav">

                <li class="sidebar-brand">

                    <img src="<?=$row['usuario_imagen']?>">

                    <div class="clear"></div>

                    <div class="notificaciones2" id="noti_act">

                        <a href="" id="abrir1"><img src="../images-sc/sobre.png"></a>

                        <a href="" id="abrir2"><img src="../images-sc/aprobado.png"></a>

                        <a href="" id="abrir3"><img src="../images-sc/notificacion.png"></a>



                        <h6 id="noti_1" style="visibility: hidden;">0</h6>

				        <h6 id="noti_3" style="visibility: hidden;">0</h6>

				        <h6 id="noti_4" style="visibility: hidden;">0</h6>

                      </div>

                </li>

                <div class="clear"></div>

                <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Listados <span class="caret"></span></a>

                      <ul class="dropdown-menu" role="menu">

                        <li class="dropdown-header">Listados</li>              

                        <?php if($_SESSION['perfil'] == '1' || $_SESSION['perfil'] == '3' || $_SESSION['perfil'] == '2'){ ?> <li><a href="listado_cursos.php">Listado Cursos</a></li> <?php } ?>

                        <?php if($_SESSION['perfil'] == '1'){ ?> <li><a href="listado_profesores_jefes.php">Listado Profesores Jefes</a></li> <?php } ?>

                        <?php if($_SESSION['perfil'] == '1'){ ?> <li><a href="listado_ayudantes.php">Listado Coordinadores</a></li> <?php } ?>

                        <?php if($_SESSION['perfil'] == '1'){ ?> <li><a href="listado_reuniones.php">Listado Reuniones</a></li> <?php } ?>

                      </ul>

                </li>

<?php

                //DISTINTOS MENUS DEPENDIENDO SI ES DOCENCIA O ADMIN

                if($_SESSION['perfil'] == '1' || $_SESSION['perfil'] == '3')

                {
                    if($_SESSION['perfil'] == '3')
                    {
                    ?>
                    <li>
                        <a href="calendario_pruebas.php">Calendario de pruebas</a>
                    </li>
                    <li>
                        <a href="calendario_pruebas_listado.php">Listado de pruebas</a>
                    </li>
                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Soporte <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">

                            <li><a href="#"><i class="fas fa-phone"></i> 7227 79936</a></li>

                            <li><a style="cursor: pointer;" data-toggle="modal" data-target="#Contacto"><i class="fas fa-envelope"></i> soporte@cdgo.cl</a></li>

                        </ul>  

                    </li>
                    <?php 
                    }
                    if($_SESSION['perfil'] == '1')

                    {

                    ?>
                    <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Calendario Ev. <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu"> 
                            <li class="dropdown-header">Listado Evaluaciones</li>                           
                            <li><a href="calendario_pruebas.php">Calendario de pruebas</a></li>
                            <li><a href="calendario_pruebas_listado.php">Listado de pruebas</a></li>
                            <li><a href="calendario_pdf.php">PDF Evaluaciones</a></li>
                        </ul>        

                    </li>

                    <!-- <li>

                        <a href="generar_periodo.php">Generar Periodo</a>

                    </li> -->

                    <li>

                        <a href="generar_anio.php">Generar Año Académico</a>

                    </li>

                    <?php 

                    }

                    ?>

                    <?php 

                    if($_SESSION['perfil'] == '1')

                    {

                    ?>
                    <li>
                        <a href="agregar_asignatura.php">Agregar Asignatura</a>
                    </li> 
                <?php 

                    }

                }    

                ?>

                  
                <!--
                     <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Solicitudes <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">

                            <li><a href="solicitudes_nuevo.php">Nueva Solicitud</a></li>

                            <li><a href="solicitudes_aprobadas.php">Solicitudes Aprobadas</a></li>

                            <li><a href="solicitudes_pendientes.php">Solicitudes Pendientes</a></li>

                            <li><a href="solicitudes_rechazadas.php">Solicitudes Rechazadas</a></li>

                        </ul>        

                    </li>-->

                <?php 

                //DISTINTOS MENUS DEPENDIENDO SI ES DOCENCIA O ADMIN

                if($_SESSION['perfil'] == '1')

                {

                ?>

                    <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mantenedores <span class="caret"></span></a>

                      <ul class="dropdown-menu" role="menu">

                        <li class="dropdown-header">Listado Mantenedores</li>

                        <!--<li><a href="alumnos.php">Alumnos</a></li>-->                   

                        <li><a href="asignaturas.php">Asignaturas</a></li>

                        <!--<li><a href="cursos.php">Cursos</a></li>-->

                        <!--<li><a href="niveles.php">Niveles</a></li>-->

                        <li><a href="jefes_area.php">Jefes de Área</a></li>

                        <li><a href="jefes_area_profe.php">Jefes Área - Profesores</a></li>             

                        <!-- <li><a href="niveles_asignaturas.php">Cursos Asignaturas</a></li> -->

                        <li><a href="niveles_generados.php">Niveles Generados</a></li>

                        <li><a href="asignatura_dificultad.php">Asig. Nivel Dificultad</a></li>

                        <li><a href="periodo_limite_lista.php">Años - Fecha Límite</a></li>

                        <li><a href="letras.php">Letras</a></li>            

                        <li><a href="profesores.php">Profesores</a></li>

                        <li><a href="usuarios.php">Usuarios</a></li>

                        <!--<li><a href="unidades.php">Unidades</a></li>-->

                      </ul>

                    </li>

                    <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mensajeria <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Listado Mensajería</li>
                            <li><a href="mensaje_nuevo.php">Nuevo Mensaje</a></li>

                            <li><a href="mensaje_lista.php">Mensajes</a></li>

                        </ul>        

                    </li>

                    <?php

                }

                if($_SESSION['perfil'] == '1' || $_SESSION['perfil'] == '3')

                {

                    ?>

                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Listado Reportes</li>
                            <li><a href="reportes.php">Reportes Planif.</a></li>

                            <li><a href="reportes_ev.php">Reportes Eval.</a></li>

                        </ul>  

                    </li>
                    <?php if($acceso_ev != -1){ ?>
                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Excel Evaluaciones <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Listado Excel</li>
                            <li><a href="bajar_excel.php">Descargar Excel</a></li>

                            <li><a href="subir_excel.php">Subir Excel</a></li>

                        </ul>  

                    </li>

                <?php 
                    }
                }

                if($_SESSION['perfil'] == '2')

                {

                ?>
                    <li><a href="calendario_pruebas.php">Calendario de pruebas</a></li>
                    <li>

                        <a href="docencia_evaluacion.php">Evaluaciones</a>

                    </li>

                <?php 

                }

                ?>

                <li>

                    <a onclick="goBack()" style="cursor: pointer;">Volver</a>

                </li>

                <li>

                    <a href="destroy.php">Salir</a>

                </li>

                <!--<li>

                    <a href="#">Contact</a>

                </li>

                <li>

                    <a href="https://twitter.com/maridlcrmn">Follow me</a>

                </li>-->

            </ul>

        </nav>
<?php }else{ ?>
    <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">

            <ul class="nav sidebar-nav">

                <li class="sidebar-brand">

                    <img src="<?=$row['usuario_imagen']?>">

                    <div class="clear"></div>

                </li>

                <div class="clear"></div>

                <li class="dropdown">

                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Calendario Ev. <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu"> 
                            <li class="dropdown-header">Listado Evaluaciones</li>                           
                            <li><a href="calendario_pruebas.php">Calendario de pruebas</a></li>
                            <li><a href="calendario_pruebas_listado.php">Listado de pruebas</a></li>
                            <li><a href="calendario_pdf.php">PDF Evaluaciones</a></li>
                        </ul>        

                    </li>
                <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Excel Evaluaciones <span class="caret"></span></a>

                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Listado Excel</li>
                            <li><a href="bajar_excel.php">Descargar Excel</a></li>

                            <li><a href="subir_excel.php">Subir Excel</a></li>

                        </ul>  

                    </li>
                    <li>

                    <a onclick="goBack()" style="cursor: pointer;">Volver</a>

                </li>

                <li>

                    <a href="destroy.php">Salir</a>

                </li>
                </ul>
            </nav>
<?php } ?>
        <!-- Modal -->
    <div class="modal fade" id="Contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Contacto CDGO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Material input -->
            <div class="md-form">                
              <label for="titulo">Ingrese Título del Mensaje</label>
              <input type="text" id="titulo" class="form-control">
            </div> 
            <!--Material textarea-->
            <div class="md-form">
              <label for="cuerpo">Ingrese Cuerpo del Mensaje</label>
              <textarea id="cuerpo" class="md-textarea form-control" rows="4"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="EnviarCorreo()">Enviar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>  
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $('.toast').toast('show');
        function EnviarCorreo(){  
            var titulo = document.getElementById("titulo").value;
            var cuerpo = document.getElementById("cuerpo").value;  
            $.ajax({ 
            url: "contacto_correo.php",
            type: "post",
            data: {titulo:titulo,cuerpo:cuerpo},
            success: function(data) {
              var datos = JSON.parse(data);
              var resultado = datos.resultado;
              //alert(resultado);
              if(resultado == 'ok')
              {
                $('#Contacto').modal('hide');
                toastr.success("Correo enviado correctamente! Nos contactaremos con usted a la brevedad.");
                document.getElementById("titulo").value = "";
                document.getElementById("cuerpo").value = "";
              }
              else
              {
                alert('me cai :(');
              }
            },
            error   : function(data) { }
    });        
        } 
    </script>