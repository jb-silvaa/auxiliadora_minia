<?php

session_start();

include('../funciones-sc/conexion.php');

session_start();

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

$id = $_GET['id'];

if($id == '')

{

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Valor vacío de periodo y limites, nada se eliminó!')

    window.location.href='periodo_limite_lista.php';

    </SCRIPT>");

}

else

{

$sql_limit= "UPDATE periodos

		SET	periodo_estado = '-1'

		WHERE periodo_id = '$id'";

$rs_limit = mysqli_query($conexion, $sql_limit);





echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Periodo y limite eliminado!')

    window.location.href='periodo_limite_lista.php';

    </SCRIPT>");

	mysqli_close($sql_limit);

}

?>