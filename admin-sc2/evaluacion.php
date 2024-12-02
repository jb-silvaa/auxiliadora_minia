<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
session_start();
$user_id = $_SESSION['id'];
include('../funciones-sc/conexion.php');
function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
$sql_user = "SELECT * FROM usuarios
			 WHERE usuario_id = '$user_id'
";
$rs_user = mysql_query($sql_user, $conexion);
$row_user = mysql_fetch_array($rs_user);
$user_nombre = $row_user['usuario_nombres'];
$user_apellidos = $row_user['usuario_apellidos'];
$encargado = $user_nombre." ".$user_apellidos;
$cur_asig = $_GET['id'];
$eval_id = $_GET['id2'];
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
            <div class="container" style="width: 90% !important;">
                <div class="row">
                    <div class="col-lg-12">
					<h1>Evaluaciones Cursos</h1>
                    <div>
                        <style>
                            .dot {
                                height: 25px;
                                width: 25px;
                                border-radius: 50%;
                                display: inline-block;
                                margin-left: 15px;
                                margin-right: 30px;
                                margin-top: 10px;
                                margin-bottom: 10px;
                            }
                        </style>
                        <table>
                        <tr>
                        <td><label> Evaluación seleccionada desde calendario</label><span class="dot" style="background-color: #add8e6 !important;"></span></td>
                        <td><label> Evaluación resubida</label><span class="dot" style="background-color: #FFB6C1 !important;"></span></td>
                        </tr>
                        </table>
                    
                    </div>
					<table class="header2" style="width: 100% !important; margin: 10px 0px !important; ">
						<tr>
							<th>Curso</th>
							<th>Asignatura</th>
							<th>Nombre Ev.</th>
							<th>Fecha Creación</th>
							<th>Fecha Ev.</th>
							<th>Copias</th>
							<th>Ver Archivo</th>
							<th>Observación</th>
							<th>Observación Profesor</th>
							<th>Aprobación</th>
							<th>Estado</th>
							<th>Aprobado/Rechazado Por</th>
						</tr>			
						<?php 
							$sql_cursos = "SELECT *
										  FROM niveles as n,
										  	   asignaturas as a,
										  	   cursos_asignaturas as ca,
										  	   evaluaciones as ev,
									   		   tipos_evaluaciones as te,
									   		   letras as l,
									   		   evaluaciones_estado as ee
									   	  WHERE n.nivel_id = ca.nivel_id
									  	  AND l.letra_id = ca.letra_id
									  	  AND a.asignatura_id = ca.asignatura_id
									  	  AND te.tipo_evaluacion_id = ev.tipo_evaluacion_id
									 	  AND ca.curso_asignatura_id = ev.curso_asignatura_id
									 	  AND ev.curso_asignatura_id = '$cur_asig'
									 	  AND ev.evaluacion_estado = '1'
									 	  AND ev.evaluacion_estado_id = ee.evaluacion_estado_id
							";
							$rs_cursos = mysql_query($sql_cursos, $conexion);
							while($row_cursos = mysql_fetch_array($rs_cursos))
							{
								if($eval_id != '' && $eval_id == $row_cursos['evaluacion_id']){
                                    echo "<tr style='background:#add8e6 !important;'>";
								}elseif($row_cursos['evaluacion_bool']=='-1' && $row_cursos['evaluacion_archivo']!=''){
                                    echo "<tr style='background:#FFB6C1 !important;'>";
                                }else{
									echo "<tr>";
                                }
								echo "
									<td>".$curso."</td>
									<td>".$asignatura."</td>
									<td>".$row_cursos['evaluacion_nombre']."</td>
									<td>".$row_cursos['evaluacion_fecha_creacion']."</td>
									<td>".$row_cursos['evaluacion_fecha']."</td>
									<td>".$row_cursos['evaluacion_copia']."</td>";

									if($row_cursos['evaluacion_archivo'] == ''){
										echo "<td>--</td>";								  
									}else{ 
										$evaluacion_archivo = $row_cursos['evaluacion_archivo'];
										?>
										<!-- Descarga de archivo, se encarga de cambiar el nombre -->
										<td><a id="myanchor" download="<?=$curso."-".$asignatura?>" href="../profesor-sc/<?=$evaluacion_archivo?>"><i class='fas fa-search fa-lg' id='open<?=$row_cursos['evaluacion_id']?>'></i></a></td>										
										<input type="hidden" name="id_curso<?=$row_cursos['evaluacion_id']?>" id="id_curso<?=$row_cursos['evaluacion_id']?>" value="<?=$row_cursos['evaluacion_id']?>">
										<script>
										    $('#open<?=$row_cursos['evaluacion_id']?>').on('click', function(){
										        $('#popup').fadeIn('slow');
										        $('.popup-overlay').fadeIn('slow');
										        $('.popup-overlay').height($(window).height());
										        $("#myanchor")[0].click()
										        var id = $('#id_curso<?=$row_cursos['evaluacion_id']?>').attr('value');
										        document.getElementById('id').value = id;
										        return false;
										    });
										 
										    $('#close').on('click', function(){
										        $('#popup').fadeOut('slow');
										        $('.popup-overlay').fadeOut('slow');
										        return false;
										    });
										</script>
									<?php
									}									
									echo "<td>".$row_cursos['evaluacion_observacion']."</td>";
									echo "<td>".$row_cursos['evaluacion_obs_correccion']."</td>";
									if($row_cursos['evaluacion_aprobacion'] == '0')
									{
										echo "<td><img src='../images-sc/exclamacion3.png' height='25' width='25'></i></td>";
									}
									if($row_cursos['evaluacion_aprobacion'] == '1')
									{
										echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";
									}
									if($row_cursos['evaluacion_aprobacion'] == '-1')
									{
										echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></i></td>";
									}
									if($row_cursos['curso_asignatura_id'] == '')
									{
										echo "<td>Sin información</td>";
									}
									echo "<td>".$row_cursos['evaluacion_estado_nombre']."</td>";
									if($row_cursos['evaluacion_encargado'] == ''){
										echo "<td>--</td>";
									}else{
										echo "<td>".$row_cursos['evaluacion_encargado']."</td></tr>";
									}	
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
		<form method="post" action="evaluacion_aprobar.php">
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



