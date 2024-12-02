function validator(){
	var elementonombre = document.getElementById("nombre");
	var elementofecha = document.getElementById("datepicker");

	if(elementonombre.value==""){
		alert("Nombre no puede estar vacio");
		return false;
	}
	if(elementonombre.value.length<3){
		alert("Nombre debe tener minimo 3 caracteres");
		return false;
	}else if(elementonombre.value== "script"|| elementonombre.value=="<script>"||elementonombre.value=="<SCRIPT>"||elementonombre.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(nombre)");
		return false;
	}else if(elementofecha.value=="script"||elementofecha.value=="<script>"||elementofecha.value=="<SCRIPT>"||elementofecha.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(FechadeNac)");
		return false;
	}
	return true;
}