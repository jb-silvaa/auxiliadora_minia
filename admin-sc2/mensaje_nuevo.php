<?php 
$id = $_GET['id'];
$perfil_archivo = 1;
include('../funciones-sc/conexion.php');
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
	<title>Sistema Clases</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
	<link 
	href="../css-sc/iphone.css"
	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
	rel="stylesheet">
	<?php 
		include('../fonts/fonts.php'); 
		include('../js-sc/bootstrap.php'); 
	?>
  <script src="validar_asignatura.js"></script>
  <script src="../js-sc/validar_caracteres.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

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
                      <h1>Agregar Destinatario</h1>
                      <form method="POST" action="mensaje_nuevo_proc.php" enctype="multipart/form-data" >

<script>
$(document).ready(function(){
    $('#abrirM').on('click', function(){
        $('#popupM').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        $('#abrirM').fadeOut('fast');
        return false;
    });
 
    $('#cerrarM').on('click', function(){
        $('#popupM').fadeOut('fast');
        $('.popup-overlay').fadeOut('slow');
        $('#abrirM').fadeIn('fast');
        return false;
    });
});

$(document).ready(function(){
  $('#checkall').click(function(){
    var checked = $(this).prop('checked');
$('#popupM').find('input:checkbox').prop('checked', checked);
});
});



</script>

<a id="abrirM" style="cursor: pointer;" ><i style="font-size: 180% " class='fas fa-plus fa-lg'></i> PRESIONE AQUÍ PARA AGREGAR </a>

 <div  style="display: none;" id="popupM">
  <div >
    <a style="font-size:150%; cursor: pointer;" id="cerrarM"><i class="far fa-times-circle"></i>OCULTAR</a>

                        <h1>Destinatarios</h1>
                        <table class="header2">
                        <input style="width: 12%; float: right;" type="checkbox" id="checkall" />
                        <label style="float: right;" for="checkall"><u>Marcar - Desmarcar todos</u></label>                        
                          <tr>
                            <?php
                            $contador = '1';
                           $sql_profesores = "SELECT * FROM profesores where profesor_estado = '1' and profesor_id>= '1' order by profesor_nombres ASC";
                            $rs_profesores = mysql_query($sql_profesores, $conexion);
                            while($row_profesores = mysql_fetch_array($rs_profesores))
                            {
                            ?>
                             <td><?=$row_profesores['profesor_nombres']." ".$row_profesores['profesor_apellidos']?>
                                <?php 
                            $profe_id = $row_profesores['profesor_id'];
                            $sql_check = "SELECT profesor_id FROM mensajes_profesores WHERE profesor_id = '$profe_id' AND mensaje_id = '$id' AND mensaje_profesor_estado = '1'";
                            $rs_check = mysql_query($sql_check, $conexion);
                            $row_check = mysql_fetch_array($rs_check);
                            ?>                         
                              <input type="checkbox" style="float: right !important; width: 10px;" <?php if($row_check['profesor_id'] != ''){ echo " checked "; } ?> name="profesor[]" value="<?=$row_profesores['profesor_id']?>"> 
                                 <input type="hidden" name="id" value="<?=$row_profesores['profesor_id']?>"></td>
                            <?php
                            if($contador%3==0){
                              echo "</tr> <tr>";
                            }       
                            ?>
                            <?php
                            $contador++;
                            }
                            ?>
                        </table>
  </div>
</div>
                         <h1>NUEVO MENSAJE</h1>
                            <div class="info-50 margin-25">
                              <input type="hidden" name="mensaje_id<?=$row_mesj['mensaje_id']?>" id="mensaje_id<?=$row_mesj['mensaje_id']?>" value="<?=$id?>">
                                <label>Texto</label>
                                <textarea placeholder="INGRESE SU MENSAJE" name="desc" onkeyup="reemplazar(this);"></textarea> 
                                <label>Archivo (Opcional)</label> 
                                <input type="file" name="files">          
                            </div>
                            <div class="info-100">
                                <input type="submit" value="ENVIAR">
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