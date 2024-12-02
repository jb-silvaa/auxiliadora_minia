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

$sql_letra = "SELECT * FROM letras WHERE letra_id = '$id'";

$rs_letra = mysqli_query($conexion, $sql_letra);

$row_letra = mysqli_fetch_array($rs_letra);

$letra = $row_letra['letra_nombre'];

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

                        <h1>MODIFICAR LETRA</h1>

                    	<form method="POST" action="letras_modificar_proc.php" enctype="multipart/form-data" onsubmit="return validator();">

                            <div class="info-50 margin-25">

                            <label>Letra</label>

                                <input type="text" id="nombre" name="nombre" readonly value="<?=$letra?>">

                                <label>ESTADO</label>                                

                                <select id="estado" name="estado" required class="minimal">

                                    <option value="">Seleccione Estado</option>

                                    <option value="1">Estado Activo</option> 

                                    <option value="-1">Estado Inactivo</option> 

                                </select>

                                <input type="hidden" name="id" value="<?=$row_letra['letra_id']?>">

                            </div>

                            <div class="info-100">

                                <input type="submit" value="MODIFICAR">

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