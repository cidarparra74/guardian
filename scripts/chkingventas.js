function validar(form){
	var tdc = document.getElementById('tcambio').value;
	var totd = parseFloat(document.getElementById('totaldebe').innerHTML);
	var toth = parseFloat(document.getElementById('totalhaber').innerHTML);
	
	if (tdc == '' || tdc <= 0) {
		alert('Debe definir el tipo de cambio.');
		return false;
	}
	
	if (totd != toth) {
		alert('La suma del DEBE y el HABER debe ser exactamente igual');
		return false;
	}
	
	return true;
}