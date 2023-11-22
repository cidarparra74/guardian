// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.idunidad,form.idunidad.value.length,1,"Id. de unidad, ")){return false;}
	if (!novacio(form.unidad,form.unidad.value.length,1,"Id. de unidad, ")){return false;}
	
	//  que no contengan valores invalidos
	//if (!alfanum(form.regional)){return false;}
}
