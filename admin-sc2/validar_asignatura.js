function validator(){
	var elementonombre = document.getElementById("nombre");
	var elementocode= document.getElementById("code");

	if(elementonombre.value==""){
		alert("Nombre no puede estar vacio");
		return false;
	}else if (elementocode.value==""){
		alert("codigo no puede ser vacio");
		return false;
}
	if(elementonombre.value.length<4){
		alert("Nombre debe tener minimo 4 caracteres");
		return false;
	}else if(elementocode.value.length<2){	
		alert("codigo debe tener minimo 2 caracteres");
		return false; 
	}else if(elementonombre.value== "script"|| elementonombre.value=="<script>"||elementonombre.value=="<SCRIPT>"||elementonombre.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(nombre)");
		return false;
	}else if(elementocode.value== "script"|| elementocode.value=="<script>"||elementocode.value=="<SCRIPT>"||elementocode.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(codigoAsig)");
		return false;
	}
	return true;
}