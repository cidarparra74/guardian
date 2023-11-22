
function validar(form) {

	// campos requeridos que no esten vacios
	if (!novacio(form.descripcion,form.descripcion.value.length,1,"la descripci\u00F3n, ")){return false;}
	if (!novacio(form.imagen,form.imagen.value.length,1,"la imagen, ")){return false;}
	
	
}
