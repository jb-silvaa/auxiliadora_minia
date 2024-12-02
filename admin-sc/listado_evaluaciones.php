<?php 

$perfil_archivo = 1;//adm = 1 , docente = 2;
$id = $_GET['id'];
include('../funciones-sc/conexion.php');

session_start();

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'listado_cursos.php'

    </script>";

    die();

}
$sql = "SELECT * 
        FROM asignaturas as a,
             cursos_asignaturas as ca,
             letras as l,
             niveles as n,
             profesores as p,
             dificultades as d,
             periodos as pe
        WHERE a.asignatura_estado = '1'
        AND ca.asignatura_id = a.asignatura_id
        AND n.nivel_id = ca.nivel_id
        AND l.letra_id = ca.letra_id
        AND ca.dificultad_id = d.dificultad_id
        AND ca.profesor_id = p.profesor_id
        AND pe.periodo_periodo = ca.curso_asignatura_periodo
        AND pe.periodo_activo = '1'
        AND ca.curso_asignatura_estado = '1'
        AND ca.curso_asignatura_id = '$id'
        ORDER BY ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";
$rs = mysqli_query($conexion, $sql);
$row_data = mysqli_fetch_array($rs);

$curso = $row_data['asignatura_nombre']." - ".$row_data['dificultad_nombre']." - ".$row_data['nivel_nombre'];
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

        <?php include('menu-lateral.php'); ?>

        

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

                        <h1>Listado Evaluaciones (<?=$curso?>)</h1>
                        <form method="POST" action="modificar_evaluacion_proc.php">
                    	<table class="header2">
<!--
                            <tr>

                                <th class="back-fff"></th>

                                <th colspan="2">Nueva asignatura</th>

                                <th><a href='asignaturas_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>-->

                    		<tr>
                    			<th>Evaluación Nombre</th>
                    			<th>Evaluación Tipo</th>
                                <th>Evaluación Fecha</th>
                                <th>Eliminar</th>
                    		  </tr>

                        <?php 

                        $sql = "SELECT *
                                FROM evaluaciones
                                WHERE evaluacion_estado = '1'
                                AND curso_asignatura_id = '$id'
                                ORDER BY evaluacion_fecha ASC";

                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {                         
                            $te_id = $row['tipo_evaluacion_id'];
                        	echo "<tr>

                        			<td><input style='height: 30px; width: 180px;' type='text' name='nameprueba".$row['evaluacion_id']."' value='".$row['evaluacion_nombre']."'></td>
                        			<td><select class='minimal' name='evaluacion".$row['evaluacion_id']."' style='width: 180px;'>";
                                       
                                       $sql_t="SELECT * FROM tipos_evaluaciones ORDER BY tipo_evaluacion_id = '$te_id' DESC, tipo_evaluacion_id ASC";
                                       $tipo_t=mysqli_query($conexion, $sql_t);
                                       while($row_t=mysqli_fetch_array($tipo_t))
                                       {
                                        echo "<option value='".$row_t['tipo_evaluacion_id']."' >";
                                        echo $row_t['tipo_evaluacion_nombre'];
                                        echo "</option>";
                                       }                                       
                                    echo "</select></td>
                                    <td><input style='height: 30px; width: 180px; padding-top: 0;' type='date' name='inicio".$row['evaluacion_id']."' placeholder='INGRESE FECHA EV.'  min='".date('Y-m-d')."' value='".$row['evaluacion_fecha']."'></td>";

                                ?>
                                    <td><a href='listado_evaluaciones_eliminar.php?id=<?=$row['evaluacion_id']?>&ca_id=<?=$id?>' onclick = "javascript: return confirm('Desea Eliminar Esta Evaluación?');"><i class='far fa-times-circle fa-lg'></i></a></td>
                        		  </tr>

                        <?php

                            $dificultad = '';

                        }

                        ?>
                        <input type='hidden' name='id_curso' value='<?=$id?>'>
                        </table> 
                        <div style="width: 100%; float: left; text-align: center;">                     
                            <input type="submit" value="Actualizar">
                            <a href="listado_cursos.php" class="boton-negro" style="display: inline-block;">Volver</a>
                        </div>
                    </form>
                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

</body>

</html>