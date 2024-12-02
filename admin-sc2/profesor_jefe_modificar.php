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
$id = $_GET['id'];
$sql_data = "SELECT * FROM profesores_jefes WHERE profesor_jefe_id = '$id'";
$rs_data = mysql_query($sql_data, $conexion);
$row_data = mysql_fetch_array($rs_data);
$profesor_id = $row_data['profesor_id'];
$nivel_id = $row_data['nivel_id'];
$letra_id = $row_data['letra_id'];
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
     <script src="validar_periodo.js"></script>
     <script src="../js-sc/validar_caracteres.js"></script>
</head>
<body>
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
                        <h1>ASIGNAR PROFESOR JEFE</h1>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                            <form method="POST" action="profesor_jefe_modificar_proc.php" onsubmit="return validator();">
                                <label>AÑO</label>
                                <input type="text" id="periodo" name="periodo" value="<?=$row_data['profesor_jefe_periodo']?>" readonly="readonly" style="margin-left: 12% !important;"> 
                                <label>CURSO</label>
                                <select id="curso" name="curso" required class="minimal">
                                <?php
                                $sql_asig = "SELECT DISTINCT *
                                            FROM cursos_asignaturas
                                            INNER JOIN letras on letras.letra_id = cursos_asignaturas.letra_id 
                                            INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id
                                            WHERE curso_asignatura_jefatura = '1'
                                            AND letras.letra_id = '$letra_id'
                                            AND niveles.nivel_id = '$nivel_id'
                                            GROUP BY nivel_nombre, letra_nombre";
                                $rs_asig = mysql_query($sql_asig, $conexion);
                                while($row_asig = mysql_fetch_array($rs_asig)){
                                    $ca_id = $row_asig['curso_asignatura_id'];
                                    $nivel = $row_asig['nivel_nombre'];
                                    $letra = $row_asig['letra_nombre'];

                                    echo "<option value='".$row_asig['nivel_id']."''>".$row_asig['nivel_nombre']."-".$row_asig['letra_nombre']."</option>";
                                }
                                ?>
                                </select>
                                <label>PROFESOR</label>                                
                                <select name="profesor" required class="minimal">
                                    <?php
                                    $sql_asig = "SELECT *
                                    FROM profesores
                                    WHERE profesor_estado = '1'
                                    ORDER BY profesor_id = '$profesor_id' DESC, profesor_nombres ASC, profesor_apellidos ASC";
                                    $rs_asig = mysql_query($sql_asig, $conexion);
                                    while($row_asig = mysql_fetch_array($rs_asig))
                                    {
                                        echo "<option value='".$row_asig['profesor_id']."''>".$row_asig['profesor_nombres']." ".$row_asig['profesor_apellidos']."</option>";
                                    }
                                    ?>                                   
                                </select>
                                <input type="hidden" name="nivel" value="<?=$nivel_id?>">
                                <input type="hidden" name="letra" value="<?=$letra_id?>">
                            <input type="submit" value="Generar">
                        </div>                        
                        </form>     
                        </div>
                        <div class="col-lg-3">
                        </div>          
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
