<?php 

include('../funciones-sc/conexion.php');

$cur_asig = $_GET['id'];

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

	<div class="perfil-izq">

      <img src="<?=$row['profesor_imagen']?>">

        <h1>Personal</h1>

        <label>Nombre</label>

        <p><?=$row['profesor_nombres']." ".$row['profesor_apellidos']?></p>

        <label>Rut</label>

        <p><?=$row['profesor_rut']?></p>

        <label>Fecha Nacimiento</label>

        <p><?=$row['profesor_fecha_nacimiento']?></p>

        <h1>Contacto</h1>

        <label>Correo</label>

        <p><?=$row['profesor_correo']?></p>

        <label>Correo Personal</label>

        <p><?=$row['profesor_correo_personal']?></p>

        <label>Teléfono</label>

        <p><?=$row['profesor_fono']?></p>

        <h1>Resumen Cursos</h1>      

        <div style="width: 300px; float: left; height: 12px; border: 1px solid #000;">

        	<div style="width: 75%; float: left; background: #000; height: 10px;"></div>

        	<div style="width: 25%; float: left; background: #999; height: 10px;"></div>

    	</div>

	</div>

	<?php

	$sql_curso = "SELECT * 

				  FROM cursos as c,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca

				  	   WHERE c.curso_codigo = ca.curso_codigo

					   AND a.asignatura_codigo = ca.asignatura_codigo

					   AND ca.profesor_id = '$profesor'

					   AND ca.curso_asignatura_id = '$cur_asig' 

				 "; 

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);

	?>

	<div class="perfil-der">			

		<h1>Nueva Carga</h1>

		<div class="info-50 margin-25 container">

            	<form method="POST" action="carga_nuevo_proc.php" enctype="multipart/form-data">

		            <label>Curso</label>

		            <input type="text" name="curso" disabled="" value="<?=$row_curso['curso_nombre']?>">

		            <input type="hidden" name="id" value="<?=$cur_asig?>">

		            <label>Asignatura</label>

		            <input type="text" name="asig" disabled="" value="<?=$row_curso['asignatura_nombre']?>">

		            <label>Tipo Carga</label>

		            <select name="tipo" class="minimal">

		            	<?php

		            	$sql_tipo = "SELECT * FROM tipos_cargas WHERE tipo_carga_estado = '1'";

		            	$rs_tipo = mysqli_query($conexion, $sql_tipo);

		            	while ($row_tipo = mysqli_fetch_array($rs_tipo)) {

		            		echo "<option value='".$row_tipo['tipo_carga_id']."'>".$row_tipo['tipo_carga_nombre']."</option>";	

		            	}		            	

		            	?>

		            </select>

		            <label>Fecha Inicio</label>

		            <input type="text" name="inicio" placeholder="INGRESE FECHA INICIO" onkeyup="reemplazar(this);">

		            <label>Fecha Término</label>

		            <input type="text" name="termino" placeholder="INGRESE FECHA TÉRMINO" onkeyup="reemplazar(this);">

		            <label>Carga</label>

		            <input type="file" name="files" id="files"> 

		            <div class="info-100">

                    <input type="submit" value="INGRESAR">

                </div>   

            </form>

        </div>

	</div>

</div>

</body>

</html>