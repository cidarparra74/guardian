function cargar_formulario(){
	document.formulario.cliente.focus();
}


function verificar_formulario(){
	
	if( confirm("Esta seguro de autorizar la solicitud?") ){
			document.formulario.aceptar_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
		
		return true;
	}
	else{
		return false;
	}
}

function des_verificar_formulario(){
	if(document.all["estado_formulario"].value == "aceptar"){
		document.formulario.aceptar_boton.disabled=true;
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