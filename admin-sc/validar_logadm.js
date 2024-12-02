function validacionadm(){
	var usuario = document.getElementById("usuario"); //obtenemos los valores de los input por su ID
	var clave = document.getElementById("clave");

	if(usuario.value == ""){
		alert("Usuario es obligatiorio");
		return false;
	}else if(usuario.value== "SCRIPT"|| usuario.value== "script"||usuario.value == "<SCRIPT>"|| usuario.value == "<script>"){
		alert("Error, Codigo malicioso detectado(usuario)");
		return false;
	}
	if(clave.value == ""){
		alert("Contraseña es obligatiorio");
		return false;
	}else if(clave.value=="SCRIPT"|| clave.value== "script"||clave.value == "<SCRIPT>"|| clave.value == "<script>"){
		alert("Error, Codigo malicioso detectado(clave)");
		return false;
	}


}