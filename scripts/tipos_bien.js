
function cargar_adicionar(){
	document.adicionar.tipo.focus();
}

function cargar_modificar(){
	document.modificar.tipo.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function verificar_adicion(){
	
	var mensaje= "Debe escribir un tipo de bien";
	var mensaje_igual= "Ya existe un tipo de bien con este nombre\nEscriba otro nombre";
	var antes= document.all["tipo"];
	var antes_espacio= antes.value;
	var bca= document.all["id_banca"].value;
	
	if(antes_espacio==""){
		alert(mensaje);
		antes.focus();
		return false;
	}
	
	var ac= antes_espacio.replace(/ /g, "");
	
	var grados= new Array();
	var dividir= document.all["existentes"].value;
	grados = dividir.split(";");
	var i=0;
	
	if(ac == ""){
		alert(mensaje);
		antes.focus();
		return false;
	}

	var nuevo_grado= new String("");	
	nuevo_grado= ac.toLowerCase() + bca;
	
	var comparacion= new String("");
	while(grados[i] != ""){
		comparacion= grados[i].toLowerCase();
		sin_espacio= comparacion.replace(/ /g, "");

		if(nuevo_grado == sin_espacio){
			alert(mensaje_igual);
			antes.focus();
			return false;
		}
		i++;
	}
	
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

function deshabilita(chk, sel){
	var  opc = sel.options[sel.selectedIndex].value;
	var chk = document.getElementById(chk);
	if(opc=="xx"){
		chk.disabled=false;
	}else{
		chk.checked=false;
		chk.disabled=true;
	}
}