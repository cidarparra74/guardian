
function verificar1(form) {
	var i=0;
	var contrato = '';
	for (i=0; i < form.idcontrato.options.length; i++) {
		if(form.idcontrato.options[i].selected){
			contrato=form.idcontrato.options[i].text;
		}
	}
	if(i==0){
		alert("No existen contratos creados para este usuario!");
		return false;
	}else{
		if(contrato==''){
			alert("Debe seleccionar un contrato para poder continuar");
			form.idcontrato.focus();
			return false;
		}else{
			form.contrato.value = contrato;
		}
	}
	
	var mynrocaso = '';
	if(document.getElementById("nrocaso")){
		for (i=0; i < form.nrocaso.options.length; i++) {
			if(form.nrocaso.options[i].selected){
				mynrocaso=form.nrocaso.options[i].text;
			}
		}
		if(i!=0){
			if(mynrocaso==''){
				alert("Debe seleccionar un n\u00FAmero de caso para poder continuar");
				form.nrocaso.focus();
				return false;
			}
		}
	}
	
}


function verificarmod(form) {
	var i=0;
	var contrato = '';
	for (i=0; i < form.idcontrato.options.length; i++) {
		if(form.idcontrato.options[i].selected){
			contrato=form.idcontrato.options[i].text;
		}
	}
	if(i==0){
		alert("No existen contratos creados para este usuario!");
		return false;
	}else{
		if(contrato==''){
			alert("Debe seleccionar un contrato para poder continuar");
			form.idcontrato.focus();
			return false;
		}else{
			form.contrato.value = contrato;
		}
	}
	
}

function ordennum(xnro){
	var literal='';
	if(xnro==1)
		literal='PRIMERA';
	else if(xnro==2)
		literal='SEGUNDA';
	else if(xnro==3)
		literal='TERCERA';
	else if(xnro==4)
		literal='CUARTA';
	else if(xnro==5)
		literal='QUINTA';
	else if(xnro==6)
		literal='SEXTA';
	else if(xnro==7)
		literal='SEPTIMA';
	else if(xnro==8)
		literal='OCTAVA';
	else if(xnro==9)
		literal='NOVENA';
	else if(xnro==10)
		literal='DECIMA';
	else if(xnro==11)
		literal='DECIMA PRIMERA';
	else if(xnro==12)
		literal='DECIMA SEGUNDA';
	else if(xnro==13)
		literal='DECIMA TERCERA';
	else if(xnro==14)
		literal='DECIMA CUARTA';
	else if(xnro==15)
		literal='DECIMA QUINTA';
	else if(xnro==16)
		literal='DECIMA SEXTA';
	else if(xnro==17)
		literal='DECIMA SEPTIMA';
	else if(xnro==18)
		literal='DECIMA OCTAVA';
	else if(xnro==19)
		literal='DECIMA NOVENA';
	else if(xnro==20)
		literal='VIGESIMA';
	else if(xnro==21)
		literal='VIGESIMA PRIMERA';
	else if(xnro==22)
		literal='VIGESIMA SEGUNDA';
	else if(xnro==23)
		literal='VIGESIMA TERCERA';
	else if(xnro==24)
		literal='VIGESIMA CUARTA';
	else if(xnro==25)
		literal='VIGESIMA QUINTA';
	else if(xnro==26)
		literal='VIGESIMA SEXTA';
	else if(xnro==27)
		literal='VIGESIMA SEPTIMA';
	else if(xnro==28)
		literal='VIGESIMA OCTAVA';
	else if(xnro==29)
		literal='VIGESIMA NOVENA';
}

function des_verificar_adicion(){
	if(confirm("Esta seguro de cancelar la elaboraci\u00F3n de este contrato?")){
	document.adicionar.boton_cancelar.disabled=true;
	document.adicionar_cancelar.submit();
	return true;
	}else{ 
	return false;}
}

//validamos las dos fechas
function verfechas1(){
	
	var fec1 = document.getElementById('filtro_fecha');
	var fec2 = document.getElementById('filtro_fech2');
	
	if(validarfecha(fec1))
		if(validarfecha(fec2))
			return true;  //true; 
		else
			fec2.focus();
	else
		fec1.focus();
	return false;
}

//para la fecha
function validarfecha(campo){
	var maximo_valor= 10;
	var tam= campo.value.length;
	var valor= campo.value;
	//dividimos la cadena por /
	var division= valor.split("/");
	if(division.length!=3 || tam!=maximo_valor){
		alert("La fecha debe tener el formato dd/mm/yyyy");
		return false;
	}else{
		if(parseInt(division[0])>31){
			alert("El d\u00EDa no debe ser mayor a 31");
			return false;
		}
		if(parseInt(division[1])>12){
			alert("El mes no debe ser mayor a 12");
			return false;
		}
		if(parseInt(division[2])<2000 || parseInt(division[2]>2500)){
			alert("El a\u00F1o debe estar en el rango 2000-2500");
			return false;
		}
		//verificando que el mes no tenga mas de 31 dias
		if(parseInt(division[1])==2){ //febrero
			
				var checkYear = ( ((division[2] % 4 == 0)&& (division[2] % 100 != 0)) || (division[2] % 400 == 0))  ? 1 : 0;
				if (checkYear )
					var diam = 29;
				else
					var diam = 28;
				if(parseInt(division[0])>diam){alert("El d\u00EDa no puede ser mayor a "+diam); campo.value=""; campo.focus(); return false;}
		}
		//meses con 30 dias
		if( parseInt(division[1])==4 || 
			parseInt(division[1])==6 || 
			parseInt(division[1])==9 || 
			parseInt(division[1])==11	){
			if(parseInt(division[0])>30){
				alert("El d\u00EDa no puede ser mayor a 30");  
				return false;
			}
		}
		//todo bien
		return true;
	}
}

function chkdisable(cual){
	if(cual == 'N'){
		document.seleccion.tipo[0].disabled = false;
		document.seleccion.tipo[1].disabled = false;
		document.seleccion.tipo[2].disabled = false;
		document.seleccion.servicio[0].checked = true;
		document.seleccion.servicio[0].disabled = false;
	}else{
		document.seleccion.tipo[0].checked = true;
		document.seleccion.tipo[0].disabled = true;
		document.seleccion.tipo[1].disabled = true;
		document.seleccion.tipo[2].disabled = true;
		document.seleccion.servicio[1].checked = true;
		document.seleccion.servicio[0].disabled = true;
	}
	
	return true;
}

function chkdisable2(cual){
	if(cual){
		document.seleccion.servicio[1].checked = true;
		document.seleccion.servicio[0].disabled = true;
	}else{
		document.seleccion.servicio[0].disabled = false;
	}
	
	return true;
}
