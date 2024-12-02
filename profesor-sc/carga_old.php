<?php 

include('../funciones-sc/conexion.php');

$cur_asig = $_GET['id'];

$sql_info = "SELECT * FROM cursos_asignaturas as ca, asignaturas as a, niveles as n, letras as l

			 WHERE curso_asignatura_id = '$cur_asig'

			 AND a.asignatura_id = ca.asignatura_id 

			 AND l.letra_id = ca.letra_id

			 AND n.nivel_id = ca.nivel_id

";

$rs_info = mysqli_query($conexion, $sql_info);

$row_info = mysqli_fetch_array($rs_info);



$curso = $row_info['nivel_nombre']."-".$row_info['letra_nombre'];

$asignatura = $row_info['asignatura_nombre'];

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

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

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

<div class="contenedor-100">

	<?php 

	include('menu-lateral.php');

	$sql_validacion = "SELECT * FROM cursos_asignaturas WHERE curso_asignatura_id = '$cur_asig' AND profesor_id = '$profesor'";

    $rs_validacion = mysqli_query($conexion, $sql_validacion);

    $row_validacion = mysqli_fetch_array($rs_validacion);

    if($row_validacion == '')

    {



       echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Acceso no autorizado');

    window.location.href='index.php';

    </SCRIPT>");

	mysqli_close($sql);

    }

	?>

	<div class="perfil-der">

		<h1>Carga Cursos</h1>	

		<h1>Unidades</h1>	

		<?php 

		$sql_unidad = "SELECT * FROM cursos_asignaturas WHERE curso_asignatura_id = '$cur_asig'";

		$rs_unidad = mysqli_query($conexion, $sql_unidad);

		$row_unidad = mysqli_fetch_array($rs_unidad);



		$unidad = $row_unidad['curso_asignatura_unidades'];

		?>

		<table class="header2">

			<tr>

				<th>Curso</th>

				<th>Asignatura</th>

				<th>Tipo Carga</th>

				<th>Ver/Subir Archivo</th>

				<th>Eliminar Carga</th>

				<th>Aprobación</th>

				<th>Observación</th>

			</tr>			

			<?php

			$contador = '1';

			while($unidad >= $contador)

			{ 

				$sql_cursos = "SELECT *

							  FROM niveles as n,

							  	   asignaturas as a,

							  	   cursos_asignaturas as ca,

							  	   cargas as car,

						   		   tipos_cargas as tc,

						   		   letras as l

						   	  WHERE n.nivel_id = ca.nivel_id

						  	  AND l.letra_id = ca.letra_id

						  	  AND a.asignatura_id = ca.asignatura_id

						  	  AND tc.tipo_carga_id = car.tipo_carga_id

						 	  AND ca.curso_asignatura_id = car.curso_asignatura_id

						 	  AND car.curso_asignatura_id = '$cur_asig'

						 	  AND car.tipo_carga_unidad = '$contador'

						 	  AND car.carga_estado = '1'

							   ";

				$rs_cursos = mysqli_query($conexion, $sql_cursos);

				$j = 0;

				while($row_cursos = mysqli_fetch_array($rs_cursos))

				{

				$j++;

				echo "<tr>

						<td>".$curso."</td>

						<td>".$asignatura."</td>

						<td>UNIDAD ".$contador."</td>";

						if($row_cursos['carga_archivo'] == '')

						{

							echo "<td><a href='carga_nuevo.php?id=".$cur_asig."&unidad=".$contador."'><i class='fas fa-upload'></i></a></td>";	



						}

						else

						{

							echo "<td><a href='".$row_cursos['carga_archivo']."'><i class='fas fa-search fa-lg'></i></a></td>";

						}

						?>

                                  <td><a href='carga_eliminar.php?id=<?=$row_cursos['carga_id']?>&id_c=<?=$cur_asig?>' onclick ="javascript: return confirm('Desea Eliminar Esta Carga?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                        <?php



                        if($row_cursos['carga_aprobacion'] == '-1'){

						echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></td>";				

						}

						if($row_cursos['carga_aprobacion'] == '0'){

							echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";

						}

						if($row_cursos['carga_aprobacion'] == '1'){

							echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";

						}

						echo "<td>".$row_cursos['carga_observacion']."</td>";

                      ?>

            		  </tr>

                        <?php

				}

				if($j == '0')

				{

					echo "<tr>

						<td>".$curso."</td>

						<td>".$asignatura."</td>

						<td>UNIDAD ".$contador."</td>

						<td><a href='carga_nuevo.php?id=".$cur_asig."&unidad=".$contador."'><i class='fas fa-upload'></i></a></td>";

					?>

                                  <td><a href='carga_eliminar.php?id=<?=$row_cursos['carga_id']?>&id_c=<?=$cur_asig?>' onclick ="javascript: return confirm('Desea Eliminar Este Archivo?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                                  <td><i class='fas fa-exclamation-circle fa-lg'></i></td>

                                  <td></td>

                        		  </tr>

                        <?php	

				}			

				$contador++;

			}

			?>

		</table>

		<h1>Anual</h1>

		<table class="header2">

			<tr>

				<th>Curso</th>

				<th>Asignatura</th>

				<th>Tipo Carga</th>

				<th>Ver/Subir Archivo</th>

				<th>Eliminar Carga</th>

				<th>Aprobación</th>

				<th>Observación</th>

			</tr>			

			<?php 

			$sql_cursos = "SELECT *

						  FROM niveles as n,

						  	   asignaturas as a,

						  	   cursos_asignaturas as ca,

						  	   cargas as car,

					   		   tipos_cargas as tc,

					   		   letras as l

					   	  WHERE n.nivel_id = ca.nivel_id

					  	  AND l.letra_id = ca.letra_id

					  	  AND a.asignatura_id = ca.asignatura_id

					  	  AND tc.tipo_carga_id = car.tipo_carga_id

					 	  AND ca.curso_asignatura_id = car.curso_asignatura_id

					 	  AND car.curso_asignatura_id = '$cur_asig'

					 	  AND car.tipo_carga_unidad = '0'

					 	  AND car.carga_estado = '1'

			";

			$rs_cursos = mysqli_query($conexion, $sql_cursos);

			$row_cursos = mysqli_fetch_array($rs_cursos);

				echo "<tr>

					<td>".$curso."</td>

					<td>".$asignatura."</td>

					<td>".$row_cursos['tipo_carga_nombre']."</td>";

					if($row_cursos['carga_archivo'] == '')

						{

							echo "<td><a href='carga_nuevo.php?id=".$cur_asig."'><i class='fas fa-upload'></i></a></td>";								  

						}

						else

						{

							echo "<td><a href='".$row_cursos['carga_archivo']."'><i class='fas fa-search fa-lg'></i></a></td>";

						}

						?>

                      <td><a href='carga_eliminar.php?id=<?=$row_cursos['carga_id']?>&id_c=<?=$cur_asig?>' onclick ="javascript: return confirm('Desea Eliminar Este Archivo?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                      <?php 

                      if($row_cursos['carga_aprobacion'] == '-1'){

						echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></td>";				

						}

						if($row_cursos['carga_aprobacion'] == '0'){

							echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";

						}

						if($row_cursos['carga_aprobacion'] == '1'){

							echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";

						}

                      ?>

            		  

            <?php

			if($row_cursos['curso_asignatura_id'] == '')

			{

				echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";

			}	

			echo "<td>".$row_cursos['carga_observacion']."</td>";

			?>

			</tr>

		</table>

	</div>

</div>

</body>

</html>