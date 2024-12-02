<?php
session_start();
include('../funciones-sc/conexion.php');
$user_id = $_SESSION['id'];
if($user_id==null || $user_id== ''){
   echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= '../admin-sc/index.php'
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

$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$id = $_POST['id'];
if($id == '')
{
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('No se puede modificar asignatura sin información!')
    window.location.href='asignaturas.php';
    </SCRIPT>");
}
else
{
$sql = "UPDATE asignaturas 
		SET	asignatura_nombre = '$nombre',
			asignatura_codigo = '$codigo'
		WHERE asignatura_id = '$id'";
$rs = mysql_query($sql, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Asignatura modificada correctamente!')
    window.location.href='asignaturas.php';
    </SCRIPT>");
	mysql_close($sql);
}
?>