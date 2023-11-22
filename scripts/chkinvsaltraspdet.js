function openwin(phpwin){
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

/*validar formulario */
function validar(form){
	
	if (!novacio(form.fecha,form.fecha.value.length,9,"la fecha, ")){return false;}
	
	if (form.centrocosto.value == form.centrocostodes.value){
		form.centrocostodes.focus();
		alert("Los centros de costo 'Origen' - 'Destino', no pueden ser iguales!!!");
		return false;
	}
	
	var cnt = 0;
	var fil = 0;
	var pro = 0;
	var exc = 0;
	
	var cod,can,idp,ext;
	// validamos el detalle
	for(i=0; i<form.nitemsingr.value; i++){
		cod = 'cod'+i;
		
		if ((document.getElementById(cod).value != '') && (document.getElementById(cod).value != 0))
		{
			cnt++;
			//vemos si toda la fila esta completa
			can = 'can'+i;
			idp = 'idpr'+i;
			ext = 'existe'+i;
			if ((document.getElementById(can).value == '') || (document.getElementById(can).value == 0) || (document.getElementById(can).innerHTML == '0'))
				fil++;
			if (document.getElementById(idp).value == '' || (document.getElementById(idp).innerHTML == '0'))
				pro++;
			if (document.getElementById(ext).innerHTML!='')
				exc++;

		}
		
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
	if(exc!=0){
		alert("La cantidad ha transferir excede el stock disponible!!.");
		return false;
	}	
	if(confirm("Seguro que desea realizar el traspaso?"))
		return true;
	else
		return false;
	
}

