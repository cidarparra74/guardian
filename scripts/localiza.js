
function cargar_adicionar(){
	document.adicionar.depto.focus();
}

function cargar_modificar(){
	document.modificar.depto.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function verificar_adicion(){
	
	return true;
}

function eliminar_confirmar(sex, que){

		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
			return true;
		}
		else{
			return false;
		}

}