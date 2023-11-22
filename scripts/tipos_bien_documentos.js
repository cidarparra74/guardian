
function deshabilitar(valor){
	if(valor.value == "si"){
		document.modificar.password.disabled=true;
		document.modificar.password_nuevo.disabled=false;	
		document.modificar.password_nuevo.value="";
		document.modificar.password_nuevo.focus();
	}
	else{
		document.modificar.password.disabled=false;
		document.modificar.password_nuevo.value="";
		document.modificar.password_nuevo.disabled=true;
	}
}

function cambiar_bien(tiene, tipo, estado, excepcion){
	var ver;
	ver= tiene.checked;
	//alert(ver);
	if(!ver == true){ //no marcado
		//alert("entra........");
		tipo.disabled=true;
		estado.value="";
		estado.disabled=true;
	}
	else{ //marcado
		//alert("no entra......");
		tipo.disabled=false;
		estado.disabled=false;
		excepcion.checked=false;
	}
}

function cambiar_bien_excepcion(tiene, tipo, estado, excepcion){
	var ver;
	ver= excepcion.checked;
	//alert(ver);
	if(!ver == true){ //no marcado
		tipo.disabled=true;
		estado.value="";
		estado.disabled=true;
		
	}
	else{ //marcado
		tipo.disabled=false;
		estado.disabled=false;
		tiene.checked=false;
	}
}

function des_documentos(){
	document.documentos.adicionar.disabled=true;
}

function validar_numero(campo, maximo){
	maximo_valor= parseInt(maximo);
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0"){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	if(parseFloat(nuevo_valor)>maximo_valor){
		alert("El puntaje no debe ser mayor a : " + maximo);
		campo.value="";
		campo.focus();
		return false;
	}
	else{
		campo.value=nuevo_valor;
	}
}