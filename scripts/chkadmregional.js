// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.regional,form.regional.value.length,2,"regi�n, ")){return false;}
	
	//  que no contengan valores invalidos
	if (!letras(form.regional)){return false;}
}
