<?php 
session_start();
$user_id = $_SESSION['jefe_id'];
if (!$_SESSION['jefe_id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
  die();
}
include('../funciones-sc/conexion.php');
error_reporting (0);
$clave = $_POST['clavenuevabox1'];
$sql_clave = "SELECT * FROM jefes_area WHERE jefe_clave = '$clave'";
$rs_clave = mysqli_query($conexion, sql_clave);
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
  
  <script>
function comprobarClave(){
    clave1 = document.f1.clavenuevabox1.value
    clave2 = document.f1.clavenuevabox2.value

    if (clave1 == clave2)
       alert("Perfecto , ambas claves son iguales")
    else
       alert("Error claves distintas.")
     header("location:../jefes_area-sc/cambiar_clave.php")
} 
</script> 

</head>
<body> 
                    
    <div id="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>MODIFICAR CLAVE</h1> <h4>PRIMERA VEZ INGRESADO,POR SEGURIDAD PORFAVOR CAMBIE SU CLAVE</h4>  
                      <form name="f1" method="POST" action="cambiar_clave_proc.php" enctype="multipart/form-data" onsubmit="comprobarClave()">
                            <div class="info-50 margin-25">
                                <label>NUEVA CLAVE</label>
                                <input type="password" minlength="4" name="clavenuevabox1" id='clavenuevabox1' placeholder="ingrese nueva clave" >
                                <label>CONFIRME NUEVA CLAVE</label>
                                 <input type="password" minlength="4" name="clavenuevabox2" id='clavenuevabox2' placeholder="confirme su clave" >
             
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
</body>
</html>