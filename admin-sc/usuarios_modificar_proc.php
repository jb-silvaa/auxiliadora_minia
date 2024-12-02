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

$usuario = $_POST['id'];

$nombre = $_POST['nombre'];

$apellido = $_POST['apellido'];

$rut = $_POST['rut'];

$correo = $_POST['correo'];

$perfil = $_POST['perfil'];

$fono = $_POST['fono'];



/* FUNCION PARA CREAR CLAVE ALEATORIA DE 7 CARACTERES */

$clave = RandomString(7,TRUE,TRUE,FALSE);



function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)

{

    $source = 'abcdefghijklmnopqrstuvwxyz';

    if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if($n==1) $source .= '1234567890';

    if($sc==1) $source .= '@$%()=*+[]-_';

    if($length>0){

        $rstr = "";

        $source = str_split($source,1);

        for($i=1; $i<=$length; $i++){

            mt_srand((double)microtime() * 1000000);

            $num = mt_rand(1,count($source));

            $rstr .= $source[$num-1];

        }

 

    }

    return $rstr;

}

/*** FIN FUNCION ***/



$sql = "UPDATE usuarios 

		SET	usuario_rut = '$rut',

			usuario_nombres = '$nombre',

			usuario_apellidos = '$apellido',

			usuario_mail = '$correo',

			usuario_fono = '$fono',

			perfil_id = '$perfil'

		WHERE usuario_id = '$usuario'";

$rs = mysqli_query($conexion, $sql);



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

    window.alert('usuario modificado correctamente!')

    window.location.href='usuarios.php';

    </SCRIPT>");

	mysqli_close($sql);

?>