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
$periodo = $_POST['periodo'];
$mensual = $_POST['limite_m'];
$anual = $_POST['limite_a'];

 $sql_existe = "SELECT periodo_periodo FROM periodos where periodo_periodo = '$periodo'";
$rs_existe = mysql_query($sql_existe,$conexion);
 $row_existe = mysql_fetch_array($rs_existe);

if($periodo == '')
{
echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('No se puede crear periodo limite sin información!')
    window.location.href='periodo_limite_nuevo.php';
    </SCRIPT>");
	mysql_close($sql);
}
else if ($periodo == $row_existe['periodo_periodo']){
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo ya existe, ingrese otro año')
    window.location.href='periodo_limite_nuevo.php';
    </SCRIPT>");
}else{
$sql_limit = "INSERT INTO periodos 
		(
			periodo_periodo,
			periodo_limite_mensual,
			periodo_limite_anual,
			periodo_estado,
			periodo_activo
		)
		values
		(
			'$periodo',
			'$mensual',
			'$anual',
			'1',
			'0'
		)";
$rs_limit = mysql_query($sql_limit, $conexion);


echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo y limite creado correctamente!')
    window.location.href='periodo_limite_lista.php';
    </SCRIPT>");
	mysql_close($sql_limit);
}
?>