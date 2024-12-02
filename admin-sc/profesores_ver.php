<?php 

$perfil_archivo = 1;//adm = 1 , docente = 2;

include('../funciones-sc/conexion.php');



$profesor = $_GET['id'];

$sql = "SELECT * FROM profesores WHERE profesor_id = '$profesor'";

$rs = mysqli_query($conexion, $sql);

$row_profesor = mysqli_fetch_array($rs);

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Sistema Clases</title>

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

	<link 

	href="../css-sc/iphone.css"

	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

	rel="stylesheet">

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

    

        <!-- Sidebar -->

        <?php include('menu-lateral.php'); 

        $periodo = $_GET['periodo'];

		if($periodo == ''){ $periodo = $_SESSION['periodo']; }

        ?>

        

        <!-- /#sidebar-wrapper -->



        <!-- Page Content -->

        <div id="page-content-wrapper">

            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">

                <span class="hamb-top"></span>

    			<span class="hamb-middle"></span>

				<span class="hamb-bottom"></span>

            </button>

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                    	<div class="info-100">

                    		<?php if($row_profesor['profesor_imagen'] == '')

                    			  {

                    			  	echo "<img src='../images-sc/foto-sinperfil.png'>";

                    			  }else

                    			  {

                    		?>

					    	<img src="<?="../profesor-sc/".$row_profesor['profesor_imagen']?>">

					    	<?php 

					    	}

					    	?>

				        </div>

				        <div class="info-50">

					        <h1>Personal</h1>

					        <label>Nombre</label>

					        <p><?=$row_profesor['profesor_nombres']." ".$row_profesor['profesor_apellidos']?></p>

					        <label>Rut</label>

					        <p><?=$row_profesor['profesor_rut']?></p>

					        <label>Fecha Nacimiento</label>

					        <p><?=$row_profesor['profesor_fecha_nacimiento']?></p>

				    	</div>

				    	<div class="info-50">

					        <h1>Contacto</h1>

					        <label>Correo</label>

					        <p><?=$row_profesor['profesor_correo']?></p>

					        <label>Correo Personal</label>

					        <p>

					        <?php 

					        	if($row_profesor['profesor_correo_personal'] == '')

					        	{

					        		echo "--";

					        	}

					        	else

					        	{

					        		echo $row_profesor['profesor_correo_personal'];

					        	}

					        ?></p>

					        <label>Teléfono</label>

					        <p><?=$row_profesor['profesor_fono']?></p>

						</div>

					    <div class="info-100">

					    <?php 

					    	/* CALCULO PORCENTAJE DE CURSOS CON INFO */

                            $sql_cursos = "SELECT curso_asignatura_unidades

                                           FROM cursos_asignaturas

                                           WHERE curso_asignatura_periodo = '$periodo_activo'

                                           AND profesor_id = '$profesor'

                                           AND curso_asignatura_estado = '1'

                            ";

                            $rs_cursos = mysqli_query($conexion, $sql_cursos);

                            while($row_cursos = mysqli_fetch_array($rs_cursos))

                            {

                              //CALCULO EL TOTAL DE ARCHIVOS A LLENAR POR EL PROFESOR

                              $total = $total+($row_cursos['curso_asignatura_unidades']+1); 

                            }



                            $sql_cursos = "SELECT COUNT(carga_aprobacion) as aprobadas

                                           FROM cargas as c,

                                                cursos_asignaturas as ca

                                           WHERE ca.curso_asignatura_periodo = '$periodo_activo'

                                           AND ca.profesor_id = '$profesor'

                                           AND ca.curso_asignatura_estado = '1'

                                           AND c.curso_asignatura_id = ca.curso_asignatura_id

                                           AND c.carga_aprobacion = '1'

                            ";

                            $rs_cursos = mysqli_query($conexion, $sql_cursos);

                            $row_cursos = mysqli_fetch_array($rs_cursos);



                            $aprobada = $row_cursos['aprobadas'];



                            //TRANSFORMO A PORCENTAJES

                            if($aprobada > '0')

                            {

                                $porc_aprob = round(($aprobada*100/$total),2);

                            }

                            else

                            {

                                $porc_aprob = '0';

                            }

                            $porc_reprob = 100-$porc_aprob;



                            if($porc_aprob < 100){ $color = "#222"; }else{ $color = "rgb(0, 230, 64)"; }

                            /*****************************************/ ?>

					        <h1>Resumen Cursos <?=$periodo_activo?></h1>      

					        <?php 

					        echo "

						        <div style='width: 500px; float: left; height: 24px; border: 1px solid #000; background: #999;'>

	                              <div style='width: ".$porc_aprob."%; float: left; background: ".$color."; height: 22px;'><p class='porcentaje-grande'>".$porc_aprob."%</p></div>

	                              <div style='width: ".$porc_reprob."%; float: left; background: #999; height: 22px;'></div>

	                            </div>

						    </div>"; ?>

						<h1>Listado Cursos</h1>	

						<div class="filtros">

							<form method="GET">

	                            <label>Año</label>

	                            <select name="periodo" class="minimal">

	                                <?php 

	                                print $sql_periodo = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo' DESC, periodo_periodo ASC";

	                                $rs_periodo = mysqli_query($conexion, $sql_periodo);

	                                while ($row_periodo = mysqli_fetch_array($rs_periodo)) {

	                                    echo "<option value=".$row_periodo['periodo_periodo'].">".$row_periodo['periodo_periodo']."</option>";

	                                }

	                                ?>

	                            </select>

	                            <input type="hidden" name="id" value="<?=$profesor?>">                            

	                            <input type="submit" value="Filtrar">

                            </form>

						</div>

						<table class="header2">

							<tr>

								<th>Curso</th>

								<th>Asignatura</th>

								<th>Dificultad</th>

								<th>Avance</th>

								<th>Carga</th>

								<th>Evaluaciones</th>

							</tr>			

							<?php 

							$sql_cursos = "SELECT * 

			                                FROM asignaturas as a,

			                                     cursos_asignaturas as ca,

			                                     letras as l,

			                                     niveles as n,

			                                     profesores as p,

			                                     dificultades as d

			                                WHERE a.asignatura_estado = '1'

			                                AND ca.asignatura_id = a.asignatura_id

			                                AND n.nivel_id = ca.nivel_id

			                                AND l.letra_id = ca.letra_id

			                                AND ca.dificultad_id = d.dificultad_id

			                                AND ca.profesor_id = p.profesor_id

			                                AND ca.curso_asignatura_periodo = '$periodo'

			                                AND ca.profesor_id = '$profesor'

			                                AND ca.curso_asignatura_estado = '1'

			                                ORDER BY ca.nivel_id, ca.letra_id ASC

										   ";

							$rs_cursos = mysqli_query($conexion, $sql_cursos);

							$contador = '0';

							while ($row_profesor_cursos = mysqli_fetch_array($rs_cursos)) {

								$unidad = $row_profesor_cursos['curso_asignatura_unidades']+1; //AGREGO LA ANUAL CON EL +1



								$sql_unidad = "SELECT count(carga_id) as total FROM cargas WHERE curso_asignatura_id = ".$row_profesor_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '1'";

								$rs_unidad = mysqli_query($conexion, $sql_unidad);

								$row_unidad = mysqli_fetch_array($rs_unidad);



								$uni_apro = $row_unidad['total'];



								$valor = round(($uni_apro*100/$unidad),2);

								if($valor < 100){ $color = "#222"; }else{ $color = "rgb(0, 230, 64)"; }

								$valor_no = 100-$valor;

								echo "<tr>

										<td>".$row_profesor_cursos['nivel_nombre']."-".$row_profesor_cursos['letra_nombre']."</td>

										<td>".$row_profesor_cursos['asignatura_nombre']."</td>

										<td>".$row_profesor_cursos['dificultad_nombre']."</td>

										<td width='260'>

											<div style='width: 240px; float: left; height: 14px; border: 1px solid #000; background: #999;'>

									          <div style='width: ".$valor."%; float: left; background: ".$color."; height: 12px;'><p class='porcentaje'>".$valor."%</p></div>

									          <div style='width: ".$valor_no."%; float: left; background: #999; height: 12px;'></div>

									      	</div>

									      </td>

										<td><a href='carga.php?id=".$row_profesor_cursos['curso_asignatura_id']."'><i class='fas fa-search fa-lg'></i></a></td>

										<td><a href='evaluacion.php?id=".$row_profesor_cursos['curso_asignatura_id']."'><i class='fas fa-search fa-lg'></i></a></td>

									</tr>";

								$contador++;

							}

							if($contador == '0'){ echo "<td colspan='6'>No hay resultados para los filtros escogidos</td>"; }

							?>

						</table>

</div>

</body>

</html>