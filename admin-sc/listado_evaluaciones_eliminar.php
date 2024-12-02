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



$id = $_GET['id'];
$ca_id = $_GET['ca_id'];
$vengo = $_GET['vengo'];


$sql =  "   UPDATE  evaluaciones

            SET     evaluacion_estado = '-1'

            WHERE   evaluacion_id = '$id'

        ";

$rs  =  mysqli_query($conexion, $sql);
if($vengo == 1)
{
echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Evaluación eliminada correctamente!')
window.location.href='evaluacion.php?id=$ca_id';
</SCRIPT>");
}
else
{
  echo ("<SCRIPT LANGUAGE='JavaScript'>
window.alert('Evaluación eliminada correctamente!')
window.location.href='listado_evaluaciones.php?id=$ca_id';
</SCRIPT>");
}


mysqli_close($sql);

?>