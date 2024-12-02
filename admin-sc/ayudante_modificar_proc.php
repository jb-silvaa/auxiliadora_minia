<?php

session_start();

$user_id = $_SESSION['id'];

if (!$_SESSION){

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



$profesor_id = $_POST['profesor'];

$id = $_POST['id'];

$ayudante_id = $_POST['ayudante'];



//Verifico si profesor ya habia sido ayudante anteriormente

$sql =	"SELECT *

        FROM ayudantes

        WHERE profesor_ayudante_id = '$profesor_id'";

$rs = mysqli_query($conexion, $sql);

$cant = mysqli_num_rows($rs);



//Si no lo fue, obtenemos sus datos para luego crearlo dentro de la tabla usuarios

if($cant == 0){

    $sql_b = "SELECT *

            FROM profesores

            WHERE profesor_id = '$profesor_id'";

    $rs_b = mysqli_query($conexion, $sql_b);

    $row_b = mysqli_fetch_array($rs_b);

    $pass = $row_b['profesor_clave'];

    $nombre = $row_b['profesor_nombres'];

    $apellido = $row_b['profesor_apellidos'];

    $rut = $row_b['profesor_rut'];

    $correo = $row_b['profesor_correo_personal'];

    $fono = $row_b['profesor_fono'];



    $sql_i = "INSERT INTO usuarios

            (

                usuario_clave,

                usuario_nombres,

                usuario_apellidos,

                usuario_rut,

                usuario_mail,

                usuario_fono,

                perfil_id

            )

            VALUES

            (

                '$pass',

                '$nombre',

                '$apellido',

                '$rut',

                '$correo',

                '$fono',

                '3'

            )

            ";

    $rs_i = mysqli_query($conexion, $sql_i);

    $user = mysqli_insert_id();

}else{

    $row = mysqli_fetch_array($rs);

    $user = $row['usuario_id'];

}



$sql = "UPDATE ayudantes

		SET	profesor_ayudante_id = '$profesor_id',

			usuario_id = '$user'

        WHERE curso_asignatura_id = '$id'

        AND ayudante_id = '$ayudante_id'";

$rs = mysqli_query($conexion, $sql);





echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Curso modificado correctamente!')

    window.location.href='listado_ayudantes.php';

    </SCRIPT>");

	mysqli_close($sql);

?>