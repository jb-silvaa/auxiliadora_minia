<?php

session_start();

if (!$_SESSION['profesor_id']){

  echo "<script LANGUAGE='JavaScript'>

                window.alert('Acceso no autorizado');

                window.location= '../index.php'

    </script>";

  die();

}



?>

<script>

function goBack() {

window.history.back();

}

</script>

<nav class="superior">

    <ul>        

        <li><a href="cerrar_sesion_profe.php" onclick = 'javascript: return confirm("Desea cerrar sesion?");'>Cerrar sesion<i class='fas fa-door-open'></i></a></li>

        <li><a href="#">Soporte</a>
            <ul>

                <li><a href="#"><i class="fas fa-phone"></i> 7227 79936</a></li>

                <li><a href="contacto_soporte.php" style="cursor: pointer;"><i class="fas fa-envelope"></i> soporte@cdgo.cl</a></li>

            </ul>
        </li>

        <li><a href="modificar_perfil.php">Editar Perfil</a></li>

        <?php 

		$sql_pj = "SELECT * FROM profesores_jefes

                    WHERE profesores_jefes.profesor_id = $user_id

                    AND profesor_jefe_estado = '1'";

		$rs_pj = mysqli_query($conexion, $sql_pj);

		if (mysqli_num_rows($rs_pj)>0) {

			?>

            <li><a href="listado_reuniones.php">Jefatura</a></li>

        <?php

		}

		?>



        <li><a href="mensaje_lista.php">Mensajes</a></li>

        <!--<li><a href="#">Solicitudes</a>

	        <ul>

                <li><a href="solicitudes_nuevo.php">Nueva Solicitud</a></li>

                <li><a href="solicitudes_aprobadas.php">Solicitudes Aprobadas</a></li>

                <li><a href="solicitudes_pendientes.php">Solicitudes Pendientes</a></li>

                <li><a href="solicitudes_rechazadas.php">Solicitudes Rechazadas</a></li>

            </ul>

        </li>-->

        <li><a href="index.php">Inicio</a></li>

        <li><a onclick="goBack()" style="cursor: pointer;"><i class="fas fa-arrow-circle-left"></i> Atras</a></li>

    </ul>     

</nav>
