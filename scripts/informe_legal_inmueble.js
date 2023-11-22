

ModalPopups.SetDefaults( { 
yesButtonText: "Si", 
cancelButtonText: "Cancelar", 
shadow: false, 
titleBackColor: "#CCE6FF", 
titleFontColor: "#404040", 
popupBackColor: "#ffffff",
footerBackColor: "#CCE6FF", footerFontColor: "#404040"
} );
 
function buscarp(xci,xti) {

	var ci = document.getElementById(xci);
	var ti = document.getElementById(xti).value;
	
	if (!novacio(ci,ci.value.length,2,"un documento v\u00E1lido, ")){return false;}
	loc_persona(xci,ti);
	return true;
}
 
var jalar_datos = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	document.getElementById('nombres_apellidos').value = datos[0];
	document.getElementById('direccion').value = datos[1];
	document.getElementById('id_propietario').value  = datos[2];
	var ddl = document.getElementById('tipo_identificacion_p');
	var opts = ddl.options.length;
	for (var i=0; i<opts; i++){
		if (ddl.options[i].value == datos[4]){
			ddl.options[i].selected = true;
			break;
		}
	}
	//document.getElementById('tipo_identificacion_p').value  = datos[4];
	document.getElementById('ci').focus();
	
};

var jalar_datos1 = function (oXML) {
	var texto = oXML.responseText;
	//alert(texto);
	var datos = texto.split("|");
	if(datos[3]==1){
	document.getElementById('cliente').value = datos[0];
	//document.getElementById('ci_cliente').value = datos[1];
	document.getElementById('id_propietari1').value  = datos[2];
	document.getElementById('ci_cliente').focus();
	}else{
		if(datos[3]==9)
			alert("Ya existe el n\u00FAmero de carnet indicado!");
		else
			document.getElementById('ci').value  = datos[1]
	}
};

var jalar_datos3 = function (oXML) {
	var texto = oXML.responseText;
	//alert(texto);
	var datos = texto.split("|");
	if(datos[3]==1){
	//document.getElementById('cliente').value = datos[0];
	//document.getElementById('ci_cliente').value = datos[1];
	//document.getElementById('id_propietari1').value  = datos[2];
	document.getElementById('ci_cliente').focus();
	alert("Se registro el cliente.");
	}else{
		if(datos[3]==9)
			alert("Ya existe el n\u00FAmero de carnet indicado!");
		else
			document.getElementById('ci').value  = datos[1]
	}
};

var jalar_datos2 = function (oXML) {
	var texto = oXML.responseText;
	var datos = texto.split("|");
	//alert(texto);
	if(datos[0]=='1'){
	document.getElementById('marca').value = datos[1];
	document.getElementById('chasis').value  = datos[2];
	document.getElementById('modelo').value = datos[3];
	document.getElementById('clase').value = datos[4];
	document.getElementById('tipo').value  = datos[5];
	document.getElementById('motor').value = datos[6];
	document.getElementById('color').value = datos[7];
	document.getElementById('alcaldia').value  = datos[8];
	document.getElementById('crpva').value = datos[9];
	document.getElementById('poliza').value = datos[10];
	document.getElementById('fpoliza').value  = datos[11];
	document.getElementById('fecha_vehiculo').value  = datos[12];
	}else{
		//document.getElementById('placa').value  = 'No Existe'
		alert("N\u00FAmero de placa no registrada a\u00FAn");
	}
};

function loc_persona(pci,pti)
{
	var codigob = document.getElementById(pci).value;
	//var tipop  = "";
	var emision = pti;
	
	var parametro = "";
	
	if (codigob != ''){
		/*document.getElementById('nroruteo').innerHTML = "<img src='../images/actions/loading.gif' />";*/
		if(pci=='ci_cliente'){
		parametro = "variable=locpersona&ci="+codigob+"&emi="+emision+"&xcu=1"+"&random=" + Math.random()*99999;
		myConn.connect("../lib/include.php", "GET", parametro , jalar_datos1);
		}else{
		parametro = "variable=locpersona&ci="+codigob+"&emi="+emision+"&xcu=2"+"&random=" + Math.random()*99999;
		myConn.connect("../lib/include.php", "GET", parametro , jalar_datos);
		}
	}
		
}

 
function cargar_formulario(){
	document.formulario.ci.focus();
}

function cargar_formulario_hab(){
	document.formulario.habilitando_informe.focus();
}

function cargar_formulario_des(){
	document.formulario.deshabilitando_informe.focus();
}

function cargar_eliminar(){
	document.formulario.eliminar_boton.focus();
}

function verificar_formulario(){
	
	//para el n\u00FAmero
	var antes= document.all["id_propietari1"];
	var antes_valor= antes.value;
	//var ver_valor= antes_valor.replace(/ /g, "");
	if(antes_valor == "0"){
		alert("Debe indicar un cliente, utilice la opci\u00F3n buscar o crear cliente");
		antes.focus();
		return false;
	}
	//para el ci cliente
	var antes= document.all["ci_cliente"];
	var antes_valor= antes.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == ""){
		alert("Debe escribir un CI para este cliente");
		antes.focus();
		return false;
	}
	//para el cliente
	var antes= document.all["cliente"];
	var antes_valor= antes.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == ""){
		alert("Debe escribir un cliente");
		antes.focus();
		return false;
	}
	/*
	//llenamos los datos de las personas
	var i=0;
	var lista_personas=document.all["lista_personas"];
	var poner="";
	var ponerTXT="0";
	var texto='';
	var textox='';
	var propiet= new Array();
	//alert(lista_personas.options[i].value);
	for (i=0; i < lista_personas.options.length; i++) {
		texto= lista_personas.options[i].value;
		poner=poner+texto+";";
		textox= lista_personas.options[i].text;
		propiet= textox.split("|");
		if(propiet[2]=="*") 	ponerTXT=texto;
	}
	document.formulario.p_lista_personas.value=poner;
	document.formulario.p_lista_titular.value=ponerTXT;
	*/
	/*
	i=0;
	var limite= parseInt(document.formulario.cantidad_documentos.value);
	//var aux=document.formulario.p_ids.value;
	//var listaA= new Array();
	//listaA= aux.split(";");

	for(i=0;i<limite;i++){
		var aux_obs="obs_"+i;
		var obs= document.all[aux_obs];
		var cadena= Trim(obs.value);
		obs.value=cadena;
	}//fin del for
	*/
	var cnt = document.getElementById('cant_campos');
	if(cnt.value==0){
		if(confirm("Desea guardar el informe sin adicionar al menos un propietario?"))
			return true;
		else{
			var ci = document.getElementById('ci');
			ci.focus();
			return false;
		}
	}
	if(document.formulario.estado_formulario.value== "adicionar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	else{
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	return true;
}

function des_verificar_formulario(){
	if(document.formulario.estado_formulario.value== "adicionar"){
		document.formulario.adicionar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
	}
	else{
		document.formulario.modificar_boton.disabled=true;
		document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
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



function eliminar_confirmar(sex, que){

		if( confirm("Esta Seguro de querer Eliminar " +sex+" "+que+"\n Cuidado! No podr\u00E1 deshacer esta acci\u00F3n!") ){
			document.formulario.eliminar_boton.disabled=true;
			document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
			return true;
		}
		else{
			return false;
		}

}

function des_eliminar_confirmar(){
	document.formulario.eliminar_boton.disabled=true;
	document.adicionar_cancelar.adicionar_boton_cancelar.disabled=true;
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
				if(parseInt(division[0])>30){alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//junio
			if(parseInt(division[1])==6){ //junio
				if(parseInt(division[0])>30){alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//septiembre
			if(division[1]=="09"){ //septiembre
				if(parseInt(division[0])>30){alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//noviembre
			if(parseInt(division[1])==11){ //noviembre
				if(parseInt(division[0])>30){alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
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


//para la fecha y su vencimiento
//para la fecha
function colocar_fecha_vencimiento(campo, meses, fecha_vencimiento, observaciones){
	//alert(meses.value);
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
				if(parseInt(division[0])>diam){
					alert("El d\u00EDa no puede ser mayor a "+diam); campo.value=""; campo.focus(); return false;}
			}
			//abril
			if(parseInt(division[1])==4){ //abril
				if(parseInt(division[0])>30){
					alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//junio
			if(parseInt(division[1])==6){ //junio
				if(parseInt(division[0])>30){
					alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//septiembre
			if(parseInt(division[1])==9){ //septiembre
				if(parseInt(division[0])>30){
					alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
			}
			//noviembre
			if(parseInt(division[1])==11){ //noviembre
				if(parseInt(division[0])>30){
					alert("El d\u00EDa no puede ser mayor a 30"); campo.value=""; campo.focus(); return false;}
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
				}else{
					if(meses.value == "09"){
						hoy.setTime(hoy.getTime()+9*24*60*60*1000);
					}else{
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

	function eliminarFila(oId){
		if(confirm('Realmente desea quitar de la lista esta persona?')){
			//var obj = document.getElementById('cant_campos')
			//obj.value = parseInt(obj.value) - 1;
			var objHijo = document.getElementById('rowDetalle_' + oId);
			var objPadre = objHijo.parentNode;
			objPadre.removeChild(objHijo);
		}
		return false;
	}

function colocar_lista(){
	//var nombres_apellidos,ci,lista_personas,id_propietario){
	var form = document.formulario;
	var estit = '';
	if(form.ci.value==""){
		alert("Debe escribir el ci");
		form.ci.focus();
		return false;
	}
	
	if(form.nombres_apellidos.value==""){
		alert("Debe buscar el nombre mediante el documento de identidad");
		form.ci.focus();
		return false;
	}
	if(form.id_propietario.value=="0"){
		alert("Debe buscar el nombre mediante el documento de identidad");
		form.ci.focus();
		return false;
	}
	var nueva_id=form.id_propietario.value;
	/*
	var i=0;
	for (i=0; i < form.lista_personas.options.length; i++) {
		//arrayPersonas[i]=lista_personas.options[i].text;
	}
	if(form.titular.checked) estit = '|*';
	var nueva_persona=form.nombres_apellidos.value+"|"+form.ci.value+estit;
	var nueva_id=form.id_propietario.value;
	var optionObj = new Option(nueva_persona, nueva_id);
	
	form.lista_personas.options[i]=optionObj;
	
	if((i+1)>2){
		form.lista_personas.size=(i+1);
	}
	else{
		form.lista_personas.size=2;
	}
	
	*/
		//var ci = document.getElementById("txtCi");
		var obj = document.getElementById('cant_campos');
		//var nombres_apellidos = document.getElementById("nombres_apellidos");
		//var tipo_identificacion_p = document.getElementById("selEmi");
		//var ci = document.getElementById("ci");
		//var direccion = document.getElementById("direccion");
		//var titular = document.getElementById("titular");
	//	var cantidad=document.getElementById('cant_campos');
		var tope=parseInt(obj.value);
		var vale=0;
		if(tope>0){
			for(i=1; i<=tope; i++)
				if(document.getElementById('hdnCi_' + i)){
					if (document.getElementById('hdnCi_' + i).value == trim(form.ci.value))
						vale++;
				}
		}
		if(vale>0){
			alert("Ya se ha incluido a esa persona!");
			form.ci.focus();
			return false;
		}
		
		obj.value = parseInt(obj.value) + 1;
		var oId = obj.value;
		var strHtml1 = form.ci.value +form.tipo_identificacion_p.options[form.tipo_identificacion_p.selectedIndex].text+ 
		'<input type="hidden" id="hdnCi_' + oId + '" name="hdnCi[]" value="' + form.ci.value + '"/>' 
		+ '<input type="hidden" id="hdnEmi_' + oId + '" name="hdnEmi[]" value="' + 
		form.tipo_identificacion_p.options[form.tipo_identificacion_p.selectedIndex].text + '"/>' ;
	/*	var strHtml2 = form.tipo_identificacion_p.options[form.tipo_identificacion_p.selectedIndex].text 
	*/	var strHtml3 = form.nombres_apellidos.value + '<input type="hidden" id="hdnNombre_' + oId + '" name="hdnNombre[]" value="' + form.nombres_apellidos.value + 
	'"/><input type="hidden" id="hdndirec_' + oId + '" name="hdnDirec[]" value="' + form.direccion.value + 
	'"/><input type="hidden" id="hdnIDprop_' + oId + '" name="hdnIDprop[]" value="' + nueva_id + '"/>' ;
    	if(form.titular.checked)
			var strHtml4 = 'Si <input type="hidden" id="hdnTitu_' + oId + '" name="hdnTitu[]" value="S"/>' ;
		else
			var strHtml4 = 'No <input type="hidden" id="hdnTitu_' + oId + '" name="hdnTitu[]" value="N"/>' ;
			
		var strHtml6 = '<img src="../images/actions/delete.png" onclick="eliminarFila(' + oId + ');" onMouseOver=" overlib(\'Quitar de la lista a esta persona\',CAPTION,\'Eliminar\' ); return true" onMouseOut=" nd(); return true" />';
		
		var strHtml7 = '<img src="../imagenes/pass/edit.png" border="0" onClick="editawin(' + nueva_id + ')" onMouseOver=" overlib(\'Modificar datos de esta persona\',CAPTION,\'Modificar\' ); return true" onMouseOut=" nd(); return true">';
		
		var objTr = document.createElement("tr");
		objTr.id = "rowDetalle_" + oId;
		var objTd1 = document.createElement("td");
		objTd1.id = "tdDetalle_1_" + oId;
		objTd1.innerHTML = strHtml1;
	/*	var objTd2 = document.createElement("td");
		objTd2.id = "tdDetalle_2_" + oId;	
		objTd2.innerHTML = strHtml2;	*/
		var objTd3 = document.createElement("td");
		objTd3.id = "tdDetall_3_" + oId;	
		objTd3.innerHTML = strHtml3;
		var objTd4 = document.createElement("td");
		objTd4.id = "tdDetalle_4_" + oId;
		objTd4.innerHTML = strHtml4;
		var objTd6 = document.createElement("td");
		objTd6.id = "tdDetalle_6_" + oId;	
		objTd6.innerHTML = strHtml6;
		var objTd7 = document.createElement("td");
		objTd7.id = "tdDetalle_7_" + oId;	
		objTd7.innerHTML = strHtml7;
		
		objTr.appendChild(objTd1);
	//	objTr.appendChild(objTd2);
		objTr.appendChild(objTd3);
		objTr.appendChild(objTd4);
		objTr.appendChild(objTd6);
		objTr.appendChild(objTd7);

		var objTbody = document.getElementById("tbDetalle");
		objTbody.appendChild(objTr);
	
	form.nombres_apellidos.value="";
	form.ci.value="";
	form.direccion.value="";
	form.titular.checked = false;
	form.ci.focus();

	return false;	//evita que haya un submit por equivocacion.
	

}

/*
//guardar
function guardar_lista(nombres_apellidos,ci,nit,direccion,lista_personas, estado_civil, tipo_identificacion_p){
	if(ci.value==""){
		alert("Debe escribir el ci");
		ci.focus();
		return false;
	}
	
	if(nombres_apellidos.value==""){
		alert("Debe escribir el nombre");
		nombres_apellidos.focus();
		return false;
	}
	
	var i=0;
	var posicion=-1;
	for (i=0; i < lista_personas.options.length; i++) {
		if(lista_personas.options[i].selected){
			posicion=i;
		}
	}
	if(posicion!=-1){
		var nueva_persona=nombres_apellidos.value+"|"+ci.value+"|"+nit.value+"|"+direccion.value+"|"+estado_civil.value+"|"+tipo_identificacion_p.value;
		var optionObj = new Option(nueva_persona, nueva_persona);
		lista_personas.options[posicion]=optionObj;
		nombres_apellidos.value="";
		ci.value="";
		nit.value="";
		direccion.value="";
		nombres_apellidos.focus();
	}
	else{
		alert("para guardar los datos debe seleccionar una persona\nCaso contratio adicionarla");
		return false;
	}
}

function sacar_lista(lista_personas){
	var i=0;
	var seleccionado=-1;
	for (i=0; i < lista_personas.options.length; i++) {
		//alert(lista_personas.options[i].selected);
		if(lista_personas.options[i].selected){
			seleccionado=i;
		}
	}
	if(i==0){
		alert("No existe propietarios creados");
		return false;
	}
	else{
		if(seleccionado==-1){
			alert("Debe seleccionar un propietario\nPara poder eliminarlo");
			lista_personas.focus();
			return false;
		}
		else{
			var arrayP=new Array();
			var arrayI=new Array();
			var contador=0;
			for (i=0; i < lista_personas.options.length; i++) {
				if(seleccionado!=i){ //entra
					arrayI[contador]=lista_personas.options[i].value;
					arrayP[contador]=lista_personas.options[i].text;
					contador++;
				}
			}
			//borramos la lista
			lista_personas.length=0;
			for(i=0; i<contador;i++){
				var optionObj = new Option(arrayP[i], arrayI[i]);
				lista_personas.options[i]=optionObj;
			}
			if(i>2){
				lista_personas.size=(i);
			}
			else{
				lista_personas.size=2;
			}
		}
	}
} */


/*
//mostrando los datos de las personas
function mostrar_datos_persona(nombres_apellidos,ci,idpr,lista_personas){
	var i=0;
	var j=0;
	var posicion_estado=0;
	var form = document.formulario;
	for (i=0; i < lista_personas.options.length; i++) {

		if(lista_personas.options[i].selected){
			var texto= lista_personas.options[i].text;
			var valor= lista_personas.options[i].value;
			var division= texto.split("|");
			nombres_apellidos.value=division[0];	
			ci.value=division[1];
			if(division[2] == '*')
			form.titular.checked = true;
			else
			form.titular.checked = false;
			idpr.value = valor;
		}
	}

}
*/

//verificando los documentos
function varificar_datos_informe(cantidad_documentos, p_ids){
	var i=0;
	var limite= parseInt(cantidad_documentos.value);
	var aux=p_ids.value;
	var listaA= new Array();
	listaA= aux.split(";");
	
	var inexistentes_sin_obs="Documentos Inexistentes sin Observaci\u00F3n:\n\n";
	var inexistentes_con_obs="Documentos Inexistentes con Observaci\u00F3n:\n\n";
	var existentes_sin_obs="Documentos Existentes sin Observaci\u00F3n:\n\n";
	var existentes_con_obs="Documentos Existentes con Observaci\u00F3n:\n\n";
	var excepcionados="Documentos Excepcionados:\n\n";

	for(i=0;i<limite;i++){
		var aux_foja="fojas_"+listaA[i];
		var aux_fondo="fondo_"+listaA[i];
		var aux_forma="forma_"+listaA[i];
		var aux_excepcion="excepcion_"+listaA[i];
		var aux_documento= "documento_nombre_"+listaA[i];
		
		var foja= document.all[aux_foja];
		var fondo= document.all[aux_fondo];
		var forma= document.all[aux_forma];
		var excepcion= document.all[aux_excepcion];
		var documento= document.all[aux_documento];
		
		if(foja.value == "0"){ //inexistentes
			//verificamos si estan excepcionados
			if(excepcion.checked){ //excepcionado
				excepcionados=excepcionados+documento.value+"   'Fojas:0'\n";
			}
			else{
				if(fondo.checked || forma.checked){ //con observaci\u00F3n
					inexistentes_con_obs=inexistentes_con_obs+documento.value+"   'Fojas:0'\n";
				}
				else{//sin observacion
					inexistentes_sin_obs=inexistentes_sin_obs+documento.value+"   'Fojas:0'\n";
				}
			}
		}
		else{ //existentes
			//verificamos si estan excepcionados
			if(excepcion.checked){ //excepcionado
				excepcionados=excepcionados+documento.value+"   'Fojas:"+foja.value+"'\n";
			}
			else{
				if(fondo.checked || forma.checked){ //con observaci\u00F3n
					existentes_con_obs=existentes_con_obs+documento.value+"   'Fojas:"+foja.value+"'\n";
				}
				else{//sin observacion
					existentes_sin_obs=existentes_sin_obs+documento.value+"   'Fojas:"+foja.value+"'\n";
				}
			}
		}//fin del else existentes
		
	}//fin del for
	
	//var mensaje=inexistentes_sin_obs+inexistentes_con_obs+existentes_sin_obs+existentes_con_obs+excepcionados;
	//alert(mensaje);
	alert(inexistentes_sin_obs);
	alert(inexistentes_con_obs);
	alert(existentes_sin_obs);
	alert(existentes_con_obs);
	alert(excepcionados);
}

function buscar_datos_auto(placa){
	var cual= placa.value;
	if(cual == ""){
		alert("Debe escribir la placa");
		placa.focus();
		return false;
	}
	parametro = "variable=locplaca&placa="+cual;
		//alert(parametro);
	myConn.connect("../lib/include.php", "GET", parametro , jalar_datos2);

}

function llenar_garantia_contratoI(descripcion_bien, extension, ubicacion, registro_dr, superficie_titulo, superficie_plano, limite_este, limite_oeste, limite_norte, limite_sud, tipo_identificacion, garantia_contrato, datos_documento){
	var obj = document.getElementById('cant_campos')
	var can = parseInt(obj.value);
	var personas="";
	for (i=1; i <= can; i++) {
		//puede q no exista el nro indicado
		//alert(document.getElementById('hdnTitu_'+i).value);
		if ( document.getElementById('hdnNombre_'+i) && document.getElementById('hdnTitu_'+i).value =='S' ) 
			personas=personas+document.getElementById('hdnNombre_'+i).value+", ";
			
	}
	personas = personas.substring(0,personas.length-2);
	personas=personas+". ";
	
	var colocar= "Un(a) ";
	//descripcion_bien
	var aux=descripcion_bien.value;
	if(aux != ""){colocar=colocar+ aux + ", "}
	//extension
	aux=extension.value;
	if(aux != ""){colocar=colocar+"con una extensi\u00F3n superficial de " + aux + ", "}
	//ubicacion
	aux=ubicacion.value;
	if(aux != ""){colocar=colocar+"ubicado en " + aux + ", "}
	//colindancias
	colocar=colocar+"cuyas colindancias son: ";
	//limite_este
	aux=limite_este.value;
	if(aux != ""){colocar=colocar+"al Este: " + aux + ", "}
	//limite_oeste
	aux=limite_oeste.value;
	if(aux != ""){colocar=colocar+"al Oeste: " + aux + ", "}
	//limite_norte
	aux=limite_norte.value;
	if(aux != ""){colocar=colocar+"al Norte: " + aux + ", "}
	//limite_sud
	aux=limite_sud.value;
	if(aux != ""){colocar=colocar+"y al Sud: " + aux + ", "}
	
	//personas
	colocar=colocar+" registrado en Derechos Reales a nombre de: "+personas;
	colocar=colocar+"seg\u00FAn consta de(la): " + datos_documento.value+ ", bajo la(el) "+registro_dr.value;
	
	garantia_contrato.value=colocar;
}


function llenar_garantia_contratoV(placa,marca,chasis,modelo,motor,clase,tipo,color,alcaldia,garantia_contrato,  crpva, fecha_vehiculo, poliza, tipo_identificacion){
	//recuperamos la lista de personas
	var obj = document.getElementById('cant_campos')
	var can = parseInt(obj.value);
	var personas="";
	for (i=1; i <= can; i++) {
		//puede q no exista el nro indicado
		if ( document.getElementById('hdnNombre_'+i) && document.getElementById('hdnTitu_'+i).value =='S') 
			personas=personas+document.getElementById('hdnNombre_'+i).value+", ";
	}
	personas = personas.substring(0,personas.length-2);
	personas=personas+". ";
	
	var colocar= "Un veh\u00EDculo motorizado ";
	//marca
	var aux=marca.value;
	if(aux != ""){colocar=colocar+"marca " + aux + ", "}
	//clase
	aux=clase.value;
	if(aux != ""){colocar=colocar+"clase " + aux + ", "}
	//tipo
	aux=tipo.value;
	if(aux != ""){colocar=colocar+"tipo " + aux + ", "}
	//color
	aux=color.value;
	if(aux != ""){colocar=colocar+"color " + aux + ", "}
	//modelo
	aux=modelo.value;
	if(aux != ""){colocar=colocar+"modelo " + aux + ", "}
	//placa
	aux=placa.value;
	if(aux != ""){colocar=colocar+"con placa de circulaci\u00F3n Nº " + aux + ", "}
	//motor
	aux=motor.value;
	if(aux != ""){colocar=colocar+"motor " + aux + ", "}
	//chasis
	aux=chasis.value;
	if(aux != ""){colocar=colocar+"chasis " + aux + ", "}
	//poliza
	aux=poliza.value;
	if(aux != ""){colocar=colocar+"con P\u00F3liza de importaci\u00F3n Nº " + aux + ", "}
	//crpva
	aux=crpva.value;
	if(aux != ""){colocar=colocar+"y dem\u00E1s datos consignados en el Certificado de Registro de Veh\u00EDculo Automotor (CRPVA) Nº " + aux + ", "}
	//ciudad
	aux=alcaldia.value;
	if(aux != ""){colocar=colocar+"expedido en la ciudad de " + aux + ", "}
	//fecha
	aux=fecha_vehiculo.value;
	if(aux != ""){colocar=colocar+"en fecha " + aux + ", "}
	
	//personas
	colocar=colocar+"registrado a nombre de: "+personas;
	
	garantia_contrato.value=colocar;
}


function llenar_garantia_contratoM(tipo,placa,marca,motor,modelo,chasis,poliza,crpva,clase,fpoliza, sidunea, fsidunea,garantia_contrato){

	var obj = document.getElementById('cant_campos')
	var can = parseInt(obj.value);
	var personas="";
	for (i=1; i <= can; i++) {
		//puede q no exista el nro indicado
		if ( document.getElementById('hdnNombre_'+i) && document.getElementById('hdnTitu_'+i).value =='S' ) 
			personas=personas+document.getElementById('hdnNombre_'+i).value+", ";
	}
	personas = personas.substring(0,personas.length-2);
	personas=personas+". ";
	
	var colocar= "Un(a) ";
	//descripcion
	var aux=tipo.value;
	if(aux != ""){colocar=colocar +" "+ aux + ", "}
	//serie
	var aux=placa.value;
	if(aux != ""){colocar=colocar+"serie " + aux + ", "}
	//marca
	var aux=marca.value;
	if(aux != ""){colocar=colocar+"marca " + aux + ", "}
	//motor
	aux=motor.value;
	if(aux != ""){colocar=colocar+"motor " + aux + ", "}
	//modelo
	aux=modelo.value;
	if(aux != ""){colocar=colocar+"modelo " + aux + ", "}
	//chasis
	aux=chasis.value;
	if(aux != ""){colocar=colocar+"chasis " + aux + ", "}
	colocar=colocar+"y dem\u00E1s datos consignados en "
	//poliza
	aux=poliza.value;
	if(aux != ""){colocar=colocar+"P\u00F3liza de importaci\u00F3n Nº: " + aux + ", "}
	//poliza fecha
	aux=fpoliza.value;
	if(aux != ""){colocar=colocar+"de fecha " + aux + ", "}
	//sidunea
	aux=sidunea.value;
	if(aux != ""){colocar=colocar+"certificado SIDUNEA Nº: " + aux + ", "}
	//sidunea fecha
	aux=fsidunea.value;
	if(aux != ""){colocar=colocar+"de fecha " + aux + ", "}
	//otros
	aux=clase.value;
	if(aux != ""){colocar=colocar +" "+ aux + ", "}
	//crpva
	aux=crpva.value;
	if(aux != ""){colocar=colocar + "registrada en el SEDAG bajo " + aux + ", "}
	
	//fecha
//	aux=fecha_registro.value;
//	if(aux != ""){colocar=colocar+"en fecha " + aux + ", "}
	
	//personas
	colocar=colocar+"registrado a nombre de: "+personas;

	garantia_contrato.value=colocar;
}


function llenar_garantia_contratoS(tipo,marca,asiento,poliza,clase,fpoliza,garantia_contrato){

	var obj = document.getElementById('cant_campos')
	var can = parseInt(obj.value);
	var personas="";
	for (i=1; i <= can; i++) {
		//puede q no exista el nro indicado
		if ( document.getElementById('hdnNombre_'+i) ) 
			personas=personas+document.getElementById('hdnNombre_'+i).value+", ";
	}
	personas = personas.substring(0,personas.length-2);
	personas=personas+". ";
	
	var colocar= "";
	//cantidad
	var aux=asiento.value;
	if(aux != ""){colocar= aux + " "}
	//descripcion
	var aux=tipo.value;
	if(aux != ""){colocar=colocar +"de "+ aux + " "}
	//raza
	var aux=marca.value;
	if(aux != ""){colocar=colocar+"de raza " + aux + ", "}

	//certificadov registro
	aux=poliza.value;
	if(aux != ""){
		colocar=colocar+"con Certificado de Registro Nº: " + aux + ", "
		//certificado fecha
		aux=fpoliza.value;
		if(aux != ""){colocar=colocar+"de fecha " + aux + ", "}
	}

	//personas
	colocar=colocar+"de propiedad de: "+personas;

	garantia_contrato.value=colocar;
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

function habilitar_informe(){
	if( confirm("Esta seguro de querer\nHabilitar este informe") ){
		return true;
	}
	else{
		return false;
	}
}

function deshabilitar_informe(){
	if( confirm("Esta seguro de querer\nDeshabilitar este informe") ){
		return true;
	}
	else{
		return false;
	}
}

function colocar_en_foco(campo){
	campo.focus();
}

function openwin(phpwin){
	var opciones="left=400, top=50, width=700, height=400, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1";
	window.open(phpwin,'maestro',opciones);
}

function editawin(oId){
	openwin('../code/_main.php?action=modifica_persona.php&id='+oId);
	//var xci = document.getElementById('id_propietario').value;
/*	var xci = document.getElementById('hdnCi_' + oId).value
	alert(xci);
	if(xci=='0'){
	alert("Debe seleccionar la persona que desea modificar.");
	}else{
	openwin('../code/_main.php?action=modifica_persona.php&id='+xci);
	}*/
}
/*
function wdEditaDatos() {
    //var docsids = "";
	//docsids = docsid();
	var xci = document.getElementById('id_propietario').value;
	if(xci=='0'){
		alert("Debe seleccionar la persona que desea modificar.");
		return false;
	}
	//openwin('../code/_main.php?action=modifica_persona.php&id='+xci);
	
	ModalPopups.Custom("idCustom1",
        "Ingrese Datos del Cliente",   
       "<table>" +    
       "<tr><td>Nombre:</td>	<td><input type=text id='txtNom' maxlength='50' class='input' size='50'></td></tr>" +    
       "<tr><td>Mis:</td>		<td><input type=text id='txtMis' maxlength='25' class='input' size='15'></td></tr>" +    
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
			"</select><input type=hidden id='txtID' value='"+xci+"'></td></tr>" +	   
       "</table>" ,    
        {
            width: 500,
            buttons: "ok,cancel",
            okButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            onOk: "wdGuardar()",
            onCancel: "wdCancelar()"
        }
    );
    ModalPopups.GetCustomControl("txtNom").focus(); 
}
function wdGuardar() {
	var xNom = ModalPopups.GetCustomControl("txtNom");
	if(xNom.value == "") {
       alert("Ingrese el nombre del cliente!");
       xNom.focus();
	   return;
	}
	//var xEmi = ModalPopups.GetCustomControl("txtEmi");
	var xMis = ModalPopups.GetCustomControl("txtMis");
	var xNit = ModalPopups.GetCustomControl("txtNit");
	var xFon = ModalPopups.GetCustomControl("txtFon");
	var xDir = ModalPopups.GetCustomControl("txtDir");
	var xEC = ModalPopups.GetCustomControl("txtEC");
	var parametro = "";
		parametro = "variable=modpersona&ci="+xDoc.value+"&emi="+xEmi.value+"&nom="+xNom.value+"&mis="+xMis.value+"&nit="+xNit.value+"&fon="+xFon.value+"&dir="+xDir.value+"&eci="+xEC.value;
		//alert(parametro);
		//document.getElementById('nroruteo').innerHTML = "<img src='../images/actions/loading.gif' />";
		myConn.connect("../lib/include.php", "GET", parametro , jalar_datos1);
    ModalPopups.Close("idCustom1");
}
*/
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


function vervalor(campo,ind){
	var valor = campo.value;
	var obs = 'obs_'+ind;
	var antes = document.getElementById(obs);
	if(valor == 2 && antes.value=='')
	//if(valor == 12 && antes=='')
	antes.value = 'Presentar original o copia legalizada';
	else
	if(antes.value == 'Presentar original o copia legalizada')
		antes.value = '';
}

function utf8_decode (str_data) {
    // Converts a UTF-8 encoded string to ISO-8859-1  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/utf8_decode    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'    
	var tmp_arr = [],
        i = 0,
        ac = 0,
        c1 = 0,
        c2 = 0,        c3 = 0;
 
    str_data += '';
 
    while (i < str_data.length) {        c1 = str_data.charCodeAt(i);
        if (c1 < 128) {
            tmp_arr[ac++] = String.fromCharCode(c1);
            i++;
        } else if (c1 > 191 && c1 < 224) {            c2 = str_data.charCodeAt(i + 1);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
            i += 2;
        } else {
            c2 = str_data.charCodeAt(i + 1);            c3 = str_data.charCodeAt(i + 2);
            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }
    } 
    return tmp_arr.join('');
}
