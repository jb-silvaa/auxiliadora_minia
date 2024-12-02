<?php
function notify( $type, $forUser, $entityID, $usuario, $enviado_por ){
    $conexion = mysqli_connect("192.168.185.111", "root", "ClaveCDGO.2017", "sistema_clases");
    $sql = "SELECT notificacion_id
    FROM notificaciones
    WHERE notificacion_destinatario_id = '$forUser'
    AND notificacion_tipo_id = '$entityID'
    AND notificacion_tipo = '$type'
    AND notificacion_autor_tipo = '$enviado_por'";
    $result = mysqli_query($conexion, $sql);
    $row_result = mysqli_fetch_array($result); // if query returned a row, it means the notification exists
    if( mysqli_num_rows($result) > 0 ){ // update the existing record, set read to false and time to the current time
        $sql = "UPDATE notificaciones
        SET notificacion_leido = 0,
            notificacion_fecha=NOW() 
        WHERE notificacion_destinatario_id = '$forUser' 
        AND notificacion_tipo_id = '$entityID' 
        AND notificacion_tipo = '$type'";
        $result = mysqli_query($conexion, $sql);
    }else{ // insert new record with the details
       $sql = "INSERT INTO notificaciones (notificacion_tipo, notificacion_destinatario_id, notificacion_tipo_id, notificacion_autor_id, notificacion_autor_tipo )
        VALUES( '$type', '$forUser', '$entityID', $usuario, '$enviado_por' )";
        $result = mysqli_query($conexion, $sql);   
    }
    echo '<script>';
    echo 'console.log('.$sql.')';
    echo '</script>';
} 
