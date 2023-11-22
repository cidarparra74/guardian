
function cargar_adicionar(){
	document.adicionar.tipo_carpeta.focus();
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

function cargar_modificar_envio(){
	document.modificar_prestar.usuario.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function cargar_eliminar_prestar(){
	document.eliminar_prestar.eliminar_prestamo_boton.focus();
}

function cargar_eliminar_envio(){
	document.eliminar_prestar.eliminar_envio_boton.focus();
}

function verificar_prestar(){
	document.modificar_prestar.modificar_envio_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}

function des_verificar_prestar(){
	document.modificar_prestar.modificar_envio_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}

function verificar_adicion(){
	/*
	var antes_espacio= nuevo.value;
	var ac= antes_espacio.replace(/ /g, "");
	
	var grados= new Array();
	var dividir= existen.value;
	grados = dividir.split(";");
	var i=0;
	
	if(ac == ""){
		alert("Escriba un"+sex+" "+ mensaje);
		//document.adicionar.item_adicion.focus();
		return false;
	}

	var nuevo_grado= new String("");	
	nuevo_grado= ac.toLowerCase();
	
	var comparacion= new String("");
	while(grados[i] != ""){
		comparacion= grados[i].toLowerCase();
		sin_espacio= comparacion.replace(/ /g, "");

		if(nuevo_grado == sin_espacio){
			alert("ya existe un"+sex+" "+mensaje + " con el mismo nombre\nEscriba otro nombre");
			//document.adicionar.item_adicion.focus();
			return false;
		}
		i++;
	}
	//para los dias
	var antes_direccion= direccion.value;
	var ver_direccion= antes_direccion.replace(/ /g, "");
	if(ver_direccion == ""){
		alert("Debe escribir una direcci\u00F3n");
		//document.adicionar.item_dias.focus();
		return false;
	}
	*/
	return true;
}

function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
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


function eliminar_prestamo_confirmar(sex, que){
	if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
		document.eliminar_prestar.eliminar_envio_boton.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}

function des_eliminar_prestamo_confirmar(){
		document.eliminar_prestar.eliminar_envio_boton.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}


function confirmar_quitar_lista(sex, que){
	if( confirm("Esta Seguro de querer\nQuitar " +sex+" "+que) ){
		document.formulario.boton_eliminar_en_archivo.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}

function des_confirmar_quitar_lista(){
		document.formulario.boton_eliminar_en_archivo.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}


function confirmar_quitar_lista1(sex, que){
	if( confirm("Esta Seguro de querer\nQuitar " +sex+" "+que) ){
		document.formulario.boton_eliminar_devuelta_cliente.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}

function des_confirmar_quitar_lista1(){
		document.formulario.boton_eliminar_devuelta_cliente.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}


function confirmar_quitar_lista2(sex, que){
	if( confirm("Esta Seguro de querer\nQuitar " +sex+" "+que) ){
		document.formulario.boton_eliminar_adjudicada.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_quitar_lista2(){
		document.formulario.boton_eliminar_adjudicada.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
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

function confirmar_aceptar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nAceptar " +sex+" "+que) ){
		document.formulario.boton_aceptar_sol_auto_arch.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}

function des_confirmar_aceptar_solicitud(){
		document.formulario.boton_aceptar_sol_auto_arch.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}


function confirmar_modificar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nModificar " +sex+" "+que) ){
		document.formulario.boton_modificar_sol_auto_arch.disabled=true;
		document.formulario_cancelar.boton_cancelar_sol_auto_arch.disabled=true;
		return true;
	}
	else{
		return false;
	}
	
}

function confirmar_guardar_carpeta(sex, que){
	if( confirm("Esta Seguro de querer\nGuardar " +sex+" "+que) ){
		document.formulario.boton_confirmar_retorno.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}

function des_confirmar_guardar_carpeta(){
		document.formulario.boton_confirmar_retorno.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}


function des_confirmar_modificar_solicitud(){
	document.formulario.boton_modificar_sol_auto_arch.disabled=true;
	document.formulario_cancelar.boton_cancelar_sol_auto_arch.disabled=true;	
}
