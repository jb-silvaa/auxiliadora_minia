<?php 
  session_start();
  if(!$_SESSION){
      print '<script language="javascript">
          alert("Error: Usuario No Autenticado"); 
          self.location = "index.php";
          </script>';
  }
  include ('../funciones-sc/conexion.php');

  $id = $_POST['id'];
  $fecha = $_POST['fecha'];
  $fecha_nueva = $_POST['fecha_nueva'];
  //SEPARO LA FECHA
  $mes = substr($fecha, 5,6);
  $periodo = substr($fecha, 0,3);

  //$sql = "SELECT * FROM encuestas WHERE encuesta_fecha_programada = '$fecha' AND sucursal_id = '$id'";
  //$rs = mysqli_query($conexion, $sql);

  $sql = "UPDATE evaluaciones SET evaluacion_fecha = '$fecha_nueva' WHERE evaluacion_id = '$id'";
  $rs = mysqli_query($conexion, $sql);
  echo $sql;
?>