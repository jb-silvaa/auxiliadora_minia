<?php 

error_reporting(0);

session_start();

include('../funciones-sc/conexion.php');

function debug_to_console($data) {

  $output = $data;

  if (is_array($output))

      $output = implode(',', $output);



  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}
?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Bajar evaluación</title>

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

	<link 

	href="../css-sc/iphone.css"

	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

	rel="stylesheet">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<?php 

		include('../fonts/fonts.php');

		include('../js-sc/bootstrap.php'); 

	?>

    <script src="validar_evaluacion.js"></script>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>


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

  <div id="wrapper">

    <div class="overlay"></div>



    <!-- Sidebar -->

    <?php 
    include('menu-lateral.php'); 
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
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
              <h1>Bajar Excel Evaluaciones</h1>
            	<form method="POST" action="bajar_excel_proc.php" enctype="multipart/form-data" onsubmit="return validator();">            

                <label>Año Excel Evaluaciones</label>

                <input type="number" id="periodo" name="periodo" onkeyup="reemplazar(this);" placeholder="Ejemplo: <?=date('Y')?>" min='0' required>

                <label>Cantidad Evaluaciones por Curso</label>

                <input type="number" id="cantidad" min="0" max="10" name="cantidad" onkeyup="reemplazar(this);" placeholder="Máximo 10" required>
                <div class="info-100">
                  <label>Tipo del nivel</label>                  
                  <select required="" class="minimal" style="float: left;" name='nivel_tipo' id="nivel_tipo" onchange="BuscarCursos()">
                    <option value="" disabled selected>Seleccione Nivel</option>
                    <?php 
                    if($acceso_ev == 0 || $acceso_ev == 3){
                    ?>
                    <option value="0">Pre-Escolar</option>
                    <?php 
                    }if($acceso_ev == 1 || $acceso_ev == 3){
                    ?>
                    <option value="1">Básica</option>
                    <?php 
                    }if($acceso_ev == 2 || $acceso_ev == 3){
                    ?>
                    <option value="2">Media</option>
                    <?php 
                    }
                    ?>
                  </select>
                </div>
                  <div id="popupM">



                        <table class="header2">

                        <input style="width: 12%; float: right;" type="checkbox" id="checkall" />

                        <label style="float: right;" for="checkall"><u>Marcar - Desmarcar todos</u></label>

                        <label>CURSO</label>

                        <table>

                            <div id="response"></div>

                        </table>

  </div>
		            <div class="info-100">

                    <input type="submit" value="DESCARGAR">

                </div>   

            </form>

        </div>
        <div class="col-lg-2"></div>
	</div>

</div>
</div>
</div>

</body>

</html>
<script>

//Script encargado de obtener todos los cursos que contienen la asignatura

//Seleccionada anteriormente

    function BuscarCursos(){

        var periodo = document.getElementById("periodo").value;
        var nivel_tipo = document.getElementById("nivel_tipo").value;

        $.ajax({

            url: 'getCursos.php',

            type: 'post',

            data: {periodo : periodo, nivel_tipo: nivel_tipo},

            dataType: 'json',

            success:function(response){



                if(response){

                    var len = response.length;

                    $("#response").empty();

                    for( var i = 0; i<len; i++){

                        var id = response[i]['asig_id'];

                        var nombre_asig = response[i]['nombre_asig'];

                        //nombre_asig = nombre_asig.replace(/\s/g,''); 

                        
                        if(i%3==0){ var fila = "<div class='row'>"; $("#response").append($(fila));}
                        var checkbox="<div class='col-xs-4'><label for="+id+">"+nombre_asig+"</label><input type='checkbox' id="+id+" value="+id+" name='nombre_asig[]'></div>"

                        $("#response").append($(checkbox));
                        
                        if(i%3==2){ var fila = "</div>"; $("#response").append($(fila));}


                    }

                }else{

                    document.getElementById("response").innerHTML = "";

                }

                

                /* var a = document.getElementById("blah");

                var arr = str;

                var returnStr = "";

                for (i = 0; i < arr.length; i++) {

                    returnStr += '<input type="checkbox" name="theCheckbox" value="' + arr[i] + '" />' + arr[i];

                }

                a.innerHTML = returnStr; */

            }

        });

    }



  $(document).ready(function(){

  $('#checkall').click(function(){

      var checked = $(this).prop('checked');

  $('#popupM').find('input:checkbox').prop('checked', checked);

  });

  });
      
</script>
