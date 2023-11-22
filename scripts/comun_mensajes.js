
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

function cargar_eliminar_solicitud(){
	document.eliminar_prestar.eliminar_solicitud_boton.focus();
}


function cargar_eliminar_prestar(){
	document.eliminar_prestar.eliminar_prestamo_boton.focus();
}

function verificar_prestar(){
	document.modificar_prestar.modificar_solicitud_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar(){
	document.modificar_prestar.modificar_solicitud_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}


function verificar_prestar_mod(){
	document.modificar_prestar.modificar_solicitud_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar_mod(){
	document.modificar_prestar.modificar_solicitud_boton.disabled=true;
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
		document.eliminar_prestar.eliminar_solicitud_boton.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_eliminar_prestamo_confirmar(){
	document.eliminar_prestar.eliminar_solicitud_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}


function eliminar_prestamo_confirmar_re(sex, que){
	if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
		document.eliminar_prestar.boton_eliminar_rechazada.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_eliminar_prestamo_confirmar_re(){
	document.eliminar_prestar.boton_eliminar_rechazada.disabled=true;
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

function confirmar_aceptar_solicitud(sex, que){
	var obsv= document.all["observacion"];
	var ver_antes= obsv.value;
	if(ver_antes == ""){
		alert("Debe escribir la observaci\u00F3n");
		return false;
	}
	else{
	if( confirm("Esta seguro de querer\nAceptar " +sex+" "+que) ){
		document.formulario.boton_aceptar_por_conf_arch.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
	}
}
function des_confirmar_aceptar_solicitud(){
	document.formulario.boton_aceptar_por_conf_arch.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}




function confirmar_retornar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nRetornar " +sex+" "+que) ){
		document.formulario.boton_retornar_conf_arch.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_retornar_solicitud(){
	document.formulario.boton_retornar_conf_arch.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_retornar_solicitud_mod(sex, que){
	if( confirm("Esta Seguro de querer\nRetornar " +sex+" "+que) ){
		document.formulario.boton_modificar_retorno.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_retornar_solicitud_mod(){
	document.formulario.boton_modificar_retorno.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}



function confirmar_devolver(sex, que){
	if( confirm("Esta Seguro de querer\nDevolver " +sex+" "+que+"\nA su Propietario") ){
		document.formulario.boton_devolver_propietario.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_devolver(){
	document.formulario.boton_devolver_propietario.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}


function confirmar_adjudicarse(sex, que){
	if( confirm("Esta Seguro de querer\nAdjudicarse " +sex+" "+que+"\nPara el Banco") ){
		document.formulario.boton_adjudicarse_carpeta.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_confirmar_adjudicarse(){
	document.formulario.boton_adjudicarse_carpeta.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}