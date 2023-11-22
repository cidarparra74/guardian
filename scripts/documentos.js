function cargar_adicionar(){
	document.adicionar.documento.focus();
}

function cargar_modificar(){
	document.modificar.documento.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
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

function des_documentos(){
	document.documentos.adicionar.disabled=true;
}

function verificar_adicion(){
	var que_es= "documento";
	var sex_es= "un";
	var mensaje_no_existe= "Escriba " + sex_es + " " + que_es;
	var ya_existe= "Ya existe un " + que_es + " con este nombre\nEscriba otro " + que_es;
	
	var valor= document.all[que_es];
	var antes_espacio= valor.value;
	var ac= antes_espacio.replace(/ /g, "");
	
	var arreglo= new Array();
	var dividir= document.all["existentes"].value;
	arreglo= dividir.split(";");
	var i=0;
	
	if(ac == ""){
		alert(mensaje_no_existe);
		valor.focus();
		return false;
	}

	var nuevo_valor= new String("");	
	nuevo_valor= ac.toLowerCase();
	
	var comparacion= new String("");
	while(arreglo[i] != ""){
		comparacion= arreglo[i].toLowerCase();
		sin_espacio= comparacion.replace(/ /g, "");

		if(nuevo_valor == sin_espacio){
			alert(ya_existe);
			valor.focus();
			return false;
		}
		i++;
	}
	
	//grupo_documento
	var que_es= "un grupo de documento";
	var valor= document.all["grupo"];
	var antes_valor= valor.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == "ninguno"){
		alert("Debe Seleccionar "+que_es);
		valor.focus();
		return false;
	}
	
	//tiene fecha
	var que_es= "si tiene Fecha";
	var valor= document.all["tiene_fecha"];
	var antes_valor= valor.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == "ninguno"){
		alert("Debe Seleccionar "+que_es);
		valor.focus();
		return false;
	}
	
	//vencimiento
	var que_es= "si tiene vencimiento";
	var valor= document.all["vencimiento"];
	var antes_valor= valor.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == "ninguno"){
		alert("Debe Seleccionar "+que_es);
		valor.focus();
		return false;
	}
	
	//meses vencimiento
	var que_es= "un mes de vencimiento";
	var valor= document.all["meses_vencimiento"];
	var antes_valor= valor.value;
	var ver_valor= antes_valor.replace(/ /g, "");
	if(ver_valor == ""){
		if(document.all["vencimiento"].value == "si"){		
			alert("Debe escribir "+que_es);
			valor.focus();
			return false;
		}
	}
	
	return true;
}

function eliminar_confirmar(sex, que, puede_eliminar){
	if(puede_eliminar.value == "si"){
		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
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

function validar_numeros(campo, maximo){
	maximo_valor= parseInt(maximo);
	var tam= campo.value.length;
	var valor= "";
	var letra= "";
	var nuevo_valor= "";
	for(i=0; i<tam; i++){
		valor= campo.value.substring(i, (i+1));
		letra= valor.toUpperCase();
		if(letra == "1" || letra == "2" || letra == "3" || letra == "4" || letra == "5" || letra == "6" || letra == "7" || letra == "8" || letra == "9" || letra == "0"){
			nuevo_valor= nuevo_valor+letra;
		}
	}
	if(parseFloat(nuevo_valor)>maximo_valor){
		alert("El puntaje no debe ser mayor a : " + maximo);
		campo.value="";
		campo.focus();
		return false;
	}
	else{
		campo.value=nuevo_valor;
	}
}