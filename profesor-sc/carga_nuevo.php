<?php 

session_start();

include('../funciones-sc/conexion.php');

//Se le agrega meses.php para desplegar el numero de unidad asociado a meses

include('../funciones-sc/meses.php');

//$cur_asig = $_GET['id'];

$cur_asig = $_GET['id'];

$unidad = $_GET['unidad'];

$user_id = $_SESSION['profesor_id'];

$periodo = date('Y');

if($unidad == ''){ $unidad = '0'; }



function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}



?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

	<script>

		function CompartirCarga() {

			var txt;

			var str = document.getElementById("letra2").value;

			var r = confirm("¿Desea agregar la carga para " + str + "?");

			if(r == true){

				txt = "si";

			}else{

				txt = "no";

			}

			//Se setea el valor en un input hidden para decidir en el proc si se realiza o no la acción.

			document.getElementById("decision").value = txt;

		}

	</script>

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

	<link rel="stylesheet" href="../css-sc/jquery-ui.css">

	<script src="../js-sc/jquery-1.10.2.js"></script>

    <script src="../js-sc/jquery-ui.js"></script>

    <script src="../js-sc/jquery.ui.datepicker-sp.js"></script>

    <script src="../js-sc/validar_caracteres.js"></script>

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

	<?php

	/*$sql_curso = "SELECT * 

				  FROM cursos as c,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca

				  	   WHERE c.curso_codigo = ca.curso_codigo

					   AND a.asignatura_codigo = ca.asignatura_codigo

					   AND ca.profesor_id = '$profesor'

					   AND ca.curso_asignatura_id = '$cur_asig' 

				 "; */

	$sql_curso = "SELECT *

				  FROM niveles as n,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca,

			   		   letras as l

			   	  WHERE n.nivel_id = ca.nivel_id

			  	  AND l.letra_id = ca.letra_id

			  	  AND a.asignatura_id = ca.asignatura_id

			 	  AND ca.curso_asignatura_id = '$cur_asig'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);

	//$cantidad = mysqli_num_rows($rs_curso);

	//debug_to_console($cantidad);

	$tipo = $row_curso['tipo_carga_id'];

	$nivelCod = $row_curso['nivel_codigo'];

	$asignaturaCod = $row_curso['asignatura_codigo'];

	$dificultad = $row_curso['dificultad_id'];

	?>

	<div class="perfil-der">			

		<h1>Nueva Carga</h1>

		<div class="info-50 margin-25 container">

            	<form method="POST" action="carga_nuevo_proc.php" enctype="multipart/form-data">

		            <label>Curso</label>

		            <input id="nombreletra" type="text" name="curso" disabled="" value="<?=$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']?>">

		            <input type="hidden" name="id" value="<?=$cur_asig?>">

		            <label>Asignatura</label>

		            <input type="text" name="asig" disabled="" value="<?=$row_curso['asignatura_nombre']?>">

		            <label>Unidad</label>

		            <?php 

		            if($unidad == '0'){ 

						$uni_texto = 'CARGA ANUAL'; 

					}else{ 

						$uni_texto = $array[$unidad+1]; 



					}

		             

		            ?>

		            <input type="text" disabled="" name="uni" value="<?=$uni_texto?>">

		            <input type="hidden" name="unidad" value="<?=$unidad?>">

		            <!--<label>Fecha Inicio</label>

		            <input type="text" name="inicio" placeholder="INGRESE FECHA INICIO" value="<?=$row_curso['carga_fecha_inicio']?>" id="datepicker">

		            <label>Fecha Término</label>

		            <input type="text" name="termino" placeholder="INGRESE FECHA TÉRMINO" value="<?=$row_curso['carga_fecha_termino']?>" id="datepicker2">-->

		            <label>Carga</label>

					<?php

						//hacer la consulta

						//Consulta para ver si existe otro curso del mismo nivel asignado al profesor,

						//Con el fin de preguntar si se le asigna la misma carga

						$sql_info1 = "SELECT *

									FROM niveles as n,

						   			asignaturas as a,

						   			cursos_asignaturas as ca,

									letras as l

						   			WHERE n.nivel_id = ca.nivel_id

						  			AND l.letra_id = ca.letra_id

						  			AND a.asignatura_id = ca.asignatura_id

									 AND ca.curso_asignatura_id != '$cur_asig'

									 AND ca.profesor_id = '$user_id'

									 AND n.nivel_codigo = '$nivelCod'

									 AND a.asignatura_codigo = '$asignaturaCod'

									 AND ca.dificultad_id = '$dificultad'

									 AND curso_asignatura_periodo = '$periodo'";

						//print $sql_info1;

						$rs_info1 = mysqli_query($conexion, $sql_info1);

						$canti = mysqli_num_rows($rs_info1);

						debug_to_console($canti);

						debug_to_console($unidad);

						$row_info1 = mysqli_fetch_array($rs_info1);

						//Si se encuentra otro curso, verificamos que el otro curso no tenga asignado alguna carga

						if($canti == 1){

							$id2 = $row_info1['curso_asignatura_id'];

							$letra = $row_info1['nivel_nombre'].'-'.$row_info1['letra_nombre'];



							$sql_info = "SELECT *

							FROM cargas

							INNER JOIN cursos_asignaturas on cursos_asignaturas.curso_asignatura_id = cargas.curso_asignatura_id

							WHERE cargas.curso_asignatura_id = '$id2'

							AND cargas.tipo_carga_unidad = '$unidad'";

							$rs_info = mysqli_query($conexion, $sql_info);

							$canti2 = mysqli_num_rows($rs_info);

							$row_info = mysqli_fetch_array($rs_info);

							$doc = $row_info['carga_archivo'];

							if($canti2 > 1 || $doc != ''){

								$id2 = '';

								$canti = 10; //Para que no entre despues

							}

						}

						

					?>

		            <input type="file" name="files" id="files" required> 

		            <label>Archivo Actual</label>

					<?php 

					$sql_existe = "SELECT * FROM cargas WHERE curso_asignatura_id = '$cur_asig' AND tipo_carga_unidad = '$unidad'";

					$rs_existe = mysqli_query($conexion, $sql_existe);

					$row_existe = mysqli_fetch_array($rs_existe);

					//Si la carga fue rechazada, se le solicita al profesor una observación sobre la corrección 

					//Realizada

		            if($row_existe['carga_aprobacion'] == '-1'){

						echo "<p>No hay archivo asociado.</p>";

						?>

		            	<label>Observación Corrección</label>

                    	<textarea placeholder="INGRESE INFORMACIÓN SOBRE CORRECCIÓN REALIZADA" name="obscorreccion" onkeyup="reemplazar(this);" required></textarea>

						<?php

		            }

		           		?>

					<input type="hidden" id="decision" name="decision">

					<input type="hidden" id="id2" name="id2" value="<?=$id2?>">

					<input type="hidden" id="letra2" name="letra2" value="<?=$letra?>">

					

		            <div class="info-100">

					<?php

						//Si se encontró un curso, y además este curso no tenía carga asignada

						//Se le asigna una función al submit que pregunta si se desea subir

						//La carga a ambos cursos.

						if($canti == 1){

							?>

							<input type="submit" value="INGRESAR" onclick="CompartirCarga()">

							<?php

						}else{

							?>

							<input type="submit" value="INGRESAR">

							<?php

						}

					?>

					

                </div>   

            </form>

        </div>

	</div>

</div>

</body>

</html>