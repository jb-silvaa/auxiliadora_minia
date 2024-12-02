<?php 

session_start();

$user_id = $_SESSION['id'];

if (!$_SESSION['id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'index.php'

    </script>";

    die();

}

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'listado_cursos.php'

    </script>";

    die();

}

include('../funciones-sc/conexion.php');

error_reporting (0);

$clave = $_POST['clavenuevabox1'];

$sql_clave = "SELECT * FROM usuarios WHERE usuario_clave = '$clave'";

$rs_clave = mysqli_query($conexion, $sql_clave);

$row_clave = mysqli_fetch_array($rs_clave);

?>



<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

  <title>Sistema Clases</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">



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

  <script>

function comprobarClave(){

    clave1 = document.f1.clavenuevabox1.value

    clave2 = document.f1.clavenuevabox2.value



    if (clave1 == clave2)

       alert("Perfecto , ambas claves son iguales")

    else

       alert("Error claves distintas.")

     header("location:../admin-sc/cambiar_claveadm.php")

} 

</script> 



</head>

<body> 

                    

    <div id="wrapper">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12">

                        <h1>MODIFICAR CLAVE</h1> <h4>PRIMERA VEZ INGRESADO,POR SEGURIDAD PORFAVOR CAMBIE SU CLAVE</h4>  

                      <form name="f1" method="POST" action="cambiar_claveadm_proc.php" enctype="multipart/form-data" onsubmit="comprobarClave()">

                            <div class="info-50 margin-25">

                                <label>NUEVA CLAVE</label>

                                <input type="password" minlength="4" name="clavenuevabox1" id='clavenuevabox1' placeholder="ingrese nueva clave" onkeyup="reemplazar(this);" value="<?=$row_clave['usuario_clave']?>">

                                <label>CONFIRME NUEVA CLAVE</label>

                                 <input type="password" minlength="4" name="clavenuevabox2" id='clavenuevabox2' placeholder="confirme su clave" onkeyup="reemplazar(this);" value="<?=$row_clave['usuario_clave']?>">

             

                            </div>

                            <div class="info-100">

                                <input type="submit" value="GUARDAR">

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