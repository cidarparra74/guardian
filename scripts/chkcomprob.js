
//  para comprobantes contables

//calcula el total de la col debe y col haber
function caltotal(fila) {

	
	return true;
	
}


function openwin(phpwin){
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

/*validar formulario */
function validar(form){

	if (!novacio(form.fecha,form.fecha.value.length,1,"una fecha, ")){return false;}
	
	if(form.centrocosto.value == '0'){
		alert("No ha seleccionado ningun centro de costo!!");
		return false;
	}
	 
	if(form.tipodoc.value == '0'){
		alert("No ha seleccionado ningun tipo de documento!!");
		return false;
	}
	 
	if(form.moneda.value != form.mone.value)
		if(form.tcambio.value == '0' || form.tcambio.value == ''){
			alert("Ingrese el tipo de cambio!");
			return false;
		}
	 
	if(form.glosa.value == ''){
		alert("No ha ingresado nada en la glosa!!");
		return false;
	}
	
	var cnt = 0;
	var fil = 0;
	var pro = 0;
	var deb, hab, vdeb, vhab, idp;
	// validamos el detalle
	for(i=0; i<10; i++){
		cod = 'cod'+i;
		if(document.getElementById(cod).value != ''){
			cnt++;
			//vemos si toda la fila esta completa
			deb = 'deb'+i;
			hab = 'hab'+i;
			idp = 'idpr'+i;
			
			vdeb = document.getElementById(deb).value ;
			vhab = document.getElementById(hab).value ;

			if(vdeb == '') vdeb = '0';
			if(vhab == '') vhab = '0';

			if((vdeb == '0') && (vhab == '0'))
				fil++;
				
			if((vdeb != '0') && (vhab != '0'))
				fil++;
				
			if(document.getElementById(idp).value == '')
				pro++;
		}
	}
	if(cnt==0){
		alert("No hay nada en el detalle!");
		return false;
	}
	if(fil!=0){
		alert("Hay "+fil+" fila(s) incompleta(s) o con errores en el detalle!");
		return false;
	}
	if(pro!=0){
		alert("Hay "+pro+" codigo(s) de cuenta invalido(s) en el detalle!");
		return false;
	}
	if(confirm("Seguro que desea guardar este comprobante?"))
		return true;
	else
		return false;
}
