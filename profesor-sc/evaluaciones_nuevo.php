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

		//include('../js-sc/bootstrap.php'); 

	?>

    <script src="validar_evaluacion.js"></script>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

<div class="contenedor-100">

	<?php 

	include('menu-lateral.php');

	?>

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

	<div class="perfil-der">			

		<h1>Nueva Evaluacion</h1>

		<div class="info-50 margin-25 container">

            	<form method="POST" action="evaluaciones_nuevo_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

		            <label>Cargar archivo</label>

		            <input type="file" name="files" id="files"> 



		            <input type="hidden" name="id" value="<?=$id?>">

                

                <label>Nombre de evaluacion</label>

                <input type="text" id="nombre" name="nameprueba" onkeyup="reemplazar(this);">



                <label>Cantidad Copias</label>

                <input type="text" name="copias" onkeyup="reemplazar(this);">

		           		<label>Tipo de Evaluacion</label>

		           	  <select    class="minimal" name='nivel'>

                               <?php 

                               $sql="SELECT * FROM tipos_evaluaciones";

                               $tipo=mysqli_query($conexion, $sql);

                               while($row=mysqli_fetch_array($tipo))

                               {

                                echo "<option value='".$row['tipo_evaluacion_id']."' >";

                                echo $row ['tipo_evaluacion_nombre'];

                                echo "</option>";

                               }

                               ?>

                                 </select>

                    <label>Fecha Evaluacion</label>

		           	<input type="date" id="date" name="inicio" placeholder="INGRESE FECHA INICIO"  min=<?php echo date('Y-m-d'); ?>>

                 <input type="hidden" name="fechacreacion" value="<?=$fecha?>">



		            <div class="info-100">

                    <input type="submit" value="INGRESAR">

                </div>   

            </form>

        </div>

	</div>

</div>

</body>

</html>

