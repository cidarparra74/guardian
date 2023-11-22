var myConn = new XHConn();

if (!myConn) alert("XMLHTTP no disponible! Intente con otro navegador.");

var jalaDatos = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	//alert(texto);
	if(datos[0]=='1'){
		alert("N\u00FAmero encontrado a nombre de: "+datos[1]);
	}else{
		alert("No encontrado: "+datos[1]);
	}
};

function buscarcli(xci){
	if (xci.value.length==0){
		alert("Ingrese un n\u00FAmero de documento!");
		return false;
	}
	var parametro = "";
	parametro = "variable=locpropiet&ci="+xci.value+"&random=" + Math.random()*99999;
	myConn.connect("../lib/include.php", "GET", parametro , jalaDatos);
}

function buscarNIT(xci){
	if (xci.value.length==0){
		alert("Ingrese un n\u00FAmero de NIT!");
		return false;
	}
	var parametro = "";
	parametro = "variable=locpropiet&ci="+xci.value+"&random=" + Math.random()*99999;
	myConn.connect("../lib/include.php", "GET", parametro , jalaDatos);
}


function trim(cad)
{
	return cad.replace(/^\s+|\s+$/g,"");
}

function verificarp(frm){
		// verificamos n veces ya que los elementos iniciales pudieron ser eliminados
		//asi que la numeracion puede ser >0 pero no existir ninguna fila
		var tipo=frm.control.value;
		
		if(tipo==0){
			//es persona natural
			if(frm.txtCI.value==''){
				alert("Ingrese el n\u00FAmero de documento!");
				return false;
			}
			if(frm.txtNombre.value==''){
				alert("Ingrese el nombre del cliente!");
				return false;
			}
			
		}else{
			//es persona juridica
			if(frm.txtNIT.value==''){
				alert("Ingrese el n\u00FAmero de NIT!");
				return false;
			}
			if(frm.txtRSocial.value==''){
				alert("Ingrese la Raz\u00F3n Social!");
				return false;
			}
		}
	}
	
	