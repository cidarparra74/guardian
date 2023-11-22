// ***** cntplanta
function validar(form) {
	//alert ("Entroooooo");
	
	// campos requeridos que no esten vacios
	
	if (!novacio(form.tipo,form.tipo.value.length,1,"Tipo de comprobante, ")){return false;}
	
	//  que no contengan valores invalidos
	if (!alfanum(form.tipo)){return false;}
}
