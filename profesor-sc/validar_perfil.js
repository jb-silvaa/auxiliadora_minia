function validator(){
	var elementofecha = document.getElementById("datepicker");
	var elementocorreo = document.getElementById("correo");
	var elementotelefono = document.getElementById("telefono");
	var signos = /\w+@\w+\.+[a-z]/;

	 if (elementocorreo.value==""){
		alert("Correo no puede estar vacio");
		return false;
	}else if (elementotelefono.value==""){
		alert("Telefono no puede estar vacio");
		return false;
	}else if(elementotelefono.value.length>13|| elementotelefono.value.length<9){
		alert("Telefono no puede tener mas de 13 caracteres o menos de 9");
		return false;
	}else if(!signos.test(elementocorreo.value)){
		alert("El correo no es valido");
		return false;
	}else if(elementofecha.value=="script"||elementofecha.value=="<script>"||elementofecha.value=="<SCRIPT>"||elementofecha.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(FechadeNac)");
		return false;
	}else if(elementocorreo.value=="script"||elementocorreo.value=="<script>"||elementocorreo.value=="<SCRIPT>"||elementocorreo.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(CorreoInstitucional)");
		return false;
	}else if(elementotelefono.value=="script"||elementotelefono.value=="<script>"||elementotelefono.value=="<SCRIPT>"||elementotelefono.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada(telefono)");
		return false;
	}
	return true;
}