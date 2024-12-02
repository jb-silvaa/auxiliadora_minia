<?php 
session_start();
if(!$_SESSION){
    print '<script language="javascript">
        alert("Error: Usuario No Autenticado"); 
        self.location = "index.php";
        </script>';
}
include('../funciones-sc/conexion.php');
$usuario = $_SESSION['profesor_id'];
$sql = "SELECT * FROM profesores WHERE profesor_id = '$usuario'";
$rs = mysqli_query($conexion, $sql);
$row = mysqli_fetch_array($rs);
$profesor_nombre = $row['profesor_nombres']." ".$row['profesor_apellidos'];
$profesor_fono = $row['profesor_fono'];
$profesor_mail = $row['profesor_correo'];
$titulo = $_POST['titulo'];
$cuerpo = $_POST['cuerpo'];
$para = "soporte@cdgo.cl";
 
$header= "From: CDGO || Contacto Colegio María Auxiliadora <soporte@cdgo.cl> \r\n";
$header.="Content-Type: text/html; charset=UTF-8";
$mensaje = "<html><body style='font-family: calibri; font-size: 15px;'><br>".
"Estimados CDGO:<br><br>".
"Este es un mail automático informando que se ha generado un nuevo contacto que se debe revisar (Profesor).<br>".
"Usuario: ".$profesor_nombre."<br>".
"Mail: ".$profesor_mail."<br>".
"Fono: ".$profesor_fono."<br>".
"Mensaje: ".$cuerpo."<br>".
"<br>Atte.<br> 
Administración CDGO</body></html>";

mail($para,$titulo,$mensaje,$header);


echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Correo enviado correctamente, le contactaremos a la brevedad posible!')

    window.location.href='contacto_soporte.php';

    </SCRIPT>"); 

/*$resultado = "ok";
$datos_graficos = array('resultado' => $resultado
          );
//Retorno del Ajax mediante JSON
echo json_encode($datos_graficos);*/
?>