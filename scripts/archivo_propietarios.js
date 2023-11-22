
function cargar_adicionar(){
	document.adicionar.nombres.focus();
}

function cargar_modificar(){
	document.modificar.nombres.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function cargar_buscar(){
	document.buscar.filtro_nombres.focus();
}

function verificar_adicion(form){

	//nombres
	var mensaje= "Debe escribir un Nombre";
	var antes= document.all["nombres"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	
	var mis_trim= document.all["mis"].value;
	var mis_poner= document.all["mis"];
	var dere= TrimLeft(mis_trim);
	var izq= TrimRight(dere);
	mis_poner.value=izq;
	//return false;
	
	document.adicionar.adicionar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	return true;
}


function des_verificar_adicion(){
	document.adicionar.adicionar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	return true;
}



function TrimRight(str) {
	var resultStr = "";
	var i = 0;

	// Return immediately if an invalid value was passed in
	if (str+"" == "undefined" || str == null)	
		return null;

	// Make sure the argument is a string
	str += "";
	
	if (str.length == 0) 
		resultStr = "";
	else {
  		// Loop through string starting at the end as long as there
  		// are spaces.
  		i = str.length - 1;
  		while ((i >= 0) && (str.charAt(i) == " "))
 			i--;
 			
 		// When the loop is done, we're sitting at the last non-space char,
 		// so return that char plus all previous chars of the string.
  		resultStr = str.substring(0, i + 1);
  	}
  	
  	return resultStr;  	
}

function TrimLeft(str){
	var resultStr = "";
	var i = len = 0;

	// Return immediately if an invalid value was passed in
	if (str+"" == "undefined" || str == null)	
		return null;

	// Make sure the argument is a string
	str += "";

	if (str.length == 0) 
		resultStr = "";
	else {	
  		// Loop through string starting at the beginning as long as there
  		// are spaces.
//	  	len = str.length - 1;
		len = str.length;
		
  		while ((i <= len) && (str.charAt(i) == " "))
			i++;

   	// When the loop is done, we're sitting at the first non-space char,
 		// so return that char plus the remaining chars of the string.
  		resultStr = str.substring(i, len);
  	}

  	return resultStr;
}






function verificar_adicion_mod(){
	//nombres
	var mensaje= "Debe escribir un Nombre";
	var antes= document.all["nombres"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	/*
	//mis
	var mensaje= "Debe escribir un MIS";
	var antes= document.all["mis"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	

	//telefonos
	var mensaje= "Debe escribir un Tel\u00E9fono";
	var antes= document.all["telefonos"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	
	//direccion
	var mensaje= "Debe escribir una Direcci\u00F3n";
	var antes= document.all["direccion"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	
	var mis_trim= document.all["mis"].value;
	var mis_poner= document.all["mis"];
	var dere= TrimLeft(mis_trim);
	var izq= TrimRight(dere);
	mis_poner.value=izq;
	//return false;
*/
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}
function des_verificar_adicion_mod(){
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}




function eliminar_confirmar(sex, que, puede_eliminar){
	//motivo
	var mensaje= "Debe escribir un motivo";
	var antes= document.all["motivo"];
	if(antes.value == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}
		if( confirm("Esta seguro(a) de proceder? " ) ){
			document.eliminar.eliminar_boton.disabled=true;
			document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
			return true;
		}
		else{
			return false;
		}

}
function des_eliminar_confirmar(){
	document.eliminar.eliminar_boton.disabled=true;
	document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
}


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