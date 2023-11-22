/************************************************************************************/
/************************************************************************************/
/************************************************************************************/
/************************************************************************************/

function cargar_formulario(){
	document.formulario.inf_nro_esc.focus();
}


function validar(){
	
	if( confirm("Est\u00E1 seguro de guardar los cambios al informe final?") ){
		if(document.all["estado_formulario"].value == "guardar"){
			document.formulario.btn_inf_final.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
		}
		return true;
	}
	else{
		return false;
	}
}

