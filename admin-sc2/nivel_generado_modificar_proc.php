<?php
include('../funciones-sc/conexion.php');

$asignaturas = $_POST['asignatura'];
$periodo = $_POST['periodo'];
$nivel_id = $_POST['nivel_id'];
$nivel_creado_id = $_POST['nivel_creado_id'];
if($nivel_id>13){
    $unidades = 42;
}else{
    $unidades = 10;
}

$sql_delete = "DELETE FROM cursos_asignaturas 
                WHERE nivel_id = '$nivel_id' 
                AND curso_asignatura_periodo = '$periodo'";
$rs_delete = mysql_query($sql_delete,$conexion);

/* echo $asignaturas[0];
$curso1 = $_GET['curso'];
if($curso1 != ''){
    $result = explode("-",$curso1);
    $nivel = $result[0];
    $letra = $result[1];
    //$orden = "ORDER BY letras.letra_id = '$letra', niveles.nivel_id = '$nivel' DESC, niveles.nivel_nombre ASC";
}else{
    $orden = "";
} */
//recorro todas las asignaturas seleccionadas para ir creando los cursos_asignaturas
for ($i=0;$i<count($asignaturas);$i++){
    $asignatura = $asignaturas[$i]; 
    $result = explode("-",$asignatura);
    $asignatura_id = $result[0];
    $dificultad = $result[1];
    $sql_cant_cursos = "SELECT COUNT(*) as cant_cursos FROM letras WHERE letra_estado = '1'";
    $rs_cant_cursos = mysql_query($sql_cant_cursos,$conexion);
    $row_cant_cursos = mysql_fetch_array($rs_cant_cursos);
    $cant_cursos = $row_cant_cursos['cant_cursos'];
    $letra = 1;
    //recorro la cantidad de letras con estado 1 para saber cuantos cursos hay por nivel (ej. 1° basico A-B-C)
    if($dificultad != '0'){
        for($j=0;$j<(int)$cant_cursos;$j++){
            if($dificultad == 1){
                //si la asignatura tiene dificultades, se agrega 3 veces el ramo para el curso
                for($k=1;$k<=3;$k++){
                    $sql = "INSERT INTO cursos_asignaturas
                    ( 
                        nivel_id,
                        letra_id,
                        asignatura_id,
                        profesor_id,
                        curso_asignatura_periodo,
                        dificultad_id,
                        curso_asignatura_notas_total,
                        curso_asignatura_notas_actual,
                        curso_asignatura_unidades,
                        curso_asignatura_estado
                    )
                    VALUES 
                    (
                        '$nivel_id',
                        '$letra',
                        '$asignatura_id',
                        '0',
                        '$periodo',
                        '$k',
                        '0',
                        '0',
                        '$unidades',
                        '1'
                    )
                    ";
                    //print $sql;
                    $rs  =  mysql_query($sql,$conexion);
                }
            }else{
                $sql = "INSERT INTO cursos_asignaturas
                ( 
                    nivel_id,
                    letra_id,
                    asignatura_id,
                    profesor_id,
                    curso_asignatura_periodo,
                    dificultad_id,
                    curso_asignatura_notas_total,
                    curso_asignatura_notas_actual,
                    curso_asignatura_unidades,
                    curso_asignatura_estado
                )
                VALUES 
                (
                    '$nivel_id',
                    '$letra',
                    '$asignatura_id',
                    '0',
                    '$periodo',
                    '0',
                    '0',
                    '0',
                    '$unidades',
                    '1'
                )
                ";
                //print $sql;
                $rs  =  mysql_query($sql,$conexion);
            }
            $letra++;
        }
    }
    
}

echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Periodo generado correctamente!')
    window.location.href='niveles_generados.php';
    </SCRIPT>");
	mysql_close($sql);
?>