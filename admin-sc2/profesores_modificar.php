<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
if ($_SESSION['perfil'] != 1){
	echo "<script LANGUAGE='JavaScript'>
				  window.alert('Acceso no autorizado');
				  window.location= 'listado_cursos.php'
	  </script>";
	  die();
  }
$id = $_GET['id'];

$sql_profesor = "SELECT * FROM profesores WHERE profesor_id = '$id'";
$rs_profesor = mysql_query($sql_profesor, $conexion);
$row_profesor = mysql_fetch_array($rs_profesor);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
	<title>Sistema Clases</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
	<link 
	href="../css-sc/iphone.css"
	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
	rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<?php 
		include('../fonts/fonts.php'); 
		include('../js-sc/bootstrap.php'); 
	?>
    <script src="../js-sc/validar_caracteres.js"></script>
  <link rel="stylesheet" href="../css-sc/jquery-ui.css">
   <script src="../js-sc/jquery-1.10.2.js"></script>
    <script src="../js-sc/jquery-ui.js"></script>
    <script src="../js-sc/jquery.ui.datepicker-sp.js"></script>
    <script src="validar_form.js"></script>

        <script>
     $(function() {
  $( "#datepicker" ).datepicker(
      {
        regional:"sp",
        firstDay:1,
        dateFormat: "yy-mm-dd",
        autoSize: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true
      });
  });
  $(function() {
    $( "#datepicker2" ).datepicker(
      {
        regional:"sp",
        firstDay:1,
        dateFormat: "yy-mm-dd",
        autoSize: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true
      });
  });
  </script>
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
                        <h1>MODIFICAR PROFESOR</h1>
                    	<form method="POST" action="profesores_modificar_proc.php" enctype="multipart/form-data" onsubmit="return validator();">
                            <div class="info-50">
                                <h1>Personal</h1>
                                <label>Nombres Profesor</label>
                                <input type="text" id="nombre" name="nombre" placeholder="INGRESE NOMBRES" onkeyup="reemplazar(this);" value="<?=$row_profesor['profesor_nombres']?>">
                                <input type="hidden" name="id" value="<?=$row_profesor['profesor_id']?>">
                                <label>Apellidos Profesor</label>
                                <input type="text" id="apellido" name="apellido" placeholder="INGRESE APELLIDOS" onkeyup="reemplazar(this);" value="<?=$row_profesor['profesor_apellidos']?>">
                                <label>RUT Profesor (Sin punto ni guion)</label>
                                <input type="text" name="rut" placeholder="INGRESE RUT" value="<?=$row_profesor['profesor_rut']?>">
                                <label>Fecha Nacimiento</label>
                                <input type="text" id="datepicker" name="fecha" onkeyup="reemplazar(this);" placeholder="INGRESE FECHA NAC." value="<?=$row_profesor['profesor_fecha_nacimiento']?>">                         
                            </div>
                            <div class="info-50">
                                <h1>Contacto</h1>
                                <label>Correo Institucional</label>
                                <input type="text" id="correo" name="correo" onkeyup="reemplazar(this);" placeholder="INGRESE CORREO INSTITUCIONAL" value="<?=$row_profesor['profesor_correo']?>">
                                <label>Correo Personal</label>
                                <input type="text" id="correop" name="correo_p" onkeyup="reemplazar(this);" placeholder="INGRESE CORREO PERSONAL" value="<?=$row_profesor['profesor_correo_personal']?>">
                                <label>Teléfono/Celular</label>
                                <input type="text" id="telefono" name="fono" onkeyup="reemplazar(this);" placeholder="INGRESE TELÉFONO/CELULAR" value="<?=$row_profesor['profesor_fono']?>">
                                <label>Foto Perfil</label>
                                <input type="file" name="files" id="files"> 
                                <label>Previsualización</label>
                                <output id="list"><img src="../profesor-sc/<?=$row_profesor['profesor_imagen']?>"></output>
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