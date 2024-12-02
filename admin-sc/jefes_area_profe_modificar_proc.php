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

//include('../admin-sc/niveles_asignaturas_nuevo.php'); 



$id_jefe = $_POST["id"];

$profesor_id = $_POST["profesor"];



$sql="UPDATE jefes_area_profe set jefes_area_profe_estado = '-1' WHERE jefe_id = '$id_jefe'";

$rs_sql = mysqli_query($conexion, $sql);



for ($i=0;$i<count($profesor_id);$i++)    

{     

  $profesor = $profesor_id[$i]; 

  $sql = "INSERT INTO jefes_area_profe 

        ( 

          profesor_id,

          jefe_id,

          jefes_area_profe_estado          

        )

        VALUES 

        (

          '$profesor',

          '$id_jefe',

          '1'          

        )

    ";

  $rs  =  mysqli_query($conexion, $sql);

}



echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Modificado correctamente!')

    window.location.href='jefes_area_profe.php';

    </SCRIPT>");

  mysqli_close($sql);

?>

