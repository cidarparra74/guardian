function cargar_adicionar(){
	document.adicionar.nombre.focus();
}

function cargar_modificar(){
	document.modificar.nombre.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function verificar_adicion(){
	
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