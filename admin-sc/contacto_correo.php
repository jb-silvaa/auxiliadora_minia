<?php 
session_start();
if(!$_SESSION){
    print '<script language="javascript">
        alert("Error: Usuario No Autenticado"); 
        self.location = "index.php";
        </script>';
}
include('../funciones-sc/conexion.php');
$usuario = $_SESSION['id'];
$sql = "SELECT * FROM usuarios WHERE usuario_id = '$usuario'";
$rs = mysqli_query($conexion, $sql);
$row = mysqli_fetch_array($rs);
$usuario_nombre = $row['usuario_nombres']." ".$row['usuario_apellidos'];
$usuario_fono = $row['usuario_fono'];
$usuario_mail = $row['usuario_mail'];
$titulo = $_POST['titulo'];
$cuerpo = $_POST['cuerpo'];
$para = "soporte@cdgo.cl";
 
$header= "From: CDGO || Contacto Colegio María Auxiliadora <soporte@cdgo.cl> \r\n";
$header.="Content-Type: text/html; charset=UTF-8";
$mensaje = "<html><body style='font-family: calibri; font-size: 15px;'><br>".
"Estimados CDGO:<br><br>".
"Este es un mail automático informando que, se ha enviado un nuevo mensaje para revisar.<br>".
"Usuario: ".$usuario_nombre."<br>".
"Mail: ".$usuario_mail."<br>".
"Fono: ".$usuario_fono."<br>".
"Mensaje: ".$cuerpo."<br>".
"<br>Atte.<br> 
Administración CDGO</body></html>";

mail($para,$titulo,$mensaje,$header);

$resultado = "ok";
$datos_graficos = array('resultado' => $resultado
          );
//Retorno del Ajax mediante JSON
echo json_encode($datos_graficos);
?>