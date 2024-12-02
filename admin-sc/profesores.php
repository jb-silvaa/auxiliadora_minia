<?php 

error_reporting(0);

include('../funciones-sc/conexion.php');

include('funcionrut.php');

session_start();

if ($_SESSION['perfil'] != 1){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'listado_cursos.php'

    </script>";

    die();

}

$perfil_archivo = 1;//adm = 1 , docente = 2;

$data = $_GET['data'];



if($data == ''){ $filtro_profesor = ' '; }

else{ $filtro_profesor = " AND (profesor_nombres LIKE '%".$data."%' OR profesor_apellidos LIKE '%".$data."%' OR profesor_rut LIKE '%".$data."%') "; }

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

                        <h1>PROFESORES</h1>

                        <div class="filtros">

                            <form action="" method="GET">

                                <input type="text" name="data" placeholder="Ingrese RUT o Nombre" onkeyup="reemplazar(this);">

                                <input type="submit" value="Filtrar">

                            </form>                            

                        </div>

                    	<table class="header2" style="width: 100% !important; margin: 20px 0 !important;">

                            <tr>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th colspan="3">Nuevo Profesor</th>

                                <th><a href='profesores_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>

                    		<tr>

                    			<th>Rut Profesor</th>

                    			<th>Nombre Profesor</th>

                    			<th>Mail Profesor</th>

                    			<th>% Completado</th>

                                <th>Ver</th>

                                <th>Modificar</th>

                                <th>Re-setear Clave</th>

                                <th>Eliminar</th>

                    		  </tr>

                        <?php 

                        $sql = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0' $filtro_profesor ORDER BY profesor_apellidos, profesor_nombres ASC";

                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                            /* CALCULO PORCENTAJE DE CURSOS CON INFO */

                            $docente = $row['profesor_id'];

                            $total = '0';

                            $sql_cursos = "SELECT curso_asignatura_unidades

                                           FROM cursos_asignaturas

                                           WHERE curso_asignatura_periodo = '$periodo_activo'

                                           AND profesor_id = '$docente'

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

                                           AND ca.profesor_id = '$docente'

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

                            /*****************************************/

                        	echo "<tr> 

                        			<td>".formateo_rut($row['profesor_rut'])."</td>

                        			<td>".$row['profesor_apellidos']." ".$row['profesor_nombres']."</td>                     			

                        			<td>".$row['profesor_correo']."</td>

                        			<td width='260'>

                                        <div style='width: 240px; float: left; height: 14px; border: 1px solid #000;'>

                                          <div style='width: ".$porc_aprob."%; float: left; background: ".$color."; height: 12px;'><p class='porcentaje'>".$porc_aprob."%</p></div>

                                          <div style='width: ".$porc_reprob."%; float: left; background: #999; height: 12px;'></div>

                                        </div>

                                    </td>

                                    <td><a href='profesores_ver.php?id=".$row['profesor_id']."'><i class='fas fa-search fa-lg'></i></a></td>

                                    <td><a href='profesores_modificar.php?id=".$row['profesor_id']."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>";

                                    ?><td><a href='profesores_cambia_clave.php?id=<?=$row['profesor_id']?>' onclick ="javascript: return confirm('Desea Re-setear la clave?');"><i class="fas fa-key fa-lg"></i></a></td>

                                    <td><a href='profesores_eliminar.php?id=<?=$row['profesor_id']?>' onclick ="javascript: return confirm('Desea Eliminar Este profesor?');"><i class='far fa-times-circle fa-lg'></i></a></td>

                        		  </tr>

                        <?php

                        }

                        ?>

                        </table>                      

                    </div>

                </div>

            </div>

        </div>

        <!-- /#page-content-wrapper -->



    </div>

    <!-- /#wrapper -->

</body>

</html>