
function validar(form) {
	
	// campos requeridos que no esten vacios
	if (!novacio(form.empresa,form.empresa.value.length,1,"un nombre para la empresa")){return false;}
	if (!novacio(form.rubro,form.rubro.value.length,1,"el rubro")){return false;}
	if (!novacio(form.direccion,form.direccion.value.length,1,"la direcci�n")){return false;}
	if (!novacio(form.nit,form.nit.value.length,1,"el NIT")){return false;}
	
	//  que no contengan valores invalidos
	//if (!letras(form.empresa)){return false;}  //solo letras
	
	if(form.rubro.value!=''){  // ayuda a revisar si se tiene algo escrito  y recien revisa el formato
		if (!letras(form.rubro)){return false;}
	}
	if(form.nit.value!=''){  // ayuda a revisar si se tiene algo escrito  y recien revisa el formato
		if (!numeros(form.nit)){return false;}
	}
	
	
}
