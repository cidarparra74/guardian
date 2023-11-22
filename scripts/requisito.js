
function validarform(form) {

	// campos requeridos que no esten vacios
	if (!novacio(form.ci,form.ci.value.length,1,"el nro de documento del cliente, ")){return false;}
	if (!novacio(form.cliente,form.cliente.value.length,1,"el nombre del cliente, ")){return false;}
	//if (!novacio(form.imagen,form.imagen.value.length,1,"la imagen, ")){return false;}
	
	
}
