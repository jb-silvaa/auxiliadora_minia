<?php

include('../funciones-sc/conexion.php');

include('../funciones-sc/notificacion.php');

session_start();

$user_id = $_SESSION['id'];

if (!$_SESSION){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= 'index.php'

    </script>";

    die();

}

$id_mensaje = $_POST["id"];

$profesor_id = $_POST["profesor"];

$descripcion = $_POST["desc"];

$fecha = date('Y-m-d');

$usuario = $_SESSION['id'];



for ($i=0;$i<count($profesor_id);$i++)    

{     

  $profesor = $profesor_id[$i]; 

  $sql = "INSERT INTO mensajes

        ( 

        usuario_id,

          profesor_id,

          mensaje_cuerpo,

          mensaje_fecha,

          mensaje_estado,

          mensaje_emisor      

        )

        VALUES 

        (

        '$usuario',

          '$profesor',

          '$descripcion',

          '$fecha',

          '1',

          '1'          

        )

    ";

$rs  =  mysqli_query($conexion, $sql);



$sql_last = "SELECT *

            FROM mensajes

            WHERE mensaje_id = (SELECT 

                                MAX(mensaje_id)

                                FROM mensajes

                                WHERE usuario_id = '$usuario')";

/* $sql_last = "SELECT MAX(mensaje_id) as mensaje_id 

            FROM mensajes 

            WHERE usuario_id = '$usuario' "; */

$rs_last = mysqli_query($conexion, $sql_last);

$row_last = mysqli_fetch_array($rs_last);

$id = $row_last['mensaje_id'];

$forUser = $row_last['profesor_id'];



notify("mensaje", $forUser, $id, $usuario, '1');



$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);





$hora = date("H_i_s");



if($i == 0){ $nombre_imagen = "archivos/".$id."_".$hora.".".$ext; }



if (isset($_FILES['files'])){ 

  //Comprobamos si el fichero es una imagen

  if ($_FILES['files']['type']=='application/msword' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['files']['type']=='application/pdf' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.presentationml.presentation' || $_FILES['files']['type']=='image/jpeg' || $_FILES['files']['type']=='image/jpg' || $_FILES['files']['type']=='application/vnd.ms-excel' || $_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){

  $destino = '../profesor-sc/archivos/'.$id.'_'.$hora.".".$ext;

  //Subimos el fichero al servidor

  move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

  $_FILES["files"]["tmp_name"];

  $sql_imagen = "UPDATE mensajes

         SET mensaje_archivo = '$nombre_imagen'

         WHERE mensaje_id = $id";

  $rs_imagen = mysqli_query($conexion, $sql_imagen);

  }

}

echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Mensaje enviado!');

    window.location.href='mensaje_lista.php';

    </SCRIPT>");

  mysqli_close($sql);

}

 

?>

