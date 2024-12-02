<?php 
include('../funciones-sc/conexion.php');
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
                        <h1>alumnos</h1>
                    	<table class="header2">
                            <tr>
                                <th class="back-fff"></th>
                                <th colspan="2">Nuevo Alumno</th>
                                <th><a href='alumnos_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>
                            </tr>
                    		<tr>
                    			<th>Rut alumno</th>
                    			<th>Nombre alumno</th>
                                <th>Modificar</th>
                                <th>Eliminar</th>
                    		  </tr>
                        <?php 
                        $sql = "SELECT * 
                                FROM alumnos 
                                WHERE alumno_estado = '1'";
                        $rs = mysql_query($sql, $conexion);
                        while($row = mysql_fetch_array($rs))
                        {
                        	echo "<tr>
                        			<td>".$row['alumno_rut']."</td>
                        			<td>".$row['alumno_nombres']." ".$row['alumno_apellidos']."</td>          
                                    <td><a href='alumnos_modificar.php?id=".$row['alumno_id']."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>
                                    <td><a href='alumnos_eliminar.php?id=".$row['alumno_id']." onclick = 'javascript: return confirm('Desea Eliminar Este alumno?');'><i class='far fa-times-circle fa-lg'></i></a></td>
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