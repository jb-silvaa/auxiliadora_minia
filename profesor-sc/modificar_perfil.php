<?php 

error_reporting(0);

session_start();

include('../funciones-sc/conexion.php');

$id = $_SESSION['profesor_id'];

$sql_profesor = "SELECT * FROM profesores WHERE profesor_id = '$id'"; //llamado de todos los datos por la ID

$rs_profesor = mysqli_query($conexion, $sql_profesor);                 //asi apareceran los datos a modificar de la session

$row_profesor = mysqli_fetch_array($rs_profesor);

?>



<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Sistema Clases</title>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

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

  <script src="validar_perfil.js"></script>

  <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>





  <div class="contenedor-100">



    <?php include('menu-lateral.php'); ?> 



    <link rel="stylesheet" href="../css-sc/jquery-ui.css">

    <script src="../js-sc/jquery-1.10.2.js"></script>

    <script src="../js-sc/jquery-ui.js"></script>

    <script src="../js-sc/jquery.ui.datepicker-sp.js"></script>



    <script>

      $(function() {

      $( "#datepicker" ).datepicker(

          {

            regional:"sp",

            firstDay:1,

            dateFormat: "yy-mm-dd",

            autoSize: true,

            showOtherMonths: true,

            selectOtherMonths: true,

            changeMonth: true,

            changeYear: true

          });

      });

      $(function() {

        $( "#datepicker2" ).datepicker(

          {

            regional:"sp",

            firstDay:1,

            dateFormat: "yy-mm-dd",

            autoSize: true,

            showOtherMonths: true,

            selectOtherMonths: true,

            changeMonth: true,

            changeYear: true

          });

      });

      </script>

      <div class="perfil-der">

        <h1>MODIFICAR PROFESOR</h1>

        	<form method="POST" action="modificar_perfil_proc.php" enctype="multipart/form-data" onsubmit="return validator();">



               <div class="info-50">

                    <h1>Personal</h1>

                    <label>Fecha Nacimiento</label>

                    <input type="text" id="datepicker" name="fecha" placeholder="INGRESE FECHA NAC." value="<?=$row_profesor['profesor_fecha_nacimiento']?>" readonly="">  

                    <label>Foto Perfil (Solo JPG)</label>

                    <input type="file" name="files" id="files"> 

                    <label>Previsualización</label>
                    <?php if(is_file("../profesor-sc/".$row_profesor['profesor_imagen'])){ ?>
                    <output id="list"><img src="../profesor-sc/<?=$row_profesor['profesor_imagen']?>"></output>  
                    <?php }else{ echo '<output id="list"><label>Sin Imagen</label>></output>'; } ?>                     

             </div>

             <div class="info-50">

                    <h1>Contacto</h1>

                    <label>Correo Personal</label>

                    <input type="text" id="correo" name="correo_p" placeholder="INGRESE CORREO PERSONAL" onkeyup="reemplazar(this);" value="<?=$row_profesor['profesor_correo_personal']?>">

                    <label>Teléfono/Celular</label>

                    <input type="text" id="telefono" name="fono" placeholder="INGRESE TELÉFONO/CELULAR" onkeyup="reemplazar(this);" value="<?=$row_profesor['profesor_fono']?>">

                     </div>

                <div class="info-100">

                    <input type="submit" value="GUARDAR">

                </div>  



            </form>

          </div>  

          </div>

        

        </div>



    <!-- /#wrapper --><!--

    <footer class="footer" style="background: #000;">

        <div class="container">

                <p style='text-align: center'>   ©2018 CDGO · Para soporte : Contáctanos al 032 3174883 ó a soporte@cdgo.cl</p>

            </div>

      </footer>-->

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