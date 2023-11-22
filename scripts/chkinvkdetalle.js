//calcula el total del importe en base a la cantidad * precio - descuento
function caltotal() {

	var IMP = 'imp';
	var sumaIMP = 0;
	for(i = 0; i < document.getElementById('nitemsingr').value; i++) {
		IMP = 'imp'+i.toString();

		sIMP = document.getElementById(IMP).value; // innerHTML
		sIMP = sIMP.toString().replace(",", ".");
		sumaIMP += isNaN(parseFloat(sIMP))? 0 : parseFloat(sIMP);
	}
	document.getElementById('totalimp').innerHTML = sumaIMP.toFixed(2);
	return true;
	
}

function calimporte(pos){
	var Can = 'can'+pos;
	var Imp = 'uni'+pos;
	var Por = 'por'+pos;
	var Des = 'des'+pos;
	var Tot = 'imp'+pos;	
	var vCan = parseFloat(document.getElementById(Can).value);
	var vImp = parseFloat(document.getElementById(Imp).innerHTML);
	var vPor = parseFloat(document.getElementById(Por).value);
	var vDes = parseFloat(document.getElementById(Des).value);
	var desc = parseFloat(document.getElementById('descuento').value);
	var vTot = 0;
	if(isNaN(vCan))
		vCan = 0;
	if(isNaN(vImp))
		vImp = 0;
	if(isNaN(vPor))
		vPor = 0;
	if(isNaN(vDes))
		vDes = 0;
	if(isNaN(desc))
		desc = 0;	
	vTot = vCan * vImp;
	if(vPor > 0 && vPor <= 100){
		if(vPor <= desc){
			vDes = vTot * vPor / 100;
		}else{
			vDes = 0;
			alert("Se ha excedido el porcentaje m\u00E1ximo permitido para descuentos!");
			document.getElementById(vPor).value = vPor.toFixed(2);
		}
	}
	vTot = vTot - vDes;	
	document.getElementById(Des).value = vDes.toFixed(2);
	document.getElementById(Tot).value = vTot.toFixed(2); // innerHTML
	caltotal();
	return true;
	
}
function caldescuento(pos){
	var Can = 'can'+pos;
	var Uni = 'uni'+pos;
	var Por = 'por'+pos;
	var Des = 'des'+pos;
	var Tot = 'imp'+pos;
	
	var vCan = parseFloat(document.getElementById(Can).value);
	var vUni = parseFloat(document.getElementById(Uni).innerHTML);
	var vTot = parseFloat(document.getElementById(Tot).value);
	var vPor = 0; //parseFloat(document.getElementById(Por).value);
	var vDes = 0; //parseFloat(document.getElementById(Des).value);
	var desc = parseFloat(document.getElementById('descuento').value);
	
	if(isNaN(vCan))
		vCan = 0;
	if(isNaN(vUni))
		vUni = 0;
	//if(isNaN(vPor))
	//	vPor = 0;
	//if(isNaN(vDes))
	//	vDes = 0;
	if(isNaN(desc))
		desc = 0;
	vDes = (vCan * vUni) - vTot;
	/*if(vPor > 0 && vPor <= 100){
		if(vPor <= desc){
			vDes = vTot * vPor / 100;
		}else{
			vDes = 0;
			alert("Se ha excedido el porcentaje m\u00E1ximo permitido para descuentos!");
			document.getElementById(vPor).value = vPor.toFixed(2);
		}
	}*/
	//vTot = vTot - vDes;
	document.getElementById(Des).value = vDes.toFixed(2);
	//document.getElementById(Tot).value = vTot.toFixed(2); // innerHTML
	caltotal();
	return true;	
}
function openwin(phpwin){
	var opciones="left=400, top=50, width=600, height=800, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

/*validar formulario */
function validar(form){

	if (!novacio(form.fecha,form.fecha.value.length,1,"una fecha, ")){return false;}
	
	if (form.centrocosto.value == '0'){
		alert("No se le ha asignado ningun centro de costo!!");
		return false;
	}
	
	if (!novacio(form.ci,form.ci.value.length,1,"un documento de identidad, ")){return false;}
	
	if (form.idpersona.value == '0'){
		alert("Valide el numero de documento!");
		return false;
	}
	
	if(form.civalido.value != form.ci.value){
		alert("El numero de documento no corresponde al nombre!");
		return false;
	}
	
	if(form.moneda.value != form.mone.value)
	if(form.tcambio.value == '0' || form.tcambio.value == ''){
		alert("Ingrese un tipo de cambio!");
		return false;
	}
	// forma de pago:  E= Efectivo, C= Credito
	// controla si la forma de pago es cr\u00E9dito 
	// para que el plazo no este vacio y sea numerico
	if (form.formapago.value!='E'){
		if(!novacio(form.plazo,form.plazo.value.length,0,"el plazo en d\u00EDas, ")){
			return false;
		}
		if(form.plazo.value!=''){
			if (!numeros(form.plazo))
				return false;
		}
	}
	
	var cnt = 0;
	var fil = 0;
	var pro = 0;
	var neg = 0;
	var exc = 0;
	var cod,can,uni,idp,imp,ext;
	
	// validamos el detalle
	for(i = 0; i < form.nitemsingr.value; i++){
		cod = 'cod'+i;
		if(document.getElementById(cod).value != '' && document.getElementById(cod).value != 0)
		{
			cnt++;
			//vemos si toda la fila esta completa
			can = 'can'+i;
			idp = 'idpr'+i;
			uni = 'uni'+i;
			imp = 'imp'+i;
			ext = 'existe'+i;
			if ((document.getElementById(can).value == '') || (document.getElementById(can).value == 0) || (document.getElementById(uni).innerHTML == '0'))
				fil++;
			if(document.getElementById(idp).value == '')
				pro++;
			if(document.getElementById(imp).value < 0)
				neg++;
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
		alert("La cantidad seleccionada excede al stock disponible!!.");
		return false;
	}
	if(neg!=0){
		alert("Hay "+neg+" importe(s) negativo(s) en el detalle!");
		return false;
	}
	if(confirm("Seguro que desea guardar este comprobante?"))
		return true;
	else
		return false;
}
