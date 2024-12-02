<?php 
session_start();
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
$user_id = $_SESSION['id'];
include('../funciones-sc/conexion.php');
include('../funciones-sc/meses.php');
$sql_user = "SELECT * FROM usuarios
			 WHERE usuario_id = '$user_id'
";
$rs_user = mysql_query($sql_user, $conexion);
$row_user = mysql_fetch_array($rs_user);
$user_nombre = $row_user['usuario_nombres'];
$user_apellidos = $row_user['usuario_apellidos'];
$perfil_archivo = 1;//adm = 1 , docente = 2;
$cur_asig = $_GET['id'];

$sql_info = "SELECT * FROM cursos_asignaturas as ca, asignaturas as a, niveles as n, letras as l
			 WHERE curso_asignatura_id = '$cur_asig'
			 AND a.asignatura_id = ca.asignatura_id 
			 AND l.letra_id = ca.letra_id
			 AND n.nivel_id = ca.nivel_id
";
$rs_info = mysql_query($sql_info, $conexion);
$row_info = mysql_fetch_array($rs_info);

$curso = $row_info['nivel_nombre']."-".$row_info['letra_nombre'];
$asignatura = $row_info['asignatura_nombre'];
$encargado = $user_nombre." ".$user_apellidos; //Variable para saber quien rechazo o acepto una carga

$borde = $_GET['carga'];
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
	<link 
	href="../css-sc/iphone.css"
	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
	rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<title>Perfil Docente</title>
	<?php 
		include('../fonts/fonts.php');
		include('../js-sc/bootstrap.php'); 
	?>
    <script src="../js-sc/validar_caracteres.js"></script>
</head>
<body>
<!------ Include the above in your HEAD tag ---------->

<div id="wrapper">
    <div class="overlay"></div>
	<?php 
	include('menu-lateral.php');
	?>
	<div id="page-content-wrapper">
            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                <span class="hamb-top"></span>
    			<span class="hamb-middle"></span>
				<span class="hamb-bottom"></span>
            </button>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
					<h1>Carga Cursos</h1>
					<h1>Unidades</h1>	
					<?php 
					$sql_unidad = "SELECT * FROM cursos_asignaturas WHERE curso_asignatura_id = '$cur_asig'";
					$rs_unidad = mysql_query($sql_unidad, $conexion);
					$row_unidad = mysql_fetch_array($rs_unidad);

					$unidad = $row_unidad['curso_asignatura_unidades'];
					?>
					<table class="header2">
						<tr>
							<th>Curso</th>
							<th>Asignatura</th>
							<th>Nombre Carga</th>
							<th>Ver Archivo</th>
							<th>Observación</th>
							<th>Observación Corrección</th>
							<th>Aprobación</th>
							<th>Aprobado/Rechazado Por</th>
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
							$rs_cursos = mysql_query($sql_cursos, $conexion);
							$row_cursos = mysql_fetch_array($rs_cursos);

                                if($row_cursos['carga_id'] == $borde && $borde != ''){
                                    echo "<tr style='border:red 2px solid;'>";
                                }else if($row_cursos['carga_bool']=='-1' && $row_cursos['carga_archivo']!=''){
									echo "<tr style='background:#FFB6C1 !important;'>";
								}else{
									echo "<tr>";
                                }
							    echo "
									<td>".$curso."</td>
									<td>".$asignatura."</td>";
									if($unidad>10){
										echo "<td>"."SEMANA ".$contador."</td>";
									}else{
										echo "<td>".$array[$contador+1]."</td>";
									}

									if($row_cursos['carga_archivo'] == ''){
										echo "<td>--</td>";								  
									}
									else{ 
										$carga_archivo = $row_cursos['carga_archivo'];
										?>
										<td><a id="myanchor" download="<?=$curso."-".$asignatura?>" href="../profesor-sc/<?=$carga_archivo?>"><i class='fas fa-search fa-lg' id='open<?=$row_cursos['carga_id']?>'></i></a></td>										
										<input type="hidden" name="id_curso<?=$row_cursos['carga_id']?>" id="id_curso<?=$row_cursos['carga_id']?>" value="<?=$row_cursos['carga_id']?>">
										<script>
                                        //Script encargado de mostrar el popup para aceptar/rechazar
                                        //Ademas de descargar el archivo con el nombre cambiado
										$(document).ready(function(){
										    $('#open<?=$row_cursos['carga_id']?>').on('click', function(){
										        $('#popup').fadeIn('slow');
										        $('.popup-overlay').fadeIn('slow');
										        $('.popup-overlay').height($(window).height());
												$("#myanchor")[0].click();
										        var id = $('#id_curso<?=$row_cursos['carga_id']?>').attr('value');
										        document.getElementById('id').value = id;
										        return false;
										    });
										 
										    $('#close').on('click', function(){
										        $('#popup').fadeOut('slow');
										        $('.popup-overlay').fadeOut('slow');
										        return false;
										    });
										});
										</script>
									<?php
									}									
									echo "<td>".$row_cursos['carga_observacion']."</td>";
									echo "<td>".$row_cursos['carga_obs_correccion']."</td>";
									if($row_cursos['carga_aprobacion'] == '0')
									{
										echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";
									}
									if($row_cursos['carga_aprobacion'] == '1')
									{
										echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";
									}
									if($row_cursos['carga_aprobacion'] == '-1')
									{
										echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></i></td>";
									}
							if($row_cursos['curso_asignatura_id'] == '')
							{
								echo "<td>Sin información</td>";
							}	
							if($row_cursos['carga_encargado'] == ''){
								echo "<td>--</td>";
							}else{
								echo "<td>".$row_cursos['carga_encargado']."</td>";
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
							<th>Nombre Carga</th>
							<th>Ver Archivo</th>
							<th>Observación</th>
							<th>Observación Corrección</th>
							<th>Aprobación</th>
							<th>Aprobado/Rechazado Por</th>
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
						$rs_cursos = mysql_query($sql_cursos, $conexion);
						$row_cursos = mysql_fetch_array($rs_cursos);
                                if($row_cursos['carga_id'] == $borde){
                                    echo "<tr bgcolor=\"#FF0000\" style='border:red 2px solid;'>";
                                }else{
									echo "<tr bgcolor=\"#FF0000\">";
								}
								$cargaanual = "ANUAL ".$row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."-".$row_cursos['asignatura_nombre'];
							   	echo "
									<td>".$row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."</td>
									<td>".$row_cursos['asignatura_nombre']."</td>
									<td>".$row_cursos['tipo_carga_nombre']."</td>";
								if($row_cursos['carga_archivo'] == ''){
									echo "<td>--</td>";								  
								}else{
								?>
									<td><a id="anchor" download="<?=$cargaanual?>" href="../profesor-sc/<?=$row_cursos['carga_archivo']?>"><i class='fas fa-search fa-lg' id='open<?=$row_cursos['carga_id']?>'></i></a></td>										
										<input type="hidden" name="id_curso<?=$row_cursos['carga_id']?>" id="id_curso<?=$row_cursos['carga_id']?>" value="<?=$row_cursos['carga_id']?>">
										<script>
										$(document).ready(function(){
										    $('#open<?=$row_cursos['carga_id']?>').on('click', function(){
										        $('#popup').fadeIn('slow');
										        $('.popup-overlay').fadeIn('slow');
										        $('.popup-overlay').height($(window).height());
										        $("#anchor")[0].click();
										        var id = $('#id_curso<?=$row_cursos['carga_id']?>').attr('value');
										        document.getElementById('id').value = id;
										        return false;
										    });
										 
										    $('#close').on('click', function(){
										        $('#popup').fadeOut('slow');
										        $('.popup-overlay').fadeOut('slow');
										        return false;
										    });
										});
										</script> <?php
								}
								echo "<td>".$row_cursos['carga_observacion']."</td>";
								echo "<td>".$row_cursos['carga_obs_correccion']."</td>";
								if($row_cursos['carga_aprobacion'] == '0')
								{
								echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";  
								}
								if($row_cursos['carga_aprobacion'] == '1')
								{
									echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";
								}
								if($row_cursos['carga_aprobacion'] == '-1')
								{
									echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></td>";
								}
						if($row_cursos['curso_asignatura_id'] == '')
						{
							echo "<td>Sin info</td>";
						}	
						if($row_cursos['carga_encargado'] == ''){
							echo "<td>--</td>";
						}else{
							echo "<td>".$row_cursos['carga_encargado']."</td>";
						}	
						?>
					</table>
				</div>
			</div>

        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
<div class="popup-contenedor" style="display: none;" id="popup">
	<div class="popup-form">
		<form method="post" action="carga_aprobar.php">
			<a href="" id="close"><i class="far fa-times-circle"></i></a>
			<label>Observación Planificación</label>
			<textarea placeholder="Ingrese Observación" name="observacion" onkeyup="reemplazar(this);"></textarea>
			<label>Aceptar</label>
			<input type="radio" name="decision" value="1">
			<label>Rechazar</label>
			<input type="radio" name="decision" value="-1">
			<input type="hidden" name="id" id="id">
			<input type="hidden" name="cur_asig" value="<?=$cur_asig?>">
			<input type="hidden" name="encargado" value="<?=$encargado?>">
			<input type="submit" value="Enviar">
		</form>
	</div>
</div>
</body>
</html>



