// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.idmoneda,form.idmoneda.value.length,1,"Id. de moneda, ")){return false;}
	if (!novacio(form.moneda,form.moneda.value.length,1,"descripci\u00F3n de la moneda, ")){return false;}
	//  que no contengan valores invalidos
	//if (!alfanum(form.regional)){return false;}
}
