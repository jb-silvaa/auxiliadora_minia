function reemplazar(cadena)
{	
	cadena.value = cadena.value.replace(/[><$'=#"]/gi,'');
	//document.getElementById(opcion).value = cadena;
}