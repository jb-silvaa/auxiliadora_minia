<?php

include('../funciones-sc/conexion.php');

include('../funciones-sc/meses.php');

function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}



$profesor_id = $_POST['id'];

$observacion = $_POST['observacion'];

$encargado = $_POST['encargado'];

$periodo = $_POST['periodo'];

//$asunto = $_POST['asunto'];

//debug_to_console($profesor_id);

//debug_to_console($periodo);



$sql_data = "SELECT * FROM cargas WHERE carga_id = '$id'";

$rs_data = mysqli_query($conexion, $sql_data);

$row_data = mysqli_fetch_array($rs_data);





	$sql_curso = "SELECT * FROM cursos_asignaturas CA 

                    join asignaturas A on A.asignatura_id = CA.asignatura_id

                    join niveles N on N.nivel_id = CA.nivel_id

                    join letras L on L.letra_id = CA.letra_id

                    join dificultades D on D.dificultad_id = CA.dificultad_id

                    join profesores P on (P.profesor_id = CA.profesor_id and P.profesor_id = $profesor_id)             

                WHERE CA.curso_asignatura_estado = '1' and CA.curso_asignatura_periodo = '$periodo'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	//$row_curso = mysqli_fetch_array($rs_curso);

    $profe = $profesor_id;

    $avanceMensual = array();

    $avanceAnual = array();

    //$correo = $row_curso['profesor_correo'];

    $total_unidades_mensuales=0;

    $total_unidades_anuales=0;

    while ($row_cursos = mysqli_fetch_array($rs_curso)) {



        $unidad = $row_cursos['curso_asignatura_unidades']; //AGREGO LA ANUAL CON EL +1 (Borre el +1, no olvidar)

        $total_unidades_mensuales = $total_unidades_mensuales + $row_cursos['curso_asignatura_unidades'];

        $total_unidades_anuales++;



        //Consulta carga mensuales aprobadas

        $sql_unidad = "SELECT count(carga_id) as totalmes FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '1' AND tipo_carga_id = '1' ";

        $rs_unidad = mysqli_query($conexion, $sql_unidad);

        $row_unidad = mysqli_fetch_array($rs_unidad);



        //Consulta carga mensuales sin revisar

        $sql_unidad_sr = "SELECT count(carga_id) as totalmes FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '0' AND tipo_carga_id = '1' AND carga_archivo != '' ";

        $rs_unidad_sr = mysqli_query($conexion, $sql_unidad_sr);

        $row_unidad_sr = mysqli_fetch_array($rs_unidad_sr);



        //Consulta carga mensuales rechazadas

        $sql_unidad_r = "SELECT count(carga_id) as totalmes FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '-1' AND tipo_carga_id = '1' ";

        $rs_unidad_r = mysqli_query($conexion, $sql_unidad_r);

        $row_unidad_r = mysqli_fetch_array($rs_unidad_r);



        //Consulta carga anual aprobada

        $sql_anual = "SELECT count(carga_id) as totalanio FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '1' AND tipo_carga_id = '2' ";

        $rs_anual = mysqli_query($conexion, $sql_anual);

        $row_anual = mysqli_fetch_array($rs_anual);



        //Consulta carga anual sin revisar

        $sql_anual_sr = "SELECT count(carga_id) as totalanio FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '0' AND tipo_carga_id = '2' AND carga_archivo != '' ";

        $rs_anual_sr = mysqli_query($conexion, $sql_anual_sr);

        $row_anual_sr = mysqli_fetch_array($rs_anual_sr);



        //Consulta carga anual rechazada

        $sql_anual_r = "SELECT count(carga_id) as totalanio FROM cargas WHERE curso_asignatura_id = ".$row_cursos['curso_asignatura_id']." AND carga_estado = '1' AND carga_aprobacion = '-1' AND tipo_carga_id = '2' ";

        $rs_anual_r = mysqli_query($conexion, $sql_anual_r);

        $row_anual_r = mysqli_fetch_array($rs_anual_r);



        $uni_apro = $row_unidad['totalmes'];

        $anual_apro = $row_anual['totalanio'];

        $total_anual_aprobadas = $total_anual_aprobadas + $anual_apro;

        $total_mensual_aprobadas = $total_mensual_aprobadas + $uni_apro;

        

        $uni_sr = $row_unidad_sr['totalmes'];

        $anual_sr = $row_anual_sr['totalanio'];

        $total_anual_sr = $total_anual_sr + $anual_sr;

        $total_mensual_sr = $total_mensual_sr + $uni_sr;



        $uni_r = $row_unidad_r['totalmes'];

        $anual_r = $row_anual_r['totalanio'];

        $total_anual_r = $total_anual_r + $anual_r;

        $total_mensual_r = $total_mensual_r + $uni_r;

        



        



        

        

        $hola = $uni_apro."/".$unidad;

        $hola2 = $anual_apro."/1";

        $avanceMensual[] = $row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."-".$row_cursos['asignatura_nombre']."-".$row_cursos['dificultad_nombre'].": ".$hola;

        $avanceAnual[] = $row_cursos['nivel_nombre']."-".$row_cursos['letra_nombre']."-".$row_cursos['asignatura_nombre']."-".$row_cursos['dificultad_nombre'].": ".$hola2;

    }

    $diff = $total_unidades_anuales-$total_anual_aprobadas;

    $diff2 = $total_unidades_mensuales-$total_mensual_aprobadas;

    //debug_to_console($avance);



    $chart = "

    {

          type: 'pie',

          data: {

            labels: ['Sin Planificación', 'Aceptada', 'Rechazada'],

            datasets: [{

              label: 'Cantidad en unidades',

              backgroundColor: ['#000', 'rgb(46, 204, 113)','rgb(207, 0, 15)'],

              data: [$diff,$total_anual_aprobadas,$total_anual_r]

            }]

            },

            options: {

              title: {

                display: true,

                text: 'Resumen Planificaciones Anuales'

              }

            }

          }

    ";

    $chart2 = "

    {

          type: 'pie',

          data: {

            labels: ['Sin Planificación', 'Aceptada', 'Rechazada'],

            datasets: [{

              label: 'Cantidad en unidades',

              backgroundColor: ['#000', 'rgb(46, 204, 113)','rgb(207, 0, 15)'],

              data: [$diff2,$total_mensual_aprobadas,$total_mensual_r]

            }]

            },

            options: {

              title: {

                display: true,

                text: 'Resumen Planificaciones Anuales'

              }

            }

          }

    ";

    $encoded = urlencode($chart);

    $imageUrl = "https://quickchart.io/chart?c=" . $encoded;



    $encoded2 = urlencode($chart2);

    $imageUrl2 = "https://quickchart.io/chart?c=" . $encoded2;



    $mensaje1    = implode( "<br />",$avanceMensual).'';

    $mensaje2    = implode( "<br />",$avanceAnual).'';



    $mensaje = "Estimado, se envía estado de avance correspondiente a las cargas de sus asignaturas,

     además de una observación adjunta. <br><br>

    <b>Enviado por: </b>".$encargado."<br>

    <b>Observación: </b>".$observacion."<br>

    <br><br>

    <b>CARGAS MENSUALES</b><br>

    ".$mensaje1."<br><br>

    <b>CARGAS ANUALES</b><br>

    ".$mensaje2."<br><br>

    <img src='$imageUrl' height='50%' width='40%'>

    <img src='$imageUrl2' height='50%' width='40%'>

    <br>

    Para subir sus planificaciones diríjase al siguiente <a href='http://www.colegiomariaauxiliadora.cl/sistema_clases/'>link</a>.<br>

    Atentamente.<br><br>

    Sistema Colegio María Auxiliadora.

    ";

//$image = file_get_contents($imageUrl);

/*$mensaje = "Hola.$mensaje1.sadsa <br><br>

            <img src='$imageUrl'>

    

    ";*/

require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

require '../PHPMailer-master/PHPMailerAutoload.php';

$email = new PHPMailer();

$email->isSMTP();

$email->Host = 'smtp.gmail.com';

$email->SMTPAuth = true;



$email->Username = 'esunmailinventado@gmail.com';

$email->Password = 'passfalsa1234';

//$email->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS();

$email->Port = 587;

$email->CharSet = 'UTF-8';

//$email->From      = 'no-reply@colegiomariaauxiliadora.cl';

$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';

$email->Subject   = 'Estado de avance cargas académicas, periodo '.$periodo.'';

$email->Body      = $mensaje;

$email->IsHTML(true);

$email->AddAddress('');

//$email->addStringEmbeddedImage($image, 'staticMap', 'chart.png', 'base64', 'image/png');

//$email->Body = '<img width="600" height="300" src="cid:staticMap">';

//$email->AddAddress( '' );//CORREO COPIA DE LAS PLANIFICACIONES  

//print_r($email);

// if (!$email->send()) {

//   echo "ERROR: " . $email->ErrorInfo;

// } else {

//   echo "SUCCESS";

// }







/*echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Carga corregida correctamente!')

window.location.href='carga.php?id=$cur_asig';

</SCRIPT>");

mysqli_close($sql);*/

?>