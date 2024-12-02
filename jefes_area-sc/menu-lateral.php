<?php
session_start();
$user_id = $_SESSION['jefe_id'];
if (!$_SESSION['jefe_id']){
  echo "<script LANGUAGE='JavaScript'>
                window.alert('Acceso no autorizado');
                window.location= 'index.php'
    </script>";
  die();
}

?>
<script>
function goBack() {
    window.history.back();
}
</script>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <img  src="../images-sc/foto-sinperfil.png"> <!-- linea de la foto fea-->
                    <div class="clear"></div>
                </li>
                <div class="clear"></div>

                <li>
                    <a href="solicitudes_pendientes.php">Inicio</a>
                </li>
                     <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Solicitudes <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">                       
                            <li><a href="solicitudes_pendientes.php">Solicitudes Pendientes</a></li>     
                        </ul>        
                    </li>
                    <li>
                        <a href="#">Reportes</a>
                    </li>
                <li>
                    <a onclick="goBack()" style="cursor: pointer;">Volver</a>
                </li>
                <li>
                    <a href="destroy.php">Salir</a>
                </li>
            </ul>
        </nav>