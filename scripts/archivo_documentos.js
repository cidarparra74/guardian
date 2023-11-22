
function ValidNumEntero(e) {
	var tecla= window.event ? tecla = e.keyCode : tecla = e.which;
	return (tecla > 47 && tecla < 58) ; 
}

function ValidNumDecimal(e) {
	var tecla= window.event ? tecla = e.keyCode : tecla = e.which;
	return ((tecla > 47 && tecla < 58) || tecla == 46); 
}

function deshabilitar(valor){
	if(valor.value == "si"){
		document.modificar.password.disabled=true;
		document.modificar.password_nuevo.disabled=false;	
		document.modificar.password_nuevo.value="";
		document.modificar.password_nuevo.focus();
	}
	else{
		document.modificar.password.disabled=false;
		document.modificar.password_nuevo.value="";
		document.modificar.password_nuevo.disabled=true;
	}
}

function cambiar_bien(tiene, tipo, estado, excepcion, propietario, observacion){
	var ver;
	ver= tiene.checked;
	//alert(ver);
	if(!ver == true){ //no marcado
		//alert("entra........");
		tipo.disabled=true;
		estado.value="";
		estado.disabled=true;
		propietario.disabled=true;
		observacion.value="";
		observacion.disabled=true;
	}
	else{ //marcado
		//alert("no entra......");
		tipo.disabled=false;
		estado.disabled=false;
		excepcion.checked=false;
		propietario.disabled=false;
		observacion.disabled=false;
	}
}

function cambiar_bien_excepcion(tiene, tipo, estado, excepcion, propietario, observacion){
	var ver;
	ver= excepcion.checked;
	//alert(ver);
	if(!ver == true){ //no marcado
		tipo.disabled=true;
		estado.value="";
		estado.disabled=true;
		propietario.disabled=true;
		observacion.value="";
		observacion.disabled=true;
	}
	else{ //marcado
		tipo.disabled=false;
		estado.disabled=false;
		tiene.checked=false;
		propietario.disabled=false;
		observacion.disabled=false;
	}
}

function des_documentos(){
	//verificamos el responsable y la observacion
	var cantidad=0;
	var i=0;
	cantidad= parseInt(document.all["cantidad_total"].value);

	for(i=1; i<=cantidad; i++){
		var aux= "tiene_excepcion_"+i;
		var excepcion= document.all[aux];
		var ver;
		ver= excepcion.checked;
		if(!ver == true){ //no marcado
			//nada
		}
		else{//marcado
			var aux_p = "responsable_"+i;
			var responsable= document.all[aux_p];
			var aux_o= "obs_excepcion_"+i;
			var obs= document.all[aux_o];
			
			if(responsable.value == "ninguno"){
				alert("Debe seleccionar el responsable para esta excepci\u00F3n");
				responsable.focus();
				return false;
			}
			if(obs.value == ""){
				alert("Debe escribir la observaci\u00F3n para esta excepci\u00F3n");
				obs.focus();
				return false;
			}
		}
	}
	
	document.documentos.adicionar.disabled=true;
}

function validar_numero(campo, maximo){
	maximo_valor= parseInt(maximo);
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0"){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	if(parseFloat(nuevo_valor)>maximo_valor){
		alert("El puntaje no debe ser mayor a : " + maximo);
		campo.value="";
		campo.focus();
		return false;
	}
	else{
		campo.value=nuevo_valor;
	}
}


//para la fecha
function colocar_fecha(campo){

	var maximo_valor= 10;
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0" || letra == "/" || letra=="A" || letra=="B" || letra=="C" || letra=="D" || letra=="E" || letra=="F" || letra=="G" || letra=="I" || letra=="J" || letra=="L" || letra=="M" || letra=="N" || letra=="O" || letra=="P" || letra=="R" || letra=="S" || letra=="T" || letra=="U" || letra=="V" || letra=="Y"){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	//alert(nuevo_valor.length);
	if((nuevo_valor.length)==maximo_valor){
		//dividimos la cadena por /
		var division= nuevo_valor.split("/");
		if(division.length!=3){
			alert("La fecha debe tener el formato dd/mm/yyyy");
			campo.value="";
			campo.focus();
			return false;
		}
		else{
			if(parseInt(division[0])>31){
				alert("El d\u00EDa no debe ser mayor a 31");
				campo.value="";
				campo.focus();
				return false;
			}
			if(parseInt(division[1])>12){
				alert("El mes no debe ser mayor a 12");
				campo.value="";
				campo.focus();
				return false;
			}
			if(parseInt(division[2])>2500 || parseInt(division[2]<2000)){
				alert("El a\u00F1o debe estar en el rango 2000-2500");
				campo.value="";
				campo.focus();
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
			//abril
			if(parseInt(division[1])==4){ //abril
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//junio
			if(parseInt(division[1])==6){ //junio
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//septiembre
			if(division[1]=="09"){ //septiembre
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//noviembre
			if(parseInt(division[1])==11){ //noviembre
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			
			//calculamos el mes en mmm
			var para_mes="";

			
			if(division[1]=="01"){para_mes="ENE";}
			if(division[1]=="02"){para_mes="FEB";}
			if(division[1]=="03"){para_mes="MAR";}
			if(division[1]=="04"){para_mes="ABR";}
			if(division[1]=="05"){para_mes="MAY";}
			if(division[1]=="06"){para_mes="JUN";}
			if(division[1]=="07"){para_mes="JUL";}
			if(division[1]=="08"){para_mes="AGO";}
			if(division[1]=="09"){para_mes="SEP";}
			if(division[1]=="10"){para_mes="OCT";}
			if(division[1]=="11"){para_mes="NOV";}
			if(division[1]=="12"){para_mes="DIC";}
			
			//todo bien y cambiamos la fecha al formato dd/mmm/yyyy
			var formar_fecha= division[0]+"/"+para_mes+"/"+division[2];
			campo.value=formar_fecha;
			campo.className = "input_derecha";
			campo.focus();
			return true;
		}
	}else{
		campo.value=nuevo_valor;
		//campo.className = "input_derecha_red";
		campo.focus();
		return true;
	}
}


//para la fecha y su vencimiento
//para la fecha
function colocar_fecha_vencimiento(campo, meses, fecha_vencimiento, obs){
	//var observaciones = document.getElementById["obs_"+obs]
	var maximo_valor= 10;
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0" || letra == "/" || letra=="A" || letra=="B" || letra=="C" || letra=="D" || letra=="E" || letra=="F" || letra=="G" || letra=="I" || letra=="J" || letra=="L" || letra=="M" || letra=="N" || letra=="O" || letra=="P" || letra=="R" || letra=="S" || letra=="T" || letra=="U" || letra=="V" || letra=="Y"){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	//alert(nuevo_valor.length);
	if((nuevo_valor.length)==maximo_valor){
		//dividimos la cadena por /
		var division= nuevo_valor.split("/");
		if(division.length!=3){
			alert("La fecha debe tener el formato dd/mm/yyyy");
			campo.value="";
			campo.focus();
			return false;
		}
		else{
			if(parseInt(division[0])>31){
				alert("El d\u00EDa no debe ser mayor a 31");
				campo.value="";
				campo.focus();
				return false;
			}
			if(parseInt(division[1])>12){
				alert("El mes no debe ser mayor a 12");
				campo.value="";
				campo.focus();
				return false;
			}
			if(parseInt(division[2])>2500 || parseInt(division[2]<2000)){
				alert("El a\u00F1o debe estar en el rango 2000-2500");
				campo.value="";
				campo.focus();
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
			//abril
			if(parseInt(division[1])==4){ //abril
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//junio
			if(parseInt(division[1])==6){ //junio
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//septiembre
			if(division[1]=="09"){ //septiembre
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//noviembre
			if(parseInt(division[1])==11){ //noviembre
				if(parseInt(division[0])>30){alert("El dia no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			
			//calculamos el mes en mmm
			var para_mes="";
			if(division[1]=="01"){para_mes="ENE";}
			if(division[1]=="02"){para_mes="FEB";}
			if(division[1]=="03"){para_mes="MAR";}
			if(division[1]=="04"){para_mes="ABR";}
			if(division[1]=="05"){para_mes="MAY";}
			if(division[1]=="06"){para_mes="JUN";}
			if(division[1]=="07"){para_mes="JUL";}
			if(division[1]=="08"){para_mes="AGO";}
			if(division[1]=="09"){para_mes="SEP";}
			if(division[1]=="10"){para_mes="OCT";}
			if(division[1]=="11"){para_mes="NOV";}
			if(division[1]=="12"){para_mes="DIC";}
			
			//todo bien y cambiamos la fecha al formato dd/mmm/yyyy
			var formar_fecha= division[0]+"/"+para_mes+"/"+division[2];
			campo.value=formar_fecha;
			
			
			//para la fecha de vencimiento
			f=division[1]+'/'+division[0]+'/'+division[2]; 
			//alert(f);
			hoy=new Date(f);
			if(meses.value!='0'){
			if(meses.value == "08"){
				hoy.setTime(hoy.getTime()+8*24*60*60*1000);
			}
			else{
				if(meses.value == "09"){
					hoy.setTime(hoy.getTime()+9*24*60*60*1000);
				}
				else{
					//alert(meses.value);
					val_v= parseInt(meses.value);
					hoy.setTime(hoy.getTime()+val_v*24*60*60*1000);
				}
			}
			
			
			
			//verificando que la nueva fecha sea menor a la del sistema
			var hoy_es= new Date();
			//alert(hoy);
			//alert(hoy_es);
			if(hoy<hoy_es){
				alert("Documento Vencido");
			//	observaciones.value= "Documento vencido";
			}
				//alert(hoy);
				var dia_x= hoy.getDate();
				//alert(dia_x);
				if(dia_x<10){
					dia_x='0'+dia_x;
				}
				
				var mes_x=hoy.getMonth()+1;
				//alert(mes_x); 
				var p_mes="";
				if((mes_x==1)){p_mes="ENE";}
				if((mes_x==2)){p_mes="FEB";}
				if((mes_x==3)){p_mes="MAR";}
				if((mes_x==4)){p_mes="ABR";}
				if((mes_x==5)){p_mes="MAY";}
				if((mes_x==6)){p_mes="JUN";}
				if((mes_x==7)){p_mes="JUL";}
				if((mes_x==8)){p_mes="AGO";}
				if((mes_x==9)){p_mes="SEP";}
				if((mes_x==10)){p_mes="OCT";}
				if((mes_x==11)){p_mes="NOV";}
				if((mes_x==12)){p_mes="DIC";}
				
				var fecha_xx= dia_x+'/'+p_mes+'/'+hoy.getFullYear();
				
				fecha_vencimiento.value=fecha_xx;
			}
			campo.focus();
			return true;
		}
	}
	else{
		campo.value=nuevo_valor;
		campo.focus();
		return true;
	}
}


function marcatodo(){
	var cantidad=0;
	var i=0;
	if(confirm("Seguro de marcar todos los documentos como no requeridos?")){
		cantidad=parseInt(document.all["cantidad_total"].value);
		for(i=0; i<cantidad; i++){
			var aux= "tiene_"+i;
			var marca= document.all[aux];
			marca.checked = true;
		}
	}
}
