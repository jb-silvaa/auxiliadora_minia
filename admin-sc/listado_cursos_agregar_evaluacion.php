<?php 
error_reporting(0);
session_start();
include('../funciones-sc/conexion.php');
function debug_to_console($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
$user_id = $_SESSION['profesor_id'];
$id = $_POST['id']; // curso asignatura id
$cantidad_ev=$_POST['cantidad_ev'];
$fecha = date('Y-m-d');

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
	rel="stylesheet"><?php 
session_start();
$user_id = $_SESSION['id'];
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');


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
                        <h1>Agregar Evaluacion (<?=$curso?>)</h1>
                 </div>   
            <form method="POST" action="agregar_evaluacion_proc.php" enctype="multipart/form-data" onsubmit="return validator();">
		    
           <?php for($i=0;$i<$cantidad_ev;$i++){   ?>
            <div class="row">
               <div class="col-md-4">
               
                <label>Nombre de evaluacion</label>
                <input type="text" id="nombre" name="nameprueba[]">
                </div>
                <div class="col-md-4">
		        <label>Tipo de Evaluacion</label>
		           	<select    class="minimal" name='evaluacion[]'>
                               <?php 
                               $sql="SELECT * FROM tipos_evaluaciones";
                               $tipo=mysqli_query($conexion, $sql);
                               while($row=mysqli_fetch_array($tipo))
                               {
                                echo "<option value='".$row['tipo_evaluacion_id']."' >";
                                echo $row ['tipo_evaluacion_nombre'];
                                echo "</option>";
                               }
                               ?>
                    </select>
                    </div>
                    <div class="col-md-4">
                <label>Fecha Evaluacion</label>
		           	<input type="date" id="date" name="inicio[]" placeholder="INGRESE FECHA INICIO"  min=<?php echo date('Y-m-d'); ?>>
                    <input type="hidden" name="fechacreacion" value="<?=$fecha?>">
                    <input type="hidden" name="id_curso" value="<?=$id?>">
                    </div>
</div>
<?php }?>
		            <div class="info-100">
                    <input type="submit" value="INGRESAR">
                </div>   
                          
            </form>
                            
                         
                        </div>
                    	                   
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
</body>
</html>
	