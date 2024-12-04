<?php
		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\SMTP;
		use PHPMailer\PHPMailer\Exception;
session_start();
require '/var/www/html/vendor/autoload.php';
include('../funciones-sc/conexion.php');



$id = $_GET['id'];



$sql =	"	UPDATE 	profesores

			SET		profesor_clave = 'RDJCRWV4bGZOY2ZlQVRLK2tBQWdFZz09',

					profesor_activo = '1',

					profesor_clave_activa = '0'

			WHERE	profesor_id = '$id'

		";

$rs	 =	mysqli_query($conexion, $sql);
//OBTENGO LA INFO DEL PROFESOR
$sql_profesor = "SELECT * FROM profesores WHERE profesor_id = '$id'";
$rs_profesor = mysqli_query($conexion, $sql_profesor);
$row_profesor = mysqli_fetch_array($rs_profesor);

$correo = $row_profesor['profesor_correo'];
//REVISO SI EL PROFESOR ES COORDINADOR, Y SI ES COORDINADOR RESETEO SU CLAVE TAMBIEN
$sql_reviso = "SELECT * FROM usuarios WHERE usuario_mail = '$correo' AND usuario_estado = '1' AND perfil_id = '3'";
$rs_reviso = mysqli_query($conexion, $sql_reviso);
$row_reviso = mysqli_fetch_array($rs_reviso);

$id_usuario = $row_reviso['usuario_id'];
if($id_usuario != '0')
{
	$sql =	"	UPDATE 	usuarios
			SET		usuario_clave = 'RDJCRWV4bGZOY2ZlQVRLK2tBQWdFZz09',
					usuario_activo = '0'
			WHERE	usuario_id = '$id_usuario'	

		";
	$rs	 =	mysqli_query($conexion, $sql);
}

		$nombre_completo = $row_profesor['profesor_nombres']." ".$row_profesor['profesor_apellidos'];
		require_once('../PHPMailer-master/class.phpmailer.php');
		$fecha_esp = date("d-m-Y", strtotime($fecha));
		$mensaje = "<table style='width:600px;'>
					<tr>
		                <th colspan='2' style='text-align:center;'><img src='https://mauxiliadora.cdgo-chile.cl/images-sc/logo-stroke2.png'></th>
		              </tr>
		              <tr>
		                <th colspan='2' style='text-align:left;'>Estimado $nombre_completo:</th>
		              </tr>
		              <tr>
		                <th colspan='2' style='text-align:left;'>Se ha reseteado su clave de profesor en el sistema de clases de María Auxiliadora.<br>Ingrese con los siguientes datos para poder ingresar a su cuenta.</th>
		              </tr>
		              <tr>
		                <th colspan='2' style='text-align:center;background:#66cc99;color:#ffffff;'><b>Información Cuenta</b></th>
		              </tr>
		              <tr>
		                <th style='background:#66cc99;color:#ffffff;'><b>Link</b></th>
		                <td><a href='https://mauxiliadora.cdgo-chile.cl/'>Click Aquí</a></td>
		              </tr>
		              <tr>
		                <th style='background:#66cc99;color:#ffffff;'><b>Usuario</b></th>
		                <td>$correo</td>
		              </tr>
		              <tr>
		                <th style='background:#66cc99;color:#ffffff;'><b>Contraseña</b></th>
		                <td>12345</td>
		              </tr>
		              <tr><td></td></tr>
		              <tr><td></td></tr>
		              <tr>
		                <th colspan='2' style='color:#cf000f;text-align:left;'><b>*Este correo fue generado automáticamente, por favor no responda a esta casilla.</b></th>
		              </tr>
		            </table><br>
		            ";
		  
		  //CON ADJUNTO
		  require '../PHPMailer-master/PHPMailerAutoload.php';
		  $email = new PHPMailer();
		  //  aqui agrego la validacion smtp auth
		  $email->IsSMTP();
		$email->Host       = 'blue102.dnsmisitio.net';
		//El puerto será el 587 ya que usamos encriptación TLS
		$email->Port       = 465;
		//Definmos la seguridad como TLS
		$email->SMTPSecure = 'ssl';
		//Tenemos que usar gmail autenticados, así que esto a TRUE
		$email->SMTPAuth   = true;
		//Definimos la cuenta que vamos a usar. Dirección completa de la misma
		$email->Username   = "mauxiliadoravina@cdgochile.cl";
		//Introducimos nuestra contraseña de gmail
		$email->Password   = "-.cdgo.05311.-";

		$email->CharSet = 'UTF-8';
		$email->From      = 'mauxiliadoravina@cdgochile.cl';
		$email->FromName  = 'Sistema de Clases - Colegio María Auxiliadora';
		  $email->Subject   = 'Reseteo de contraseña';
		  $email->Body      = $mensaje;
		  $email->IsHTML(true);
 
		  $email->AddAddress( trim($correo) );
		  
		/*
		  $file_to_attach = '../pdf/cartas/'.$row['cuenta_corriente_pdf'];

		  $email->AddAttachment( $file_to_attach , $nombre_imagen);*/
		//   $email->Send();

echo ("<SCRIPT LANGUAGE='JavaScript'>

window.alert('Clave re-seteada correctamente! Nueva clave = 12345')

window.location.href='profesores.php?';

</SCRIPT>");

mysqli_close($sql);

?>