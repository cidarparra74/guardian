

function cargar_buscar(){
	document.buscar.filtro_nombres.focus();
}

function verificar_buscar(form){

	//nombres
	var nomb= form.filtro_nombres;
	var ci  = form.filtro_ci;
	var caso= form.filtro_ncaso;
	if(nomb.value == "" && ci.value == "" && caso.value == ""){
		alert("Debe ingresar un criterio de b\u00FAsqueda.");
		nomb.focus();
		return false;
	}
	if((nomb.value != "" || ci.value != "") && caso.value != ""){
		alert("Debe ingresar solo un criterio de b\u00FAsqueda.");
		nomb.focus();
		return false;
	}
	return true;
}

