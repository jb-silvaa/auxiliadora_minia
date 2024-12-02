<?php   //PENDIENTE PARA MODIFICAR

include('../funciones-sc/conexion.php');

session_start();

if ($_SESSION['perfil'] != 1){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'listado_cursos.php'

    </script>";

    die();

}

$asig = $_POST['asignatura'];



if(isset($_POST["id"])){

	foreach($_POST["id"] as $asig){

		$sql="UPDATE niveles_asignaturas set nivel_asignatura_estado = '-1'

		 where asignatura_id = '".$asig."'";

		mysqli_query($conexion, $connect,$sql);

	}

}





$sql = "UPDATE NIVELES_ASIGNATURAS 

		SET nivel_asignatura_estado ='1'

		WHERE asignatura_id = '$asig'";







$rs = mysqli_query($conexion, $sql);



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Alumno modificado correctamente!')

    window.location.href='niveles_asignaturas.php';

    </SCRIPT>");

	mysqli_close($sql);

?>