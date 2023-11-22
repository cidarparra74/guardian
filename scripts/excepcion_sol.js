// ***** 
function vecorreo(form) {

	// campos requeridos que no esten vacios
	
	if (!novacio(form.correoe,form.correoe.value.length,1,"correo, ")){return false;}
	
	return true;
}
