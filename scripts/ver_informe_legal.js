
function cargar_formulario(){
	document.formulario.cliente.focus();
}

function cargar_modificar(){
	document.modificar.tipo_carpeta.focus();
}

function cargar_prestar(){
	document.prestar.usuario.focus();
}

function cargar_modificar_prestar(){
	document.modificar_prestar.usuario.focus();
}

function cargar_eliminar(){
	document.formulario.eliminar_boton.focus();
}

function cargar_eliminar_prestar(){
	document.eliminar_prestar.eliminar_prestamo_boton.focus();
}


function verificar_prestar_mod(){
	document.modificar_prestar.modificar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar_mod(){
	document.modificar_prestar.modificar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}


//solicitud de informe legal
function adicionar_solicitud(){
	document.formulario.solicitar.disabled=true;
}




function verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}



function verificar_formulario(){
	
	var antes = document.all["tipo_bien"];
	if(antes.value == "--"){
		alert("Debe elegir el tipo de garant\u00EDa.");
		antes.focus();
		return false;
	}
	//para el nro de bien
	var antes = document.all["nrobien"];
	if(antes.value == ""){
		alert("Debe indicar el n\u00FAmero de bien!");
		antes.focus();
		return false;
	}
	//verificar que no exista el nro
	
	
	//if(document.all["estado_formulario"].value == "adicionar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	//}
	
	return true;
	
}
function des_verificar_formulario(){
	if(document.all["estado_formulario"].value != "eliminar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	/*
	if(document.all["estado_formulario"].value == "modificar"){
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	*/
	if(document.all["estado_formulario"].value == "eliminar"){
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
	return true;
}


function verificar_adicion_mod(){
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}
function des_verificar_adicion_mod(){
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}

function mover_confirmar(){
		if( confirm("Esta Seguro de querer Mover \na la bandeja de recepcionados?" ) ){
			document.formov.mover_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
			return true;
		}else{
			return false;
		}
}

function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
			document.formulario.eliminar_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
			return true;
		}
		else{
			return false;
		}
	}
	else{
		alert("No puede eliminar " +sex+" "+que +"\nPorque existen registros asociados");
		return false;
	}
}

function des_eliminar_confirmar(){
	document.eliminar.eliminar_boton.disabled=true;
	document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
}


function eliminar_prestamo_confirmar(sex, que){
	if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
		document.eliminar_prestar.eliminar_prestamo_boton.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_eliminar_prestamo_confirmar(){
	document.eliminar_prestar.eliminar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
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

function validar_bien(){
	//para ver tipo de i.l. a adicionar
	var antes = document.all["acc_tipo_bien"];
	if(antes.value == "ninguno"){
		alert("Debe elegir el tipo de bien para el nuevo I.L.");
		antes.focus();
		return false;
	}
	return true;
}