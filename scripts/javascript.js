
/****para el cambio de password del administrador************************/
function verificar_cambio(pass, pass_n1, pass_n2){
	var antes_pass= pass.value;
	var ver_pass= antes_pass.replace(/ /g, "");
	if(ver_pass == ""){
		alert("Debe escribir su antiguo password");
		document.admin.password_ant.focus();
		return false;
	}
	
	var antes_pass_n1= pass_n1.value;
	var ver_pass_n1= antes_pass_n1.replace(/ /g, "");
	if(ver_pass_n1 == ""){
		alert("Debe escribir su nuevo password");
		document.admin.password_n1.focus();
		return false;
	}
	
	var antes_pass_n2= pass_n2.value;
	var ver_pass_n2= antes_pass_n2.replace(/ /g, "");
	if(ver_pass_n2 == ""){
		alert("Debe repetir su nuevo password");
		document.admin.password_n2.focus();
		return false;
	}
	
	if(antes_pass_n1 != antes_pass_n2){
		alert("Los datos de su nuevo password deben coincidir\nEl campo de Nuevo Password no coincide\ncon el campo de Repetir Password");
		document.admin.password_n1.focus();
		return false;
	}
	
	return true;
}


//para el primer cambio del password
function verificar_primer_cambio(pass_n1, pass_n2){
	
	var antes_pass_n1= pass_n1.value;
	var ver_pass_n1= antes_pass_n1.replace(/ /g, "");
	if(ver_pass_n1 == ""){
		alert("Debe escribir su nuevo password");
		document.admin.password_n1.focus();
		return false;
	}
	
	var antes_pass_n2= pass_n2.value;
	var ver_pass_n2= antes_pass_n2.replace(/ /g, "");
	if(ver_pass_n2 == ""){
		alert("Debe repetir su nuevo password");
		document.admin.password_n2.focus();
		return false;
	}
	
	if(antes_pass_n1 != antes_pass_n2){
		alert("Los datos de su nuevo password deben coincidir\nEl campo de Nuevo Password no coincide\ncon el campo de Repetir Password");
		document.admin.password_n1.focus();
		return false;
	}
	
	return true;
}


function cargar_pass(){
	document.admin.password_ant.focus();
}

function cargar_pass1(){
	document.admin.password_n1.focus();
}
/****fin de para el cambio de password del administrador************************/