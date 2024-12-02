<?php 

include('../funciones-sc/conexion.php');

$id = $_GET['id'];



$sql_info = "SELECT * 

             FROM solicitudes as s, tipos_solicitudes as ts 

             WHERE s.solicitud_id = '$id'

             AND s.tipo_solicitud_id = ts.tipo_solicitud_id";

$rs_info = mysqli_query($conexion, $sql_info);

$row_info = mysqli_fetch_array($rs_info);



if($row_info['usuario_id']  == '0')

{

  $sql_nombre = "SELECT * FROM profesores WHERE profesor_id = ".$row_info['profesor_id'];

  $rs_nombre = mysqli_query($conexion, $sql_nombre);

  $row_nombre = mysqli_fetch_array($rs_nombre);



  $solicitante = $row_nombre['profesor_nombres']." ".$row_nombre['profesor_apellidos'];

}

else

{

  $sql_nombre = "SELECT * FROM usuarios WHERE usuario_id = ".$row_info['usuario_id'];

  $rs_nombre = mysqli_query($conexion, $sql_nombre);

  $row_nombre = mysqli_fetch_array($rs_nombre);



  $solicitante = $row_nombre['usuario_nombres']." ".$row_nombre['usuario_apellidos'];

}

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

  <link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

  <link 

  href="../css-sc/iphone.css"

  media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

  rel="stylesheet">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

  <title>Perfil Docente</title>

  <?php 

    include('../fonts/fonts.php');

    //include('../js-sc/bootstrap.php'); 

  ?>

  <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>



<div class="contenedor-100">

  <?php 

  include('menu-lateral.php');

  ?>

  <div class="perfil-der">

      <h1>DATOS SOLICITUD</h1>

            <div class="container">                      

                            <div class="info-100">

                                <table class="header2">

                                  <tr>

                                    <th>Tipo Solicitud</th>

                                    <td><?=$row_info['tipo_solicitud_nombre']?></td>

                                  </tr>

                                  <tr>

                                    <th>Solicitante</th>

                                    <td><?=$solicitante?></td>

                                  </tr>

                                  <tr>

                                    <th>Fecha Creación</th>

                                    <td><?=$row_info['solicitud_fecha_creacion']?></td>

                                  </tr>

                                  <tr>

                                    <th>Descripción</th>

                                    <td><?=$row_info['solicitud_cuerpo']?></td>

                                  </tr>

                                  <tr>

                                    <th>Archivo</th>

                                    <td>

                                      <?php

                                      if($row_info['solicitud_archivo'] == '')

                                      {

                                        echo "--";

                                      }

                                      else

                                      {

                                      ?>

                                      <a href="<?=$row_info['solicitud_archivo']?>"><i class='fas fa-search fa-lg'></i></a>

                                      <?php 

                                      }

                                      ?>

                                    </td>

                                  </tr>

                                </table>

                                <table class="header2">

                                  <tr>

                                    <th>Usuario</th>

                                    <th>Fecha</th>

                                    <th>Comentario</th>

                                    <th>Archivo</th>

                                  </tr>

                                  <?php 

                                  $sql_historial = "SELECT * FROM solicitudes_historial WHERE solicitud_id = '$id'";

                                  $rs_historial = mysqli_query($conexion, $sql_historial);

                                  while ($row_historial = mysqli_fetch_array($rs_historial)) {

                                    if($row_historial['usuario_id'] != '0')

                                    {

                                      $sql_usuario = "SELECT * FROM usuarios WHERE usuario_id = ".$row_historial['usuario_id'];

                                      $rs_usuario = mysqli_query($conexion, $sql_usuario);

                                      $row_usuario = mysqli_fetch_array($rs_usuario);



                                      $solicitante_h = $row_usuario['usuario_nombres']." ".$row_usuario['usuario_apellidos'];

                                    }

                                    if($row_historial['profesor_id'] != '0')

                                    {

                                      $sql_usuario = "SELECT * FROM profesores WHERE profesor_id = ".$row_historial['profesor_id'];

                                      $rs_usuario = mysqli_query($conexion, $sql_usuario);

                                      $row_usuario = mysqli_fetch_array($rs_usuario);



                                      $solicitante_h = $row_usuario['profesore_nombres']." ".$row_usuario['profesor_apellidos'];

                                    }

                                    echo "<tr><td>".$solicitante_h."</td><td>".$row_historial['solicitud_historial_fecha']."</td><td>".$row_historial['solicitud_historial_comentario']."</td>";

                                    if($row_historial['solicitud_historial_archivo'] != '')

                                    {

                                      echo "<td><a href='../profesor-sc/".$row_historial['solicitud_historial_archivo']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";

                                    }

                                    else

                                    {

                                      echo "<td>--</td></tr>";

                                    }

                                  }

                                  ?>

                                </table>

                              </div>

                              <div class="perfil-90">

                                  <h1>AGREGAR COMENTARIO</h1>

                              </div>

                              <div class="container">

                                

                                <form method="POST" action="solicitudes_modificar_proc.php" enctype="multipart/form-data">

                                <label>Descripción</label>

                                <textarea placeholder="INGRESE DESCRIPCIÓN" name="desc" onkeyup="reemplazar(this);"></textarea> 

                                <label>Archivo (Opcional)</label> 

                                <input type="file" name="files">  

                                <input type="hidden" name="id" value="<?=$id?>">

                                <label>Nuevo Receptor</label>

                                <select name="usuario" class="minimal"  required="">

                                  <option value="">Seleccione Receptor</option>

                                  <?php 

                                  $sql_receptor = "SELECT * FROM usuarios as u, perfiles as p WHERE usuario_estado = '1' AND p.perfil_id = u.perfil_id";

                                  $rs_receptor = mysqli_query($conexion, $sql_receptor);

                                  while ($row_receptor = mysqli_fetch_array($rs_receptor)) {

                                    echo "<option value='1_".$row_receptor['usuario_id']."'>".$row_receptor['usuario_nombres']." ".$row_receptor['usuario_apellidos']." (".$row_receptor['perfil_nombre'].")</option>";

                                  }/*

                                  $sql_receptor = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0'";

                                  $rs_receptor = mysqli_query($conexion, $sql_receptor);

                                  while ($row_receptor = mysqli_fetch_array($rs_receptor)) {

                                    echo "<option value='2_".$row_receptor['profesor_id']."'>".$row_receptor['profesor_nombres']." ".$row_receptor['profesor_apellidos']."</option>";

                                  }*/

                                  ?>

                                </select>       

                            </div>

                            <div class="info-100">

                                <input type="submit" value="INGRESAR">

                            </div>   

                        </form>

                      </div>                      

                      <h1>CERRAR SOLICITUD</h1> 

                      <div class="container">                     

                        <form method="POST" action="solicitudes_finalizar_proc.php" enctype="multipart/form-data">

                          

                          <label>Observación Final</label>

                          <textarea placeholder="INGRESE OBSERVACIÓN FINAL" name="obs" onkeyup="reemplazar(this);"></textarea>

                          <div class="info-50 margin-25">

                            <label>Aprobar</label>

                            <input type="radio" name="estado" value="1" style="width: 20px;">

                            <input type="hidden" name="id" value="<?=$id?>">

                            <label>Rechazar</label>

                            <input type="radio" name="estado" value="-1" style="width: 20px;">                            

                          </div>

                          <div class="info-100">

                            <input type="submit" value="Finalizar">

                          </div>

                        </form>

                    </div>

                </div>

            </div>

</body>

</html>

<script type="text/javascript">

    function archivo(evt) {

      var files = evt.target.files; // FileList object

       

        //Obtenemos la imagen del campo "file". 

      for (var i = 0, f; f = files[i]; i++) {         

           //Solo admitimos imágenes.

           if (!f.type.match('image.*')) {

                continue;

           }

       

           var reader = new FileReader();

           

           reader.onload = (function(theFile) {

               return function(e) {

               // Creamos la imagen.

                      document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');

               };

           })(f);

 

           reader.readAsDataURL(f);

       }

}

             

      document.getElementById('files').addEventListener('change', archivo, false);

</script>