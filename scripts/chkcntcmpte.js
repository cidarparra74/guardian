function sumaTotal(){
	var cod = 'cod';
	var deb = 'deb';
	var hab = 'hab';
	var sumadebe = 0;
	var sumahaber = 0;
	var sdebe = 0;
	var shab = 0;

	for(i = 0; i < document.getElementById('nitemsingr').value; i++) {
		cod = 'cod'+i.toString();
		deb = 'deb'+i.toString();
		hab = 'hab'+i.toString();
		
		if (document.getElementById(cod).value != '' && document.getElementById(cod).value != 0)
		{
			sdebe = document.getElementById(deb).value;
			sdebe = sdebe.toString().replace(",", ".");
			sumadebe += isNaN(parseFloat(sdebe))? 0 : parseFloat(sdebe);
			
			shab = document.getElementById(hab).value;
			shab = shab.toString().replace(",", ".");
			sumahaber += isNaN(parseFloat(shab))? 0 : parseFloat(shab);
		}
	}
	document.getElementById('totald').innerHTML = parseFloat(sumadebe).toFixed(2);
	document.getElementById('totalh').innerHTML = parseFloat(sumahaber).toFixed(2);
	
	return true;
}

function openwin(phpwin){
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

/*validar formulario */
function validar(form){		
	var cnt = 0;
	var fil = 0;
	var regs = 0;
	var tdebe = 0;
	var thaber = 0;
	if (!esFechaValida(document.getElementById('fecha')))
	{	
		return false;
	}	
	if (isNaN(document.getElementById('tcambio').value) || document.getElementById('tcambio').value == '')
	{
		alert('No ha definido el tipo de cambio');
		return false;
	}
	if (document.getElementById('glosa').value == '')
	{
		alert('No definido la glosa');
		return false;
	}
	
	// validamos el detalle
	regs = document.getElementById('nitemsingr').value;
	for (var i = 0; i < document.getElementById('nitemsingr').value; i++){
		cod = 'cod'+i.toString();		
		deb = 'deb'+i.toString();
		hab = 'hab'+i.toString();
					
		sdebe = document.getElementById(deb).value;
		sdebe = sdebe.toString().replace(",", ".");	
		mdebe = isNaN(parseFloat(sdebe))? 0 : parseFloat(sdebe);		
		
		shab = document.getElementById(hab).value;	
		shab = shab.toString().replace(",", ".");	
		mhaber = isNaN(parseFloat(shab))? 0 : parseFloat(shab);	
				
		if (document.getElementById(cod).value != '' && document.getElementById(cod).value != 0){
			cnt++;
		
			if (mdebe > 0 && mhaber > 0)
			{
				alert ('No puede registrar el Debe y el Haber en una misma fila.');
				return false;
			}
			if (mdebe == 0 && mhaber == 0)
			{
				alert ('Debe ingresar un monto para el Debe \u00F3 el Haber.');
				return false;
			}
		}		
	}	
	
	if (cnt == 0){
		alert("Debe especificar el Debe y el Haber en el detalle.");
		return false;
	}
	if (cnt == 1){
		alert("Debe registrar montos tanto para el Debe como para el Haber.");
		return false;
	}
	tdebe = document.getElementById('totald').innerHTML;
	thaber = document.getElementById('totalh').innerHTML;
	if (tdebe != thaber)
	{
		alert('La suma del Debe no es igual a la suma del Haber.');
		return false;
	}
	
	if(confirm("Seguro que desea guardar este comprobante?"))
		return true;
	else
		return false;
}