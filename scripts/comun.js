/************* para  funciones en comun *********************/
//----------------------------------------------------------------------------------------------

function estableceFoco(control){
	control.focus();
}

function enviaform(frnNAME){ 
	document.getElementById(frnNAME).submit();
}

// *****************************
// Marcelo

function novacio(campo,tamano,tamano_min,nombre){
  if (tamano <= tamano_min) {
    alert("Escriba " + nombre + " por favor..." );
    campo.focus();
    campo.select();
    return false;
  } else {
    return true;
  }
}

function alfanum(campo) {
  var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmn\u00F1opqrstuvwxyz\u00E1\u00E9\u00ED\u00F3\u00FA " + 
  				"-.,'" + "0123456789";  // contiene tb  espacio (vacio).
  var checkStr = campo.value;
  var allValid = true;
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i);
    for (j = 0; j < checkOK.length; j++)
      if (ch == checkOK.charAt(j))
        break;
    if (j == checkOK.length) {
      allValid = false;
      break;
    }
  }
  if (!allValid) {
    alert("Escriba s\u00F3lo alfan\u00FAmericos...");
    campo.focus();
	campo.select();
    return (false);
  }else {
    return true;
  }
}

function letras(campo) {
  var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmn\u00F1opqrstuvwxyz\u00E1\u00E9\u00ED\u00F3\u00FA ";
  var checkStr = campo.value;
  var allValid = true;
  for (i = 0; i < checkStr.length; i++) {
    ch = checkStr.charAt(i);
    for (j = 0; j < checkOK.length; j++)
      if (ch == checkOK.charAt(j))
        break;
    if (j == checkOK.length) {
      allValid = false;
      break;
    }
  }
  if (!allValid) {
    alert("Escriba s\u00F3lo letras...");
    campo.focus();
	campo.select();
    return (false);
  }else {
    return true;
  }
}
  
function numeros(campo){
	var checkOK = "0123456789.,";
  	var checkStr = campo.value;
  	var allValid = true;
	var decPoints = 0;
	var allNum = "";
	for (i = 0; i < checkStr.length; i++) {
		ch = checkStr.charAt(i);
		for (j = 0; j < checkOK.length; j++)
		  if (ch == checkOK.charAt(j))
			break;
		if (j == checkOK.length) {
		  allValid = false;
		  break;
		}
		allNum += ch;
	}
	  if (!allValid) {
		alert("Escriba s\u00F3lo n\u00FAmeros.");
		campo.focus();
		campo.select();
		return (false);
	  } else {
    return true;
  }
}  

// copyright 1999 Idocs, Inc. http://www.idocs.com
// Distribute this script freely but keep this notice in place
function numbersonly(myfield, e, dec)
{
	var key;
	var keychar;
	if (window.event)
		key = window.event.keyCode;
	else if (e)
			key = e.which;
		else
			return true;
	keychar = String.fromCharCode(key);
	// control keys
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
	return true;
	// numbers
	else if ((("0123456789.").indexOf(keychar) > -1))
		return true;
	else
		return false;
}

// para verificar si un numero es par o impar
function espar(campo){

	if(campo.value %2 == 0)
		return true;
	else{	alert("Escriba solo n\u00FAmeros pares!!!");
			campo.focus();
			campo.select();
			return false;
		}
		
}

function rangofechasvalido(inicio, final){
	fec1 = inicio.split("-");
	fec2 = final.split("-");
	
	fechaini = new Date();
	fechaini.setFullYear(fec1[2], eval(fec1[1])-1, fec1[0]);
	fechafin = new Date();
	fechafin.setFullYear(fec2[2], eval(fec2[1])-1, fec2[0]);
	
	if (fechaini > fechafin)		
		return false;	
	else
		return true;
}

// selecciona todos los elementos de una lista con seleccion multiple
function marcarTodos(id)
{	
	if (document.getElementById(id) != null)
	{		
		var els = document.getElementById(id).options;
    	for(i = 0; i < els.length; i++)
        	els[i].selected = true;	
	}
}

// verifica si la fecha tiene el formato correcto
function esFechaValida(fecha){
	if (fecha != undefined && fecha.value != "" ){
		var fecval = fecha.value ;
		//fecval = fecval.replace(/\\//g, '-');
		//alert(fecval);
		if (!/^\d{2}\-\d{2}\-\d{4}$/.test(fecval)){
			alert("Formato de fecha no v\u00E1lido (dd-mm-aaaa)");
			return false;
		}
		var dia  =  parseInt(fecval.substring(0,2),10);
		var mes  =  parseInt(fecval.substring(3,5),10);
		var anio =  parseInt(fecval.substring(6),10);
		numDias=31;
		switch(mes){
			case 4: case 6: case 9: case 11:
				numDias=30;
				break;
			case 2:
				if (esBisiesto(anio)){ numDias=29 }else{ numDias=28};
				break;
		}
		if (dia>numDias || dia==0){
			alert("Fecha introducida err\u00F3nea");
			return false;
		}
		if (mes>12 || mes==0){
			alert("Fecha introducida err\u00F3nea");
			return false;
		}
		return true;
	}
}

function esBisiesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
	return true;
	}
else {
	return false;
	}
}