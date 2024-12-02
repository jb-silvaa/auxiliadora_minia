<?php

include_once '../../class/Conexion.php';



$con = new Conexion();

$tipo = $_POST["tipo"];

// Obtiene resumen para pantalla inicio

if ($tipo == "obtenerResumen"){

    $tipo_dato = $_POST["tipo_dato"];

    $resultado = array();

    if ($tipo_dato == "esperaCat"){

        $sql = "SELECT fecha_admision, categorizacion FROM admision WHERE categorizacion IS NOT NULL";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["categorizacion"]) - strtotime($fila["fecha_admision"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    if ($tipo_dato == "esperaAM"){

        $sql = "SELECT hora_box, atencion_medica FROM admision WHERE hora_box IS NOT NULL AND atencion_medica IS NOT NULL LIMIT 20";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["atencion_medica"]) - strtotime($fila["hora_box"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    if ($tipo_dato == "tiempoCat"){

        $sql = "SELECT ini_tiempo, fin_tiempo FROM tiempo_admision WHERE tipo_tiempo = 'C' LIMIT 20";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["fin_tiempo"]) - strtotime($fila["ini_tiempo"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    if ($tipo_dato == "tiempoMed"){

        $sql = "SELECT ini_tiempo, fin_tiempo FROM tiempo_admision WHERE tipo_tiempo = 'M' LIMIT 20";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["fin_tiempo"]) - strtotime($fila["ini_tiempo"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    if ($tipo_dato == "tiempoEnf"){

        $sql = "SELECT ini_tiempo, fin_tiempo FROM tiempo_admision WHERE tipo_tiempo = 'E' LIMIT 20";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["fin_tiempo"]) - strtotime($fila["ini_tiempo"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    if ($tipo_dato == "tiempoTen"){

        $sql = "SELECT ini_tiempo, fin_tiempo FROM tiempo_admision WHERE tipo_tiempo = 't' LIMIT 20";

        $result = $con->query($conexion, $sql);

        $tiempos = array();

        $i = 1;

        while ($fila = mysqli_fetch_assoc($result)){

            $tiempo = strtotime($fila["fin_tiempo"]) - strtotime($fila["ini_tiempo"]);

            $data = array("id"=>$i,"tiempo"=>$tiempo);

            array_push($resultado, $data);

            $i++;

        }

    }

    header('Content-type: application/json; charset=utf-8');

    echo json_encode($resultado,JSON_FORCE_OBJECT);

}

if ($tipo == "pacientesDia"){

    $sql = "SELECT CAST(fecha_ingreso AS DATE) AS fecha, COUNT(*) AS cantidad FROM admision GROUP BY CAST(fecha_ingreso AS DATE)";

    $result = $con->query($conexion, $sql);



}