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

$sql_asignatura = "SELECT * FROM asignaturas WHERE asignatura_id = '$id'";

$rs_asignatura = mysqli_query($conexion, $sql_asignatura);

$row_asignatura = mysqli_fetch_array($rs_asignatura);

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

   <script src="validar_asignatura.js"></script>

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

                        <h1>MODIFICAR ASIGNATURA NIVEL</h1>

                    	<form method="POST" action="asignatura_dificultad_modificar_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

                            <div class="info-50 margin-25">

                                <label>Nombre asignatura</label>

                                <input style="margin: 10px 1%;" type="text" id="nombre" name="nombre" placeholder="INGRESE NOMBRE" value="<?=$row_asignatura['asignatura_nombre']?>" readonly>

                                <input type="hidden" name="id" value="<?=$row_asignatura['asignatura_id']?>">

                                <label >Tiene nivel de dificultad ?</label>



                             

                              <span ><label style="margin-left:1px;" >SI</label> <input style="width: 1px; text-align: center;"  type="radio" name="dificultad" value="1" <?php if($row_asignatura['asignatura_dificultad'] == '1'){ echo ' checked '; } ?>></span>



                              <span> <label style="margin-left: 150px;  ">NO</label> <input style="width: 1px; text-align: center;" type="radio" name="dificultad" value="0" <?php if($row_asignatura['asignatura_dificultad'] == '0'){ echo ' checked '; } ?>> </span>



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