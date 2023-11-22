
ModalPopups.SetDefaults( { 
yesButtonText: "Si", 
cancelButtonText: "Cancelar", 
shadow: false, 
titleBackColor: "#CCE6FF", 
titleFontColor: "#404040", 
popupBackColor: "#ffffff",
footerBackColor: "#CCE6FF", 
footerFontColor: "#404040"
} );


function verificar4(frm){
//adicionar4.html
	//validamos campos vacios
	//var sStr = "";
	var tipo = "";
	//var frm=document.frm;
	for (i=0;i<frm.elements.length;i++){
		//frm.elements[i].name;
		tipo = frm.elements[i].type;
		//alert(tipo);
		if(tipo=='text' || tipo=='textarea'){
			if(frm.elements[i].value==''){
				alert("No puede dejar casillas en blanco!");
				frm.elements[i].focus()
				return false;
			}
		} // ver demas casos: textarea; select-one
		
		//sStr += "VALOR: " + frm.elements[i].value + "\n" ;
	}
	//alert(sStr);
	return true;
}

function des_verificar_adicion(){
	if(confirm("Esta seguro de cancelar la elaboraci\u00F3n de este contrato?")){
	document.adicionar.boton_cancelar.disabled=true;
	document.adicionar_cancelar.submit();
	return true;
	}else{ 
	return false;}
}

function isNumber(n) {   
return !isNaN(parseFloat(n)) && isFinite(n); 
}


function monedax(campo,mone) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	if(tam==0){
		alert("No ingreso ningun valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(isNumber(txt)){
			if(mone=='B'){
				lmone = ' Bolivianos)';
				smone = 'Bs ';
			}else if(mone=='U'){
				lmone = ' D\u00F3lares de los Estados Unidos de America)'; 
				smone = 'US$ ';
			}else {
				lmone = ' Euros)'; 
				smone = '€ '; 
			}
			txt = txt.replace(/-/g,'');
			txt = formato_numero(txt,2,".","");
			resulta=covertirNumLetras(txt);
			txt = formato_numero(txt,2,".",",");
			resulta=smone+txt+" ("+resulta+lmone;
			document.getElementById(campo).value = resulta;
		}else
			alert("Formato num\u00E9rico incorrecto, no se admiten letras ni comas\n Ej: 9999.99");
	}
}

/*
 * Da formato a un n\u00FAmero para su visualizaci\u00F3n
 *
 * numero (Number o String) - N\u00FAmero que se mostrar\u00E1
 * decimales (Number, opcional) - Nº de decimales (por defecto, auto)
 * separador_decimal (String, opcional) - Separador decimal (por defecto, coma)
 * separador_miles (String, opcional) - Separador de miles (por defecto, ninguno)
 */
function formato_numero(numero, decimales, separador_decimal, separador_miles){ // v2007-08-06
    numero=parseFloat(numero);
    if(isNaN(numero)){
        return "";
    }

    if(decimales!==undefined){
        // Redondeamos
        numero=numero.toFixed(decimales);
    }

    // Convertimos el punto en separador_decimal
    numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");

    if(separador_miles){
        // A\u00F1adimos los separadores de miles
        var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
        while(miles.test(numero)) {
            numero=numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }

    return numero;
}


function moneda(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	if(tam==0){
		alert("No ingreso ningun valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(isNumber(txt))
			wdPedirDatos(campo,txt);
		else
			alert("Formato num\u00E9rico incorrecto, no se admiten letras ni comas\n Ej: 9999.99");
		//alert(txt.replace(/-/g,''));
	}
}

function porcentaje(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		alert("No ingreso ningun valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			nroydec = txt.split('.');
			partedec = nroydec[1];
			if (partedec == 0 || partedec == undefined){
				partedec = "00";
			}
			lendec = partedec.length;
			if(lendec < 2) lendec = 2;
			if(lendec > 3) lendec = 3;
			//alert(txt); 
			txt = formato_numero(txt,lendec,".","");
			//alert(txt); 
			resulta=covertirNumDeci(txt);
			txt = formato_numero(txt,lendec,".",",");
			resulta=txt+"% ("+resulta+" POR CIENTO)";
			//alert(resulta);
			document.getElementById(campo).value = resulta;
		}else{
			alert("Formato num\u00E9rico incorrecto, no se admiten letras ni comas\n Ej: 99.99");
		}
	}
}

function porcentajeBlur(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		return true;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			nroydec = txt.split('.');
			partedec = nroydec[1];
			if (partedec == 0 || partedec == undefined){
				partedec = "00";
			}
			lendec = partedec.length;
			if(lendec < 2) lendec = 2;
			if(lendec > 3) lendec = 3;
			//alert(txt); 
			txt = formato_numero(txt,lendec,".","");
			//alert(txt); 
			resulta=covertirNumDeci(txt);
			txt = formato_numero(txt,lendec,".",",");
			resulta=txt+"% ("+resulta+" POR CIENTO)";
			document.getElementById(campo).value = resulta;
		}
	}
	return true;
}

function decimal(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		alert("No ingres\u00F3 ning\u00FAn valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			nroydec = txt.split('.');
			partedec = nroydec[1];
			if (partedec == 0 || partedec == undefined){
				partedec = "00";
			}
			lendec = partedec.length;
			if(lendec < 2) lendec = 2;
			if(lendec > 3) lendec = 3;
			//alert(txt); 
			txt = formato_numero(txt,lendec,".","");
			//alert(txt); 
			resulta=covertirNumDeci(txt);
			txt = formato_numero(txt,lendec,".",",");
			resulta=txt+" ("+resulta+")";
			document.getElementById(campo).value = resulta;
		}else{
			alert("Formato num\u00E9rico incorrecto, no se admiten letras ni comas\n Ej: 9999.99");
			return false;
		}
	}
	return true;
}

function decimalBlur(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		return true;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			nroydec = txt.split('.');
			partedec = nroydec[1];
			if (partedec == 0 || partedec == undefined){
				partedec = "00";
			}
			lendec = partedec.length;
			if(lendec < 2) lendec = 2;
			if(lendec > 3) lendec = 3;
			//alert(txt); 
			txt = formato_numero(txt,lendec,".","");
			//alert(txt); 
			resulta=covertirNumDeci(txt);
			txt = formato_numero(txt,lendec,".",",");
			resulta=txt+" ("+resulta+")";
			document.getElementById(campo).value = resulta;
		}
	}
	return true;
}

function entero(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		alert("No ingres\u00F3 ning\u00FAn valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			txt = formato_numero(txt,0,".","");
			resulta=covertirNumEntero(txt);
			resulta=txt+" ("+resulta+")";
			document.getElementById(campo).value = resulta;
		}else{
			alert("Formato num\u00E9rico incorrecto, no se admiten letras ni comas\n Ej: 9999");
			return false;
		}
	}
	return true;
}

function enteroBlur(campo) {
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value
	var resulta='';
	if(tam==0){
		return true;
	}else{
		if(isNumber(txt)){
			txt=txt.replace(/-/g,'');
			txt = formato_numero(txt,0,".","");
			resulta=covertirNumEntero(txt);
			resulta=txt+" ("+resulta+")";
			document.getElementById(campo).value = resulta;
		}
	}
	return true;
}

function wdPedirDatos(pcual,pque) {
	var comando = 'wdContinuar("'+pcual+'")';
	ModalPopups.Custom("idCustom1",
        "Ingrese tipo de moneda",   
       "<table><tr><td>Moneda:</td><td><label><input type='radio' name='group1' id='group1' value='Bs' checked> Bolivianos</label><br>"+
	   "<label><input type='radio' name='group1' id='group1' value='Us'> Dolares</label><br><input type='hidden' name='nro' id='nro' value='"+
	   pque+"'></td></tr></table>" ,    
        {
            width: 500,
            buttons: "ok,cancel",
            okButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            onOk: comando,
            onCancel: "wdCancelar()"
        }
    );
} 
  
function wdContinuar(xcual) {
   var xMon = ModalPopups.GetCustomControl("group1");
	var xNro = ModalPopups.GetCustomControl("nro");
	var lmone='';
	var smone='';
	var resulta='';
	var valor = xNro.value
	if(xMon[0].checked){
		lmone = ' Bolivianos)';
		smone = 'Bs ';
	}else{
		lmone = ' D\u00F3lares Americanos)'; 
		smone = 'us$ ';
    }
	valor = valor.replace(/-/g,'');
	resulta=covertirNumLetras(valor);
	resulta=smone+valor+" ("+resulta+lmone;
	document.getElementById(xcual).value = resulta;
	ModalPopups.Close("idCustom1");
}

function wdCancelar() {    
    ModalPopups.Cancel("idCustom1");
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

function trim(s){
s = s.replace(/\s+/gi, ' '); //sacar espacios repetidos dejando solo uno
s = s.replace(/^\s+|\s+$/gi, ''); //sacar espacios blanco principio y final
return s;
}

function mod(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  parteEnt = Math.floor(resDiv);            // Obtiene la parte Entera de resDiv 
  parteFrac = resDiv - parteEnt ;      // Obtiene la parte Fraccionaria de la divisi\u00F3n
  //modulo = parteFrac * divisor;  // Regresa la parte fraccionaria * la divisi\u00F3n (modulo) 
  modulo = Math.round(parteFrac * divisor)
  return modulo; 
} // Fin de funci\u00F3n mod
 
// Funci\u00F3n ObtenerParteEntDiv, regresa la parte entera de una divisi\u00F3n
function ObtenerParteEntDiv(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  parteEntDiv = Math.floor(resDiv);
  return parteEntDiv; 
} // Fin de funci\u00F3n ObtenerParteEntDiv
 
// function fraction_part, regresa la parte Fraccionaria de una cantidad
function fraction_part(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  f_part = Math.floor(resDiv); 
  return f_part; 
} // Fin de funci\u00F3n fraction_part
 
 
// function string_literal conversion is the core of this program 
// converts numbers to spanish strings, handling the general special 
// cases in spanish language. 
function string_literal_conversion(number) 
{   
   // first, divide your number in hundreds, tens and units, cascadig 
   // trough subsequent divisions, using the modulus of each division 
   // for the next. 
 
   centenas = ObtenerParteEntDiv(number, 100); 
   
   number = mod(number, 100); 
 
   decenas = ObtenerParteEntDiv(number, 10); 
   number = mod(number, 10); 
 
   unidades = ObtenerParteEntDiv(number, 1); 
   number = mod(number, 1);  
   string_hundreds="";
   string_tens="";
   string_units="";
   // cascade trough hundreds. This will convert the hundreds part to 
   // their corresponding string in spanish.
   if(centenas == 1){
      string_hundreds = "ciento ";
   } 
   
   
   if(centenas == 2){
      string_hundreds = "doscientos ";
   }
    
   if(centenas == 3){
      string_hundreds = "trescientos ";
   } 
   
   if(centenas == 4){
      string_hundreds = "cuatrocientos ";
   } 
   
   if(centenas == 5){
      string_hundreds = "quinientos ";
   } 
   
   if(centenas == 6){
      string_hundreds = "seiscientos ";
   } 
   
   if(centenas == 7){
      string_hundreds = "setecientos ";
   } 
   
   if(centenas == 8){
      string_hundreds = "ochocientos ";
   } 
   
   if(centenas == 9){
      string_hundreds = "novecientos ";
   } 
   
 // end switch hundreds 
 
  // casgade trough tens. This will convert the tens part to corresponding 
  // strings in spanish. Note, however that the strings between 11 and 19 
  // are all special cases. Also 21-29 is a special case in spanish. 
   if(decenas == 1){
      //Special case, depends on units for each conversion
      if(unidades == 1){
         string_tens = "once";
      }
      
      if(unidades == 2){
         string_tens = "doce";
      }
      
      if(unidades == 3){
         string_tens = "trece";
      }
      
      if(unidades == 4){
         string_tens = "catorce";
      }
      
      if(unidades == 5){
         string_tens = "quince";
      }
      
      if(unidades == 6){
         string_tens = "dieciseis";
      }
      
      if(unidades == 7){
         string_tens = "diecisiete";
      }
      
      if(unidades == 8){
         string_tens = "dieciocho";
      }
      
      if(unidades == 9){
         string_tens = "diecinueve";
      }
   } 
   //alert("STRING_TENS ="+string_tens);
   
   if(decenas == 2){
      string_tens = "veinti";
 
   }
   if(decenas == 3){
      string_tens = "treinta";
   }
   if(decenas == 4){
      string_tens = "cuarenta";
   }
   if(decenas == 5){
      string_tens = "cincuenta";
   }
   if(decenas == 6){
      string_tens = "sesenta";
   }
   if(decenas == 7){
      string_tens = "setenta";
   }
   if(decenas == 8){
      string_tens = "ochenta";
   }
   if(decenas == 9){
      string_tens = "noventa";
   }
   
    // Fin de swicth decenas
 
 
   // cascades trough units, This will convert the units part to corresponding 
   // strings in spanish. Note however that a check is being made to see wether 
   // the special cases 11-19 were used. In that case, the whole conversion of 
   // individual units is ignored since it was already made in the tens cascade. 
 
   if (decenas == 1) 
   { 
      string_units="";  // empties the units check, since it has alredy been handled on the tens switch 
   } 
   else 
   { 
      
	  if(unidades == 1){
         string_units = "un";
      }
      if(unidades == 2){
         string_units = "dos";
      }
      if(unidades == 3){
         string_units = "tres";
      }
      if(unidades == 4){
         string_units = "cuatro";
      }
      if(unidades == 5){
         string_units = "cinco";
      }
      if(unidades == 6){
         string_units = "seis";
      }
      if(unidades == 7){
         string_units = "siete";
      }
      if(unidades == 8){
         string_units = "ocho";
      }
      if(unidades == 9){
         string_units = "nueve";
      }
       // end switch units 
   } // end if-then-else 
   
 
//final special cases. This conditions will handle the special cases which 
//are not as general as the ones in the cascades. Basically four: 
 
// when you've got 100, you dont' say 'ciento' you say 'cien' 
// 'ciento' is used only for [101 >= number > 199] 
if (centenas == 1 && decenas == 0 && unidades == 0) 
{ 
   string_hundreds = "cien " ; 
}  
 
// when you've got 10, you don't say any of the 11-19 special 
// cases.. just say 'diez' 
if (decenas == 1 && unidades ==0) 
{ 
   string_tens = "diez " ; 
} 
 
// when you've got 20, you don't say 'veinti', which is used 
// only for [21 >= number > 29] 
if (decenas == 2 && unidades ==0) 
{ 
  string_tens = "veinte " ; 
} 
 
// for numbers >= 30, you don't use a single word such as veintiuno 
// (twenty one), you must add 'y' (and), and use two words. v.gr 31 
// 'treinta y uno' (thirty and one) 
if (decenas >=3 && unidades >=1) 
{ 
   string_tens = string_tens+" y "; 
} 
  
// this line gathers all the hundreds, tens and units into the final string 
// and returns it as the function value.
final_string = string_hundreds+string_tens+string_units;
return final_string ; 
 
} //end of function string_literal_conversion()================================ 


// handle some external special cases. Specially the millions, thousands 
// and hundreds descriptors. Since the same rules apply to all number triads 
// descriptions are handled outside the string conversion function, so it can 
// be re used for each triad. 
 function formatNumber2(number) {   
 return Math.max(0, number).toFixed(0).replace(/(?=(?:\d{3})+$)(?!^)/g, ',');  
 }
 
function covertirNumLetras(number)
{
   // number = formatNumber2(number);

   number1=number.toString(); 
   number1=number1.replace(/-/g,''); 
   //settype (number, "integer");
   cent = number1.split(".");   
   centavos = cent[1];
   //Mind Mod
   number=cent[0] * 1;
   
  if (centavos == '0' || centavos == undefined){
        centavos = "00";
   }
 
   if (number == 0 || number == "") 
   { // if amount = 0, then forget all about conversions, 
      centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
      // function breakdown 
	  millions_final_string="";
	  thousands_final_string = ""; 
  }else{ 
   
     millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
      number = mod(number, 1000000);           // conversion function 
      
     if (millions != 0)
      {                      
      // This condition handles the plural case 
         if (millions == 1){              
		 // if only 1, use 'millon' (million). if 
            descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
         }else{                           // a descriptor for this triad. 
              descriptor = " millones "; 
         } 
      }else{    
         descriptor = " ";                 // if 0 million then use no descriptor. 
      } 
      millions_final_string = string_literal_conversion(millions)+descriptor; 
      
      thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
      //print "Th:".thousands;
     if (thousands != 1) 
      {                   // This condition eliminates the descriptor 
         thousands_final_string =string_literal_conversion(thousands) + " mil "; 
       //  descriptor = " mil ";          // if there are no thousands on the amount 
      } 
      if (thousands == 1)
      {
         thousands_final_string = " un mil "; 
     }
      if (thousands < 1) 
      { 
         thousands_final_string = " "; 
      } 
  
      // this will handle numbers between 1 and 999 which 
      // need no descriptor whatsoever. 
 
     centenas  = number;                     
      centenas_final_string = string_literal_conversion(centenas) ; 
      
   } //end if (number ==0) 
 
   /*if (ereg("un",centenas_final_string))
   {
     centenas_final_string = ereg_replace("","o",centenas_final_string); 
   }*/
   //finally, print the output. 
 
   /* Concatena los millones, miles y cientos*/
   cad = millions_final_string+thousands_final_string+centenas_final_string; 
   
   /* Convierte la cadena a May\u00FAsculas*/
   cad = cad.toUpperCase();       
 
   if (centavos.length>2)
   {  
      if(centavos.substring(2,3)>= 5){
         centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
      } else{
         centavos = centavos.substring(0,1);
      }
   }
 
   /* Concatena a los centavos la cadena "/100" */
   if (centavos.length==1){
      centavos = centavos+"0";
   }
   centavos = centavos+ "/100"; 
 
   /* Regresa el n\u00FAmero en cadena entre par\u00E9ntesis y con tipo de moneda y la fase M.N.*/
   //Mind Mod, si se deja MIL pesos y se utiliza esta funci\u00F3n para imprimir documentos
   //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
   if(cad == ' MIL ')
   {
        cad=' UN MIL ';
   }
   
  // alert( "FINAL="+cad+centavos);
   return trim(cad+" "+centavos);
}


function covertirNumPorcen(number)
{
   number1=number.toString(); 
    number1=number1.replace(/%/g,'');
   cent = number1.split(".");   
   centavos = cent[1] * 1;
   number=cent[0] * 1;
   
   if (cent[1] == 0 || cent[1] == undefined){
        centavos = "00";
   }
 
   if (number == 0 || number == ""){ // if amount = 0, then forget all about conversions, 
      centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
	  millions_final_string="";
	  thousands_final_string = ""; 
  }else{ 
   
     millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
      number = mod(number, 1000000);           // conversion function 
      
     if (millions != 0)
      {                      
      // This condition handles the plural case 
         if (millions == 1) 
         {              // if only 1, use 'millon' (million). if 
            descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
         } else{                           // a descriptor for this triad. 
              descriptor = " millones "; 
            } 
      } 
      else 
      {    
         descriptor = " ";                 // if 0 million then use no descriptor. 
      } 
      millions_final_string = string_literal_conversion(millions)+descriptor; 
          
      
      thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
      //print "Th:".thousands;
     if (thousands != 1) 
      {                   // This condition eliminates the descriptor 
         thousands_final_string =string_literal_conversion(thousands) + " mil "; 
       //  descriptor = " mil ";          // if there are no thousands on the amount 
      } 
      if (thousands == 1)
      {
         thousands_final_string = " un mil "; 
     }
      if (thousands < 1) 
      { 
         thousands_final_string = " "; 
      } 
  
      // this will handle numbers between 1 and 999 which 
      // need no descriptor whatsoever. 
 
     centenas  = number;                     
      centenas_final_string = string_literal_conversion(centenas) ; 
      
   } //end if (number ==0) 
 
   /*if (ereg("un",centenas_final_string))
   {
     centenas_final_string = ereg_replace("","o",centenas_final_string); 
   }*/
   //finally, print the output. 
 
   /* Concatena los millones, miles y cientos*/
   cad = millions_final_string+thousands_final_string+centenas_final_string; 
   
   /* Convierte la cadena a May\u00FAsculas*/
   cad = cad.toUpperCase();       
 
   if (centavos.length>2)
   {  
      if(centavos.substring(2,3)>= 5){
         centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
      } else{
         centavos = centavos.substring(0,1);
      }
   }
 
   /* Concatena a los centavos la cadena "/100" */
   if (centavos.length==1){
      centavos = centavos+"0";
   }
    if (centavos*1==0)
		centavos = 'cero';
	else
		centavos = string_literal_conversion(centavos);
    
	centavos = centavos.toUpperCase();
   /* Regresa el n\u00FAmero en cadena entre par\u00E9ntesis y con tipo de moneda y la fase M.N.*/
   //Mind Mod, si se deja MIL pesos y se utiliza esta funci\u00F3n para imprimir documentos
   //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
   if(cad == ' MIL ')
   {
        cad=' UN MIL ';
   }
   
  // alert( "FINAL="+cad+centavos);
   return trim(cad+" PUNTO "+centavos);
}



function covertirNumDeci(number)
{

   number1=number.toString(); 
    number1=number1.replace(/%/g,'');
   cent = number1.split(".");   
   centavos = cent[1];
   number=cent[0] * 1;
   
   if (cent[1]*1 == 0 || cent[1] == undefined){
        centavos = "00";
   }
 
   if (number == 0 || number == ""){ // if amount = 0, then forget all about conversions, 
      centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
	  millions_final_string="";
	  thousands_final_string = ""; 
  }else{ 
   
     millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
      number = mod(number, 1000000);           // conversion function 
      
     if (millions != 0)
      {                      
      // This condition handles the plural case 
         if (millions == 1) 
         {              // if only 1, use 'millon' (million). if 
            descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
         } else{                           // a descriptor for this triad. 
              descriptor = " millones "; 
            } 
      } else {    
         descriptor = " ";                 // if 0 million then use no descriptor. 
      } 
      millions_final_string = string_literal_conversion(millions)+descriptor; 
          
      
      thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
      //print "Th:".thousands;
     if (thousands != 1) 
      {                   // This condition eliminates the descriptor 
         thousands_final_string =string_literal_conversion(thousands) + " mil "; 
       //  descriptor = " mil ";          // if there are no thousands on the amount 
      } 
      if (thousands == 1)
      {
         thousands_final_string = " un mil "; 
     }
      if (thousands < 1) 
      { 
         thousands_final_string = " "; 
      } 
  
      // this will handle numbers between 1 and 999 which 
      // need no descriptor whatsoever. 
 
     centenas  = number;                     
      centenas_final_string = string_literal_conversion(centenas) ; 
      
   } //end if (number ==0) 
 
   //finally, print the output. 
 
   /* Concatena los millones, miles y cientos*/
   cad = millions_final_string+thousands_final_string+centenas_final_string; 
   
   /* Convierte la cadena a May\u00FAsculas*/
   cad = cad.toUpperCase();       
/*
   if (centavos.length>2)
   {  
      
	  if(centavos.substring(2,1)>= 5){
         centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
      } else{
         centavos = centavos.substring(0,1);
      }
   }
   */
// alert(centavos.substring(0,1));
	if (centavos.length==1){
		centavos = centavos+"0";
	}
	if (centavos*1==0)
		centavos = "cero";
	else{
		//vemos casos 0.0X y 0.00X
		if (centavos.substring(0,1) == "0" && centavos.substring(1,1) != "0")
			centavos = "CERO " + string_literal_conversion(centavos); 
		else{
			if(centavos.substring(0,2) == "00")
				centavos = "CERO CERO " + string_literal_conversion(centavos); 
			else
				centavos = string_literal_conversion(centavos); 
		}
	}
	centavos = centavos.toUpperCase();
   /* Regresa el n\u00FAmero en cadena entre par\u00E9ntesis y con tipo de moneda y la fase M.N.*/
   //Mind Mod, si se deja MIL pesos y se utiliza esta funci\u00F3n para imprimir documentos
   //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
   if(cad == ' MIL ')
   {
        cad=' UN MIL ';
   }
  // alert ('*'+cad+'*');
   if(trim(cad) == 'UN')
   {
        cad='UN0';
   }

   return trim(cad+" PUNTO "+centavos);
}


function covertirNumEntero(number)
{

   number1=number.toString(); 
    number1=number1.replace(/%/g,'');
   cent = number1.split(".");   
  // centavos = cent[1] * 1;
   number=cent[0] * 1;
   
  // if (centavos == 0 || centavos == undefined){
  //      centavos = "00";
  // }
 
   if (number == 0 || number == ""){ // if amount = 0, then forget all about conversions, 
      centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
	  millions_final_string="";
	  thousands_final_string = ""; 
  }else{ 
   
     millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
      number = mod(number, 1000000);           // conversion function 
      
     if (millions != 0)
      {                      
      // This condition handles the plural case 
         if (millions == 1) 
         {              // if only 1, use 'millon' (million). if 
            descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
         } else{                           // a descriptor for this triad. 
              descriptor = " millones "; 
            } 
      } else {    
         descriptor = " ";                 // if 0 million then use no descriptor. 
      } 
      millions_final_string = string_literal_conversion(millions)+descriptor; 
          
      
      thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
      //print "Th:".thousands;
     if (thousands != 1) 
      {                   // This condition eliminates the descriptor 
         thousands_final_string =string_literal_conversion(thousands) + " mil "; 
       //  descriptor = " mil ";          // if there are no thousands on the amount 
      } 
      if (thousands == 1)
      {
         thousands_final_string = " un mil "; 
     }
      if (thousands < 1) 
      { 
         thousands_final_string = " "; 
      } 
  
      // this will handle numbers between 1 and 999 which 
      // need no descriptor whatsoever. 
 
     centenas  = number;                     
      centenas_final_string = string_literal_conversion(centenas) ; 
      
   } //end if (number ==0) 
 
   //finally, print the output. 
 
   /* Concatena los millones, miles y cientos*/
   cad = millions_final_string+thousands_final_string+centenas_final_string; 
   
   /* Convierte la cadena a May\u00FAsculas*/
   cad = cad.toUpperCase();       
 /*
   if (centavos.length>2)
   {  
      if(centavos.substring(2,3)>= 5){
         centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
      } else{
         centavos = centavos.substring(0,1);
      }
   }
 
   // Concatena a los centavos la cadena "/100" 
   if (centavos.length==1){
      centavos = centavos+"0";
   }
   centavos = string_literal_conversion(centavos); 
 */
   /* Regresa el n\u00FAmero en cadena entre par\u00E9ntesis y con tipo de moneda y la fase M.N.*/
   //Mind Mod, si se deja MIL pesos y se utiliza esta funci\u00F3n para imprimir documentos
   //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
   if(cad == ' MIL ')
   {
        cad=' UN MIL ';
   }

   return trim(cad);
}

/* Devuelve si una cadena "dd/mm/yyyy" o
"dd-mm-yyyy" o "dd.mm.yyyy" es una fecha v\u00E1lida */
function esFecha (strValue){
  //check to see if its in a correct format
  var objRegExp = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/

  if(!objRegExp.test(strValue))
    return false; //doesn't match pattern, bad date
  else {
    var strSeparator = strValue.substring(2,3);
    //create a lookup for months not equal to Feb.
    var arrayDate = strValue.split(strSeparator);

    var arrayLookup = { '01' : 31,'03' : 31,
      '04' : 30,'05' : 31,
      '06' : 30,'07' : 31,
      '08' : 31,'09' : 30,
      '10' : 31,'11' : 30,'12' : 31
    }

    var intDay = parseInt(arrayDate[0],10);
    var intMonth = parseInt(arrayDate[1],10);
    var intYear = parseInt(arrayDate[2],10);
    //check if month value and day value agree

    if (arrayLookup[arrayDate[1]] != null) {
      if (intDay <= arrayLookup[arrayDate[1]] && intDay != 0
        && intYear > 1975 && intYear < 2050)
        return true;     //found in lookup table, good date
    }

    //check for February (bugfix 20050322)
    //bugfix for parseInt kevin
    //bugfix biss year  O.Jp Voutat

    if (intMonth == 2) {
      var intYear = parseInt(arrayDate[2]);

      if (intDay > 0 && intDay < 29) {
        return true;
      }
      else if (intDay == 29) {
        if ((intYear % 4 == 0) && (intYear % 100 != 0) ||
            (intYear % 400 == 0)) {
          // year div by 4 and ((not div by 100) or div by 400) ->ok
          return true;
        }
      }
    }
  }

  return false; //any other values, bad date
}

function fechalarga(campo){
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value;
	var resulta='';
	if(tam==0){
		alert("No ingreso ningun valor!");
		document.getElementById(campo).focus();
		return false;
	}else{
		if(esFecha(txt)){
			txt=txt.replace(/\-/g,'/');
			txt=txt.replace(/\./g,'/');
			datofec = txt.split("/");
			resulta=covertirFecLit(datofec[1]); 
			resulta=datofec[0]+" de "+resulta+" de "+datofec[2];
			document.getElementById(campo).value = resulta;
		}else{
			alert("Formato de fecha incorrecto, ingrese dd/mm/yyyy");
			return false;
		}
	}
	return true;
}


function fechalargaBlur(campo){
	var tam= document.getElementById(campo).value.length;
	var txt= document.getElementById(campo).value;
	var resulta='';
	if(tam==0){
		return true;
	}else{
		if(esFecha(txt)){
			txt=txt.replace(/\-/g,'/');
			txt=txt.replace(/\./g,'/');
			datofec = txt.split("/");
			resulta=covertirFecLit(datofec[1]); 
			resulta=datofec[0]+" de "+resulta+" de "+datofec[2];
			document.getElementById(campo).value = resulta;
		}
	}
	return true;
}


function covertirFecLit(xnro){
	var literal='';
	if(xnro==1)
		literal='enero';
	else if(xnro==2)
		literal='febrero';
	else if(xnro==3)
		literal='marzo';
	else if(xnro==4)
		literal='abril';
	else if(xnro==5)
		literal='mayo';
	else if(xnro==6)
		literal='junio';
	else if(xnro==7)
		literal='julio';
	else if(xnro==8)
		literal='agosto';
	else if(xnro==9)
		literal='septiembre';
	else if(xnro==10)
		literal='octubre';
	else if(xnro==11)
		literal='noviembre';
	else if(xnro==12)
		literal='diciembre';
	
	return literal;
}

function agregarFila(obj){
	
		var cantidad=document.getElementById('cant_campos');
		var tope=parseInt(cantidad.value);
		var vale=0;
		if(tope>0){
			for(i=1; i<=tope; i++)
				if(document.getElementById('hdnCi_' + i)){
					if (document.getElementById('hdnCi_' + i).value == trim(ci.value))
						vale++;
					}
		}
		if(vale>0){
			alert("Ya se ha incluido a esa persona!");
			ci.focus();
			return false;
		}
		obj.value = parseInt(obj.value) + 1;
		var oId = obj.value;
		var strHtml1 = ci.value +' '+txtemi+ '<input type="hidden" id="hdnCi_' + oId + '" name="hdnCi[]" value="' + trim(ci.value) + '"/>' ;
		var strHtml2 = nombre.value + '<input type="hidden" id="hdnEmi_' + oId + '" name="hdnEmi[]" value="' + txtemi + '"/>' ;
		var strHtml3 = txtselRol + '<input type="hidden" id="hdnNombre_' + oId + '" name="hdnNombre[]" value="' + nombre.value + '"/><input type="hidden" id="hdnTipo_' + oId + '" name="hdnTipo[]" value="' + tipo.value + '"/><input type="hidden" id="hdnProcede_' + oId + '" name="hdnProcede[]" value="' + procede.value + '"/><input type="hidden" id="hdnPais_' + oId + '" name="hdnPais[]" value="' + pais.value + '"/><input type="hidden" id="hdnOcupa_' + oId + '" name="hdnOcupa[]" value="' + ocupa.value + '"/><input type="hidden" id="hdnDirec_' + oId + '" name="hdnDirec[]" value="' + direc.value + '"/><input type="hidden" id="hdnRol_' + oId + '" name="hdnRol[]" value="' + txtselRol + '"/><input type="hidden" id="hdnEstCivil_' + oId + '" name="hdnEstCivil[]" value="' + txtestCivil + '"/><input type="hidden" id="hdnRedaccion_' + oId + '" name="hdnRedaccion[]" value="' + redaccion.value + '"/><input type="hidden" id="hdnParrafo_' + oId + '" name="hdnParrafo[]" value="' + parrafo.value + '"/><input type="hidden" id="hdnControl_' + oId + '" name="hdnControl[]" value="' + control.value + '"/>' ;
    	var strHtml4 = '<img src="../images/actions/delete.png" width="16" height="16" alt="Eliminar" onclick="eliminarFila(' + oId + ');"/>';
    		
		var objTr = document.createElement("tr");
		objTr.id = "rowDetalle_" + oId;
		var objTd1 = document.createElement("td");
		objTd1.id = "tdDetalle_1_" + oId;
		objTd1.innerHTML = strHtml1;
		var objTd2 = document.createElement("td");
		objTd2.id = "tdDetalle_2_" + oId;	
		objTd2.innerHTML = strHtml2;
		var objTd3 = document.createElement("td");
		objTd3.id = "tdDetall_3_" + oId;	
		objTd3.innerHTML = strHtml3;
		var objTd4 = document.createElement("td");
		objTd4.id = "tdDetalle_6_" + oId;	
		objTd4.innerHTML = strHtml4;

		objTr.appendChild(objTd1);
		objTr.appendChild(objTd2);
		objTr.appendChild(objTd3);
		objTr.appendChild(objTd4);

		var objTbody = document.getElementById("tbDetalle");
		objTbody.appendChild(objTr);
		nombre.value = '';
		ci.value = '';
		direc.value = '';
		ocupa.value = '';
		procede.value = '';
		redaccion.value = '';
		parrafo.value = '';
		estCivil.value = '-';
		selRol.value = '-'
		emi.selectedIndex = 1;
		ci.focus();
		return false;	//evita que haya un submit por equivocacion. 
	}
	
	function eliminarFila(oId){
		if(confirm('Realmente desea quitar de la lista?')){
			var obj = document.getElementById('cant_campos')
			obj.value = parseInt(obj.value) - 1;
			var objHijo = document.getElementById('rowDetalle_' + oId);
			var objPadre = objHijo.parentNode;
			objPadre.removeChild(objHijo);
		}
		return false;
	}

	
function agregarFilaTabla(fila, cols, ntabla){
		// actualizamos contador de filas
		fila.value = parseInt(fila.value) + 1;
		var oId = fila.value;
		var tabla = "tbTabla"+ntabla;
		//hacemos un loop para ver cuantas columnas incluir
		var objTr = document.createElement("tr");
		idFila = "fila" + ntabla + "_" + oId; 
		objTr.id = idFila; 
		
		for(i=1; i<=cols; i++){
			var objTd = document.createElement("td");
			var strHtml = "<input name='campo"+ntabla+"_"+oId+i+"' type='text' class='input' value='' size='15' maxlength='250' {if $principal[lista.index].nocambia == '1'}readonly{/if}>";
			//alert(strHtml);
			objTd.innerHTML = strHtml;
			objTr.appendChild(objTd);
		}
		var objTbody = document.getElementById(tabla);
		objTbody.appendChild(objTr);
		
		return false;	//evita que haya un submit por equivocacion. 
	}
	
function quitarFilaTabla(fila, ntabla){
		var oId = fila.value;
		if(confirm('Realmente desea quitar \u00FAltima fila de la lista? '+ oId)){
			var objHijo = document.getElementById('fila'+ntabla+'_' + oId);
			var objPadre = objHijo.parentNode;
			objPadre.removeChild(objHijo);
			fila.value = parseInt(fila.value) - 1;
		}
		return false;
	}	