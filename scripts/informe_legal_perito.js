function validaform(form)
{
	var id = form.perito.value
	if(id=='--'){
		alert("Debe seleccionar el nombre de alguna persona!");
		return false;
	}else{
		return true;
	}
	
}