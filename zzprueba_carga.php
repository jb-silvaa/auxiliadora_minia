<?php
session_start();
include 'profesor-sc/SED.php';//llamando al archivo con las funciones
$clave = 12345;
$claveE=SED::encryption($clave);//encriptamos la clave ingresada por el usuario en el input de texto
?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>
	<title>Sistema Clases</title>
	<script src="validar.js"></script>
	<link rel="stylesheet" type="text/css" href="css-sc/styles.css">
	<link 
	href="css-sc/iphone.css"
	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"
	rel="stylesheet">
	<?php include('fonts/fonts.php') ?>
</head>
<body class="login-bg">
	<div class="login-acceso">
		<div class="contenedor-logo">
			<img src="images-sc/logo-stroke2.png">
		</div>
		<form method="POST" action="validar.php" onsubmit="return validacion();">
		<h1>Login</h1>
    <?=$claveE?>
		<input type="submit" value="Login" >
		<div class="contenedor-link">
			<a href="recuperacion.php">¿Olvidó su clave?</a>
		</div>
		</form>
	</div>
</body>
</html>