// ***** cntplanta
function validar(form) {
	//alert ("Entroooooo");
	
	// campos requeridos que no esten vacios
	
	if (!novacio(form.idcuentasup,form.idcuentasup.value.length,0,"Cuenta Sup., ")){return false;}
	if (!novacio(form.codigo,form.codigo.value.length,0,"C\u00F3digo, ")){return false;}
	if (!novacio(form.cuenta,form.cuenta.value.length,0,"Cuenta, ")){return false;}
	
	//  que no contengan valores invalidos
	
}
