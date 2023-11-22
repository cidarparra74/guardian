
 
function cargar_formulario(){
	document.formulario.inf_nro_esc.focus();
}

function cargar_formulario_hab(){
	document.formulario.habilitando_informe.focus();
}

function cargar_formulario_des(){
	document.formulario.deshabilitando_informe.focus();
}

function validar(form){
	
	//para el n\u00FAmero
	var antes_valor= form.inf_nro_esc.value;
	//var ver_valor= antes_valor.replace(/ /g, "");
	if(antes_valor == "0"){
		alert("Debe indicar un n\u00FAmero de escritura.");
		form.inf_nro_esc.focus();
		return false;
	}
	//inf_fch_ini
	var antes_valor= form.inf_fch_ini.value;
	//var ver_valor= antes_valor.replace(/ /g, "");
	if(antes_valor == "0"){
		alert("Debe indicar la fecha inicial.");
		form.inf_fch_ini.focus();
		return false;
	}
	
	var antes_valor= form.inf_fch_fin.value;
	//var ver_valor= antes_valor.replace(/ /g, "");
	if(antes_valor == "0"){
		alert("Debe indicar la fecha final.");
		form.inf_fch_fin.focus();
		return false;
	}
	return true;
}

function validar_numeros(campo, maximo){
	maximo_valor= parseInt(maximo);
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0" ){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	if(parseFloat(nuevo_valor)>maximo_valor){
		alert("El n\u00FAmero no debe ser mayor a : " + maximo);
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
				//if(parseInt(division[0])>28){alert("El dia no puede ser mayor a 28"); campo.value=""; campo.focus(); return false;}
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


function Trim(s) {
   	// Remove leading spaces and carriage returns
   	while ((s.substring(0,1) == ' ') || (s.substring(0,1) == '\n') || (s.substring(0,1) == '\r'))
   	 { s = s.substring(1,s.length); }
     
   	// Remove trailing spaces and carriage returns
	 while ((s.substring(s.length-1,s.length) == ' ') || (s.substring(s.length-1,s.length) == '\n') || (s.substring(s.length-1,s.length) == '\r'))
		 { s = s.substring(0,s.length-1); }
		 
	return s;
}

function trim(cad)
{
	return cad.replace(/^\s+|\s+$/g,"");
}

