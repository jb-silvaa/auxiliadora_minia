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
     <link rel="stylesheet" href="../css-sc/jquery-ui.css">
    <script src="../js-sc/jquery-1.10.2.js"></script>
    <script src="../js-sc/jquery-ui.js"></script>
    <script src="../js-sc/jquery.ui.datepicker-sp.js"></script>

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

 <script src="validar_periodo.js"></script>
 
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
                        <h1>NUEVO LIMITE ANUAL MENSUAL</h1>
                    	<form method="POST" action="periodo_limite_nuevo_proc.php" enctype="multipart/form-data" onsubmit="return validator();" >
                            <div class="info-50 margin-25">
                                <label>Periodo(Año)</label>
                                <input type="text"id="periodo" name="periodo" placeholder="INGRESE AÑO" onkeyup="reemplazar(this);">
                                <label>Limite Mensual</label>
                                <input type="text" id="datepicker" name="limite_m" placeholder="FECHA LIMITE MENSUAL" readonly="">
                                <label >Limite Anual</label>                             
                                  <input type="text" id="datepicker2" name="limite_a" placeholder="FECHA LIMITE ANUAL" readonly="">    
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