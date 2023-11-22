
function cargar_adicionar(){
	document.adicionar.tramitador.focus();
}

function cargar_modificar(){
	document.modificar.tramitador.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function verificar_adicion(){
	var mensaje= "Debe escribir un tramitador";
	var mensaje_igual= "Ya existe un tramitador con este nombre\nEscriba otro tramitador";
	var antes= document.all["tramitador"];
	var antes_espacio= antes.value;
	
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
	nuevo_grado= ac.toLowerCase();
	
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