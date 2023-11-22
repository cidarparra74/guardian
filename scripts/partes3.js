var myConn = new XHConn();

if (!myConn) alert("XMLHTTP no disponible! Intente con otro navegador.");

var jalaDatos = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	var eciv, emit ;
	//alert(texto);
	if(datos[0]!='?'){
			
			if(datos[7] != '')
				eciv = String(datos[7]).substring(0,1);
			else
				eciv = '-';
			if(datos[8] != '')
				emit = datos[8];
			else
				emit = '-';
			document.getElementById('selTipo').value = datos[0];
			document.getElementById('txtNombre').value = datos[1];
			//document.getElementById('txtProcede').value  = datos[2];
			//document.getElementById('selPais').value  = datos[3];
			//document.getElementById('txtOcupa').value  = datos[4];
			//document.getElementById('txtDireccion').value  = datos[5];
			//document.getElementById('txtParrafo').value  = datos[6];
			//document.getElementById('selEstCivil').value  = eciv;
			//if(document.getElementById('selEmi').value =='-')
			document.getElementById('selEmi').value  = emit;
			document.getElementById('selRol').focus();

	}else{
		alert("No encontrado: "+datos[1]);
	}
};

var jalaDatosNit = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	var eciv, emit ;
	//alert(texto);
	if(datos[0]!='?'){

			document.getElementById('txtRSocial').value = datos[1];
			document.getElementById('txtMatricula').value  = datos[2];
			document.getElementById('selPais2').value  = datos[3];
			document.getElementById('txtDomicilio').value  = datos[5];
			document.getElementById('txtRepresenta').value  = datos[6];
			document.getElementById('selRol2').focus();

	}else{
		alert("No encontrado: "+datos[1]);
	}
};

function buscarcli(xci,xem){
	if (xci.value.length==0){
		alert("Ingrese un n\u00FAmero de documento!");
		return false;
	}
	var parametro = "";
	//var emision = xem.options[xem.selectedIndex].text;
	//if(emision == '--') emision = '';
	//parametro = "variable=locclisec3&ci="+xci.value+"&emi="+emision+"&random=" + Math.random()*99999;
	
	parametro = "variable=locclisec3&ci="+xci.value+"&emi=x&random=" + Math.random()*99999;
	myConn.connect("../lib/include.php", "GET", parametro , jalaDatos);
}

function buscarNIT(xci){
	if (xci.value.length==0){
		alert("Ingrese un n\u00FAmero de NIT!");
		return false;
	}
	var parametro = "";
	parametro = "variable=locclisec2&ci="+xci.value+"&random=" + Math.random()*99999;
	myConn.connect("../lib/include.php", "GET", parametro , jalaDatosNit);
}

function des_verificar_adicion(){
	if(confirm("Esta seguro de cancelar la elaboraci\u00F3n de este contrato?")){
	document.partes.adicionar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	return true;
	}else{
	return false;}
}

function trim(cad)
{
	return cad.replace(/^\s+|\s+$/g,"");
}


/* ------- funciones para las partes ------- */
	function agregarFila(obj, pmin, pmax){
		var control = document.getElementById("control");
		if(control.value=='0') {
		//estamos en personas naturales
			var nombre = document.getElementById("txtNombre");
			var emi = document.getElementById("selEmi");
			var ci = document.getElementById("txtCI");
			var tipo = document.getElementById("selTipo");
			//var procede = document.getElementById("txtProcede");
			//var pais = document.getElementById("selPais");
			//var ocupa = document.getElementById("txtOcupa");
			//var direc = document.getElementById("txtDireccion");
		//	var rol = document.getElementById("selRol");
			//var estCivil = document.getElementById("selEstCivil");
			var selRol = document.getElementById("selRol");
			//var redaccion = document.getElementById("txtRedaccion");
			//var parrafo = document.getElementById("txtParrafo");
			//VARIALES PARA COMBOS
			if(emi.value!='-')
				//var txtemi = emi.options[emi.selectedIndex].text;
				var txtemi = emi.value;
			else{
				alert("Ingrese el lugar de emisi\u00F3n del documento por favor.");
				emi.focus();
				return false;
			}
			
			
			if(ci.value==''){
				alert("Ingrese el n\u00FAmero de documento por favor.");
				ci.focus();
				return false;
			}
			if(nombre.value==''){
				alert("Ingrese el nombre de la persona por favor.");
				nombre.focus();
				return false;
			}
			
			if(selRol.value=='-'){
				alert("Seleccione el rol de la persona por favor.");
				selRol.focus();
				return false;
			}
			//var txtestCivil = estCivil.options[estCivil.selectedIndex].text;
			var txtselRol = selRol.options[selRol.selectedIndex].text;
		}else{
		//estamos en personas juridicas
			var nombre = document.getElementById("txtRSocial");
			var emi = document.getElementById("selRol2"); //este no se usa pero lo asignamos para evitar error en JS
			var ci = document.getElementById("txtNIT");
			var tipo = document.getElementById("selRol2"); //este no se usa pero lo asignamos para evitar error en JS
			var procede = document.getElementById("txtMatricula");
			var pais = document.getElementById("selPais2");
			var ocupa = '';
			var direc = document.getElementById("txtDomicilio");
		//	var rol = document.getElementById("selRol2");
			var estCivil = document.getElementById("selRol2"); //este no se usa pero lo asignamos para evitar error en JS
			var selRol = document.getElementById("selRol2");
			var redaccion = '';
			var parrafo = document.getElementById("txtRepresenta");
			//VARIALES PARA COMBOS
			var txtemi = '';
			var txtestCivil = '';
			
			
			if(ci.value==''){
				alert("Ingrese el n\u00FAmero de NIT por favor.");
				ci.focus();
				return false;
			}
			if(nombre.value==''){
				alert("Ingrese el nombre o Raz\u00F3n Social por favor.");
				nombre.focus();
				return false;
			}
			if(procede.value==''){
				alert("Ingrese el n\u00FAmero de matr\u00EDcula por favor.");
				procede.focus();
				return false;
			}
			if(direc.value==''){
				alert("Ingrese la direcci\u00F3n por favor.");
				direc.focus();
				return false;
			}
			if(parrafo.value==''){
				alert("Ingrese la cl\u00E1usula para el representante por favor.");
				parrafo.focus();
				return false;
			}
			if(selRol.value=='-'){
				alert("Seleccione el rol de la persona por favor.");
				selRol.focus();
				return false;
			}
			var txtselRol = selRol.options[selRol.selectedIndex].text;
		}	
		var cantidad=document.getElementById('cant_campos');
		var tope=parseInt(cantidad.value);
		var vale=0;
		var cant = 0;
		if(tope>0){
			for(i=1; i<=tope; i++)
				if(document.getElementById('hdnCi_' + i)){
					if (document.getElementById('hdnCi_' + i).value == trim(ci.value)  ) 	//&& document.getElementById('hdnRol_' + i).value == trim(txtselRol))
						vale++;
						cant++;
					}
		}
		// 
		if(vale>0){
			alert("Ya se ha incluido a esa persona!");
			ci.focus();
			return false;
		}
		if(pmax > 0 && cant >= pmax){
					alert("Ingrese maximo "+pmax+" persona(s)!");
			ci.focus();
			return false;
		}
		obj.value = parseInt(obj.value) + 1;
		var oId = obj.value;
		var strHtml1 = ci.value +' '+txtemi+ '<input type="hidden" id="hdnCi_' + oId + '" name="hdnCi[]" value="' + trim(ci.value) + '"/>' ;
		var strHtml2 = nombre.value + '<input type="hidden" id="hdnEmi_' + oId + '" name="hdnEmi[]" value="' + txtemi + '"/>' ;
		var strHtml3 = txtselRol + '<input type="hidden" id="hdnNombre_' + oId + '" name="hdnNombre[]" value="' + nombre.value + '"/><input type="hidden" id="hdnTipo_' + oId + '" name="hdnTipo[]" value="' + tipo.value + '"/><input type="hidden" id="hdnRol_' + oId + '" name="hdnRol[]" value="' + txtselRol + '"/><input type="hidden" id="hdnControl_' + oId + '" name="hdnControl[]" value="' + control.value + '"/>' ;
    	var strHtml4 = '<img src="../images/actions/delete.png" width="16" height="16" alt="Eliminar" onclick="eliminarFila(' + oId + ');"/> <input type="text"  name="posi[]" value="' + oId + '" size="1" class="input">';

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
		
		//selRol.value = '-'
		emi.selectedIndex = 0;
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

	function subirfila(oId){
		
		var obj = document.getElementById('cant_campos')
		//guardamos fila actual
		
			obj.value = parseInt(obj.value) - 1;
			var objHijo = document.getElementById('rowDetalle_' + oId);
			var objPadre = objHijo.parentNode;
			objPadre.removeChild(objHijo);
		
		return false;
	}
	
	function cancelar(){
		var objc = document.getElementById('cant_campos')
			objc.value = '0' ;
		var obj = document.getElementById('tbDetalle');
		var objPadre = obj.parentNode;
		objPadre.removeChild(obj);
		obj = document.createElement("tbody");
		obj.id = 'tbDetalle';
		objPadre.appendChild(obj);
		return false;
	}
	
	function verificarp(frm, pmin, pmax){
		// verificamos n veces ya que los elementos iniciales pudieron ser eliminados
		//asi que la numeracion puede ser >0 pero no existir ninguna fila
		var cantidad=document.getElementById('cant_campos');
		var calidad=document.getElementById('cant_cali');
		var tope=parseInt(cantidad.value);
		var cali=parseInt(calidad.value);
		var vale=0;
		
			for(i=1; i<=10; i++)
				if (document.getElementById('hdnCi_' + i))
					vale++;
		
		if(vale < pmin){
			if(pmin == 1)
			alert("Ingrese al menos una persona!");
			else
			alert("Ingrese al menos "+pmin+" persona(s)!");
			return false;
		}else{
		//verificar si hay variables calidad
			if(cali>0){
				var rolc=document.getElementById('selRol');
				var item = '', xval='', yval = '';
				//recorremos el select
				for(i=0; i<=cali; i++){
					item = rolc.options[i].value;
					//buscamos var tipo calidad
					if(item.substring(0,1)=='*'){
						xval = rolc.options[i].text;
						var k=0;
						//recorremos tabla
						for(j=1; j<=tope; j++){
							//validar q exista campo
							if (document.getElementById('hdnRol_' + j)){
								//recuperamos valor calidad
								yval = document.getElementById('hdnRol_' + j).value;
								//ver si dato del select esta en la tabla
								if(yval==xval) k=1;
							}
						}
						//se encontro en la tabla?
						if(k==0){
							alert("Es obligatorio ingresar "+xval);
							return false;
						}
					}
				}
			} 
			/* */
			
			//vemos maximo numero de personas
			if(pmax > 0){
				if(vale > pmax){
					alert("Ingrese maximo "+pmax+" persona(s)!");
					return false;
				}
			}
					//return false;
			return true;
		}
	}
	
	function doredaccion(){
		//armamos el parrafo
		var redaccion = document.getElementById("txtRedaccion");
		var procede = document.getElementById("txtProcede");
		var ocupa = document.getElementById("txtOcupa");
		var direc = document.getElementById("txtDireccion");
		var estCivil = document.getElementById("selEstCivil");
		var parrafo = document.getElementById("txtParrafo");
		var texto = "mayor de edad, estado civil " + estCivil.options[estCivil.selectedIndex].text ;
		texto += " de nacionalidad " + procede.value + " profesi\u00F3n u ocupaci\u00F3n " + ocupa.value ;
		texto += ", h\u00E1bil por derecho, con domicilio en " + direc.value  ;
		if(parrafo.value != '')
			texto += ", " + parrafo.value;
		
		redaccion.value = texto ;
	}