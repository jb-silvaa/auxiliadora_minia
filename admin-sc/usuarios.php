<?php 

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

                        <h1>USUARIOS</h1>

                    	<table class="header2">

                            <tr>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th colspan="2">Nuevo Usuario</th>

                                <th><a href='usuarios_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>

                    		<tr>

                    			<th>Rut Usuario</th>

                    			<th>Nombre Usuario</th>

                    			<th>Mail Usuario</th>

                    			<th>Fono Usuario</th>

                    			<th>Perfil</th>

                                <th>Modificar</th>

                                <th>Eliminar</th>

                    		  </tr>

                        <?php 

                        $sql = "SELECT * 

                                FROM usuarios as u,

                                     perfiles as p 

                                WHERE usuario_estado = '1'

                                AND p.perfil_id = u.perfil_id
                                ORDER BY usuario_nombres, usuario_apellidos asc";

                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                        	echo "<tr>

                        			<td>".formateo_rut($row['usuario_rut'])."</td>

                        			<td>".$row['usuario_nombres']." ".$row['usuario_apellidos']."</td>                     			

                        			<td>".$row['usuario_mail']."</td>

                        			<td>".$row['usuario_fono']."</td>

                        			<td>".$row['perfil_nombre']."</td>

                                    <td><a href='usuarios_modificar.php?id=".$row['usuario_id']."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>

                                    <td><a href='usuarios_eliminar.php?id=".$row['usuario_id']." onclick = 'javascript: return confirm('Desea Eliminar Este Usuario?');'><i class='far fa-times-circle fa-lg'></i></a></td>

                        		  </tr>";

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