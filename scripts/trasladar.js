

function confirmar(form){
	if(form.id_oficina.value == "0"){
		alert("No ha seleccionado la oficina destino!");
		return false;
	}
	else{
		if( confirm("Esta Seguro de realizar el traslado? " ) ){
			return true;
		}
		else{
			return false;
		}
	}
}