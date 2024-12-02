function validator(){
	var elementoperiodo = document.getElementById ('periodo');

	if(elementoperiodo.value==""){
		alert("Periodo(año) no puede estar vacio");
		return false;
		}else if(elementoperiodo.value== "script"|| elementoperiodo.value=="<script>"||elementoperiodo.value=="<SCRIPT>"||elementoperiodo.value=="SCRIPT"){
		alert("ERROR,Insercion maliciosa detectada");
		return false;
	}else if (elementoperiodo.value.length>4 || elementoperiodo.value.length<4){
		alert("Largo invalido , 4 caracteres obligatorios");
		return false;
	}else if (elementoperiodo.value<2018){
		alert("Error , no puede ingresar años menores a 2018");
		return false;
	}
	return true
}