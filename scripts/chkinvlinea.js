// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.idlinea,form.idlinea.value.length,1,"Id. de l\u00EDnea, ")){return false;}
	if (!novacio(form.linea,form.linea.value.length,1,"Id. de l\u00EDnea, ")){return false;}
	//  que no contengan valores invalidos
	//if (!alfanum(form.regional)){return false;}
}
