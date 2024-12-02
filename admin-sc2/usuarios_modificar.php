<?php 
include('../funciones-sc/conexion.php');
session_start();
if ($_SESSION['perfil'] != 1){
	echo "<script LANGUAGE='JavaScript'>
				  window.alert('Acceso no autorizado');
				  window.location= 'listado_cursos.php'
	  </script>";
	  die();
  }
$usuario = $_GET['id'];
$sql_usuario = "SELECT * FROM usuarios WHERE usuario_id = '$usuario'";
$rs_usuario = mysql_query($sql_usuario, $conexion);
$row_usuario = mysql_fetch_array($rs_usuario);
$perfil = $row_usuario['perfil_id'];
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
  <script src="validar_form_usuario.js"></script>
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
                        <h1>MODIFICAR USUARIO</h1>
                    	<form method="POST" action="usuarios_modificar_proc.php" enctype="multipart/form-data" onsubmit="return validator();">
                            <div class="info-50">
                                <h1>Personal</h1>
                                <label>Nombres Usuario</label>
                                <input type="text" id="nombre" name="nombre" placeholder="INGRESE NOMBRES" onkeyup="reemplazar(this);" value="<?=$row_usuario['usuario_nombres']?>">
                                <input type="hidden" name="id" value="<?=$row_usuario['usuario_id']?>">
                                <label>Apellidos Usuario</label>
                                <input type="text" id="apellido" name="apellido" placeholder="INGRESE APELLIDOS" onkeyup="reemplazar(this);" value="<?=$row_usuario['usuario_apellidos']?>">
                                <label>RUT Usuario (Sin punto ni guion)</label>
                                <input type="text" id="rut" name="rut" placeholder="INGRESE RUT SIN PUNTO NI GUION" onkeyup="reemplazar(this);" value="<?=$row_usuario['usuario_rut']?>">
                                <label>Perfil Usuario</label>
                                <select name="perfil" required class="minimal">
                                  <?php 
                                  $sql_perfil = "SELECT * FROM perfiles WHERE perfil_estado = '1' ORDER BY perfil_id = '$perfil' DESC, perfil_nombre ASC";
                                  $rs_perfil = mysql_query($sql_perfil, $conexion);
                                  while($row_perfil = mysql_fetch_array($rs_perfil))
                                  {
                                    echo "<option value='".$row_perfil['perfil_id']."'>".$row_perfil['perfil_nombre']."</option>";
                                  }
                                  ?>
                                </select>                         
                            </div>
                            <div class="info-50">
                                <h1>Contacto</h1>
                                <label>Correo</label>
                                <input type="text" id="correo" name="correo" placeholder="INGRESE CORREO PERSONAL" onkeyup="reemplazar(this);" value="<?=$row_usuario['usuario_mail']?>">
                                <label>Teléfono/Celular</label>
                                <input type="text" id="telefono" name="fono" placeholder="INGRESE TELÉFONO/CELULAR" onkeyup="reemplazar(this);" value="<?=$row_usuario['usuario_fono']?>">
                                <label>Foto Perfil</label>
                                <input type="file" name="files" id="files"> 
                                <label>Previsualización</label>
                                <output id="list"><img src="<?=$row_usuario['usuario_imagen']?>"></output>
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