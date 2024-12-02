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
if ($_SESSION['perfil'] != 1){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'listado_cursos.php'
    </script>";
    die();
}
include('../funciones-sc/conexion.php');
//include('../admin-sc/niveles_asignaturas_nuevo.php'); 

$id_nivel = $_POST["id"];
$asignatura_id = $_POST["asignatura"];

$sql="UPDATE niveles_asignaturas set nivel_asignatura_estado = '-1' WHERE nivel_id = '$id_nivel'";
$rs_sql = mysql_query($sql,$conexion);

for ($i=0;$i<count($asignatura_id);$i++)    
{     
  $asignatura = $asignatura_id[$i]; 
  $sql = "INSERT INTO niveles_asignaturas 
        ( 
          asignatura_id,
          nivel_id,
          nivel_asignatura_estado          
        )
        VALUES 
        (
          '$asignatura',
          '$id_nivel',
          '1'          
        )
    ";
  $rs  =  mysql_query($sql,$conexion);
}

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Nivel Asignatura creado correctamente!')
    window.location.href='niveles_asignaturas.php';
    </SCRIPT>");
  mysql_close($sql);
?>
