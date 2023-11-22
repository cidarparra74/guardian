function validar(form) {
	
	// campos requeridos que no esten vacios
	if (!novacio(form.afp,form.afp.value.length,0,"el aporte a AFP")){return false;}
	if (!novacio(form.cns,form.cns.value.length,0,"el aporte a la CNS")){return false;}
	if (!novacio(form.hraextra,form.hraextra.value.length,0,"nro. de horas extras trabajandas.")){return false;}
	if (!novacio(form.hraadicional,form.hraadicional.value.length,0,"el nro. de horas adicionales trabajadas.")){return false;}
	if (!novacio(form.anio,form.anio.value.length,0,"el a\u00F1o actual")){return false;}
	

	//  que no contengan valores invalidos
	//if (!letras(form.empresa)){return false;}  //solo letras
	
	if(form.iva.value!=''){  
		if (!numeros(form.iva)){return false;}
	}	
	
	if(form.it.value!=''){  
		if (!numeros(form.it)){return false;}
	}
	
	if(form.lineas.value!=''){  
		if (!numeros(form.lineas)){return false;}
	}
	if(form.paginas.value!=''){  
		if (!numeros(form.paginas)){return false;}
	}
	if(form.comprob.value!=''){  
		if (!numeros(form.comprob)){return false;}
	}
	if(form.impuesto.value!=''){  
		if (!numeros(form.impuesto)){return false;}
	}
	
	if(form.paginas.value!=''){  
		if (!espar(form.paginas)){return false;}
	}
	
}
