<?php 

$perfil_archivo = 2;//adm = 1 , docente = 2;

include('../funciones-sc/conexion.php');



$data = $_GET['data'];

if($data == ''){ $filtro_profesor = ' '; }

else{ $filtro_usuario = " AND (usuario_nombres LIKE '%".$data."%' OR usuario_apellidos LIKE '%".$data."%') "; }

?>

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

     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    

    <?php 

        include('../fonts/fonts.php'); 

        include('../js-sc/bootstrap.php'); 

    ?>

    <script src="../js-sc/validar_caracteres.js"></script>

</head>

<body>

  

        <div class="contenedor-100">

        <?php include('menu-lateral.php'); ?>

     

<!--<style>

.perfil-msg{

    width: calc(100% - 360px);

    float: left;

    background: #ececec;

  flex: 1;

  margin-top: -5px;

}



.contenedor-90 input[type='submit']{

  padding: 6px 10px;

  text-align: center;

  width: 150px;

  float: none;

  margin: 20px 0;

  border: none;

  border-radius: 30px;

  background: #000;

  color: #fff;

  cursor: pointer;

}



        </style> -->



        <!-- Page Content -->

            <div class="perfil-der">

            

                

                         

                         <br>

                        <h1>MENSAJES</h1>

                         <div class="filtros">

                            <form action="" method="GET">

                                <input type="text" name="data" placeholder="Ingrese Nombre o Apellido" onkeyup="reemplazar(this);">

                                <input type="submit" value="BUSCAR">

                            </form>                            

                        </div>

                        <table class="header2">

                            <tr>

                              <th class="back-fff"></th>

                              <th class="back-fff"></th>

                              <th colspan="2">Redactar nuevo mensaje</th>

                              <th><a href='mensaje_nuevo.php'><i class='fas fa-plus fa-lg'></i></a></th>

                            </tr>

                            <tr>  

                                <th>REMITENTE</th>

                                <th>FECHA</th>

                                <th>CONTENIDO</th> 

                                <th>ARCHIVO</th>

                                <th>RESPONDER</th>

                              </tr>

                         <?php 

                       $sql = "SELECT * 

                                FROM mensajes as m 

                                join profesores as p on m.profesor_id = p.profesor_id

                                join usuarios as u on m.usuario_id = u.usuario_id

                                WHERE m.mensaje_estado = '1' AND m.profesor_id = $user_id <> '0' $filtro_usuario 

                                ORDER BY mensaje_fecha DESC";



                        $rs = mysqli_query($conexion, $sql);

                        while($row = mysqli_fetch_array($rs))

                        {

                                   echo "<tr>";

                                    if($row['mensaje_emisor'] == '0'){

                                        echo" <td>".$row['profesor_nombres']." ".$row['profesor_apellidos']."</td>";

                                    }else{

                                        echo" <td>".$row['usuario_nombres']." ".$row['usuario_apellidos']."</td>";

                                    } 

                                    echo"<td>".$row['mensaje_fecha']."</td>   

                                    <td style='width:450px;'>".$row['mensaje_cuerpo']."</td>";



                                    if($row['mensaje_archivo']=="") {

                                        echo "<td> -- </td>";

                                    }else{

                                        echo "<td><a href='../profesor-sc/".$row['mensaje_archivo']."'><i class='fas fa-search fa-lg'></i></a></td>";

                                    }

                                  echo "<td><a href='mensaje_responder.php?id=".$row['mensaje_id']."'><i class='fas fa-search fa-lg'></i></a></td></tr>";

                        }

                        ?>

                        </table>

               

            </div>

  </div>

</body>

</html>