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

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css?v=0.01">

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

		<h1>Listado Cursos</h1>

		<table class="header2">

			<tr>

				<th>Curso</th>

				<th>Asignatura</th>

				<th>Nivel</th>

				<th>Avance</th>

				<th>Carga</th>

				<th>Evaluaciones</th>

			</tr>			

			<?php

			$sql_cursos = "SELECT * FROM cursos_asignaturas CA 

			join asignaturas A on A.asignatura_id = CA.asignatura_id

			join niveles N on N.nivel_id = CA.nivel_id

			join letras L on L.letra_id = CA.letra_id

			join dificultades D on D.dificultad_id = CA.dificultad_id

		    join profesores P on (P.profesor_id = CA.profesor_id and P.profesor_id = $user_id)             

            WHERE CA.curso_asignatura_estado = '1' and CA.curso_asignatura_periodo = '$periodo'";

			$rs_cursos = mysqli_query($conexion, $sql_cursos);

			debug_to_console($user_id);

			while ($row_cursos = mysqli_fetch_array($rs_cursos)) {

				$unidad = $row_cursos['curso_asignatura_unidades']+1; //AGREGO LA ANUAL CON EL +1



				$sql_unidad = "SELECT count(carga_id) as total FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '1'";

				$rs_unidad = mysqli_query($conexion, $sql_unidad);

				$row_unidad = mysqli_fetch_array($rs_unidad);



				$uni_apro = $row_unidad['total'];



				$valor = round(($uni_apro*100/$unidad),2);

				if($valor < 100){ $color = "#222"; }else{ $color = "rgb(0, 230, 64)"; }

				$valor_no = 100-$valor;

				echo "<tr>

						<td>".$row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."</td>

						<td>".$row_cursos['asignatura_nombre']."</td>

						<td>".$row_cursos['dificultad_nombre']."</td>

						<td width='260'>

						<div style='width: 240px; float: left; height: 14px; border: 1px solid #000;'>

				          <div style='width: ".$valor."%; float: left; background: ".$color."; height: 12px;'><p class='porcentaje'>".$valor."%</p></div>

				          <div style='width: ".$valor_no."%; float: left; background: #999; height: 12px;'></div>

				      	</div>

				      </td>

						<td><a href='carga.php?id=".$row_cursos['curso_asignatura_id']."'><i class='fas fa-upload'></i></a></td>

						<td><a href='evaluaciones.php?id=".$row_cursos['curso_asignatura_id']."'><i class='fas fa-upload'></i></a></td>";

						?>

				</tr>

				<?php

			}

			?>

		</table>

	</div>

</div>

</body>

</html>		