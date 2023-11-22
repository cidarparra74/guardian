
function verificasel(form) {
	
	if(form.linea != undefined){
		if(form.linea.value == '-')
			if(!confirm("No ha seleccionado ninguna l\u00EDnea, seguro de proseguir?"))
				return false;
	}
	valido=false;
	for(a=0; a<form.elements.length; a++){
		if(form[a].type=="checkbox" && form[a].checked==true){
			valido=true;
			break
		}
	}
	if(!valido){
		alert("Marque las casillas correspondientes!");
		return false
	}
	return true;
}
