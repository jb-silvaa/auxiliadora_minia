<script src="https://code.jquery.com/jquery-3.2.1.js"></script>

<script src="../js-sc/getNotification.js"></script>

<?php 

//session_id();

$perfil=2;

session_start();

$user_id = $_SESSION['profesor_id'];

if($user_id==null || $user_id= ''){

   echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

    die();

}

//BUSCO PERIODO ACTIVO

$sql_periodo = "SELECT * FROM periodos WHERE periodo_activo = '1'";

$rs_periodo = mysqli_query($conexion, $sql_periodo);

$row_periodo = mysqli_fetch_array($rs_periodo);



$periodo_menu = $row_periodo['periodo_periodo'];

$user_id = $_SESSION['profesor_id'];

$profesor = $_SESSION['profesor_id']; //cambiar aqui para que el menu cambie al profesor correspondiente

$sql = "SELECT * FROM profesores WHERE profesor_id = '$profesor'";

$rs = mysqli_query($conexion, $sql);

$row = mysqli_fetch_array($rs);



/* CALCULO PORCENTAJE DE CURSOS CON INFO */



$sql_cursos = "SELECT curso_asignatura_unidades

               FROM cursos_asignaturas

               WHERE curso_asignatura_periodo = '$periodo_menu'

               AND profesor_id = '$profesor'

               AND curso_asignatura_estado = '1'

";

$rs_cursos = mysqli_query($conexion, $sql_cursos);

while($row_cursos = mysqli_fetch_array($rs_cursos))

{

  //CALCULO EL TOTAL DE ARCHIVOS A LLENAR POR EL PROFESOR

  $total = $total+($row_cursos['curso_asignatura_unidades']+1); 

}



$sql_cursos = "SELECT COUNT(carga_aprobacion) as aprobadas

               FROM cargas as c,

                    cursos_asignaturas as ca

               WHERE ca.curso_asignatura_periodo = '$periodo_menu'

               AND ca.profesor_id = '$profesor'

               AND ca.curso_asignatura_estado = '1'

               AND c.curso_asignatura_id = ca.curso_asignatura_id

               AND c.carga_aprobacion = '1'

";

$rs_cursos = mysqli_query($conexion, $sql_cursos);

$row_cursos = mysqli_fetch_array($rs_cursos);



$aprobada = $row_cursos['aprobadas'];



//TRANSFORMO A PORCENTAJES

if($aprobada == '0'){

  $porc_aprob = '0';

}

else

{

  $porc_aprob = round(($aprobada*100/$total),2);

}

$porc_reprob = 100-$porc_aprob;

/*****************************************/



$sql_so = "SELECT count(solicitud_id) as so FROM solicitudes WHERE solicitud_estado = '1' AND solicitud_estado_id = '0' ANd solicitud_leido = '1' AND receptor_profesor_id = ".$profesor;

$rs_so = mysqli_query($conexion, $sql_so);

$row_so = mysqli_fetch_array($rs_so);



$aprobado = "SELECT COUNT(carga_id) as ap FROM cargas car 

JOIN cursos_asignaturas ca on car.curso_asignatura_id = ca.curso_asignatura_id where car.carga_aprobacion = 1 AND carga_estado = '1' AND ca.curso_asignatura_periodo = '$periodo_menu' AND car.carga_leido = '1' AND ca.profesor_id = '$profesor'";

$rs_aprobado = mysqli_query($conexion, $aprobado);

$row_aprobado = mysqli_fetch_array($rs_aprobado);



$reprobado= "SELECT COUNT(carga_id) as re FROM cargas car 

JOIN cursos_asignaturas ca on car.curso_asignatura_id = ca.curso_asignatura_id where car.carga_aprobacion = '-1' AND carga_estado = '1' AND ca.curso_asignatura_periodo = '$periodo_menu' AND car.carga_leido = '-1' AND ca.profesor_id = '$profesor'";

$rs_reprobado = mysqli_query($conexion, $reprobado);

$row_reprobado = mysqli_fetch_array($rs_reprobado);



$sql_ev = "SELECT count(evaluacion_id) as ev FROM evaluaciones as ev, cursos_asignaturas as ca WHERE ev.evaluacion_estado = '1' AND ev.curso_asignatura_id = ca.curso_asignatura_id ANd ev.evaluacion_leido = '1' AND ca.curso_asignatura_periodo = '$periodo_menu' AND ca.profesor_id = ".$profesor;

$rs_ev = mysqli_query($conexion, $sql_ev);

$row_ev = mysqli_fetch_array($rs_ev);



$evaluacion = $row_ev['ev'];

?>



<script>

//MUESTRA EL POPUP SOLO PARA LOS MENSAJES, PARA PLANIS Y EVAL MUESTRA EL ICONO DE QUE HAY NOTIFICACION

function displayNotis(notis){

    $('#popup1').fadeIn('slow');

    $('.popup-overlay').fadeIn('slow');

    $('.popup-overlay').height($(window).height());

    console.log(notis);

    $.ajax({

        type: "POST",

        url: "../funciones-sc/update_notificacion.php",

        data: {data : notis, autor : 1},

        success: function(response, textStatus, XMLHttpRequest){

            //alert(response);

        },

        error:function (){

            alert("mal");

        }

    });

    return false;



  /*   $('#cerrar1').on('click', function(){

        $('#popup1').fadeOut('slow');

        $('.popup-overlay').fadeOut('slow');

        return false;

    }); */

}

</script>



<script>

window.onload = getNotifications(<?=$profesor?>,2);

</script>



<!---<script>

 function accion(tipo)

{

  tipo_get = tipo;

  //alert(tipo_get);

$.ajax({

        type:'POST', 

        url: 'update_notificacion.php?id='+tipo_get,

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

</script>-->



<!--------AJAXXXXXXX SCRIPT Y ACTUALIZACION DE BD PARA MENSAJE-->

<script>

function mens(){

  $.ajax({

    type:"POST",

    url:"update_mensajes.php",

    success:function(response, textStatus, XMLHttpRequest){

    //alert(response);

       },

       error:function (){

        alert("mal");

       }

     });

}

</script>

<!--FIN DE AJAX-->

<?php

$sql_mostrar = "SELECT  COUNT(m.profesor_id) as totalmsg FROM mensajes as m 

                                join profesores as p on m.profesor_id = p.profesor_id

                                join usuarios as u on m.usuario_id = u.usuario_id

                                WHERE m.mensaje_estado = '1' and m.mensaje_leido = '0' and m.profesor_id = '$user_id' and m.mensaje_emisor = '1'";

                               

$rs_mostrar = mysqli_query($conexion, $sql_mostrar);

$row_mostrar= mysqli_fetch_array($rs_mostrar);



/* //AQUI SALTA EL POPUP

 if($row_mostrar['totalmsg'] != 0){

 ?>



<div class="popup-contenedor" id="popupIndex">

  <div class="popup-form popup-largo scroll"> 

    <a  href="" id="cerrarIndex" onclick="mens()"><i class="far fa-times-circle"> Cerrar </i></a>

 <h1>MENSAJE RECIBIDO</h1>

                                <?php 

                       $sql_msg = "SELECT * 

                                FROM mensajes as m 

                                join profesores as p on m.profesor_id = p.profesor_id

                                join usuarios as u on m.usuario_id = u.usuario_id

                                WHERE m.mensaje_estado = '1' and m.mensaje_leido = '0' and m.profesor_id = '$user_id'";

                        

                        $rs_msg = mysqli_query($conexion, $sql_msg);

                        while($row_msg = mysqli_fetch_array($rs_msg))

                        {

                                   echo " <table class='header2'> 



                                   <tr>

                                   <th>REMITENTE</th>

                                    <td>".$row_msg['usuario_nombres']." ".$row_msg['usuario_apellidos']."</td> 

                                    </tr>

                                  <tr> 

                                    <th>FECHA</th>

                                    <td>".$row_msg['mensaje_fecha']."</td>

                                    </tr> 

                                    <tr>

                                    <th>CONTENIDO</th>   

                                    <td style='width:480px;'>".$row_msg['mensaje_cuerpo']."</td>

                                    </tr>

                                    <tr>

                                     <th>ARCHIVO</th>";

                                     if($row_msg['mensaje_archivo']==''){

                                      echo "<td> -- </td></tr>

                                      </table>";

                                     }else{

                                       echo "<td><a style='float:none;' href='../profesor-sc/".$row_msg['mensaje_archivo']."'><i class='fas fa-search fa-lg'></i></a></td></tr>

                                       </table>";

                                     }

                                  }

                                  ?>

  </div>

</div>

<?php

}*/

?> 





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

        <th>Ver</th>

      </tr>

		</table>

  </div>

</div>

<div class="popup-contenedor" style="display: none;" id="popup2">

  <div id="popupIndex" class="popup-form popup-largo scroll">

    <a href="" id="cerrar2"><i class="far fa-times-circle"></i></a>

    <h1>PLANIFICACIONES APROBADAS</h1>

    <table id="tabla-planis-a" class="header2">

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

    <h1>PLANIFICACIONES REPROBADAS</h1>

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

<div class="popup-contenedor" style="display: none;" id="popup4">

  <div id="popupIndex" class="popup-form popup-largo scroll">

    <a href="" id="cerrar4"><i class="far fa-times-circle"></i></a>

    <h1>EVALUACIONES</h1>

    <table id="tabla-eval" class="header2">

      <tr>

        <th>Curso</th>

        <th>Asignatura</th> 

        <th>Evaluacion</th>

        <th>Fecha</th>

        <th>Ver</th>

        <th>Estado</th>

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

        document.getElementById("noti_2").style.visibility = "hidden";

        $.ajax({

            type: "POST",

            url: "../funciones-sc/update_notificacion.php",

            data: {id : <?=$profesor?>, tipo : 'planificacion' },

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

        document.getElementById("noti_3").style.visibility = "hidden";

        $.ajax({

            type: "POST",

            url: "../funciones-sc/update_notificacion.php",

            data: {id : <?=$profesor?>, tipo : 'planificacion' },

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



<script>

$(document).ready(function(){

    $('#abrir4').on('click', function(){

        $('#popup4').fadeIn('slow');

        $('.popup-overlay').fadeIn('slow');

        $('.popup-overlay').height($(window).height());

        document.getElementById("noti_4").style.visibility = "hidden";

        $.ajax({

            type: "POST",

            url: "../funciones-sc/update_notificacion.php",

            data: {id : <?=$profesor?>, tipo : 'evaluacion' },

            success: function(response, textStatus, XMLHttpRequest){

                //alert(response);

            },

            error:function (){

                alert("mal");

            }

        });

        return false;

    });

 

    $('#cerrar4').on('click', function(){

        $('#popup4').fadeOut('slow');

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

        url: 'update_notificacion.php?id='+tipo_get,

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

<div class="perfil-izq">

<link rel="stylesheet" type="text/css" href="../css-sc/btn_cerrarsession.css">

      <?php 

      if($row['profesor_imagen'] == '')

      {

        print "<img src='../images-sc/foto-sinperfil.png'>";

      }

      else

      {

      ?>

      <img src="<?=$row['profesor_imagen']?>">

      <?php 

      }

      ?>

      <div class="notificaciones">

        <a href="" id="abrir1"><img src="../images-sc/sobre1.png"></a>

        <a href="" id="abrir2"><img src="../images-sc/aprobado1.png"></a>

        <a href="" id="abrir3"><img src="../images-sc/rechazado1.png"></a>

        <a href="" id="abrir4"><img src="../images-sc/evaluacion1.png"></a>

        

        <h6 id="noti_1" style="visibility: hidden;">0</h6>

        <h6 id="noti_2" style="visibility: hidden;">0</h6>

        <h6 id="noti_3" style="visibility: hidden;">0</h6>

        <h6 id="noti_4" style="visibility: hidden;">0</h6>

</div>

<?php 

/********** GENERO POPUP CON LAS DISTINTAS NOTIFICACIONES **********/



/*******************************************************************/

?>      

<h1>Personal</h1>  

        <label>Nombre</label>

        <p><?=$row['profesor_nombres']." ".$row['profesor_apellidos']?></p>

        <label>Rut</label>

        <p><?=$row['profesor_rut']?></p>

        <label>Fecha Nacimiento</label>

        <p><?=$row['profesor_fecha_nacimiento']?></p>

        <h1>Contacto</h1>

        <label>Correo</label>

        <p><?=$row['profesor_correo']?></p>

        <label>Correo Personal</label>

        <p><?php if($row['profesor_correo_personal'] == ''){ echo '--'; }else{ echo $row['profesor_correo_personal']; } ?></p>

        <label>Teléfono</label>

        <p><?=$row['profesor_fono']?></p>

        <h1>Resumen Cursos</h1>      

        <div style="width: 300px;  float: left; height: 14px; border: 1px solid #000;">

          <div style="width: <?=$porc_aprob?>%;  float: left; background: #222; height: 12px;"><p class="porcentaje"><?=$porc_aprob?>%</p></div>

          <div style="width: <?=$porc_reprob?>%; float: left; background: #999; height: 12px;"></div>

      </div>



  </div>

  <?php 

  include('menu-superior.php');

  ?>