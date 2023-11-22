
function cargar_formulario(){
	document.formulario.nombres_apellidos.focus();
}

function cargar_eliminar(){
	document.formulario.eliminar_boton.focus();
}

function cargar_formulario_hab(){
	document.formulario.habilitando_informe.focus();
}

function cargar_formulario_des(){
	document.formulario.deshabilitando_informe.focus();
}

function verificar_formulario(){
	
	//para el n\u00FAmero
	/*var antes= document.all["numero_informe"];
	var antes_valor= antes.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == ""){
		alert("Debe escribir un n\u00FAmero para este informe");
		antes.focus();
		return false;
	}*/
	
	//para el cliente
	var antes= document.all["cliente"];
	var antes_valor= antes.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == ""){
		alert("Debe escribir un cliente");
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
	
	//llenanmos los datos de las personas
	var i=0;
	var lista_personas=document.all["lista_personas"];
	var poner="";
	for (i=0; i < lista_personas.options.length; i++) {
		var texto= lista_personas.options[i].text;
		poner=poner+texto+";";
	}
	document.formulario.p_lista_personas.value=poner;
	
	
	
	
	
	i=0;
	var limite= parseInt(document.formulario.cantidad_documentos.value);
	var aux=document.formulario.p_ids.value;
	var listaA= new Array();
	listaA= aux.split(";");

	for(i=0;i<limite;i++){
		var aux_obs="observaciones_"+listaA[i];
		var obs= document.all[aux_obs];
		var cadena= Trim(obs.value);
		obs.value=cadena;
	}//fin del for
	
	
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



function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
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
			
			/*var xxx= parseInt(division[1]);
			alert(xxx);*/
			//calculamos el mes en mmm
			var para_mes="";
			/*if(parseInt(division[1])==1){para_mes="ENE";}
			if(parseInt(division[1])==2){para_mes="FEB";}
			if(parseInt(division[1])==3){para_mes="MAR";}
			if(parseInt(division[1])==4){para_mes="ABR";}
			if(parseInt(division[1])==5){para_mes="MAY";}
			if(parseInt(division[1])==6){para_mes="JUN";}
			if(parseInt(division[1])==7){para_mes="JUL";}
			if(parseInt(division[1])==8){para_mes="AGO";}
			if(parseInt(division[1])==9){para_mes="SEP";}
			if(parseInt(division[1])==10){para_mes="OCT";}
			if(parseInt(division[1])==11){para_mes="NOV";}
			if(parseInt(division[1])==12){para_mes="DIC";}*/
			
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


function colocar_lista(nombres_apellidos,ci,nit,direccion,lista_personas,estado_civil, tipo_identificacion_p){
	if(nombres_apellidos.value==""){
		alert("Debe escribir el nombre");
		nombres_apellidos.focus();
		return false;
	}
	
	if(ci.value==""){
		alert("Debe escribir el ci");
		ci.focus();
		return false;
	}
	
	var i=0;
	for (i=0; i < lista_personas.options.length; i++) {
		//arrayPersonas[i]=lista_personas.options[i].text;
	}
	var nueva_persona=nombres_apellidos.value+"|"+ci.value+"|"+nit.value+"|"+direccion.value+"|"+estado_civil.value+"|"+tipo_identificacion_p.value;
	var optionObj = new Option(nueva_persona, nueva_persona);
	lista_personas.options[i]=optionObj;
	nombres_apellidos.value="";
	ci.value="";
	nit.value="";
	direccion.value="";
	nombres_apellidos.focus();
	if((i+1)>2){
		lista_personas.size=(i+1);
	}
	else{
		lista_personas.size=2;
	}
}

//guardar
function guardar_lista(nombres_apellidos,ci,nit,direccion,lista_personas, estado_civil, tipo_identificacion_p){
	if(nombres_apellidos.value==""){
		alert("Debe escribir el nombre");
		nombres_apellidos.focus();
		return false;
	}
	
	if(ci.value==""){
		alert("Debe escribir el ci");
		ci.focus();
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

function sacar_lista(nombres_apellidos,ci,nit,direccion,lista_personas){
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
			var contador=0;
			for (i=0; i < lista_personas.options.length; i++) {
				if(seleccionado!=i){ //entra
					arrayP[contador]=lista_personas.options[i].text;
					contador++;
				}
			}
			//borramos la lista
			lista_personas.length=0;
			for(i=0; i<contador;i++){
				var optionObj = new Option(arrayP[i], arrayP[i]);
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
}

//mostrando los datos de las personas
function mostrar_datos_persona(nombres_apellidos,ci,nit,direccion,lista_personas, estado_civil, tipo_identificacion_p){
	var i=0;
	var j=0;
	var posicion_estado=0;
	//var seleccionado=-1;
	for (i=0; i < lista_personas.options.length; i++) {
		//alert(lista_personas.options[i].selected);
		if(lista_personas.options[i].selected){
			var texto= lista_personas.options[i].text;
			var division= texto.split("|");
			nombres_apellidos.value=division[0];	
			ci.value=division[1];
			nit.value=division[2];
			direccion.value=division[3];
			
			
			var aux= division[4];
			for(j=0;j<estado_civil.options.length; j++){
				if(aux == estado_civil.options[j].value){
					posicion_estado=j;
				}
			}
			estado_civil.options[posicion_estado].selected=true;
			//alert(tipo_identificacion_p);
			var aux2= division[5];
			for(j=0;j<tipo_identificacion_p.options.length; j++){
				if(aux2 == tipo_identificacion_p.options[j].value){
					posicion_estado=j;
				}
			}
			tipo_identificacion_p.options[posicion_estado].selected=true;
		}
	}
	/*if(i==0){
		alert("No existe propietarios creados");
		return false;
	}*/	
}


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

function buscar_datos_auto(placa,rua,chasis,rpva,motor,fecha_vehiculo,poliza,ciudad){
	var cual= placa.value;
	if(cual == ""){
		alert("Debe escribir la placa");
		placa.focus();
		return false;
	}
	
	nombre_archivo="autoriza.php?carpeta_entrar=informe_legal&busqueda_datos_vehiculo=acc"+"&placa="+placa.value;
	//alert(nombre_archivo);
	//return false;
	var alto=(screen.height/2)-50; 
	var ancho=(screen.width/2)-50; 
	var ejecutar="window.open('" + nombre_archivo + "', 'ventana_busqueda','width=100,height=100, top=" + alto + ", left=" + ancho + ", toolbar=0,status=0')";
	eval(ejecutar);
}

function llenar_garantia_contrato(placa,marca,chasis,modelo,motor,clase,tipo,color,alcaldia,garantia_contrato, lista_personas, crpva, fecha_vehiculo, poliza, tipo_identificacion){
	//recuperamos la lista de personas
	var i=0;
	
	var j=0;
	var nombre_doc="";
	var posicion=0;
	
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
	/*
	var personas="";
	for (i=0; i < lista_personas.options.length; i++) {
			var texto= lista_personas.options[i].text;
			var division= texto.split("|");
			var aux= division[5];
			for(j=0;j<tipo_identificacion.options.length; j++){
				if(aux == tipo_identificacion.options[j].value){
					posicion=j;
				}
			}
			nombre_doc=tipo_identificacion.options[posicion].text;
			
			personas=personas+division[0]+" con "+nombre_doc+" Nº "+division[1]+", ";
	}
	personas = personas.substring(0,personas.length-2);
	personas=personas+".";
	*/
	
	var colocar= "Un veh\u00EDculo motorizado ";
	//marca
	var aux=marca.value;
	if(aux != ""){colocar=colocar+"marca: " + aux + ", "}
	//clase
	aux=clase.value;
	if(aux != ""){colocar=colocar+"clase: " + aux + ", "}
	//tipo
	aux=tipo.value;
	if(aux != ""){colocar=colocar+"tipo: " + aux + ", "}
	//color
	aux=color.value;
	if(aux != ""){colocar=colocar+"color: " + aux + ", "}
	//modelo
	aux=modelo.value;
	if(aux != ""){colocar=colocar+"modelo: " + aux + ", "}
	//placa
	aux=placa.value;
	if(aux != ""){colocar=colocar+"con placa de circulaci\u00F3n Nº: " + aux + ", "}
	//motor
	aux=motor.value;
	if(aux != ""){colocar=colocar+"motor: " + aux + ", "}
	//chasis
	aux=chasis.value;
	if(aux != ""){colocar=colocar+"chasis: " + aux + ", "}
	//poliza
	aux=poliza.value;
	if(aux != ""){colocar=colocar+"P\u00F3liza de importaci\u00F3n Nº: " + aux + ", "}
	//crpva
	aux=crpva.value;
	if(aux != ""){colocar=colocar+"y dem\u00E1s datos consignados en el Certificado de Registro de Veh\u00EDculo Automotor (CRPVA) Nº : " + aux + ", "}
	//ciudad
	aux=alcaldia.value;
	if(aux != ""){colocar=colocar+"expedido en la ciudad de: " + aux + ", "}
	//fecha
	aux=fecha_vehiculo.value;
	if(aux != ""){colocar=colocar+"en fecha: " + aux + ", "}
	
	//personas
	colocar=colocar+" de propiedad de: "+personas;
	
	/*var colocar="La hipoteca de un Veh\u00EDculo Motorizado de propiedad de EL (LA) (LOS) (LAS) "+personas+"que se encuentra LIBRE DE GRAVAMENES, consiste en: ";
	colocar=colocar+"Un "+clase.value+", tipo "+tipo.value+", color "+color.value+", marca "+marca.value+", modelo "+modelo.value+", placa de circulaci\u00F3n Nº "+placa.value+", motor "+motor.value;
	colocar=colocar+", chasis Nº "+chasis.value+", Certificado de Registro de Propiedad de Veh\u00EDculo Automotor (CRPVA) Nº       , expedido en "+alcaldia.value+", en fecha    , ";
	colocar=colocar+"Form. RUA 03 No.      P\u00F3liza de Importaci\u00F3n Nº";*/
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

function habilitar_informe(){
	if( confirm("Esta Seguro de querer\nHabilitar este informe") ){
		return true;
	}
	else{
		return false;
	}
}

function deshabilitar_informe(){
	if( confirm("Esta Seguro de querer\nDeshabilitar este informe") ){
		return true;
	}
	else{
		return false;
	}
}

function colocar_en_foco(campo){
	campo.focus();
}
