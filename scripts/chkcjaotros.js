function openwin(phpwin){
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

function validar(form){
	var tcambio = form.tcambio.value;
	var glosa = form.glosa.value;
	var cta1 = form.idcta0.value;
	var cta2 = form.idcuenta.value;
	var monto = form.monto.value;
	
	if (isNaN(tcambio) || tcambio == '')
	{
		alert("No ha definido el tipo de cambio.");
		return false;
	}
	if (glosa == '')
	{
		alert("Debe especificar la glosa");
		return false;
	}
	if (cta1 == cta2)
	{
		alert("La cuenta seleccionada debe ser diferente a la Cuenta de Caja.");
		return false;
	}
	if (isNaN(monto) || monto == '')
	{
		alert("Ingrese el monto a registrar.");
		return false;
	}
	if (confirm("Seguro que desea guardar este comprobante?"))
		return true;
	else
		return false;
}