<?php
		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\SMTP;
		use PHPMailer\PHPMailer\Exception;
session_start();
require '/var/www/html/vendor/autoload.php';
$user_id = $_SESSION['profesor_id'];

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}

include('../funciones-sc/conexion.php');

include('../funciones-sc/notificacion.php');

function debug_to_console($data) {

    $output = $data;

    if (is_array($output))

        $output = implode(',', $output);



    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";

}

$cur_asig = $_POST['id'];

$unidad = $_POST['unidad'];

$observacion = $_POST['obscorreccion'];

$demo = $_POST['decision'];

$cur_asig2 = $_POST['id2'];



if($unidad == '0'){ $tipo = '2'; }

else{

	$tipo = '1';

}



//BUSCO SI YA EXISTE LA CARGA

$sql_existe = "SELECT * FROM cargas 

				WHERE curso_asignatura_id = '$cur_asig' AND tipo_carga_unidad = '$unidad'";

$rs_existe = mysqli_query($conexion, $sql_existe);

$row_existe = mysqli_fetch_array($rs_existe);



$id_existe = $row_existe['carga_id'];



//Si no existe, se inserta a la BD

if($id_existe == ''){

	$sql = "INSERT INTO cargas 

			(

				curso_asignatura_id,

				tipo_carga_id,

				tipo_carga_unidad,

				carga_aprobacion,

				carga_estado

			)

			values

			(

				'$cur_asig',

				'$tipo',

				'$unidad',

				'0',

				'1'

			)";

	$rs = mysqli_query($conexion, $sql);



	/* $sql_last = "SELECT MAX(carga_id) as carga_id FROM cargas WHERE curso_asignatura_id = '$cur_asig' ";

	$rs_last = mysqli_query($conexion, $sql_last);

	$row_last = mysqli_fetch_array($rs_last);

    $carga = $row_last['carga_id']; */

    //quizas puedo usar esto y me ahorro la consulta

    $carga = mysqli_insert_id($conexion);

}else{

    //Si existe, modificamos sus datos



    //Agrego la observacion realizada a la carga

    //Se setea la aprobacion en 0

	$sql1 =	"	UPDATE 	cargas

			SET		carga_obs_correccion = '$observacion',

                    carga_aprobacion = '0'

			WHERE	carga_id = '$row_existe[carga_id]'

		";

	$rs	 =	mysqli_query($conexion, $sql1);



    //Guardamos el id de la carga para insertar el archivo

	$carga = $row_existe['carga_id'];

}



//Se suben notis para todos los usuarios

$sql_todos = "SELECT * FROM usuarios";

$rs_todos = mysqli_query($conexion, $sql_todos);

while($row_todos = mysqli_fetch_array($rs_todos)){

    $user = $row_todos['usuario_id'];

    $sql_ayudante = "SELECT * 

                    FROM ayudantes 

                    INNER JOIN usuarios on usuarios.usuario_id = ayudantes.usuario_id

                    WHERE ayudantes.usuario_id = '$user'";

    $rs_ayudante = mysqli_query($conexion, $sql_ayudante);

    $row_ayudante = mysqli_fetch_array($rs_ayudante);

    if(mysqli_num_rows($rs_ayudante)>0){

        if($row_ayudante['curso_asignatura_id'] == $cur_asig){

            notify("planificacion", $row_todos['usuario_id'], $carga, $user_id, 2);

        }

        continue;



    }

    notify("planificacion", $row_todos['usuario_id'], $carga, $user_id, 2);

}

//(notificacion_tipo, notificacion_destinatario_id,notificacion_tipo_id,notificacion_autor_id,notificacion_autor_tipo)

$filename = $_FILES['files']['name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);
if($ext == ''){ $ext = "docx"; }


$hora = date("H_i_s");

$nombre_imagen = "documentos/".$carga."_".$hora.".".$ext;
$para_copiar = "documentos/".$carga."_".$hora.".".$ext;
if (isset($_FILES['files'])){	

	//Comprobamos si el fichero es una imagen

	if ($ext == 'doc' || $ext == 'docx' || $_FILES['files']['type']=='application/octet-stream' || $_FILES['files']['type']=='application/pdf' || $_FILES['files']['type']=='application/msword' || 

		$_FILES['files']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){

		$destino = 'documentos/'.$carga.'_'.$hora.".".$ext;

		//Subimos el fichero al servidor

		move_uploaded_file($_FILES["files"]["tmp_name"], $destino);

		$_FILES["files"]["tmp_name"];

		$sql_imagen = "UPDATE cargas

			   SET carga_archivo = '$nombre_imagen',

			   	   carga_leido_admin = '1'

			   WHERE carga_id = $carga";

		$rs_imagen = mysqli_query($conexion, $sql_imagen);

	}else{
		echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Extensión de archivo no válida!')

    window.location.href='index.php';

    </SCRIPT>");
	}

}



//BUSCO SI YA EXISTE LA CARGA

if($demo == 'si'){



	$sql_existe = "SELECT * FROM cargas 

					WHERE curso_asignatura_id = '$cur_asig2' AND tipo_carga_unidad = '$unidad'";

	$rs_existe = mysqli_query($conexion, $sql_existe);

	$row_existe = mysqli_fetch_array($rs_existe);



	$id_existe = $row_existe['carga_id'];



	if($id_existe == ''){

		$sql = "INSERT INTO cargas 

				(

					curso_asignatura_id,

					tipo_carga_id,

					tipo_carga_unidad,

					carga_aprobacion,

					carga_estado

				)

				values

				(

					'$cur_asig2',

					'$tipo',

					'$unidad',

					'0',

					'1'

				)";

		$rs = mysqli_query($conexion, $sql);



		/* $sql_last = "SELECT MAX(carga_id) as carga_id FROM cargas WHERE curso_asignatura_id = '$cur_asig2' ";

		$rs_last = mysqli_query($conexion, $sql_last);

		$row_last = mysqli_fetch_array($rs_last);

        $carga = $row_last['carga_id']; */

        $carga = mysqli_insert_id($conexion);

	}else{

        //Si existe, modificamos sus datos



        //Agrego la observacion realizada a la carga

        //Se setea la aprobacion en 0

		$sql1 =	"	UPDATE 	cargas

				SET		carga_obs_correccion = '$observacion',

                        carga_aprobacion = '0'

				WHERE	carga_id = '$row_existe[carga_id]'

			";

		$rs	 =	mysqli_query($conexion, $sql1);



        //Guardamos el id de la carga para insertar el archivo

		$carga = $row_existe['carga_id'];

    }

    //Se suben notis para todos los usuarios

$sql_todos = "SELECT * FROM usuarios";

$rs_todos = mysqli_query($conexion, $sql_todos);

while($row_todos = mysqli_fetch_array($rs_todos)){

    $user = $row_todos['usuario_id'];

    $sql_ayudante = "SELECT * 

                    FROM ayudantes 

                    INNER JOIN usuarios on usuarios.usuario_id = ayudantes.usuario_id

                    WHERE ayudantes.usuario_id = '$user'";

    $rs_ayudante = mysqli_query($conexion, $sql_ayudante);

    $row_ayudante = mysqli_fetch_array($rs_ayudante);

    if(mysqli_num_rows($rs_ayudante)>0){

        if($row_ayudante['curso_asignatura_id'] == $cur_asig){

            notify("planificacion", $row_todos['usuario_id'], $carga, $user_id, 2);

        }

        continue;



    }

    notify("planificacion", $row_todos['usuario_id'], $carga, $user_id, 2);

}



	$filename = $_FILES['files']['name'];

	$ext = pathinfo($filename, PATHINFO_EXTENSION);



	$hora = date("H_i_s");

	$nombre_imagen = "documentos/".$carga."_".$hora.".".$ext;

	copy($para_copiar, $nombre_imagen);

			$sql_imagen = "UPDATE cargas

				SET carga_archivo = '$nombre_imagen',

					carga_leido_admin = '1'

				WHERE carga_id = $carga";

			$rs_imagen = mysqli_query($conexion, $sql_imagen);



}











require_once('../PHPMailer-master/class.phpmailer.php');



//CON ADJUNTO

	require '../PHPMailer-master/PHPMailerAutoload.php';

	$email = new PHPMailer();



	$sql_curso = "SELECT *

				  FROM niveles as n,

				  	   asignaturas as a,

				  	   cursos_asignaturas as ca,

			   		   letras as l,

			   		   profesores as p

			   	  WHERE n.nivel_id = ca.nivel_id

			  	  AND l.letra_id = ca.letra_id

			  	  AND a.asignatura_id = ca.asignatura_id

			  	  AND p.profesor_id = ca.profesor_id

			 	  AND ca.curso_asignatura_id = '$cur_asig'";

	$rs_curso = mysqli_query($conexion, $sql_curso);

	$row_curso = mysqli_fetch_array($rs_curso);


/*
	$mensaje = "Estimado se ha generado una nueva planificación para su aprobación o rechazo:<br><br>

	<b>Profesor: </b>".$row_curso['profesor_nombres']." ".$row_curso['profesor_apellidos']."<br>

	<b>Curso: </b>".$row_curso['nivel_nombre']."-".$row_curso['letra_nombre']."<br>	

	<b>Asignatura: </b>".$row_curso['asignatura_nombre']."<br><br>

	Para revisar esta u otras planificaciones diríjase al siguiente <a href='https://mauxiliadora.cdgo-chile.cl/admin-sc/index.php?id=".$carga."'>link</a>.<br>

	Si usted es Jefe de Departamento puede descargar el archivo desde este <a href='https://mauxiliadora.cdgo-chile.cl/profesor-sc/".$nombre_imagen."'>link</a>.<br>

	Atentamente.<br><br>

	Sistema Colegio María Auxiliadora.

	";



	//	aqui agrego la validacion smtp auth -.cdgo.05311.-


            // //  aqui agrego la validacion smtp auth
            $email->IsSMTP();
            //$email->SMTPDebug = 1;
            $email->Host       = 'smtppro.zoho.com';
            //El puerto será el 587 ya que usamos encriptación TLS
            $email->Port       = 465;
            //Definmos la seguridad como TLS
            $email->SMTPSecure = 'ssl';
            //Tenemos que usar gmail autenticados, así que esto a TRUE
            $email->SMTPAuth   = true;
            //Definimos la cuenta que vamos a usar. Dirección completa de la misma
            $email->Username   = "contacto@cdgo.cl";
            //Introducimos nuestra contraseña de gmail
            $email->Password   = "Jlvv.2016";

	$email->CharSet = 'UTF-8';
	$email->From      = 'contacto@cdgo.cl';
	$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';
	$email->Subject   = 'Creación de nueva planificación.';
	$email->Body      = $mensaje;
	$email->IsHTML(true);

	//BUSCO USUARIOS ADMIN, PERFIL ADMIN. Y ENVIO CORREO

	$sql_mail = "SELECT usuario_mail FROM usuarios WHERE usuario_estado = '1' AND perfil_id = '1' AND usuario_id <> 23 AND usuario_id <> 24";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['usuario_mail'] );

	}	

	$sql_mail = "SELECT jefe_correo FROM jefes_area as ja, jefes_area_profe as jap WHERE jefe_estado = '1' AND ja.jefe_id = jap.jefe_id AND jap.profesor_id = '$user_id'";

	$rs_mail = mysqli_query($conexion, $sql_mail);

	while($row_mail = mysqli_fetch_array($rs_mail))

	{

		$email->AddAddress( $row_mail['jefe_correo'] );

	}

//$email->AddAddress( 'fmoreno@cdgo.cl' );
//	$email->Send();
*/
/*
	if(!$email->Send())

	{

	   echo "Error sending: " . $email->ErrorInfo;

	}

	else

	{

	   echo "E-mail sent";

	}
*/


echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Carga creada/modificada correctamente!')

    window.location.href='index.php';

    </SCRIPT>");

?>