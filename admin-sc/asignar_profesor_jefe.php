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

                            <form method="POST" action="asignar_profesor_jefe_proc.php" onsubmit="return validator();">

                                <label>AÑO</label>                                

                                <select id="periodo" name="periodo" required class="minimal">

                                    <option value="">Seleccione Año</option> 

                                    <?php

                                    $sql_asig = "SELECT * FROM periodos WHERE periodo_activo = '1' ORDER BY periodo_periodo DESC";

                                    $rs_asig = mysqli_query($conexion, $sql_asig);

                                    while($row_asig = mysqli_fetch_array($rs_asig))

                                    {

                                        echo "<option value='".$row_asig['periodo_periodo']."''>".$row_asig['periodo_periodo']."</option>";

                                    }

                                    ?>                                   

                                </select>

                                <label>CURSO</label>

                                <select id="curso" name="curso" required class="minimal">

                                    <option value="0">- Select -</option>

                                </select>

                                <script>

                                    $(document).ready(function(){



                                    $("#periodo").change(function(){

                                        var anio = $(this).val();

                                        //console.log(anio);



                                        $.ajax({

                                            url: 'getProfeJefe.php',

                                            type: 'post',

                                            data: {periodo:anio},

                                            dataType: 'json',

                                            success:function(response){



                                                var len = response.length;



                                                $("#curso").empty();

                                                for( var i = 0; i<len; i++){

                                                    var id = response[i]['ca_id'];

                                                    var nivel = response[i]['nivel'];

                                                    var letra = response[i]['letra'];

                                                    

                                                    $("#curso").append("<option value='"+id+"'>"+nivel+"-"+letra+"</option>");



                                                }

                                            }

                                        });

                                    });



                                    });

                                </script>

                                <label>PROFESOR</label>                                

                                <select name="profesor" required class="minimal">

                                    <option value="">Seleccione Profesor</option> 

                                    <?php

                                    $sql_asig = "SELECT *

                                    FROM profesores

                                    WHERE profesor_estado = '1'
                                    ORDER BY profesor_apellidos ASC, profesor_nombres ASC
                                    ";

                                    $rs_asig = mysqli_query($conexion, $sql_asig);

                                    while($row_asig = mysqli_fetch_array($rs_asig))

                                    {

                                        echo "<option value='".$row_asig['profesor_id']."''>".$row_asig['profesor_apellidos']." ".$row_asig['profesor_nombres']."</option>";

                                    }

                                    ?>                                   

                                </select>

                            <input type="submit" value="ASIGNAR">

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

