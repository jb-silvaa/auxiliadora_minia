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

  <script src="validar_asignatura.js"></script>

  <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

<!------ Include the above in your HEAD tag ---------->



    <div id="wrapper">

        <div class="overlay"></div>

    

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

                    <div class="col-lg-12">

                        <h1>NUEVA SOLICITUD</h1>

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

                                      <a href="../profesor-sc/<?=$row_info['solicitud_archivo']?>"><i class='fas fa-search fa-lg'></i></a>

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



                                      $solicitante_h = $row_usuario['profesor_nombres']." ".$row_usuario['profesor_apellidos'];

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

                                <h1>AGREGAR COMENTARIO</h1>

                                <form method="POST" action="solicitudes_modificar_proc.php" enctype="multipart/form-data">

                                <label>Descripción</label>

                                <textarea placeholder="INGRESE DESCRIPCIÓN" name="desc" onkeyup="reemplazar(this);"></textarea> 

                                <label>Archivo (Opcional)</label> 

                                <input type="file" name="files">  

                                <input type="hidden" name="id" value="<?=$id?>">

                                <label>Nuevo Receptor</label>

                                <select name="usuario" class="minimal" style="margin-left: 8%;" required="">

                                  <option value="">Seleccione Receptor</option>

                                  <optgroup label="Administradores">

                                  <?php 

                                  $sql_receptor = "SELECT * FROM usuarios WHERE usuario_estado = '1' AND usuario_id <> '0'";

                                  $rs_receptor = mysqli_query($conexion, $sql_receptor);

                                  while ($row_receptor = mysqli_fetch_array($rs_receptor)) {

                                    echo "<option value='1_".$row_receptor['usuario_id']."'>".$row_receptor['usuario_nombres']." ".$row_receptor['usuario_apellidos']."</option>";

                                  }

                                  ?>

                                  </optgroup>                                  

                                  <optgroup label="Profesores">

                                  <?php

                                  $sql_receptor = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0'";

                                  $rs_receptor = mysqli_query($conexion, $sql_receptor);

                                  while ($row_receptor = mysqli_fetch_array($rs_receptor)) {

                                    echo "<option value='2_".$row_receptor['profesor_id']."'>".$row_receptor['profesor_nombres']." ".$row_receptor['profesor_apellidos']."</option>";

                                  }

                                  ?>

                                  </optgroup>

                                </select>       

                            </div>

                            <div class="info-100">

                                <input type="submit" value="INGRESAR">

                            </div>   

                        </form>

                        <h1>FINALIZAR SOLICITUD</h1>

                        <form method="POST" action="solicitudes_finalizar_proc.php" enctype="multipart/form-data">

                          

                          <label>Observación Final</label>

                          <textarea placeholder="INGRESE OBSERVACIÓN FINAL" name="obs" onkeyup="reemplazar(this);"></textarea>

                          <div class="info-50 margin-25">

                            <label>Aprobar</label>

                            <input type="radio" name="estado" value="1">

                            <input type="hidden" name="id" value="<?=$id?>">

                            <label>Rechazar</label>

                            <input type="radio" name="estado" value="-1">                            

                          </div>

                          <div class="info-100">

                            <input type="submit" value="Finalizar">

                          </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

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