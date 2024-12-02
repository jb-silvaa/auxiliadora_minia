<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
if (!$_SESSION['id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
    die();
}
if ($_SESSION['perfil'] != 1){
    echo "<script LANGUAGE='JavaScript'>
                  window.alert('Acceso no autorizado');
                  window.location= 'listado_cursos.php'
      </script>";
      die();
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
    <script src="validar_jefesarea.js"></script>
    <script src="../js-sc/validar_caracteres.js"></script>
</head>
<body>
<!------ Include the above in your HEAD tag ---------->

    <div id="wrapper">
         <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                <span class="hamb-top"></span>
                <span class="hamb-middle"></span>
                <span class="hamb-bottom"></span>
            </button>
        <div class="overlay"></div>

 <?php include('menu-lateral.php');

         ?>
    
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>NUEVO JEFE DE AREA</h1>
                    	<form method="POST" action="jefes_area_nuevo_proc.php" enctype="multipart/form-data" onsubmit="return validator();">
                            <div class="info-50 margin-25">
                                <h1>Personal</h1>
                                <label>Nombres Jefe de Area</label>
                                <input type="text" id="nombre" name="nombre" placeholder="INGRESE NOMBRES" onkeyup="reemplazar(this);">
                                <label>Apellidos </label>
                                <input type="text" id="apellido" name="apellido" placeholder="INGRESE APELLIDOS" onkeyup="reemplazar(this);">
                                <label>RUT</label>
                                <input type="text" id="rut" name="rut" placeholder="INGRESE RUT SIN PUNTO NI GUION" onkeyup="reemplazar(this);">
                                <label>CORREO</label>
                                <input type="text" id="correo" name="correo" placeholder="INGRESE CORREO" onkeyup="reemplazar(this);">                 
                            </div>
                            <div class="info-100">
                                <input type="submit" value="INGRESAR">
                            </div>   
                        </form>
                    </div>
                </div>
            </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>
</html>