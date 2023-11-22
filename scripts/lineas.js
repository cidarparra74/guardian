function cargar(){
	document.frmdatos.numero.focus();
}

function verificar_datos(form){
	if (!novacio(form.numero,form.numero.value.length,1,"el n\u00FAmero de l\u00EDnea, ")){return false;}
	if (!novacio(form.importe,form.importe.value.length,1,"el importe de la l\u00EDnea, ")){return false;}
	if (!novacio(form.escritura,form.escritura.value.length,1,"el n\u00FAmero de escritura, ")){return false;}
	if (!novacio(form.fecha,form.fecha.value.length,1,"la fecha de la escritura, ")){return false;}
	if (!novacio(form.notario,form.notario.value.length,1,"el nombre del notario, ")){return false;}
	if (!esFechaValida(form.fecha)){
		form.fecha.focus();
		return false;
	}
	return true;
}

function eliminar_confirmar(puede_eliminar){
	if(puede_eliminar.value == "s"){
		if( confirm("Est\u00E1 seguro de querer eliminar esta l\u00EDnea?") ){
			return true;
		}else{
			return false;
		}
	}else{
		alert("No se puede eliminar porque existen registros asociados");
		return false;
	}
}