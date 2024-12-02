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
$ayudante_id = $_GET['id'];
$sql_curso = "SELECT * 
            FROM ayudantes as ay
            INNER JOIN cursos_asignaturas as ca on ca.curso_asignatura_id = ay.curso_asignatura_id
            INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
            INNER JOIN letras as l on l.letra_id = ca.letra_id
            INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
            INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
            WHERE ay.ayudante_id = '$ayudante_id'";
$rs_curso = mysql_query($sql_curso, $conexion);
$row_curso = mysql_fetch_array($rs_curso);

$profesor = $row_curso['profesor_ayudante_id'];
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
                        <h1>Modificar Ayudante</h1>
                    	<form method="POST" action="ayudante_modificar_proc.php" enctype="multipart/form-data">
                            <div class="info-50 margin-25">
                                <label>Nivel</label>
                                <input type="text" name="nivel" readonly="" value="<?=$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']?>" >
                                <input type="hidden" name="id" value="<?=$row_curso['curso_asignatura_id']?>">
                                <input type="hidden" name="ayudante" value="<?=$row_curso['ayudante_id']?>">
                                <label>Asignatura</label>
                                <input type="text" name="asignatura" readonly=""  value="<?=$row_curso['asignatura_nombre']?>">
                                <label>Díficultad</label>
                                <input type="text" name="dificultad" readonly=""  value="<?=$row_curso['dificultad_nombre']?>">
                                <label>Ayudante</label>
                                <select name="profesor" class="minimal">
                                  <?php 
                                  if($profesor == '0'){ echo "<option value='0'>SIN INFO</option>"; }
                                  $sql_profesor = "SELECT * 
                                                   FROM profesores AS p
                                                   WHERE profesor_estado = '1'
                                                   AND profesor_id <> '0'                          
                                                   ORDER BY p.profesor_id = '$profesor' DESC, profesor_apellidos, profesor_nombres ASC";
                                  $rs_profesor = mysql_query($sql_profesor, $conexion);
                                  while($row_profesor = mysql_fetch_array($rs_profesor))
                                  {
                                    echo "<option value=".$row_profesor['profesor_id'].">".$row_profesor['profesor_apellidos']." ".$row_profesor['profesor_nombres']."</option>";
                                  }
                                  if($profesor != '0'){ echo "<option value='0'>SIN INFO</option>"; }
                                  ?>
                                </select>
                            </div>
                            <div class="info-100">
                                <input type="submit" value="INGRESAR">
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