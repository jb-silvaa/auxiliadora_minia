<?php 

include('../funciones-sc/conexion.php');

?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

  <link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

  <link 

  href="../css-sc/iphone.css"

  media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

  rel="stylesheet">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

  <title>Perfil Docente</title>

  <?php 

    include('../fonts/fonts.php');

    //include('../js-sc/bootstrap.php'); 

  ?>

  <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>



<div class="contenedor-100">

  <?php 

  include('menu-lateral.php');

  ?>

  <div class="perfil-der">

      <h1>NUEVA SOLICITUD</h1>

      <div class="container"> 

                	<form method="POST" action="solicitudes_nuevo_proc.php" enctype="multipart/form-data">

                        <div class="info-50 margin-25">

                            <label>Tipo Solicitud</label>

                            <select name="tipo" class="minimal" required="">

                              <option value="">Seleccione Tipo</option>

                              <?php 

                              $sql_tipo = "SELECT * FROM tipos_solicitudes WHERE tipo_solicitud_estado = '1'";

                              $rs_tipo = mysqli_query($conexion, $sql_tipo);

                              while ($row_tipo = mysqli_fetch_array($rs_tipo)) {

                                echo "<option value='".$row_tipo['tipo_solicitud_id']."'>".$row_tipo['tipo_solicitud_nombre']."</option>";

                              }

                              ?>

                            </select>

                            <label>Descripción</label>

                            <textarea placeholder="INGRESE DESCRIPCIÓN" name="desc" onkeyup="reemplazar(this);"></textarea> 

                            <label>Archivo (Opcional)</label> 

                            <input type="file" name="files">

                            <label>Receptor Solicitud</label>

                            <select name="receptor" class="minimal" required="">

                            <option value="">Seleccione Receptor</option>

                              <?php 

                              $sql_rec = "SELECT * FROM usuarios as u, perfiles as p WHERE usuario_estado = '1' AND p.perfil_id = u.perfil_id";

                              $rs_rec = mysqli_query($conexion, $sql_rec);

                              while ($row_rec = mysqli_fetch_array($rs_rec)) {

                                echo "<option value='".$row_rec['usuario_id']."'>".$row_rec['usuario_nombres']." ".$row_rec['usuario_apellidos']." (".$row_rec['perfil_nombre'].")</option>";

                              }

                              ?>

                            </select>          

                        </div>

                        <div class="info-100">

                            <input type="submit" value="INGRESAR">

                        </div>   

                    </form>

                </div>

            </div>

        </div>

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