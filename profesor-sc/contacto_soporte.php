<?php 

session_start();

include('../funciones-sc/conexion.php');

error_reporting (0);

function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

//$periodo = date('Y');

$sql_periodo = "SELECT * FROM periodos WHERE periodo_activo = '1'";

$rs_periodo = mysqli_query($conexion, $sql_periodo);

$row_periodo = mysqli_fetch_array($rs_periodo);



$periodo = $row_periodo['periodo_periodo'];

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

	<link 

	href="../css-sc/iphone.css"

	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

	rel="stylesheet">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<title>Perfil Docente</title>

	<?php 

		include('../fonts/fonts.php');

		//include('../js-sc/bootstrap.php'); 

	?>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>



<div class="contenedor-100">

	<?php 

	include('menu-lateral.php');

	?>

	<div class="perfil-der">

		<h1>Solicitar Soporte</h1>
		<div class="modal fade" id="Contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	      aria-hidden="true">
	      <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content">
	          <form method="POST" action="contacto_correo.php">               
	              <label for="titulo">Ingrese Título del Mensaje</label>
	              <input type="text" name="titulo" class="form-control">	            
	              <label for="cuerpo">Ingrese Cuerpo del Mensaje</label>
	              <textarea name="cuerpo" class="md-textarea form-control" rows="4"></textarea>	            
	              <div style="width: 100%; float: left; text-align: center;">
	              	<input type="submit" value="Enviar">
	          	  </div>
	          </form>  
	        </div>
	      </div>
	    </div>
		

	</div>

</div>

</body>

</html>		