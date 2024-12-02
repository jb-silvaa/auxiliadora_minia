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
$id = $_POST['id'];
$periodo = $_POST['periodo'];
$mensual = $_POST['limite_m'];
$anual = $_POST['limite_a'];

 $sql_existe = "SELECT periodo_periodo FROM periodos where periodo_periodo = '$periodo'";
$rs_existe = mysql_query($sql_existe,$conexion);
 $row_existe = mysql_fetch_array($rs_existe);

if($id == '')
{
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('No se puede modificar periodo limite sin información!')
    window.location.href='periodo_limite_lista.php';
    </SCRIPT>");
}else{
$sql_limit = "UPDATE periodos
		SET	periodo_periodo = '$periodo',
			periodo_limite_mensual = '$mensual',
			periodo_limite_anual = '$anual'
		WHERE periodo_id = '$id'";
$rs_limit = mysql_query($sql_limit, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo y limite modificado correctamente!')
    window.location.href='periodo_limite_lista.php';
    </SCRIPT>");
	mysql_close($sql_limit);
}
?>