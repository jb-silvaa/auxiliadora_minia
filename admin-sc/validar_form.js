function validator(){

	var elementonombre = document.getElementById("nombre");

	var elementoapellido= document.getElementById("apellido");

	var elementorut = document.getElementById("rut");

	var elementofecha = document.getElementById("datepicker");

	var elementocorreo = document.getElementById("correo");

	var elementocorreop = document.getElementById("correo_p");

	var elementotelefono = document.getElementById("telefono");

	var signos = /\w+\w+\.+[a-z]/;



	if(elementonombre.value==""){

		alert("Nombre no puede estar vacio");

		return false;

	}else if (elementoapellido.value==""){

		alert("Apellido no puede ser vacio");

		return false;

	}else if (elementorut.value==""){

		alert("Rut no puede estar vacio");

		return false;



	}else if (elementocorreo.value==""){

		alert("Correo no puede estar vacio");

		return false;

	}else if (elementotelefono.value==""){

		alert("Telefono no puede estar vacio");

		return false;

	}

	if(elementonombre.value.length<3){

		alert("Nombre debe tener minimo 3 caracteres");

		return false;

	}else if(elementoapellido.value.length<4){

		alert("Apellido debe tener minimo 4 caracteres");

		return false; 

	}else if(elementorut.value.length<8 || elementorut.value.length>9){

		alert("Rut debe tener minimo 8 caracteres y maximo 9");

		return false; 



	}else if(elementotelefono.value.length>13|| elementotelefono.value.length<9){

		alert("Telefono no puede tener mas de 13 caracteres o menos de 9");

		return false;

	}

	else if(!signos.test(elementocorreo.value)){

		alert("El correo no es valido");

		return false;

	}else if(elementonombre.value== "script"|| elementonombre.value=="<script>"||elementonombre.value=="<SCRIPT>"||elementonombre.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(nombre)");

		return false;

	}

	if(elementoapellido.value== "script"|| elementoapellido.value=="<script>"||elementoapellido.value=="<SCRIPT>"||elementoapellido.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(apellido)");

		return false;

	}else if(elementorut.value=="script"||elementorut.value=="<script>"||elementorut.value=="<SCRIPT>"||elementorut.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(rut)");

		return false;

	}else if(elementofecha.value=="script"||elementofecha.value=="<script>"||elementofecha.value=="<SCRIPT>"||elementofecha.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(FechadeNac)");

		return false;

	}else if(elementocorreo.value=="script"||elementocorreo.value=="<script>"||elementocorreo.value=="<SCRIPT>"||elementocorreo.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(CorreoInstitucional)");

		return false;

	}else if(elementocorreop.value=="script"||elementocorreop.value=="<script>"||elementocorreop.value=="<SCRIPT>"||elementocorreop.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(Correopersonal)");

		return false;

	}/*else if(signos.test(elementocorreop.value)){

      alert("Correo Invalido");

      return false;

	}*/else if(elementotelefono.value=="script"||elementotelefono.value=="<script>"||elementotelefono.value=="<SCRIPT>"||elementotelefono.value=="SCRIPT"){

		alert("ERROR,Insercion maliciosa detectada(telefono)");

		return false;

	}

	return true;

}