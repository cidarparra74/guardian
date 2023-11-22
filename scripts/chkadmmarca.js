// ***** 
function validar(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.idmarca,form.idmarca.value.length,1,"Id. de marca, ")){return false;}
	if (!novacio(form.marca,form.marca.value.length,1,"Id. de marca, ")){return false;}
	//  que no contengan valores invalidos
	//if (!alfanum(form.regional)){return false;}
}
