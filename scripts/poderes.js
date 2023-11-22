var myConn = new XHConn();

if (!myConn) alert("XMLHTTP no disponible! Intente con otro navegador.");

var insresult = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	//alert(texto);
	//ok|0
	if(datos[0]!='ok'){
		alert("No se pudo guardar en la base de datos, vuelva a intentar ");
	}else{
		//colocamos el idpersona
		var fila='ida_'+document.getElementById('filas').value;
		//alert(fila);
		var ida = document.getElementById(fila);
		ida.value = datos[1];
	}
};

var delresult = function (oXML) {
	var texto = oXML.responseText;
	//var datos = texto.split("|");
	//if(datos[0]!='ok'){
	if(texto!='ok'){
		alert("No se pudo eliminar de la base de datos!");
	}
};

var leeresult = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	//alert(texto);
	if(datos[0]!='ok'){
		alert("No se pudo guardar en la base de datos, vuelva a intentar ");
	}else{
		var nombre = document.getElementById("nombre");
		var ci = document.getElementById("ci");
		var tipo = document.getElementById("tipo");
		var estado = document.getElementById("estado");
		//var porcentaje = document.getElementById("porcentaje");
		var facultades = document.getElementById("facultades");
		var restricciones = document.getElementById("restricciones");
		//colocamos valores
		var oId = datos[1];
		nombre.value = datos[2];
		ci.value = datos[3];
		tipo.value = datos[4];
		estado.value = datos[5];
		//porcentaje.value = datos[6];
		facultades.value = datos[6];
		restricciones.value = datos[7];
		eliminarFila(oId);
	}
};

var jalaDatosP = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	var idp = document.getElementById("idp");
	//alert(texto);
	if(datos[0] == 'ok') {
		alert("Datos guardados correctamente.");
		idp.value = datos[1];
		//alert(document.getElementById("idp").value);
	}else{
		alert("No se pudo guardar los datos, intente nuevamente.");
	}
};

function guardarP(xil){
	var nume = document.getElementById("numero");
	var nota = document.getElementById("notario");
	var fech = document.getElementById("fecha");
	var otor = document.getElementById("otorgante");
	var regi = document.getElementById("registro");
	var foja = document.getElementById("fojas");
	var tipo = document.getElementById("tipo_documento");
	var idp = document.getElementById("idp");
	if (nume.value.length==0){
		alert("Ingrese un numero de poder!");
		return false;
	}
	if (nota.value.length==0){
		alert("Ingrese el nombre del notario!");
		return false;
	}
	var parametro = "";
	parametro = "variable=inspoder&nume="+nume.value+"&nota="+nota.value+"&fech="+fech.value+
				"&otor="+otor.value+"&regi="+regi.value+"&foja="+foja.value+"&tipo="+tipo.value+
				"&idi="+xil+"&idp="+idp.value+"&r=" + Math.random()*99999;
	myConn.connect("../lib/includeP.php", "POST", parametro , jalaDatosP);
	
}

function guardar(xno, xci, xti, xpo, xfa, xre, xes){
	if (xno.value.length==0){
		alert("Ingrese un nombre!");
		return false;
	}
	var parametro = "";
	var idp = document.getElementById("idp");
	parametro = "variable=insapodera&idp="+idp.value+"&nomb="+xno.value+"&ci="+xci.value+"&tipo="+xti.value+"&facu="+xfa.value+"&rest="+xre.value+"&esta="+xes.value+"&r=" + Math.random()*99999;
	//+"&porc="+xpo.value
	myConn.connect("../lib/includeP.php", "POST", parametro , insresult);
}

function eliminar(xida){
	parametro = "variable=delapodera&ida="+xida;
	myConn.connect("../lib/include.php", "GET", parametro , delresult);
}

function leer(xida, xoid){
	parametro = "variable=leeapodera&ida="+xida+"&oid="+xoid;
	myConn.connect("../lib/include.php", "GET", parametro , leeresult);
}


function trim(cad)
{
	return cad.replace(/^\s+|\s+$/g,"");
}


/* ------- funciones para las partes ------- */
	function colocar_lista(idp){

		//vemos si ya se guardo el poder
		var idp = document.getElementById("idp");
		if(idp.value=='0'){
			alert("Debe guardar los datos del poder antes de adicionar apoderados!");
			return false;
		}
		//
		var nombre = document.getElementById("nombre");
		if(nombre.value==''){
			alert("Ingrese el nombre de la persona por favor.");
			nombre.focus();
			return false;
		}
		var ci = document.getElementById("ci");
		if(ci.value==''){
			alert("Ingrese el C.I. de la persona por favor.");
			ci.focus();
			return false;
		}
		var filas=document.getElementById('filas');
		var tope=parseInt(filas.value);
		var vale=0;
		if(tope>0 && trim(ci.value)!=''){
			for(i=1; i<=tope; i++)
				if(document.getElementById('hdnCi_' + i)){
					if (document.getElementById('hdnCi_' + i).value == trim(ci.value) )
						vale++;
					}
		}
		// 
		if(vale>0){
			alert("Ya se ha incluido a esa persona!");
			nombre.focus();
			return false;
		}
		
		
		var tipo = document.getElementById("tipo");
		//var porcentaje = document.getElementById("porcentaje");
		var porcentaje = '';
		var facultades = document.getElementById("facultades");
		var restricciones = document.getElementById("restricciones");
		var estado = document.getElementById("estado");
		
		//guardamos via ajax
		guardar(nombre, ci, tipo, porcentaje, facultades, restricciones, estado);
		
		var oId = tope + 1;
		//actualizamos el nro de filas
		filas.value = oId;
		
		var strHtml1 = nombre.value + '<input type="hidden" id="hdnCi_' + oId + '" name="hdnCi[]" value="' + ci.value + '"/><input type="hidden" id="ida_' + oId + '" name="ida_' + oId + '" value=""/>';
		var strHtml2 = tipo.value  ;
		//var strHtml3 = porcentaje.value ;
    	var strHtml4 = (estado.value=='S') ? 'Vigente' : 'Revocado';
		var strHtml5 = '<a href="javascript:void(0)" onClick="return eliminarFila(' + oId + ')" onMouseOver=" overlib(\'Eliminar esta persona\',CAPTION,\'Eliminar\' ); return true" onMouseOut=" nd(); return true"><img src="../images/actions/delete.png" width="16" height="16" border="0" /></a>&nbsp;';
		//
		var objTr = document.createElement("tr");
		objTr.id = "rowDetalle_" + oId;
		var objTd1 = document.createElement("td");
		objTd1.id = "tdDetalle_1_" + oId;
		objTd1.innerHTML = strHtml1;
		var objTd2 = document.createElement("td");
		objTd2.id = "tdDetalle_2_" + oId;	
		objTd2.innerHTML = strHtml2;
		//var objTd3 = document.createElement("td");
		//objTd3.id = "tdDetall_3_" + oId;	
		//objTd3.innerHTML = strHtml3;
		var objTd4 = document.createElement("td");
		objTd4.id = "tdDetalle_4_" + oId;	
		objTd4.innerHTML = strHtml4;
		var objTd5 = document.createElement("td");
		objTd5.id = "tdDetalle_5_" + oId;	
		objTd5.innerHTML = strHtml5;
		
		objTr.appendChild(objTd1);
		objTr.appendChild(objTd2);
		//objTr.appendChild(objTd3);
		objTr.appendChild(objTd4);
		objTr.appendChild(objTd5);

		var objTbody = document.getElementById("tbDetalle");
		objTbody.appendChild(objTr);
		
		nombre.value = '';
		ci.value = '';
		tipo.value = '';
		//porcentaje.value = '';
		facultades.value = '';
		restricciones.value = '';
		nombre.focus();
		return false;	//evita que haya un submit por equivocacion. 
	}
	
	function modificarFila(oId){
		var fila='ida_'+oId;
		var ida = document.getElementById(fila);
		if(ida.value!=''){
			leer(ida.value, oId);
		}else{
			alert("No se puede editar!, cierre esta ventana y vuelva a ingresar");
		}
	}
	
	function eliminarFila(oId){
		if(confirm('Realmente desea quitar esta persona de la lista?')){
			//var obj = document.getElementById('filas')
			//obj.value = parseInt(obj.value) - 1;
			var fila='ida_'+oId;
			var ida = document.getElementById(fila);
			if(ida.value!=''){
				//eliminamos de la base de datos
				eliminar(ida.value);
			}
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
	
	
function closewin(){
    if (navigator.userAgent.indexOf('MSIE 6.0') > 0)
        window.opener='x';
    if (parent.window.location == window.location)
    {
        window.open("","_self");
        window.close();
    }
    else
        parent.window.close();
}
