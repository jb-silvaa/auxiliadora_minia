<?php 

error_reporting(0);

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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<?php 

		include('../fonts/fonts.php'); 

		//include('../js-sc/bootstrap.php'); 

	?>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

<!------ Include the above in your HEAD tag ---------->



    <div id="wrapper">

        <div class="overlay"></div>

        <!-- Sidebar --> 

        <!-- /#sidebar-wrapper -->

        <div class="contenedor-100">

            <?php 

    include('menu-lateral.php');



    ?>

                        <div class="perfil-der">

                        <h1>LISTADO DE REUNIONES</h1>

                    	<table class="header2">

                            <tr>

                              

                                <tr>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th colspan="2">Agregar Reunión </th>

                                <th><a href='reunion_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>

                            </tr>

                    		<tr>

                                <th>Curso</th>

                                <th>Tipo</th>

                                <th>Asunto</th>

                                <th>Resumen</th>

                                <th>Archivo</th>

                    		  </tr>

                        <?php

                        $sql = "SELECT * 

                        FROM profesor_jefe_reuniones as pjr,

                            niveles as n,

                            letras as l

                        WHERE pjr.profesor_id = $user_id

                        AND n.nivel_id = pjr.nivel_id

                        AND l.letra_id = pjr.letra_id";

                        $rs = mysqli_query($conexion, $sql); 

                        while($row = mysqli_fetch_array($rs)){

                        	echo "<tr>

                                    <td>".$row['nivel_nombre']."-".$row['letra_nombre']."</td>

                                    <td>".$row['reunion_tipo']."</td>

                                    <td>".$row['reunion_asunto']."</td>

                                    <td>".$row['reunion_resumen']."</td>";

                                    if($row['reunion_archivo'] == ''){

                                        echo "<td>--</td>";

                                    }else{

                                        echo "<td><a href=".$row['reunion_archivo']."><i class='fas fa-search fa-lg'></i></a></td>";

                                    }

                                    echo "

                                    

                        		  </tr>";

                        }

                        ?>

                        </table> 

                        </div>                     

                    </div>

                </div>



    <!-- /#wrapper -->

</body>

</html>