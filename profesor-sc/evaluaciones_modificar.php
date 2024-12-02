<?php 

error_reporting(0);

session_start();

include('../funciones-sc/conexion.php');

$user_id = $_SESSION['profesor_id'];

$id = $_GET['id'];



$sql_eva = "SELECT * FROM evaluaciones WHERE evaluacion_id = '$id'";

$rs_eva = mysqli_query($conexion, $sql_eva);

$row_eva = mysqli_fetch_array($rs_eva);



$id_tipo = $row_eva['tipo_evaluacion_id'];

  ?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Subir evaluacion</title>

</head>

<body>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

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

  <script src="validar_evaluacion.js"></script>

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





</head>

<body>

<div class="contenedor-100">

	<?php 

	include('menu-lateral.php');

	?>



	<div class="perfil-der">			

		<h1>MODIFICAR EVALUACION</h1>

		<div class="info-50 margin-25 container">

            	<form method="POST" action="evaluaciones_modificar_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

		            <label>Cargar archivo</label>

                <input type="file" name="files" id="files"> 

		            <input type="hidden" name="id" value="<?=$id?>">

                

                <label>Nombre de evaluacion</label>

                <input type="text" name="nameprueba" id="nombre" onkeyup="reemplazar(this);" value="<?=$row_eva['evaluacion_nombre']?>">



                <label>Cantidad Copias</label>

                <input type="text" name="copias" onkeyup="reemplazar(this);" value="<?=$row_eva['evaluacion_copia']?>">

                 <!--

		            <label>Archivo Actual</label>

		            <?php 

		            if($row_curso['evaluacion_archivo'] == ''){

		              echo "<p>No hay archivo asociado aun.</p>";

		            }else{

		              ?>

		              <iframe src="http://docs.google.com/gview?url=http://www.cdgopruebas.cl/<?=$row_curso['evaluacion_archivo']?>&embedded=true"></iframe>

		           	  <?php 

		            }

		           	?>

-->

		           	<label>Tipo de Evaluacion</label>

		           	<select class="minimal" name='nivel'>

                               <?php 

                               $sql="SELECT * FROM tipos_evaluaciones WHERE tipo_evaluacion_estado = '1' ORDER BY tipo_evaluacion_id = '$id_tipo' DESC, tipo_evaluacion_nombre ASC";

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

		           	<input type="text" name="inicio" placeholder="INGRESE FECHA INICIO" value="<?=$row_eva['evaluacion_fecha']?>" id="datepicker" readonly="">

                  <?php

                  if($row_eva['evaluacion_aprobacion'] == '-1'){

						        echo "<p>No hay archivo asociado.</p>";

						        ?>

		            	  <label>Observación Corrección</label>

                    <textarea placeholder="INGRESE INFORMACIÓN SOBRE CORRECCIÓN REALIZADA" name="obscorreccion1" onkeyup="reemplazar(this);"></textarea>

						        <?php

		              }

		           		?>

                          

		            <div class="info-100">

                    <input type="submit" value="INGRESAR">

                </div>   

            </form>

        </div>

	</div>

</div>

</body>

</html>