<?php

session_start();

include '../profesor-sc/SED.php';//llamando al archivo con las funciones

$usuario=$_POST['usuario']; //tomando los datos de los input

$clave=$_POST['clave'];

$id_mail_p = $_POST['id_mail'];

$_SESSION['usuario'] = $usuario;

include('../funciones-sc/conexion.php');



$claveE=SED::encryption($clave);//encriptamos la clave ingresada por el usuario en el input de texto



$consulta = "SELECT * FROM usuarios where usuario_mail='$usuario' and usuario_clave='$claveE' AND usuario_estado = '1'"; //$claveE , esta comparando con alguna clave = de la BD , por eso se cambio de $clave el cual tomaba el valor del input , por la claveE la cual es la encriptacion del input.

$resultado=mysqli_query($conexion, $consulta);//devuelve un valor de coincidencias

$row_usuario = mysqli_fetch_array($resultado);

$_SESSION['id'] = $row_usuario['usuario_id'];

$activa = $row_usuario['usuario_activo'];

$encript = $row_usuario['usuario_clave']; //tomamos la clave encriptada de la base de datos (cambiar_clave_proc)

$_SESSION['perfil'] = $row_usuario['perfil_id'];



$sql_periodo = "SELECT * FROM periodos WHERE periodo_activo = '1'";

$rs_periodo = mysqli_query($conexion, $sql_periodo);

$row_periodo = mysqli_fetch_array($rs_periodo);

$_SESSION['periodo'] = $row_periodo['periodo_periodo'];



if($row_usuario['usuario_id'] != ''){

	if($activa == '1'){  

		header("location:../admin-sc/cambiar_claveadm.php");

	}else{ 		

		if($encript == $claveE){

			if($id_mail_p!=''){

				$sql_pendiente = "SELECT * from cargas

				where carga_id = '$id_mail_p' and carga_estado = '1'";

                $rs_pendiente = mysqli_query($conexion, $sql_pendiente);

                $row_pendiente= mysqli_fetch_array($rs_pendiente);

                $curso = $row_pendiente['curso_asignatura_id'];

				header("location:../admin-sc/carga.php?id=".$curso.'&carga='.$id_mail_p);

			}else{
				if($row_usuario['usuario_id'] != 49 && $row_usuario['usuario_id'] != 50){
					header("location:../admin-sc/listado_cursos.php");
				}
				else
				{
					header("location:calendario_pruebas.php");
				}

			}

		}

	}	

}else{

       

	     echo "<script LANGUAGE='JavaScript'>

                window.alert('Contraseña erronea');

                window.location= '../admin-sc/index.php'

    </script>";

} 

/*mysqli_free_result($resultado);

mysqli_close($conexion);*/

?>