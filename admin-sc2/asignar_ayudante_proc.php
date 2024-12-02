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

$periodo = trim($_POST['periodo']);
$profesor_id = trim($_POST['profesor']);

//Verifico si profesor ya habia sido ayudante anteriormente
$sql =	"SELECT *
        FROM ayudantes
        WHERE profesor_ayudante_id = '$profesor_id'";
$rs = mysql_query($sql, $conexion);
$cant = mysql_num_rows($rs);

//Si no lo fue, obtenemos sus datos para luego crearlo dentro de la tabla usuarios
if($cant == 0){
    $sql_b = "SELECT *
            FROM profesores
            WHERE profesor_id = '$profesor_id'";
    $rs_b = mysql_query($sql_b,$conexion);
    $row_b = mysql_fetch_array($rs_b);
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
    $rs_i = mysql_query($sql_i,$conexion);
    $user = mysql_insert_id();
}else{
    $row = mysql_fetch_array($rs);
    $user = $row['usuario_id'];
}



//Inserto dentro de la BD al ayudante junto a sus cursos encargados
if(!empty($_POST['curso'])){
    // Loop to store and display values of individual checked checkbox.
    foreach($_POST['curso'] as $ca_id){
        $sql_curso = "INSERT INTO ayudantes
        (
        profesor_ayudante_id,
        ayudante_periodo,
        usuario_id,
        curso_asignatura_id,
        ayudante_estado
        )
        VALUES
        (
        '$profesor_id',
        '$periodo',
        '$user',
        '$ca_id',
        '1'
        )
        ";	
        $rs_curso = mysql_query($sql_curso, $conexion);
    }
}



echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Ayudante agregado correctamente!')
    window.location.href='asignar_ayudante.php';
    </SCRIPT>");
	mysql_close($sql);
?>
