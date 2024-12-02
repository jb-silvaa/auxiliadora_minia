<?php 
include('../funciones-sc/conexion.php');
$id = $_GET['id'];

$sql_info = "SELECT * 
             FROM mensajes
             WHERE mensaje_id = '$id'";
$rs_info = mysql_query($sql_info, $conexion);
$row_info = mysql_fetch_array($rs_info);

if($row_info['mensaje_emisor']  == '0')
{
  $sql_nombre = "SELECT * FROM profesores WHERE profesor_id = ".$row_info['profesor_id'];
  $rs_nombre = mysql_query($sql_nombre, $conexion);
  $row_nombre = mysql_fetch_array($rs_nombre);

  $solicitante = $row_nombre['profesor_nombres']." ".$row_nombre['profesor_apellidos'];
}
else
{
  $sql_nombre = "SELECT * FROM usuarios WHERE usuario_id = ".$row_info['usuario_id'];
  $rs_nombre = mysql_query($sql_nombre, $conexion);
  $row_nombre = mysql_fetch_array($rs_nombre);

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
    <script src="../js-sc/validar_caracteres.js"></script>
  <script src="validar_asignatura.js"></script>
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
                        <h1>MENSAJE</h1>
                            <div class="info-100">
                                <table class="header2">
                                  <tr>
                                    <th>Remitente</th>
                                    <td><?=$solicitante?></td>
                                  </tr>
                                  <tr>
                                    <th>Fecha Creación</th>
                                    <td><?=$row_info['mensaje_fecha']?></td>
                                  </tr>
                                  <tr>
                                    <th>Descripción</th>
                                    <td><?=$row_info['mensaje_cuerpo']?></td>
                                  </tr>
                                  <tr>
                                    <th>Archivo</th>
                                    <td>
                                      <?php
                                      if($row_info['mensaje_archivo'] == '')
                                      {
                                        echo "--";
                                      }
                                      else
                                      {
                                      ?>
                                      <a href="../profesor-sc/<?=$row_info['mensaje_archivo']?>"><i class='fas fa-search fa-lg'></i></a>
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
                                  $sql_historial = "SELECT * FROM mensajes_historial WHERE mensaje_id = '$id'";
                                  $rs_historial = mysql_query($sql_historial, $conexion);
                                  while ($row_historial = mysql_fetch_array($rs_historial)) {
                                    if($row_historial['usuario_id'] != '0')
                                    {
                                      $sql_usuario = "SELECT * FROM usuarios WHERE usuario_id = ".$row_historial['usuario_id'];
                                      $rs_usuario = mysql_query($sql_usuario, $conexion);
                                      $row_usuario = mysql_fetch_array($rs_usuario);

                                      $solicitante_h = $row_usuario['usuario_nombres']." ".$row_usuario['usuario_apellidos'];
                                    }
                                    if($row_historial['profesor_id'] != '0')
                                    {
                                      $sql_usuario = "SELECT * FROM profesores WHERE profesor_id = ".$row_historial['profesor_id'];
                                      $rs_usuario = mysql_query($sql_usuario, $conexion);
                                      $row_usuario = mysql_fetch_array($rs_usuario);

                                      $solicitante_h = $row_usuario['profesor_nombres']." ".$row_usuario['profesor_apellidos'];
                                    }
                                    echo "<tr><td>".$solicitante_h."</td><td>".$row_historial['mensaje_historial_fecha']."</td><td>".$row_historial['mensaje_historial_comentario']."</td>";
                                    if($row_historial['mensaje_historial_archivo'] != '')
                                    {
                                      echo "<td><a href='../profesor-sc/".$row_historial['mensaje_historial_archivo']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";
                                    }
                                    else
                                    {
                                      echo "<td>--</td></tr>";
                                    }
                                  }
                                  ?>
                                </table>
                                <h1>RESPONDER MENSAJE</h1>
                                <form method="POST" action="mensaje_responder_proc.php" enctype="multipart/form-data">
                                <label>Cuerpo</label>
                                <textarea placeholder="INGRESE MENSAJE" name="desc" onkeyup="reemplazar(this);"></textarea> 
                                <label>Archivo (Opcional)</label> 
                                <input type="file" name="files">  
                                <input type="hidden" name="id" value="<?=$id?>"> 
                            </div>
                            <div class="info-100">
                                <input type="submit" value="ENVIAR">
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