<?php
include('../funciones-sc/conexion.php');
$id_pendiente = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
	<title>Sistema Clases</title>
	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">
	<link 
	href="../css-sc/iphone.css"
	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
	rel="stylesheet">
	<?php include('../fonts/fonts.php') ?>
<script src="validar_logadm.js"></script>
<script src="../js-sc/validar_caracteres.js"></script>
</head>
<body class="login-bg">
	<div class="login-acceso">
		<div class="contenedor-logo">
			<img src="../images-sc/logo-stroke2.png">
		</div>
		<h1>Login</h1>
		<form method="POST" action="validaradmin.php" onsubmit="return validacionadm();" >

			 	<input type="hidden" name="id_mail" value="<?=$id_pendiente?>">

			<p>Correo Usuario</p>
			<input type="text" id="usuario" name="usuario" onkeyup="reemplazar(this);" >
			<p>Clave</p>
			<input type="password" id="clave" name="clave" >
			<input type="submit" value="Login">
		</form>
		<div class="contenedor-link">
			<a href="recuperacion.php">¿Olvidó su clave?</a>
		</div>
	</div>
</body>
</html>