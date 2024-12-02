<?php
session_start();
include('../funciones-sc/conexion.php');

  $nivel_id = trim($_POST['eva_nivel']);

  $sql_tabla = "SELECT letr.letra_id, letr.letra_nombre
                FROM letras as letr
                  INNER JOIN niveles_letras as tabla ON letr.letra_id = tabla.letra_id
                WHERE tabla.nivel_id = '$nivel_id'
                AND tabla.nivel_letra_estado = '1'
                ORDER BY letr.letra_nombre";
  $rs_tabla = mysqli_query($conexion, $sql_tabla);
  $html = "";
  $html.= "<option value='' disabled selected>Seleccione Letra</option>";
  while($row_tabla = mysqli_fetch_array($rs_tabla)){
    $letra_id     = $row_tabla['letra_id'];
    $letra_nombre = $row_tabla['letra_nombre'];

    $html.="<option value='$letra_id'>$letra_nombre</option>"; 
  }

  echo $html;

?>
