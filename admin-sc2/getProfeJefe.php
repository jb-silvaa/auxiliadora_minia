<?php
include('../funciones-sc/conexion.php');

$periodo = $_POST['periodo'];   // department id

/*$sql = "SELECT id,name FROM users WHERE department=".$departid;

$result = mysqli_query($con,$sql);

$users_arr = array();

while( $row = mysqli_fetch_array($result) ){
    $userid = $row['id'];
    $name = $row['name'];

    $users_arr[] = array("id" => $userid, "name" => $name);
}*/



$sql_asig = "SELECT DISTINCT *
            FROM cursos_asignaturas
            INNER JOIN letras on letras.letra_id = cursos_asignaturas.letra_id 
            INNER JOIN niveles on niveles.nivel_id = cursos_asignaturas.nivel_id
            WHERE curso_asignatura_jefatura = '0'
            AND curso_asignatura_periodo = '$periodo'
            GROUP BY nivel_nombre, letra_nombre";
$rs_asig = mysql_query($sql_asig, $conexion);
while($row_asig = mysql_fetch_array($rs_asig)){
    $ca_id = $row_asig['curso_asignatura_id'];
    $nivel = $row_asig['nivel_nombre'];
    $letra = $row_asig['letra_nombre'];

    $users_arr[] = array("ca_id" => $ca_id, "nivel" => $nivel, "letra" => $letra);
}

// encoding array to json format
echo json_encode($users_arr);

?>