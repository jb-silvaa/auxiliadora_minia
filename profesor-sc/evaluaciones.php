<?php 

error_reporting(0);

include('../funciones-sc/conexion.php');

$id = $_GET['id'];

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

                        <h1>LISTADO DE EVALUACIONES</h1>

                    	<table class="header2">
                           
                            <tr>

                              

                                <tr>

                                <th class="back-fff"></th> 

                                <!--<th class="back-fff"></th>-->

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th class="back-fff"></th>

                                <th colspan="2">Agregar Evaluaciones </th>

                                <th><a href='evaluaciones_nuevo.php?id=<?=$id?>'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>

                            </tr>

                    		<tr>

                                <th>Nombre Evaluacion</th>

                                <th>Tipo Evaluacion</th>

                                <th>Fecha Evaluacion</th>

                                <th> Ver/Subir </th>
                                
                                <th>Modificar</th>

                                <!--<th>Eliminar</th>-->

                                <th>Aprobación</th>

                                <th>Observación</th>

                                <th></th>

                    		  </tr>

                        <?php 

                        $sql = "SELECT *, DATE_FORMAT( evaluacion_fecha ,  '%d-%m-%Y' ) as fecha_ev 

                                FROM evaluaciones E

                                JOIN tipos_evaluaciones TE

                                ON E.tipo_evaluacion_id = TE.tipo_evaluacion_id

                                WHERE evaluacion_estado = '1'

                                AND curso_asignatura_id = '$id'

                                ORDER BY evaluacion_fecha DESC"

                                ;



                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                        	echo "<tr>

                                    <td>".$row['evaluacion_nombre']."</td>

                                    <td>".$row['tipo_evaluacion_nombre']."</td>

                                    <td>".$row['fecha_ev']."</td>";

                                    if($row['evaluacion_archivo'] == ''){

                                        echo "<td>
                                                <form method='POST' action='evaluaciones_subir.php?id=".$row['evaluacion_id']."&curso_id=".$id."' enctype='multipart/form-data'>
                                                    <input type='file' name='files".$row['evaluacion_id']."' style='width:170px;' required='required'><br>
                                                    <input type='text' name='cantidad' placeholder='N° Copias' style='width:170px!important; float:left;padding:6px 10px!important;' required='required'>
                                                    <input type='submit' value='Subir' style='margin: 10px 40px!important; width: 80px; !important; float: left;'>
                                                </form>
                                            </td>";

                                    }else{

                                        echo "<td><a href=".$row['evaluacion_archivo']."><i class='fas fa-search fa-lg'></i></a> / ";?>
                                        <a href="evaluaciones_eliminar.php?id=<?=$row['evaluacion_id']?>&id_c=<?=$id?>" onclick = "javascript: return confirm('Desea Eliminar Este Archivo?');"><i class='far fa-times-circle fa-lg'></i></a></td>
                                        <?php

                                    }

                                    echo "

                                    

                                    <td><a href='evaluaciones_modificar.php?id=".$row['evaluacion_id']."&id_c=".$id." '><i class='fas fa-pencil-alt fa-lg'></i></a></td>                   			

                                    <!--<td><a href='evaluaciones_eliminar.php?id=".$row['evaluacion_id']."&id_c=".$id." onclick = 'javascript: return confirm('Desea Eliminar Este Evaluacion?');'><i class='far fa-times-circle fa-lg'></i></a></td>-->";

                                    if($row['evaluacion_aprobacion'] == '-1'){

                                        echo "<td><i class='fas fa-times-circle fa-lg rojo'></i></td>";				

                                    }else if($row['evaluacion_aprobacion'] == '0'){

                                        echo "<td><i class='fas fa-exclamation-circle fa-lg'></i></td>";

                                    }else if($row['evaluacion_aprobacion'] == '1'){

                                        echo "<td><i class='fas fa-check-circle fa-lg verde'></i></td>";

                                    }

                                    echo "<td>".$row['evaluacion_observacion']."</td>

                                    <td></td>

                                    

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