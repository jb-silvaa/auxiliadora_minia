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
<script src="validar.js"></script>
</head>
<body class="login-bg">
	<div class="login-acceso">
		<div class="contenedor-logo">
			<img src="../images-sc/logo-stroke2.png">
		</div>
		<h1>Login</h1>
		<form method="POST" action="validar.php" onsubmit="return validacion();" >
			<p>Correo Jefe de area</p>
			<input type="text" name="usuario" id="usuariobox" >
			<p>Clave</p>
			<input type="password" name="clave" id="clavebox"  >
			<input type="submit" value="Login">
		</form>
		<div class="contenedor-link">
			<a href="recuperacion.php">¿Olvidó su clave?</a>
		</div>
	</div>
</body>
</html>