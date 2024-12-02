<?php 
include('../funciones-sc/conexion.php');
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
                    	<form method="POST" action="solicitudes_nuevo_proc.php" enctype="multipart/form-data">
                            <div class="info-50 margin-25">
                                <label>Tipo Solicitud</label>
                                <select name="tipo" class="minimal" required="">
                                  <option value="">Seleccione Tipo</option>
                                  <?php 
                                  $sql_tipo = "SELECT * FROM tipos_solicitudes WHERE tipo_solicitud_estado = '1'";
                                  $rs_tipo = mysql_query($sql_tipo, $conexion);
                                  while ($row_tipo = mysql_fetch_array($rs_tipo)) {
                                    echo "<option value='".$row_tipo['tipo_solicitud_id']."'>".$row_tipo['tipo_solicitud_nombre']."</option>";
                                  }
                                  ?>
                                </select>
                                <label>Descripción</label>
                                <textarea placeholder="INGRESE DESCRIPCIÓN" name="desc" onkeyup="reemplazar(this);"></textarea> 
                                <label>Archivo (Opcional)</label> 
                                <input type="file" name="files">
                                <label>Receptor Solicitud</label>
                                <select name="receptor" class="minimal" required="">
                                <option value="">Seleccione Receptor</option>
                                  <optgroup label="Administradores">
                                  <?php 
                                  $sql_rec = "SELECT * FROM usuarios as u, perfiles as p WHERE usuario_estado = '1' AND p.perfil_id = u.perfil_id";
                                  $rs_rec = mysql_query($sql_rec, $conexion);
                                  while ($row_rec = mysql_fetch_array($rs_rec)) {
                                    echo "<option value='".$row_rec['usuario_id']."_1'>".$row_rec['usuario_nombres']." ".$row_rec['usuario_apellidos']." (".$row_rec['perfil_nombre'].")</option>";
                                  }
                                  ?>
                                  </optgroup>
                                  <optgroup label="Profesores">
                                  <?php 
                                  $sql_rec = "SELECT * FROM profesores WHERE profesor_estado = '1' ORDER BY profesor_apellidos, profesor_nombres ASC";
                                  $rs_rec = mysql_query($sql_rec, $conexion);
                                  while ($row_rec = mysql_fetch_array($rs_rec)) {
                                    echo "<option value='".$row_rec['profesor_id']."_2'>".$row_rec['profesor_apellidos']." ".$row_rec['profesor_nombres']."</option>";
                                  }
                                  ?>
                                  </optgroup>
                                </select>                    
                            </div>
                            <div class="info-100">
                                <input type="submit" value="INGRESAR">
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