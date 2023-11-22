
function des_formulario(){
	document.formulario.boton_regularizar.disabled=true;
	document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
}

function verificar_formulario(){
	var obs= document.all["observacion"];
	if(obs.value==""){
		alert("Debe escribir la observaci\u00F3n de regularizaci\u00F3n");
		obs.focus();
		return false;
	}

	if( confirm("Esta seguro de querer regularizar este documento") ){
		document.formulario.boton_regularizar.disabled=true;
		document.formulario_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
	
}
