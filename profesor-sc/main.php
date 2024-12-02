<?php 

include('../funciones-sc/conexion.php');

$sql = "SELECT * FROM profesores WHERE profesor_id = '1'";

$rs = mysqli_query($conexion, $sql);

$row = mysqli_fetch_array($rs);



?>

<!DOCTYPE html>

<html>

<head>

<link rel="shortcut icon" type="image/png" href="../images-sc/ico.png"/>

	<title>Profile Profesor</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

	<script src="../js-sc/menu-lateral.js"></script>

	<link rel="stylesheet" href="../fonts/font-awesome/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../css-sc/styles.css">

	<link 

	href="css-sc/iphone.css"

	media="screen and (min-device-width: 300px) and (max-device-width: 599px)  and (orientation: portrait)"

	rel="stylesheet">

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body style="background: #000;">

<?php

include 'menu-lateral.php';

?>

</div><!-- CIERRE DEL DIV QUE ESTA EN EL ARCHIVO MENU-LATERAL.PHP -->



</body>

</html>