
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

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function cargar_eliminar_prestar(){
	document.eliminar_prestar.eliminar_prestamo_boton.focus();
}

function verificar_prestar(){}

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
		return true;
	}
	else{
		return false;
	}
}

function confirmar_aceptar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nAceptar " +sex+" "+que) ){
		document.formulario.boton_aceptar_solicitud.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_aceptar_solicitud(){
	document.formulario.boton_aceptar_solicitud.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_rechazar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nRechazar " +sex+" "+que) ){
		document.formulario.boton_rechazar_solicitud.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_rechazar_solicitud(){
	document.formulario.boton_rechazar_solicitud.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_enviar_todos(sex, que){
	if( confirm("Esta Seguro de querer\nEnviar " +sex+" "+que) ){
		document.formulario.boton_aceptar_todos.disabled=true;
		document.formulario.boton_rechazar_todos.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_enviar_todos(){
	document.formulario.boton_aceptar_todos.disabled=true;
	document.formulario.boton_rechazar_todos.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_modificar_solicitud_mod(sex, que){
	if( confirm("Esta Seguro de querer\nModificar " +sex+" "+que) ){
		document.formulario.boton_aceptar_solicitud.disabled=true;
		document.formulario.boton_rechazar_solicitud.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_modificar_solicitud_mod(){
	document.formulario.boton_aceptar_solicitud.disabled=true;
	document.formulario.boton_rechazar_solicitud.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_modificar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nModificar " +sex+" "+que) ){
		document.formulario.boton_eliminar_envio.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_modificar_solicitud(){
	document.formulario.boton_eliminar_envio.disabled=true;
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

function marcando_todos(valor, cantidad, num_form){
	//alert(num_form);
	var che= valor.checked;
	var canti= parseInt(cantidad.value);
	if(che){
		for(i=1; i<=canti; i++){
			aux= "marcado_"+i+"_"+num_form;
			//alert(aux);
			var acc=document.all[aux];
			if(acc != null){
				acc.checked=true;
			}
		}
	}
	else{
		for(i=1; i<=canti; i++){
			aux= "marcado_"+i+"_"+num_form;
			var acc=document.all[aux];
			if(acc != null){
				acc.checked=false;
			}
		}
	}
}

function marcando_todos2(valor){
	//alert(num_form);
	var che= valor.checked;
	cantidad=parseInt(document.all["cantidad_total"].value);
	if(che){
		for(i=0; i<cantidad; i++){
			var aux= "marcado_"+i;
			var marca= document.all[aux];
			marca.checked = true;
		}
	}else{
		for(i=1; i<=cantidad; i++){
			aux= "marcado_"+i;
			var acc=document.all[aux];
			if(acc != null){
				acc.checked=false;
			}
		}
	}
}
//deshabilitando el boton de envio en grupo, en lista de mensajes
function des_mandar_ac_todos(form){
	form.mandar_todos.disabled=true;
	//return false;
	//document.modificar_prestar.modificar_prestamo_boton.disabled=true;
}

function des_acc(campo){
	//alert(campo.);
	//var ac= document.all["campo"];
	//ac.value="jjj";
	campo.value="jjj";
}