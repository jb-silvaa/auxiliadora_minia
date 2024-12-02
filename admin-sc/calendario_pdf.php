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

$sql_periodo = "SELECT periodo_periodo FROM periodos WHERE periodo_activo = '1'";
$rs_periodo = mysqli_query($conexion, $sql_periodo);
$row_periodo = mysqli_fetch_array($rs_periodo);
$periodo_activo = $row_periodo['periodo_periodo'];
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
              <h1>Listado de Evaluaciones <?=$periodo_activo?> por mes</h1>
            	<form method="POST" action="descargar_calendario.php" target="_blank" enctype="multipart/form-data" onsubmit="return validator();">

		            <label>Seleccione Mes</label>
                <select class="minimal" name="mes" required="required">
                  <option value="">Seleccione Mes</option>
                  <?php 
                  $sql_meses = "SELECT * FROM meses";
                  $rs_meses = mysqli_query($conexion, $sql_meses);
                  while ($row_meses = mysqli_fetch_array($rs_meses)) {
                    echo "<option value='".$row_meses['mes_id']."'>".$row_meses['mes_nombre']."</option>";
                  }
                  ?>                  
                </select>
                <label>Seleccione Nivel</label>
                <select class="minimal" name="nt" required="required">
                  <option value="">Seleccione Nivel</option>
                  <option value="0">PRE-ESCOLAR</option>
                  <option value="1">ENSEÑANZA BÁSICA</option>                 
                  <option value="2">ENSEÑANZA MEDIA</option>
                </select>
		            <div class="info-100">

                    <input type="submit" value="GENERAR">

                </div>   

            </form>
            <h1>Listado de Evaluaciones <?=$periodo_activo?> por curso</h1>
              <form method="POST" action="descargar_calendario_curso.php" target="_blank" enctype="multipart/form-data" onsubmit="return validator();">

                <label>Seleccione Curso</label>
                <select class="minimal" name="nivel" required="required">
                  <option value="">Seleccione Curso</option>
                  <?php 
                  $sql_curso = "SELECT DISTINCT nivel_nombre, n.nivel_id 
                                FROM cursos_asignaturas as ca, niveles as n
                                WHERE ca.nivel_id = n.nivel_id 
                                AND curso_asignatura_periodo = '2021'";
                  $rs_curso = mysqli_query($conexion, $sql_curso);
                  while ($row_curso = mysqli_fetch_array($rs_curso)) {
                    echo "<option value='".$row_curso['nivel_id']."'>".$row_curso['nivel_nombre']."</option>";
                  }
                  ?>                  
                </select>
                <label>Seleccione Letra</label>
                <select class="minimal" name="letra" required="required">
                  <option value="">Seleccione Letra</option>
                  <?php 
                  $sql_curso = "SELECT DISTINCT letra_nombre, l.letra_id 
                                FROM cursos_asignaturas as ca, letras as l
                                WHERE ca.letra_id = l.letra_id 
                                AND curso_asignatura_periodo = '2021'";
                  $rs_curso = mysqli_query($conexion, $sql_curso);
                  while ($row_curso = mysqli_fetch_array($rs_curso)) {
                    echo "<option value='".$row_curso['letra_id']."'>".$row_curso['letra_nombre']."</option>";
                  }
                  ?>                  
                </select>
                <label>Seleccione Semestre</label>
                <select class="minimal" name="semestre" required="required">
                  <option value="">Seleccione Semestre</option>
                  <option value="1">PRIMER SEMESTRE</option>                 
                  <option value="2">SEGUNDO SEMESTRE</option>
                </select>
                <div class="info-100">

                    <input type="submit" value="GENERAR">

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

