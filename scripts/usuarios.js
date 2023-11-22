
var jalar_datos = function (oXML) {
	var texto = oXML.responseText;
	if(texto=='T')
		alert("El login indicado ya existe!");
	document.getElementById('login').focus();
	
};


function loc_login(login,antes)
{
	parametro = "variable=loclogin&login="+login+"&antes="+antes;
	myConn.connect("../lib/include.php", "GET", parametro , jalar_datos);

}

var jalar_datos2 = function (oXML) {
	var texto = oXML.responseText;
	if(texto=='s'){
		alert("El usuario ha sido habilitado.");
	}else{
		alert("No se ha podido habilitar al usuario! Intente nuevamente."); 
	}
};

function habilitarusr(idus)
{
	var mrandom = Math.round(Math.random()*99999);
	var mcheck = (mrandom+29054); 
	if(confirm("Esta seguro de habilitar el acceso a este usuario?")){
		parametro = "variable=activausr&idus="+idus+"&random=" + mrandom+"&idr=" + mcheck;
		myConn.connect("../lib/include.php", "GET", parametro , jalar_datos2);
	}
}
function cargar_adicionar(){
	document.adicionar.nombres.focus();
}

function cargar_modificar(){
	document.modificar.nombres.focus();
}

function cargar_eliminar(){
	document.eliminar.eliminar_boton.focus();
}

function verificar_adicion(llog, lpas){
	
	var txt= document.getElementById("nombres").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Indique el nombre del usuario por favor");
		document.getElementById("nombres").focus();
		return false;
	}
	var txt= document.getElementById("perfil").value
	txt = txt.replace(/ /g, "");
	if(txt=='ninguno'){
		alert("Seleccione el perfil por favor");
		document.getElementById("perfil").focus();
		return false;
	}
	var txt= document.getElementById("login").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese el login por favor");
		document.getElementById("login").focus();
		return false;
	}
	
	if(llog > 0 && txt.length < llog ){
		alert("Ingrese el login con "+llog+" o m\u00E1s caracteres");
		document.getElementById("login").focus();
		return false;
	}
	if(!document.getElementById("pasword").disabled){
		var txt= document.getElementById("pasword").value
		txt = txt.replace(/ /g, "");
		if(txt==''){
			alert("Ingrese la contrase\u00F1a por favor");
			document.getElementById("pasword").focus();
			return false;
		}
		if(lpas > 0 && txt.length < lpas ){
			alert("Ingrese la contrase\u00F1a con "+lpas+" o m\u00E1s caracteres");
			document.getElementById("pasword").focus();
			return false;
		}
		var txt2= document.getElementById("pasword2").value
		txt2 = txt2.replace(/ /g, "");
		if(txt!=txt2){
			alert("Las contrase\u00F1as no coinciden!");
			document.getElementById("pasword").focus();
			return false;
		}
	}
	
	// verificar si existe login via Ajax
	//var txt= document.getElementById("login").value
	//var ant= document.getElementById("antes").value
	//loc_login(txt,ant);
	
	if(!confirm("Estan todos los datos correctos?"))
		return false;
	return true;
}


function habilitachk(chk){
	document.all("pasword").disabled = !chk.checked;
	document.all("pasword2").disabled = !chk.checked;
	//document.all("pasword").focus();
	return false;
}

function eliminar_confirmar(sex, que){

		if( confirm("Esta Seguro de querer\nEliminar " +sex+" "+que) ){
			return true;
		}
		else{
			return false;
		}

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