function validacion(){
	var usuario = document.getElementById('usuariobox').value; //obtenemos los valores de los input por su ID
	var clave = document.getElementById('clavebox').value;

	if(usuario == ""){
		alert("Usuario es obligatiorio");
		return false;
	}
	if(clave == ""){
		alert("Contraseña es obligatiorio");
		return false;
	}


}