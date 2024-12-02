<?php 

error_reporting(0);

session_start();

include('../funciones-sc/conexion.php');

function debug_to_console($data) {

  $output = $data;

  if (is_array($output))

      $output = implode(',', $output);



  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

$user_id = $_SESSION['profesor_id'];

$id = $_GET['id'];

$fecha = date('Y-m-d');

  ?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Subir evaluacion</title>

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

    <script src="validar_evaluacion.js"></script>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>


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
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <h1>Subir Excel Evaluaciones</h1>
            	<form method="POST" action="subir_excel_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

		            <label>Cargar archivo</label>

		            <input type="file" name="files" id="files"> 


		            <div class="info-100">

                    <input type="submit" value="INGRESAR">

                </div>   

            </form>

        </div>
        <div class="col-lg-2"></div>
	</div>

</div>
</div>
</div>

</body>

</html>

