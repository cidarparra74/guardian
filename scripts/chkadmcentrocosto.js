
function validar(form) {
	
	// campos requeridos que no esten vacios
	if (!novacio(form.centrocosto,form.centrocosto.value.length,1,"un nombre para el centro de costo"))
		{return false;}
	
	//  que no contengan valores invalidos
	if (!alfanum(form.centrocosto)){return false;}
}
