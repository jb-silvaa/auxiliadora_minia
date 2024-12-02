<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
if ($_SESSION['perfil'] != 1){
	echo "<script LANGUAGE='JavaScript'>
				  window.alert('Acceso no autorizado');
				  window.location= 'listado_cursos.php'
	  </script>";
	  die();
  }

  $sql_periodo = "SELECT * FROM periodos WHERE periodo_estado = '1'";
  $rs_periodo = mysql_query($sql_periodo,$conexion);
  $row_periodo = mysql_fetch_array($rs_periodo);
  $periodo = $row_periodo['periodo_periodo'];
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

    <div  id="wrapper">
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
                        <h1>NIVELES GENERADOS</h1>
                        <table class="header2">
                            <!--<tr>
                                <th class="back-fff"></th>
                                <th>GENERAR PLANTILLA ANUAL</th>
                                <th><a href='plantilla_anual_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>
                            </tr>-->
                            <tr>  
                                <th>CURSO</th>
                                <th>PERIODO</th>
                                <th>MODIFICAR</th>
                                <th>ELIMINAR</th>
                            </tr>
                        <?php 
                        /*$sql = "SELECT * FROM niveles_asignaturas N 
                        JOIN niveles NI ON N.nivel_id = NI.nivel_id 
                        JOIN asignaturas A ON N.asignatura_id = A.asignatura_id 
                        WHERE NI.niveles_estado = 1 and A.asignatura_estado= 1 
                        ORDER BY NI.nivel_id ASC ";*/
                        $sql = "SELECT * 
                        FROM niveles_creados as nc
                        INNER JOIN cursos_asignaturas as ca on nc.nivel_id = ca.nivel_id
                        AND nc.nivel_creado_periodo = ca.curso_asignatura_periodo
                        INNER JOIN niveles as n on n.nivel_id = nc.nivel_id
                        WHERE nivel_creado_estado = '1' 
                        AND nivel_creado_periodo = '$periodo'
                        GROUP BY nc.nivel_id";
                        $rs = mysql_query($sql, $conexion);
                        while($row = mysql_fetch_array($rs))
                        {
                            echo "<tr>
                                    <td>".$row['nivel_nombre']."</td>
                                    <td>".$row['nivel_creado_periodo']."</td>
                                    <td><a href='nivel_generado_modificar.php?id=".$row['nivel_creado_id']."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>";
                                    ?>
                                    <td><a href='nivel_generado_eliminar.php?id=<?=$row['nivel_creado_id']?>' onclick = "javascript: return confirm('Desea Eliminar Este nivel?');"><i class='far fa-times-circle fa-lg'></i></a></td>
                                    <?php
                                    echo "</tr>";
                        }
                        ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
