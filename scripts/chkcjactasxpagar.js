function sumaTotal(){
	var deb = 'deb';
	var hab = 'hab';
	var sumadebe = 0;
	var sumahaber = 0;
	var sdebe = 0;
	var shab = 0;
	
	for(i = 0; i < document.getElementById('nitemsingr').value; i++) {
		deb = 'deb'+i.toString();
		hab = 'hab'+i.toString();		
		
		sdebe = document.getElementById(deb).value;
		sdebe = sdebe.toString().replace(",", ".");
		sumadebe += isNaN(parseFloat(sdebe))? 0 : parseFloat(sdebe);
		
		shab = document.getElementById(hab).value;
		shab = shab.toString().replace(",", ".");
		sumahaber += isNaN(parseFloat(shab))? 0 : parseFloat(shab);
	}	
	// alert(document.getElementById('deb0').value);
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
	var cod, con, deb, hab;
	for (j = 0; j < form.nitemsingr.value; j++)
	{
		cod = 'cod' + i;
		if (document.getElementById(cod).value != '')
			filas++;
	}
	// validamos el detalle
	for(i = 0; i < form.nitemsingr.value; i++){
		cod = 'cod' + i;
		con = 'con' + i;
		deb = 'deb' + i;
		hab = 'hab' + i;
		
		if (document.getElementById(cod).value != '') {
			if (document.getElementById(con).value != '') {
				continue;
			else
				break;
		
			if (document.getElementById(deb).value != '' &&
				document.getElementById(hab).value != '')
				break;			
			else {
				if (document.getElementById(deb).value == '' &&
					document.getElementById(hab).value == '')
					break;
				else
					continue;
			}
		}
		cnt++;		
	}
	if(cnt==0){
		alert("No hay nada en el detalle!");
		return false;
	}
	if(fil!=0){
		alert("Hay "+fil+" fila(s) incompleta(s) en el detalle!");
		return false;
	}
	if(pro!=0){
		alert("Hay "+pro+" codigo(s) de producto invalido(s) en el detalle!");
		return false;
	}
	if(confirm("Seguro que desea guardar este comprobante?"))
		return true;
	else
		return false;
}