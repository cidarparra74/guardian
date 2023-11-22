function validar(form) {

	if(form.PWactual!=null){
		if (!novacio(form.PWactual,form.PWactual.value.length,1,"la contrase\u00F1a actual, ")){return false;}	
	}
	
	if(form.passmod!=null){
		if (!novacio(form.passmod,form.passmod.value.length,1,"la contrase\u00F1a, ")){return false;}	
	}
	if(form.confirmamod!=null){
		if (!novacio(form.confirmamod,form.confirmamod.value.length,1,"la confirmaci\u00F3n de la contrase\u00F1a, ")){return false;}	
	}
	
	if(form.passmod!=null){		
		if(form.confirmamod!=null){
		if (form.passmod.value != form.confirmamod.value) {
			alert("Las contrase\u00F1a y la confirmaci\u00F3n de la misma, no son iguales, ");
			return false;}
		}
	}
	
	// campos requeridos que no esten vacios
	
	if (!novacio(form.USlogin,form.USlogin.value.length,1,"el nombre de usuario, ")){return false;}
	if (!novacio(form.password,form.password.value.length,1,"la contrase\u00F1a, ")){return false;}
	if (!novacio(form.confirma,form.confirma.value.length,1,"la confirmaci\u00F3n de la contrase\u00F1a, ")){return false;}
	if (form.password.value != form.confirma.value) {
			alert("Las contrase\u00F1a y la confirmaci\u00F3n de la misma, no son iguales, ");
			return false;}
	if (!novacio(form.nombre,form.nombre.value.length,1,"el nombre, ")){return false;}
	
}
