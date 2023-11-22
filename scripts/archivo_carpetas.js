
function cargar_adicionar(){
	document.adicionar.tipo_carpeta.focus();
}

function cargar_modificar(){
	document.modificar.tipo_carpeta.focus();
}

function cargar_prestar(){
	document.prestar.usuario.focus();
}

function cargar_modificar_prestar(){
	document.modificar_prestar.usuario.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function cargar_eliminar_prestar(){
	document.eliminar_prestar.eliminar_prestamo_boton.focus();
}


function verificar_prestar_mod(){
	document.modificar_prestar.modificar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar_mod(){
	document.modificar_prestar.modificar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
}




function verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}



function verificar_adicion(){
	var txt= document.getElementById("tipo_carpeta").value
	txt = txt.replace(/ /g, "");
	if(txt=='ninguno'){
		alert("Seleccione el tipo de carpeta por favor");
		//document.getElementById("tipo_carpeta").focus();
		return false;
	}
	var txt= document.getElementById("oficina").value
	txt = txt.replace(/ /g, "");
	if(txt=='ninguno'){
		alert("Seleccione la oficina por favor");
		document.getElementById("oficina").focus();
		return false;
	}
	var txt= document.getElementById("operacion").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese el n\u00FAmero de la operaci\u00F3n por favor");
		document.getElementById("operacion").focus();
		return false;
	}
	if(!confirm("Estan todos los datos correctos?"))
		return false;
	/*
	var antes_espacio= nuevo.value;
	var ac= antes_espacio.replace(/ /g, "");
	
	var grados= new Array();
	var dividir= existen.value;
	grados = dividir.split(";");
	var i=0;
	
	if(ac == ""){
		alert("Escriba un"+sex+" "+ mensaje);
		//document.adicionar.item_adicion.focus();
		return false;
	}

	var nuevo_grado= new String("");	
	nuevo_grado= ac.toLowerCase();
	
	var comparacion= new String("");
	while(grados[i] != ""){
		comparacion= grados[i].toLowerCase();
		sin_espacio= comparacion.replace(/ /g, "");

		if(nuevo_grado == sin_espacio){
			alert("ya existe un"+sex+" "+mensaje + " con el mismo nombre\nEscriba otro nombre");
			//document.adicionar.item_adicion.focus();
			return false;
		}
		i++;
	}
	//para los dias
	var antes_direccion= direccion.value;
	var ver_direccion= antes_direccion.replace(/ /g, "");
	if(ver_direccion == ""){
		alert("Debe escribir una direcci\u00F3n");
		//document.adicionar.item_dias.focus();
		return false;
	}
	*/
	document.adicionar.adicionar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	return true;
}
function des_verificar_adicion(){
	document.adicionar.adicionar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	return true;
}


function verificar_adicion_mod(){
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}
function des_verificar_adicion_mod(){
	document.modificar.modificar_boton.disabled=true;
	document.modificar_cancelar.modificar_boton_cancelar.disabled=true;
	return true;
}



function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
			document.eliminar.eliminar_boton.disabled=true;
			document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
			return true;
		}else{
			return false;
		}
	}else{
		//alert("No puede eliminar " +sex+" "+que +"\nPorque existen registros asociados");
		//return false;
		if( confirm("Existen documentos registrados en esta carpeta!\nEsta Seguro de querer eliminarla? ") ){
			document.eliminar.eliminar_boton.disabled=true;
			document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
			return true;
		}else{
			return false;
		}
	}
}

function des_eliminar_confirmar(){
	document.eliminar.eliminar_boton.disabled=true;
	document.eliminar_cancelar.eliminar_boton_cancelar.disabled=true;
}


function eliminar_prestamo_confirmar(sex, que){
	if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
		document.eliminar_prestar.eliminar_prestamo_boton.disabled=true;
		document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
		return true;
	}
	else{
		return false;
	}
}
function des_eliminar_prestamo_confirmar(){
	document.eliminar_prestar.eliminar_prestamo_boton.disabled=true;
	document.modificar_prestar_cancelar.prestar_boton_cancelar.disabled=true;
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


//para la fecha y su vencimiento
//para la fecha
function colocar_fecha_vencimiento(campo, meses, fecha_vencimiento, observaciones){
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
				observaciones.value= "Documento vencido";
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

function ValidNum(e) {
	var tecla= window.event ? tecla = e.keyCode : tecla = e.which;
	return ((tecla > 47 && tecla < 58) || tecla == 46); 
}