 <?php     
     
function formateo_rut($rut_param){ 
     
    $parte4 = substr($rut_param, -1); //digito verificador 
    $parte3 = substr($rut_param, -4,3); // desde el final al inicio 
    $parte2 = substr($rut_param, -7,3);  
     $parte1 = substr($rut_param, 0,-7); //todos los caracteres desde el 8 hacia el inicio 
    return $parte1.".".$parte2.".".$parte3."-".$parte4; 

}
?>