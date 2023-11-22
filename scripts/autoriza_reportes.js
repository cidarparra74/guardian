
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
		return true;
	}
	else{
		return false;
	}
}

function confirmar_rechazar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nRechazar " +sex+" "+que) ){
		return true;
	}
	else{
		return false;
	}
}

function confirmar_enviar_todos(sex, que){
	if( confirm("Esta Seguro de querer\nEnviar " +sex+" "+que) ){
		return true;
	}
	else{
		return false;
	}
}

function confirmar_modificar_solicitud(sex, que){
	if( confirm("Esta Seguro de querer\nModificar " +sex+" "+que) ){
		return true;
	}
	else{
		return false;
	}
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

function marcando_todos(valor, cantidad){
	//alert(valor.checked);
	var che= valor.checked;
	var canti= parseInt(cantidad.value);
	if(che){
		for(i=1; i<=canti; i++){
			aux= "marcado_"+i;
			//alert(aux);
			var acc=document.all[aux];
			acc.checked=true;
		}
	}
	else{
		for(i=1; i<=canti; i++){
			aux= "marcado_"+i;
			var acc=document.all[aux];
			acc.checked=false;
		}
	}
}