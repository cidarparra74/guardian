
function cargar_formulario(){
	document.formulario.cliente.focus();
	
}


function buscarnit(xci) {

	var ci = document.getElementById(xci);
	//var ti = document.getElementById(xti).value;
	
	if (!novacio(ci,ci.value.length,2,"un nit v\u00E1lido, ")){return false;}
	loc_empresa(xci);
	return true;
}


var jalar_datos = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	document.getElementById('cliente').value = datos[0];
	//document.getElementById('direccion').value = datos[1];
	document.getElementById('id_propietari1').value  = datos[2];
	document.getElementById('motivo').focus();
	
};

function loc_empresa(pci)
{
	var codigob = document.getElementById(pci).value;

	var parametro = "";
	if (codigob != ''){
		/*document.getElementById('nroruteo').innerHTML = "<img src='../images/actions/loading.gif' />";*/
		parametro = "variable=locempresa&ci="+codigob+"&random=" + Math.random()*99999;
		myConn.connect("../lib/include.php", "GET", parametro , jalar_datos);

	}
		
}



function wdPedirDatos(pcual) {
    var docsids = "";
	docsids = docsid();
	ModalPopups.Custom("idCustom1",
        "Ingrese Datos del Cliente",   
       "<table>" +    
       "<tr><td>Documento:</td>	<td><input type=text id='txtDoc' maxlength='25' class='input' size='15'>&nbsp;<select id='txtEmi' class='input'>"+
	   docsids+"</select></td></tr>" +	   
        "<tr><td>Nombre:</td>	<td><input type=text id='txtNom' maxlength='50' class='input' size='50'></td></tr>" +    
        "<tr><td>Nit:</td>		<td><input type=text id='txtNit' maxlength='25' class='input' size='15'></td></tr>" +    
        "<tr><td>Telefonos:</td>	<td><input type=text id='txtFon' maxlength='40' class='input' size='30'></td></tr>" +    
		"<tr><td>Direcci&oacute;n:</td><td><input type=text id='txtDir' maxlength='50' class='input' size='50'></td></tr>" +
		"<tr><td>Estado civil:</td>	<td>" + 
		"<select id='txtEC' class='input'>" +
				"<option value='S' selected>Soltero(a)</option>" +
				"<option value='C' >Casado(a)</option>" +
				"<option value='V' >Viudo(a)</option>" +
				"<option value='D' >Divorciado(a)</option>" +
				"<option value='O' >Concuvinado(a)</option>" +
			"</select></td></tr>" +	   
       "</table>" ,    
        {
            width: 500,
            buttons: "ok,cancel",
            okButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            onOk: "wdContinuar("+pcual+")",
            onCancel: "wdCancelar()"
        }
    );
    ModalPopups.GetCustomControl("txtDoc").focus(); 
}
function wdContinuar(xcual) {
    var xDoc = ModalPopups.GetCustomControl("txtDoc");
	var xNom = ModalPopups.GetCustomControl("txtNom");
	if(xDoc.value == "") {
       alert("Ingrese el n\u00FAmero de documento de identidad!");
       xDoc.focus();
	   return;
	}
	if(xNom.value == "") {
       alert("Ingrese el nombre del cliente!");
       xNom.focus();
	   return;
	}
	var xEmi = ModalPopups.GetCustomControl("txtEmi");
	//var xMis = ModalPopups.GetCustomControl("txtMis");   "&mis="+xMis.value+
	var xNit = ModalPopups.GetCustomControl("txtNit");
	var xFon = ModalPopups.GetCustomControl("txtFon");
	var xDir = ModalPopups.GetCustomControl("txtDir");
	var xEC = ModalPopups.GetCustomControl("txtEC");
	var parametro = "";
	parametro = "variable=inspersona&ci="+xDoc.value+"&emi="+xEmi.value+"&nom="+xNom.value+"&nit="+xNit.value+"&fon="+xFon.value+"&dir="+xDir.value+"&eci="+xEC.value+"&xcu="+xcual;
		//alert(parametro);
	myConn.connect("../lib/include.php", "GET", parametro , jalar_datos3);
    ModalPopups.Close("idCustom1");
}

function wdCancelar() {    
    ModalPopups.Cancel("idCustom1");
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
	document.formulario.eliminar_boton.focus();
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


//solicitud de informe legal
function adicionar_solicitud(){
	document.formulario.solicitar.disabled=true;
}




function verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}
function des_verificar_prestar(){
	document.prestar.prestar_boton.disabled=true;
	document.prestar_cancelar.prestar_boton_cancelar.disabled=true;
}



function verificar_formulario(){
	
	//para el cliente
	var antes= document.all["cliente"];
	var ver_antes= antes.value;
	if(ver_antes == ""){
		alert("Debe escribir el nombre del cliente");
		antes.focus();
		return false;
	}
	
	//para xi el cliente
	var antes= document.all["ci_cliente"];
	var ver_antes= antes.value;
	if(ver_antes == ""){
		alert("Debe escribir el documentos de identidad del cliente");
		antes.focus();
		return false;
	}
	
	if(document.all["estado_formulario"].value == "adicionar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
	if(document.all["estado_formulario"].value == "modificar"){
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
	
	return true;
}
function des_verificar_formulario(){
	if(document.all["estado_formulario"].value == "adicionar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
	if(document.all["estado_formulario"].value == "modificar"){
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
	if(document.all["estado_formulario"].value == "eliminar"){
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	
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

function mover_confirmar(){
		if( confirm("Esta Seguro de querer Mover \na la bandeja de recepcionados?" ) ){
			document.formov.mover_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
			return true;
		}else{
			return false;
		}
}

function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if(que=="operacion"){
			if(document.formulario.justifica.value==''){
				alert("Debe indicar el motivo de la eliminaci\u00F3n !");
				document.formulario.justifica.focus();
				return false;
			}
		}
		if( confirm("Esta seguro de querer eliminar " +sex+" "+que+" ?") ){
			document.formulario.eliminar_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
			return true;
		}
		else{
			return false;
		}
	}
	else{
		alert("No puede eliminar " +sex+" "+que +"\nPorque existen registros asociados");
		return false;
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

function validar_bien(){
	//para ver tipo de i.l. a adicionar
	var antes = document.all["acc_tipo_bien"];
	if(antes.value == "ninguno"){
		alert("Debe elegir el tipo de bien para el nuevo I.L.");
		antes.focus();
		return false;
	}
	return true;
}


/* ------- funciones para las partes ------- */
	function agregarFila(obj){
		var oId = parseInt(obj.value) ;
		var vale=0;
		if(oId>0){
			for(i=1; i<=oId; i++)
				if(document.getElementById('hdnNom_' + i)){
					if (document.getElementById('hdnNom_' + i).value == '')
						vale++;
					}
		}
		// 
		if(vale>0){
			alert("No pueden haber l\u00EDneas con nombres vacios!");
			ci.focus();
			return false;
		}
		//
		oId = oId + 1;
		var strHtml1 = '<input type="text" id="hdnNom_' + oId + '" name="hdnNom[]" value="" maxlength="50" class="input" size="30" />' ;
		var strHtml2 = '<input type="text" id="hdnCar_' + oId + '" name="hdnCar[]" value="" maxlength="50" class="input" size="26" />' ;
		var strHtml3 = '<input type="text" id="hdnCi_' + oId + '" name="hdnCi[]" value="" maxlength="20" class="input" size="10" />' ;
    	/*
		var strHtml4 = '<img src="../images/actions/delete.png" width="16" height="16" alt="Eliminar" onclick="eliminarFila(' + oId + ');"/> <input type="text"  name="posi[]" value="' + oId + '" size="1" class="input">';
		*/
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
		/*var objTd4 = document.createElement("td");
		objTd4.id = "tdDetalle_6_" + oId;	
		objTd4.innerHTML = strHtml4;
		*/
		objTr.appendChild(objTd1);
		objTr.appendChild(objTd2);
		objTr.appendChild(objTd3);
		//objTr.appendChild(objTd4);

		var objTbody = document.getElementById("tbDetalle");
		objTbody.appendChild(objTr);
		//ci.focus();
		obj.value = oId;
		return false;	//evita que haya un submit por equivocacion. 
	}
	
	
function eliminar_poder_confirmar(idp, obj){
	if( confirm("Esta seguro de querer eliminar este poder?") ){
		obj.volver.value = 'E'; 
		obj.idpoder.value = idp ;
		obj.submit();
		return true;
	}else{
		return false;
	}
}