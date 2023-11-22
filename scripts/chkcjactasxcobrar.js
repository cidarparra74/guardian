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
	var cnt = 0;
	var fil = 0;
	var pro = 0;
	var neg = 0;
	var cod,can,cst,fle,adu,uti,idp,vcan,vcos,vfle,vadu,vuti;
	// validamos el detalle
	for(i = 0; i < form.nitemsingr.value; i++){
		cod = 'cod'+i;
		if(document.getElementById(cod).value != ''){
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