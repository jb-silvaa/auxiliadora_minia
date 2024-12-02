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

  <link rel="stylesheet" type="text/css" href="../css-sc/btn_cerrarsession.css">

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

                        <h1>MODIFICAR NIVEL ASIGNATURA</h1>

                        <table class="header2">

                        <form method="POST" action="niveles_asignaturas_nuevo_proc.php" enctype="multipart/form-data">

                            <?php

                            $sql_asignaturas = "SELECT * FROM asignaturas WHERE asignatura_estado = '1' ORDER BY asignatura_nombre aSC";

                            $rs_asignaturas = mysqli_query($conexion, $sql_asignaturas);

                            while($row_asignaturas = mysqli_fetch_array($rs_asignaturas))

                            {            

                            ?>

                            <tr> 

                            <td><?=$row_asignaturas['asignatura_nombre']?></td>

                            <?php 

                            $asig_id = $row_asignaturas['asignatura_id'];

                            $sql_check = "SELECT asignatura_id FROM niveles_asignaturas WHERE asignatura_id = '$asig_id' AND nivel_id = '$id' AND nivel_asignatura_estado = '1'";

                            $rs_check = mysqli_query($conexion, $sql_check);

                            $row_check = mysqli_fetch_array($rs_check);

                            ?>                         

                            <td> <input type="checkbox" <?php if($row_check['asignatura_id'] != ''){ echo " checked "; } ?> id="checkbox" name="asignatura[]" value="<?=$row_asignaturas['asignatura_id']?>"> 

                                 <input type="hidden" name="id" value="<?=$id?>">

                            </td></tr>

                            <?php

                            }

                            ?>

                            <div class="info-100">

                                <input type="submit" name="BtnIngresar" value="INGRESAR">

                            </div> 

                        </form>  

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

<script type="text/javascript">

    function archivo(evt) {

      var files = evt.target.files; // FileList object

       

        //Obtenemos la imagen del campo "file". 

      for (var i = 0, f; f = files[i]; i++) {         

           //Solo admitimos imágenes.

           if (!f.type.match('image.*')) {

                continue;

           }

       

           var reader = new FileReader();

           

           reader.onload = (function(theFile) {

               return function(e) {

               // Creamos la imagen.

                      document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');

               };

           })(f);

 

           reader.readAsDataURL(f);

       }

}

             

      document.getElementById('files').addEventListener('change', archivo, false);

</script>