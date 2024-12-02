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
                        <h1>Agregar Evaluacion </h1>
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
                               $tipo=mysql_query($sql);
                               while($row=mysql_fetch_array($tipo))
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
	