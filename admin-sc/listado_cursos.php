<?php 
session_start();
//print $_SESSION['id'];
$user_id = $_SESSION['id'];
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
$periodo = $_GET['periodo'];
if($periodo == ''){ 
    if($_SESSION['filtro_periodo'] != ''){
        $periodo = $_SESSION['filtro_periodo'];
    }else{
        $periodo = /*date('Y')*/'2024'; 
        $_SESSION['filtro_periodo'] = $periodo;
    }
}else{
   $_SESSION['filtro_periodo'] = $periodo;
}

$nivel = $_GET['nivel'];
if($nivel == '0' || $nivel == '')
{ 
    $nivel_filtro = "";
    $nivel = '0';
}
else
{
    $nivel_filtro = " AND n.nivel_id = ".$nivel." ";
}

$letra = $_GET['letra'];
if($letra == '0' || $letra == '')
{ 
    $letra_filtro = "";
    $letra = '0';
}
else
{
    $letra_filtro = " AND l.letra_id = ".$letra." ";
}

$asignatura = $_GET['asignatura'];
if($asignatura == '0' || $asignatura == '')
{ 
    $asignatura_filtro = "";
    $asignatura = '0';
}
else
{
    $asignatura_filtro = " AND ca.asignatura_id = ".$asignatura." ";
}

$docente = $_GET['docente'];
if($docente == '0' || $docente == '')
{ 
    $docente_filtro = "";
    $docente = '0';
}
else
{
    $docente_filtro = " AND ca.profesor_id = ".$docente." ";
}

//FILTRO PARA LIMITAR VISTA DE CURSOS A AYUDANTES
$sql_ay = "SELECT *
        FROM ayudantes
        WHERE usuario_id = '$user_id'";
$rs_ay = mysqli_query($conexion, $sql_ay);
$cant = mysqli_num_rows($rs_ay);
$ay_filtro = "";
$ayudante_filtro = "";
//if($cant>0){
if($_SESSION['perfil'] == 3){
    $ay_filtro = ", ayudantes as ay";
    $ayudante_filtro = "AND ca.curso_asignatura_id = ay.curso_asignatura_id
                        AND ay.usuario_id = '$user_id'
                        AND ay.ayudante_estado = '1'";
}else{
    $ay_filtro = "";
    $ayudante_filtro = "";
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
                        <h1>listado de cursos</h1>
                        <div class="filtros4">
                            <form method="GET">
                            <label>Año</label>
                            <select name="periodo" class="minimal">
                                <?php 
                                $sql_periodo = "SELECT * FROM periodos ORDER BY periodo_periodo = '$periodo' DESC, periodo_periodo ASC";
                                $rs_periodo = mysqli_query($conexion, $sql_periodo);
                                while ($row_periodo = mysqli_fetch_array($rs_periodo)) {
                                    echo "<option value=".$row_periodo['periodo_periodo'].">".$row_periodo['periodo_periodo']."</option>";
                                }
                                ?>
                            </select>
                            <label>Cursos</label>
                            <select name="nivel" class="minimal">                                
                            <?php 
                            if($nivel == '0'){ echo "<option value='0'>TODOS</option>"; }
                            $sql_nivel = "SELECT * FROM niveles WHERE niveles_estado = '1' ORDER BY nivel_id = $nivel DESC, nivel_id ASC";
                            $rs_nivel = mysqli_query($conexion, $sql_nivel);
                            while ($row_nivel = mysqli_fetch_array($rs_nivel)) {
                                echo "<option value=".$row_nivel['nivel_id'].">".$row_nivel['nivel_nombre']."</option>";
                            }
                            if($nivel != '0'){ echo "<option value='0'>TODOS</option>"; }
                            ?>
                            </select>
                            <label>Letra</label>
                            <select name="letra" class="minimal">                                
                            <?php 
                            if($letra == '0'){ echo "<option value='0'>TODAS</option>"; }
                            $sql_letra = "SELECT * FROM letras WHERE letra_estado = '1' ORDER BY letra_id = $letra DESC, letra_id ASC";
                            $rs_letra = mysqli_query($conexion, $sql_letra);
                            while ($row_letra = mysqli_fetch_array($rs_letra)) {
                                echo "<option value=".$row_letra['letra_id'].">".$row_letra['letra_nombre']."</option>";
                            }
                            if($letra != '0'){ echo "<option value='0'>TODAS</option>"; }
                            ?>
                            </select>
                            <label>Asignatura</label>
                            <select name="asignatura" class="minimal">
                                <?php 
                                if($asignatura == '0'){ echo "<option value='0'>TODAS</option>"; }
                                $sql_asig = "SELECT * FROM asignaturas WHERE asignatura_estado = '1' ORDER BY asignatura_id = '$asignatura' DESC, asignatura_nombre ASC";
                                $rs_asig = mysqli_query($conexion, $sql_asig);
                                while ($row_asig = mysqli_fetch_array($rs_asig)) {
                                    echo "<option value=".$row_asig['asignatura_id'].">".$row_asig['asignatura_nombre']."</option>";
                                }
                                if($asignatura != '0'){ echo "<option value='0'>TODAS</option>"; }
                                ?>
                            </select>
                            <label>Profesor</label>
                            <select name="docente" class="minimal">
                                <?php 
                                if($docente == '0'){ echo "<option value='0'>TODOS</option>"; }
                                $sql_profe = "SELECT * FROM profesores WHERE profesor_estado = '1' AND profesor_id <> '0' ORDER BY profesor_id = '$docente' DESC, profesor_apellidos, profesor_nombres ASC";
                                $rs_profe = mysqli_query($conexion, $sql_profe);
                                while ($row_profe = mysqli_fetch_array($rs_profe)) {
                                    echo "<option value=".$row_profe['profesor_id'].">".$row_profe['profesor_apellidos']." ".$row_profe['profesor_nombres']."</option>";
                                }
                                if($docente != '0'){ echo "<option value='0'>TODOS</option>"; }
                                ?>
                            </select>
                            <input type="submit" value="Filtrar">
                            </form>
                        </div>
                    	<table class="header2">
                    		<tr>
                                <th>Curso Nombre</th>
                    			<th>Asignatura Nombre</th>                    			
                                <th>Profesor</th>
                                <th>Profesor Jefe</th>
                                <th>Nivel</th>
                                <th>Notas</th>
                                <th>P. Mensual</th>
                                <?php
                                if($user_id == '1' || $perfil == '3'){
                                ?>
                                <th>Agregar Evaluacion</th>
                                <?php 
                                }
                                if($_SESSION['perfil'] == '1'){
                                ?>
                                <th>Modificar</th>
                                <th>Eliminar</th>
                                <?php
                                    }
                                    ?>
                    		  </tr>
                        <?php 
                        $sql = "SELECT * 
                                FROM asignaturas as a,
                                     cursos_asignaturas as ca,
                                     letras as l,
                                     niveles as n,
                                     profesores as p,
                                     dificultades as d
                                     $ay_filtro
                                WHERE a.asignatura_estado = '1'
                                AND ca.asignatura_id = a.asignatura_id
                                AND n.nivel_id = ca.nivel_id
                                AND l.letra_id = ca.letra_id
                                AND ca.dificultad_id = d.dificultad_id
                                AND ca.profesor_id = p.profesor_id
                                AND ca.curso_asignatura_periodo = '$periodo'
                                AND ca.curso_asignatura_estado = '1'
                                $nivel_filtro
                                $asignatura_filtro
                                $docente_filtro
                                $ayudante_filtro
                                $letra_filtro
                                ORDER BY ca.nivel_id, ca.letra_id, a.asignatura_nombre, ca.dificultad_id ASC";
                        $rs = mysqli_query($conexion, $sql);
                        $contador = '0';
                        while($row = mysqli_fetch_array($rs)){
                            $nivel_id = $row['nivel_id'];
                            $letra_id = $row['letra_id'];
                            $sql1 = "SELECT * 
                            FROM profesores_jefes as pj,
                                profesores as p
                            WHERE pj.nivel_id = '$nivel_id'
                            AND pj.letra_id = '$letra_id'
                            AND pj.profesor_id = p.profesor_id
                            AND pj.profesor_jefe_periodo = '$periodo'";
                            $rs1 = mysqli_query($conexion, $sql1);
                            $row1 = mysqli_fetch_array($rs1);   
                            $js_ajax_modi = "ajax_modal_modifi(".$row['curso_asignatura_id'].")";
                        	echo "<tr>
                                    <td>".$row['nivel_nombre']."-".$row['letra_nombre']."</td>
                        			<td>".$row['asignatura_nombre']."</td>                    			
                                    <td>".$row['profesor_nombres']." ".$row['profesor_apellidos']."</td>
                                    <td>".$row1['profesor_nombres']." ".$row1['profesor_apellidos']."</td>
                                    <td>".$row['dificultad_nombre']."</td>
                                    <td>".$row['curso_asignatura_notas_total']."</td>
                                    <td>".$row['curso_asignatura_unidades']."</td>";


                                    if($user_id == '1' || $perfil == '3'){
                                    echo "<td>
                                    <a title= 'Agregar' aria-hidden='true' data-toggle='modal' data-target='#exampleModalCenter' onclick='$js_ajax_modi'>
                                    <i class='fas fa-plus fa-lg $icon' style='margin-left:10px;'></i>
                                    </a>
                                    
                                    ";
                                    
                                    //REVISO SI EXISTEN EVALUACIONES
                                    $ca_id = $row['curso_asignatura_id'];
                                    $sql_existe = "SELECT COUNT(evaluacion_id) as total FROM evaluaciones WHERE curso_asignatura_id = '$ca_id' AND evaluacion_estado = '1'";
                                    $rs_existe = mysqli_query($conexion, $sql_existe);
                                    $row_existe = mysqli_fetch_array($rs_existe);
                                    if($row_existe['total'] > 0)
                                    {
                                        echo "
                                        <a title= 'Agregar' href='listado_evaluaciones.php?id=$ca_id'>
                                        <i class='fas fa-search fa-lg $icon' style='margin-left:10px;'></i>
                                        </a>
                                        </td>";
                                    }
                                    else{ echo "</td>"; }
                                    }
                                    //<a href='listado_cursos_agregar_evaluacion.php?id=".$row['curso_asignatura_id']."'><i class='fas fa-file-upload fa-lg'></i></a></td>
                                    if($_SESSION['perfil'] == '1'){
                                    echo "<td><a href='listado_cursos_modificar.php?id=".$row['curso_asignatura_id']."&nivel=".$nivel."&asignatura=".$asignatura."&docente=".$docente."'><i class='fas fa-pencil-alt fa-lg'></i></a></td>";
                                    ?>
                                    <td><a href='listado_cursos_eliminar.php?id=<?=$row['curso_asignatura_id']?>&nivel=<?=$nivel?>&asignatura=<?=$asignatura?>&docente=<?=$docente?>' onclick = "javascript: return confirm('Desea Eliminar Este asignatura?');"><i class='far fa-times-circle fa-lg'></i></a></td>
                                    <?php
                                    }
                                    ?>
                                  </tr>
                        <?php
                            $dificultad = '';
                            $contador++;
                        }
                        if($contador == '0'){ echo "<td colspan='8'>No hay resultados para los filtros escogidos</td>"; }
                        ?>
                        </table>                      
                    </div>
                       <!-- cod del modal modifi -->
            <div class="modal fade " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
               <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
               <div id="modalx" class="modal-dialog modal-dialog-centered modal-lg" role="document">
                  
               </div>
            </div>   
         <!-- FIN cod del modal modifi -->

                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
</body>
</html>

<script>
 function ajax_modal_modifi(id_curso_asignatura){//FUNCION PARA GUARDAR EL ARCHIVO
         
         document.getElementById("modalx").innerHTML = "";
         console.log("hola id_tipo_arreglo: "+id_curso_asignatura);
         
         $.ajax({
         type: "POST",
         url: "modal_cantidad_evaluaciones.php",
         data:{ 
            id_curso_asignatura : id_curso_asignatura
            
         },
            
         success: function(results) {   
            console.log("gane: "+ results);    
            var  formolo = document.getElementById("modalx");
            //console.log("holax: formo: "+ formolo);
            formolo.innerHTML = results;
            $(document).ready(function () {
         $('#tabla_modal').dataTable();
         $('.dataTables_length').addClass('bs-select');
      });
         
         },
         });
      
      }
 </script>