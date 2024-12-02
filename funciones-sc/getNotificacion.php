<?php
include('conexion.php');
include('meses.php');
//$reset = (int) $_GET[ "reset" ]; // either 1 or 0 ( true and false )
$reset = 1;   
//$user_id = $_GET[ "id" ]; // the user who's notifications we will be loading    
$user_id = $_GET['id'];
$perfil = $_GET['perfil'];
if( $reset === 1 ){
    $sql = "SELECT *
    FROM notificaciones
    WHERE notificacion_destinatario_id = '$user_id'
    AND notificacion_autor_tipo != '$perfil'
    ORDER BY notificacion_fecha DESC";
    setcookie( "loadedNotifications", "20", time() + 86400, "/" ); // store the cookie holding the amount of loaded notifications  
}else{
    $loadedNots=(int) $_COOKIE[ "loadedNotifications" ]; // get the amount of previously loaded notifications
    $sql = "SELECT *
    FROM notificaciones
    WHERE notificacion_destinatario_id = '$user_id'
    ORDER BY notificacion_fecha DESC LIMIT " . $loadedNots . " 20;";
    $loadedNots = (string)( $loadedNots + 20 ); // calculate how many notifications have been loaded after query
    setcookie( "loadedNotifications", $loadedNots, time() + 86400, "/" ); // update cookie with new value
}
$result = mysqli_query($conexion, sql);
$notifications = array(); // declare an array to store the fetched notifications
if(mysql_num_rows($result)>0){
    while( $row_result = mysql_fetch_array($result) ){
        $notifications[] = array( 
        "id" => $row_result['notificacion_id'],
        "type" => $row_result['notificacion_tipo'],
        "entityID" => $row_result['notificacion_tipo_id'],
        "fecha" => $row_result['notificacion_fecha'],
        "read" => $row_result['notificacion_leido'],
        //mensajes
        "autor" => '',
        "archivo" => '--',
        "text" => '',
        //planificaciones
        "carga_aprobacion" => '',
        "carga_curso" => '',
        "carga_unidad" => '',
        "carga_asignatura" => '',
        "carga_curso_asignatura_id" => '',
        //evaluaciones
        "evaluacion_aprobacion" => '',
        "evaluacion_curso" => '',
        "evaluacion_asignatura" => '',
        "evaluacion_nombre" => '',
        "evaluacion_fecha" => '',
        "evaluacion_curso_asignatura_id" => ''
        );
    }
}else{
    // no more notifications
}   
    /* 
    * now we need to find the activity that relates to the notification  
    * and create a text message that will be displayed to the user  
    * containing the users who are responsible for that particular activity  
    */    
    for( $i = 0; $i < count( $notifications ); $i++ ){
        $sql = ""; // reset query string each time loop runs
        // use different code for each type of notification ( ie. comments or ratings )
        switch( $notifications[$i]["type"] ){
            case "mensaje":
                $sql_texto = "SELECT * 
                        FROM mensajes 
                        WHERE mensaje_id=" . $notifications[ $i ][ "entityID" ] . ";";
                $result = mysqli_query($conexion, sql_texto);
                $row = mysql_fetch_array($result);
                if($perfil==1){
                    $texto = "WHERE usuario_id = '0'";
                }else{
                    $texto = "WHERE profesor_id = '0'";
                }
                $sql_texto1 = "SELECT * FROM mensajes_historial
                        WHERE mensaje_historial_id = (SELECT
                                                        MAX(mensaje_historial_id)
                                                        FROM mensajes_historial
                                                        $texto 
                                                        AND mensaje_id=" . $notifications[ $i ][ "entityID" ] . " ) ";
                $result1 = mysqli_query($conexion, sql_texto1);
                $row1 = mysql_fetch_array($result1);

                if(mysql_num_rows($result1)>0){
                    $notifications[$i]["text"] = $row1['mensaje_historial_comentario'];
                }else{
                    $notifications[$i]["text"] = $row['mensaje_cuerpo'];     
                }
                if($row['mensaje_archivo'] != ''){
                    $notifications[$i]["archivo"] = $row['mensaje_archivo'];
                }
                if($perfil == 1){
                    $id_autor = $row['profesor_id'];
                    $sql_autor = "SELECT profesor_nombres, profesor_apellidos 
                        FROM profesores
                        WHERE profesor_id = '$id_autor' ";
                    $result_autor = mysqli_query($conexion, sql_autor);
                    $row_autor = mysql_fetch_array($result_autor);
                    $autor = $row_autor['profesor_nombres']." ".$row_autor['profesor_apellidos'];
                }else{
                    $id_autor = $row['usuario_id'];
                    $sql_autor = "SELECT usuario_nombres, usuario_apellidos 
                        FROM usuarios
                        WHERE usuario_id = '$id_autor' ";
                    $result_autor = mysqli_query($conexion, sql_autor);
                    $row_autor = mysql_fetch_array($result_autor);
                    $autor = $row_autor['usuario_nombres']." ".$row_autor['usuario_apellidos'];
                }
                $notifications[$i]["autor"] = $autor;
                break;// other cases to suit your needs

            case "planificacion":
                $sql_carga = "SELECT * 
                        FROM cargas as c
                        INNER JOIN cursos_asignaturas as ca on c.curso_asignatura_id = ca.curso_asignatura_id
                        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
                        INNER JOIN letras as l on l.letra_id = ca.letra_id
                        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
                        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
                        WHERE carga_id=" . $notifications[ $i ][ "entityID" ] . ";";
                $result = mysqli_query($conexion, sql_carga);
                $row = mysql_fetch_array($result);
                $mes = $row['tipo_carga_unidad'];
                $notifications[$i]["carga_aprobacion"] = $row['carga_aprobacion'];
                $notifications[$i]["carga_unidad"] = $array[$mes+1];
                $notifications[$i]["carga_curso"] = $row['nivel_nombre']."-".$row['letra_nombre'];
                $notifications[$i]["carga_asignatura"] = $row['asignatura_nombre'];
                $notifications[$i]["carga_curso_asignatura_id"] = $row['curso_asignatura_id'];    
                break;

            case "evaluacion":
                $sql_eval = "SELECT * 
                        FROM evaluaciones as e
                        INNER JOIN cursos_asignaturas as ca on e.curso_asignatura_id = ca.curso_asignatura_id
                        INNER JOIN niveles as n on n.nivel_id = ca.nivel_id
                        INNER JOIN letras as l on l.letra_id = ca.letra_id
                        INNER JOIN dificultades as d on d.dificultad_id = ca.dificultad_id
                        INNER JOIN asignaturas as a on a.asignatura_id = ca.asignatura_id
                        WHERE evaluacion_id=" . $notifications[ $i ][ "entityID" ] . ";";
                $result_eval = mysqli_query($conexion, sql_eval);
                $row = mysql_fetch_array($result_eval);
                $notifications[$i]["evaluacion_aprobacion"] = $row['evaluacion_aprobacion'];
                $notifications[$i]["evaluacion_nombre"] = $row['evaluacion_nombre'];
                $notifications[$i]["evaluacion_fecha"] = $row['evaluacion_fecha'];
                $notifications[$i]["evaluacion_curso"] = $row['nivel_nombre']."-".$row['letra_nombre'];
                $notifications[$i]["evaluacion_asignatura"] = $row['asignatura_nombre'];
                $notifications[$i]["evaluacion_curso_asignatura_id"] = $row['curso_asignatura_id'];    
                break;
        }
    }  

echo( json_encode( $notifications ) ); // convert array to JSON text  