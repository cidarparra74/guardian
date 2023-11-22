
function validar(form) {
	
	// campos requeridos que no esten vacios
	if (!novacio(form.iva,form.iva.value.length,0,"el IVA")){return false;}
	if (!novacio(form.it,form.it.value.length,0,"el IT")){return false;}
	if (!novacio(form.lineas,form.lineas.value.length,0,"nro. de l\u00EDneas por p\u00E1gina")){return false;}
	if (!novacio(form.paginas,form.paginas.value.length,0,"el nro. de p\u00E1ginas")){return false;}
	if (!novacio(form.comprob,form.comprob.value.length,0,"el nro. l\u00EDneas de comp. de kardex")){return false;}
	if (!novacio(form.impuesto,form.impuesto.value.length,0,"el impuesto p/ precio facturado")){return false;}

	//  que no contengan valores invalidos
	//if (!letras(form.empresa)){return false;}  //solo letras
	
	if(form.iva.value!=''){  
		if (!numeros(form.iva)){return false;}
	}	
	
	if(form.it.value!=''){  		if (!numeros(form.it)){return false;}
	}
	
	if(form.lineas.value!=''){  		if (!numeros(form.lineas)){return false;}
	}
	if(form.paginas.value!=''){  		if (!numeros(form.paginas)){return false;}
	}
	if(form.comprob.value!=''){  		if (!numeros(form.comprob)){return false;}
	}
	if(form.impuesto.value!=''){  		if (!numeros(form.impuesto)){return false;}
	}
	if(form.descuento.value!=''){  
		if (!numeros(form.descuento)){return false;}
	}
	if(form.paginas.value!=''){  		if (!espar(form.paginas)){return false;}
	}
	
}
