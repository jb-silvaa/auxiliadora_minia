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

$fecha = date('Y-m-d');

debug_to_console($fecha);

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

		<h1>Nueva Reunión</h1>

		<div class="info-50 margin-25 container">

            	<form method="POST" action="reunion_nuevo_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

                    <label>Curso</label>

                        <select name="curso" class="minimal">

                            <?php 

                            $sql_curso = "SELECT *

                            FROM profesores_jefes

                            INNER JOIN letras on letras.letra_id = profesores_jefes.letra_id 

                            INNER JOIN niveles on niveles.nivel_id = profesores_jefes.nivel_id

                            WHERE profesor_id = '$user_id'";

                            $rs_curso = mysqli_query($conexion, $sql_curso);

                            while ($row_curso = mysqli_fetch_array($rs_curso)) {

                                $nombre = $row_curso['nivel_nombre']."-".$row_curso['letra_nombre'];

                                $nid = $row_curso['nivel_id'];

                                $lid = $row_curso['letra_id'];

                                echo "<option value=\"$nid|$lid\">".$nombre."</option>";

                            }

                            debug_to_console($nombre);

                            debug_to_console($nid);

                            debug_to_console($lid);

                            ?>

                        </select>

                    <label>Tipo</label>

                        <select name="tipo" class="minimal">

                            <option value="apoderados">Reunión de apoderados</option>

                            <option value="alumno">Reunión con alumno</option>

                            <option value="apoderado">Reunión con apoderado</option>

                            <option value="otro">Otro</option>

                        </select>

                    <label>Asunto reunión</label>

                        <input type="text" id="asunto" name="asunto" onkeyup="reemplazar(this);">

                    <label>Descripción reunión</label>

                    	<textarea placeholder="INGRESE DESCRIPCIÓN" name="descripcion" onkeyup="reemplazar(this);" required></textarea>

                    <label>Cargar archivo</label>

		            <input type="file" name="files" id="files"> 

                    <label>Fecha Reunión</label>

		           	    <input type="date" id="date" name="inicio" placeholder="INGRESE FECHA INICIO"  min=<?php echo date('Y-m-d'); ?>>



		            <input type="hidden" name="id" value="<?=$user_id?>">



		            <div class="info-100">

                    <input type="submit" value="INGRESAR">

                </div>   

            </form>

        </div>

	</div>

</div>

</body>

</html>

