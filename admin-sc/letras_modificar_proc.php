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



$estado = $_POST['estado'];

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

$sql = "UPDATE letras 

		SET	letra_estado = '$estado'

        WHERE letra_id = '$id'";

$rs = mysqli_query($conexion, $sql);





echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Asignatura modificada correctamente!')

    window.location.href='letras.php';

    </SCRIPT>");

	mysqli_close($sql);

}

?>