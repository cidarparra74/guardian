
function validar(form) {

	// campos requeridos que no esten vacios
	// producto es la descripcion
	if (!novacio(form.producto,form.producto.value.length,1,"el nombre del producto, ")){return false;}  
	if (!novacio(form.codigo_propio,form.codigo_propio.value.length,0,"el cod. propio, ")){return false;}

	
}
