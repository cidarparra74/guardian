//calcula el total del importe en base a la cantidad * precio - descuento
function caltotal(fila) {	
	calCIF(fila);
	calCosto(fila);
	calPrecios(fila);	
	sumaTotal();		

	return true;	
}

function calCIF(pos){
	var Cos = 'cos'+pos;
	var Fle = 'fle'+pos;
	var CIF = 'cif'+pos;
	var Des = 'desc'+pos;
	var vCos = document.getElementById(Cos).value;
	var vFle = document.getElementById(Fle).value;
	var vDes = document.getElementById(Des).value;
	var vCIF = 0;
	vCos = vCos.toString().replace(",", ".");
	vFle = vFle.toString().replace(",", ".");
	vDes = vDes.toString().replace(",", ".");
	
	if (isNaN(vCos) == true)
		vCos = 0;

	if (isNaN(vFle) == true)
		vFle = 0;
		
	if (isNaN(vDes) == true)
		vDes = 0;
	/*vCIF = parseFloat(vCos) + ((parseFloat(vCos) * parseFloat(vFle)) / 100);*/
	vCIF = (parseFloat(vFle) + parseFloat(vCos)) - parseFloat(vDes);
	document.getElementById(CIF).innerHTML = parseFloat(vCIF).toFixed(2);
	return true;	
}

function calCosto(pos){
	var Adu = 'adu'+pos;
	var CIF = 'cif'+pos;
	var ALM = 'alm'+pos;
	var vCif = document.getElementById(CIF).innerHTML;
	var vAdu = document.getElementById(Adu).value;
	var vALM = 0;
	vCif = vCif.toString().replace(",", ".");
	vAdu = vAdu.toString().replace(",", ".");
	
	if (isNaN(vAdu) == true) 
		vAdu = 0;
	
	/*vALM = parseFloat(vCif) + ((parseFloat(vCif) * parseFloat(vAdu)) / 100);*/
	vALM = (1 + (parseFloat(vAdu) / 100)) * parseFloat(vCif);	
	document.getElementById(ALM).innerHTML = parseFloat(vALM).toFixed(2);
	return true;
}

function calPrecios(pos){

	var Uti = 'uti'+pos;
	var ALM = 'alm'+pos;
	var sinf = 'psin'+pos;
	var conf = 'pcon'+pos;
	
	var vAlm = document.getElementById(ALM).innerHTML;
	var vUti = document.getElementById(Uti).value;
	var vImp = document.getElementById('impuesto').value;
	var vPRE1 = 0;
	var vPRE2 = 0;	
	vAlm = vAlm.toString().replace(",", ".");
	vUti = vUti.toString().replace(",", ".");
	vImp = vImp.toString().replace(",", ".");
	
	if (isNaN(vUti) == true) 
		vUti = 0;
		
	vPRE1 = parseFloat(vAlm) + ((parseFloat(vAlm) * parseFloat(vUti)) / 100);
	vPRE2 = (vPRE1 / vImp);
	document.getElementById(sinf).value = vPRE1.toFixed(2);
	document.getElementById(conf).value = vPRE2.toFixed(2);
	
	return true;
}

function sumaTotal(){
	var CIF = 'cif';
	var ALM = 'alm';
	var SINF = 'psin';
	var CONF = 'pcon';
	var sumaCIF = 0;
	var sumaALM = 0;
	var sumaSIN = 0;
	var sumaCON = 0;
	var sCIF = 0;
	var sALM = 0;
	var sSIN = 0;
	var sCON = 0;
	
	for(i = 0; i < document.getElementById('nitemsingr').value; i++) {
		CIF = 'cif'+i.toString();
		ALM = 'alm'+i.toString();
		SINF = 'psin'+i.toString();
		CONF = 'pcon'+i.toString();
		
		sCIF = document.getElementById(CIF).innerHTML;
		sCIF = sCIF.toString().replace(",", ".");
		sumaCIF += isNaN(parseFloat(sCIF))? 0 : parseFloat(sCIF);
		
		sALM = document.getElementById(ALM).innerHTML;
		sALM = sALM.toString().replace(",", ".");
		sumaALM += isNaN(parseFloat(sALM))? 0 : parseFloat(sALM);
		
		sSIN = document.getElementById(SINF).value; 
		sSIN = sSIN.toString().replace(",", ".");
		sumaSIN += isNaN(parseFloat(sSIN))? 0 : parseFloat(sSIN);
		
		sCON = document.getElementById(CONF).value;
		sCON = sCON.toString().replace(",", ".");
		sumaCON += isNaN(parseFloat(sCON))? 0 : parseFloat(sCON);
	}	
	
	document.getElementById('totalcif').innerHTML = parseFloat(sumaCIF).toFixed(2);
	document.getElementById('totalalm').innerHTML = parseFloat(sumaALM).toFixed(2);
	document.getElementById('totalsin').innerHTML = parseFloat(sumaSIN).toFixed(2);
	document.getElementById('totalcon').innerHTML = parseFloat(sumaCON).toFixed(2);
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
		alert("No se le ha asignado ningun centro de costo!!");
		return false;
	}
	
	if (!novacio(form.ci,form.ci.value.length,1,"un documento de identidad, ")){return false;}
	
	if(form.idpersona.value == '0'){
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
	var cod,can,cst,fle,adu,uti,idp,vcan,vcos,vfle,vadu,vuti;
	// validamos el detalle
	for(i = 0; i < form.nitemsingr.value; i++){
		cod = 'cod'+i;		
		if(document.getElementById(cod).value != '' && document.getElementById(cod).value != 0){
			cnt++;
			//vemos si toda la fila esta completa
			can = 'can'+i;
			cst = 'cos'+i;
			fle = 'fle'+i;
			adu = 'adu'+i;
			uti = 'uti'+i;
			idp = 'idpr'+i;
			vcan = document.getElementById(can).value ;
			vcos = document.getElementById(cst).value ;
			vfle = document.getElementById(fle).value ;
			vadu = document.getElementById(adu).value ;
			vuti = document.getElementById(uti).value ;
			if(vcan == '') vcan = '0';
			if(vcos == '') vcos = '0';
			if(vfle == '') vfle = '0';
			if(vadu == '') vadu = '0';
			if(vuti == '') vuti = '0';
			if((vcan == '0') || (vcos == '0'))
				fil++;
			if(document.getElementById(idp).value == '')
				pro++;
			if (vuti == '0') alert("Advertencia: No existe % de utilidad en la fila "+(i+1));
		}
	}
	if(cnt==0){
		alert("No hay nada en el detalle!");
		return false;
	}
	if(fil!=0){
		alert("Hay "+fil+" fila(s) incompleta(s) en el detalle!!!");
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

