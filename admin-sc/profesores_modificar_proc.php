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

$profesor = $_POST['id'];

$nombre = $_POST['nombre'];

$apellido = $_POST['apellido'];

$rut = $_POST['rut'];

$fecha = $_POST['fecha'];

$correo = $_POST['correo'];

$correo_p = $_POST['correo_p'];

$fono = $_POST['fono'];


//REVISO SI EL PROFESOR ES COORDINADOR PAR ACTUALIZARLO TAMBIEN
$sql_reviso = "SELECT * FROM profesores WHERE profesor_id = '$profesor'";
$rs_reviso = mysqli_query($conexion, $sql_reviso);
$row_reviso = mysqli_fetch_array($rs_reviso);

$correo_old = $row_reviso['profesor_correo'];

$sql_busco = "SELECT * FROM usuarios WHERE usuario_mail = '$correo_old' AND usuario_estado = '1'";
$rs_busco = mysqli_query($conexion, $sql_busco);
$row_busco = mysqli_fetch_array($rs_busco);

if($row_busco['usuario_id'] != '')
{
	$usuario = $row_busco['usuario_id'];
	$sql = "UPDATE usuarios 

		SET	usuario_rut = '$rut',

			usuario_nombres = '$nombre',

			usuario_apellidos = '$apellido',

			usuario_mail = '$correo',

			usuario_fono = '$fono'

		WHERE usuario_id = '$usuario'";

	$rs = mysqli_query($conexion, $sql);
}

$sql = "UPDATE profesores 

		SET	profesor_rut = '$rut',

			profesor_nombres = '$nombre',

			profesor_apellidos = '$apellido',

			profesor_fecha_nacimiento = '$fecha',

			profesor_correo = '$correo',

			profesor_correo_personal = '$correo_p',

			profesor_fono = '$fono'

		WHERE profesor_id = '$profesor'";

$rs = mysqli_query($conexion, $sql);


$hora = date("H_i_s");

$nombre_imagen = "foto-perfil/".$profesor."_".$hora.".jpg";

if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($_FILES['files']['type']=='image/jpeg'){

	$destino = '../profesor-sc/foto-perfil/'.$profesor.'_'.$hora.'.jpg';

	//Subimos el fichero al servidor

	move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

	$_FILES["files"]["tmp_name"];

	$sql_imagen = "UPDATE profesores

			   SET profesor_imagen = '$nombre_imagen'

			   WHERE profesor_id = $profesor";

	$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}

}



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Profesor modificado correctamente!')

    window.location.href='profesores.php';

    </SCRIPT>");

	mysqli_close($sql);

?>