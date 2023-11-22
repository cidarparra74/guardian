

function confirmar(form){
	if(form.id_usuario.value == "0"){
		alert("No ha seleccionado el usuario destino!");
		return false;
	}
	else{
		if( confirm("Esta seguro de realizar el traspaso? " ) ){
			return true;
		}
		else{
			return false;
		}
	}
}

function enviarfrm(){
	document.buscar.submit();
	return true;
}