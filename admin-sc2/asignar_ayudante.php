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
$periodo = date('Y');
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
                        <h1>ASIGNAR AYUDANTE</h1>
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-12" style="text-align: center;">
                            <form method="POST" action="asignar_ayudante_proc.php" onsubmit="return validator();">
                                <label>AÑO</label>                                
                                <select id="periodo" name="periodo" required class="minimal">
                                    <option value="">Seleccione Año</option> 
                                    <?php
                                    $sql_asig = "SELECT * FROM periodos WHERE periodo_activo = '1' ORDER BY periodo_periodo DESC";
                                    $rs_asig = mysql_query($sql_asig, $conexion);
                                    while($row_asig = mysql_fetch_array($rs_asig))
                                    {
                                        echo "<option value='".$row_asig['periodo_periodo']."''>".$row_asig['periodo_periodo']."</option>";
                                    }
                                    ?>                                   
                                </select>
                                <label>PROFESOR</label>                                
                                <select name="profesor" required class="minimal">
                                    <option value="">Seleccione Profesor</option> 
                                    <?php
                                    $sql_asig = "SELECT *
                                    FROM profesores";
                                    $rs_asig = mysql_query($sql_asig, $conexion);
                                    while($row_asig = mysql_fetch_array($rs_asig))
                                    {
                                        echo "<option value='".$row_asig['profesor_id']."''>".$row_asig['profesor_nombres']."-".$row_asig['profesor_apellidos']."</option>";
                                    }
                                    ?>                                   
                                </select>
                                <label>ASIGNATURA</label>
                                <select id="asignatura" name="asignatura" required class="minimal">
                                    <option value="">Seleccione Asignatura</option>
                                    <?php
                                    $sql = "SELECT *
                                    FROM asignaturas";
                                    $rs = mysql_query($sql, $conexion);
                                    while($row = mysql_fetch_array($rs))
                                    {
                                        echo "<option value='".$row['asignatura_id']."''>".$row['asignatura_nombre']."</option>";
                                    }
                                    ?> 
                                </select>
                                <h1>Agregar Cursos</h1>
                                <script>
                                    $(document).ready(function(){
                                    $('#checkall').click(function(){
                                        var checked = $(this).prop('checked');
                                    $('#popupM').find('input:checkbox').prop('checked', checked);
                                    });
                                    });
                                </script>
  <div id="popupM">

                        <table class="header2">
                        <input style="width: 12%; float: right;" type="checkbox" id="checkall" />
                        <label style="float: right;" for="checkall"><u>Marcar - Desmarcar todos</u></label>
                        <label>CURSO</label>
                        <table>
                            <div id="response"></div>
                        </table>
                                <script>
                                    //Script encargado de obtener todos los cursos que contienen la asignatura
                                    //Seleccionada anteriormente
                                    $(document).ready(function(){

                                        $("#asignatura").change(function(){
                                            var asig = $(this).val();
                                            var anio = document.getElementById("periodo").value;
                                            $.ajax({
                                                url: 'getAsignatura.php',
                                                type: 'post',
                                                data: {asignatura : asig, periodo: anio},
                                                dataType: 'json',
                                                success:function(response){

                                                    if(response){
                                                        var len = response.length;
                                                        $("#response").empty();
                                                        for( var i = 0; i<len; i++){
                                                            var id = response[i]['asig_id'];
                                                            var curso = response[i]['curso'];
                                                            curso = curso.replace(/\s/g,''); 
                                                            
                                                            var checkbox="<div class='col-xs-4'><label for="+id+">"+curso+"</label><input type='checkbox' id="+id+" value="+id+" name='curso[]'></div>"
                                                            $("#response").append($(checkbox));

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
                                        });

                                    });
                                </script>
                        </table>
  </div>
<br>
<br>
<br>
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
