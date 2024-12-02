<?php 
$perfil_archivo = 1;//adm = 1 , docente = 2;
include('../funciones-sc/conexion.php');
session_start();
if (!$_SESSION['id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
    die();
}
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
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <?php 
        include('../fonts/fonts.php'); 
        include('../js-sc/bootstrap.php'); 
    ?>
    <script src="../js-sc/validar_caracteres.js"></script>
</head>
<body>
    <div id="wrapper">
        <div class="overlay"></div>
        <!-- Sidebar -->
        <?php include('menu-lateral.php'); ?>
        <!-- Page Content -->
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
                        <h1>Jefes de area Profesores</h1>
                        <table class="header2">
                        
                            <tr>  
                                <th>NOMBRE JEFE</th>
                                <th>NOMBRE PROFESORES</th>
                                <th>VER</th>
                              </tr>
                        <?php 
                        /*$sql = "SELECT * FROM niveles_asignaturas N 
                        JOIN niveles NI ON N.nivel_id = NI.nivel_id 
                        JOIN asignaturas A ON N.asignatura_id = A.asignatura_id 
                        WHERE NI.niveles_estado = 1 and A.asignatura_estado= 1 
                        ORDER BY NI.nivel_id ASC ";*/
                        $sql = "SELECT * FROM jefes_area WHERE jefe_estado = '1'";
                        $rs = mysql_query($sql, $conexion);
                        while($row = mysql_fetch_array($rs))
                        {
                            $jefe_id = $row['jefe_id'];
                            $sql_contar = "SELECT count(profesor_id) AS total FROM jefes_area_profe WHERE jefes_area_profe_estado = '1' AND jefe_id = '$jefe_id'";
                            $rs_contar = mysql_query($sql_contar, $conexion);
                            $row_contar = mysql_fetch_array($rs_contar);

                            $rowspan = $row_contar['total'];
                            if($rowspan == '0'){ $rowspan = '1'; }
                            echo "<tr style='border-top:2px solid black'>
                                    <td rowspan=".$rowspan.">".$row['jefe_nombre']." ".$row['jefe_apellido']."</td>";    
                            $sql_profe = "SELECT * FROM profesores as p, jefes_area_profe as jp WHERE p.profesor_id = jp.profesor_id AND jp.jefe_id = '$jefe_id' AND jefes_area_profe_estado = '1'";
                            $rs_profe = mysql_query($sql_profe, $conexion);
                            $contador = '1';
                            while ($row_profe = mysql_fetch_array($rs_profe)) {
                                if($contador == '1')
                                {
                                    echo "<td>".$row_profe['profesor_nombres']." ".$row_profe['profesor_apellidos']."</td>";
                                    echo "<td rowspan=".$rowspan."><a href='jefes_area_profe_modificar.php?id=".$row['jefe_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";
                                }
                                else
                                {
                                    echo "<tr><td>".$row_profe['profesor_nombres']." ".$row_profe['profesor_apellidos']."</td></tr>"; 
                                }
                               $contador++; 
                            }
                            if($contador == '1'){ echo "<td style='border-bottom:2px solid black'>--</td><td style='border-bottom:2px solid black'><a href='jefes_area_profe_modificar.php?id=".$row['jefe_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>"; } 
                        }
                        ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
</body>
</html>
