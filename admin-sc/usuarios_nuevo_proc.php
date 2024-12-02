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

include '../profesor-sc/SED.php';

$nombre = $_POST['nombre'];

$apellido = $_POST['apellido'];

$rut = $_POST['rut'];

$correo = $_POST['correo'];

$perfil = $_POST['perfil'];

$fono = $_POST['fono'];



$sql_correo = "SELECT usuario_id FROM usuarios WHERE usuario_mail = '$correo' AND usuario_estado = '1'";

$rs_correo = mysqli_query($conexion, $sql_correo);

$row_correo = mysqli_fetch_array($rs_correo);



if($row_correo['usuario_id'] != '')

{

	echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('usuario NO creado!, correo ya existe para otro usuario.')

    window.location.href='usuarios.php';

    </SCRIPT>");

	mysqli_close($sql);	

}

else

{

	/* FUNCION PARA CREAR CLAVE ALEATORIA DE 6 CARACTERES */



	$clave = 1234;/*RandomString(6,TRUE,TRUE,TRUE,FALSE);

	function RandomString($longitud = 6, $opcLetra = TRUE, $opcNumero = TRUE, $opcMayus = TRUE, $opcEspecial = FALSE){

	$letras ="abcdefghijklmnopqrstuvwxyz";

	$numeros = "1234567890";

	$letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$especiales ="|@#~$%()=^*+[]{}-_";

	$listado = "";

	$password = "";

	if ($opcLetra == TRUE) $listado .= $letras;

	if ($opcNumero == TRUE) $listado .= $numeros;

	if($opcMayus == TRUE) $listado .= $letrasMayus;

	if($opcEspecial == TRUE) $listado .= $especiales;



	for( $i=1; $i<=$longitud; $i++) {

	$caracter = $listado[rand(0,strlen($listado)-1)];

	$password.=$caracter;

	$listado = str_shuffle($listado);

	}

	return $password;

	}
*/


	$pass_cript=SED::encryption($clave);

	/*** FIN FUNCION ***/



	$sql = "INSERT INTO usuarios 

			(

				usuario_rut,

				perfil_id,

				usuario_clave,

				usuario_nombres,

				usuario_apellidos,

				usuario_mail,

				usuario_fono,

				usuario_estado,

				usuario_activo

			)

			values

			(

				'$rut',

				'$perfil',

				'$pass_cript',

				'$nombre',

				'$apellido',

				'$correo',

				'$fono',

				'1',

				'1'

			)";

	$rs = mysqli_query($conexion, $sql);



	$sql_last = "SELECT MAX(usuario_id) as usuario_id FROM usuarios WHERE usuario_mail = '$correo' ";

	$rs_last = mysqli_query($conexion, $sql_last);

	$row_last = mysqli_fetch_array($rs_last);

	$usuario = $row_last['usuario_id'];

	$hora = date("H_i_s");

	$nombre_imagen = "foto-perfil/".$usuario."_".$hora.".jpg";

	if (isset($_FILES['files'])){	

		//Comprobamos si el fichero es una imagen

		if ($_FILES['files']['type']=='image/jpeg'){

		$destino = 'foto-perfil/'.$usuario.'_'.$hora.'.jpg';

		//Subimos el fichero al servidor

		move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

		$_FILES["files"]["tmp_name"];

		$sql_imagen = "UPDATE usuarios

				   SET usuario_imagen = '$nombre_imagen'

				   WHERE usuario_id = $usuario";

		$rs_imagen = mysqli_query($conexion, $sql_imagen);

		}

	}



	echo ("<SCRIPT LANGUAGE='JavaScript'>

	    window.alert('usuario creado correctamente!, Anote su contraseña= $clave')

	    window.location.href='usuarios.php';

	    </SCRIPT>");

		mysqli_close($sql);

}

?>