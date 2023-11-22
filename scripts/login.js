
function verificar_pss(){

	var txt= document.getElementById("password0").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese su contrase\u00F1a actual.");
		document.getElementById("password0").focus();
		return false;
	}
	var txt= document.getElementById("password1").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese la contrase\u00F1a nueva por favor.");
		document.getElementById("password1").focus();
		return false;
	}

		var txt2= document.getElementById("password2").value
		txt2 = txt2.replace(/ /g, "");
		if(txt2==''){
			alert("Repita la contrase\u00F1a nueva por favor.");
			document.getElementById("password2").focus();
			return false;
		}

		if(txt!=txt2){
			alert("Las contrase\u00F1as no coinciden!");
			document.getElementById("password1").focus();
			return false;
		}
	

	return true;
}

function verificar_up(){

	var txt= document.getElementById("password").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese su contrase\u00F1a de windows.");
		document.getElementById("password").focus();
		return false;
	}
	var txt= document.getElementById("username2").value
	txt = txt.replace(/ /g, "");
	if(txt==''){
		alert("Ingrese su nombre de usuario guardian por favor.");
		document.getElementById("username2").focus();
		return false;
	}
	var txt2= document.getElementById("password2").value
	txt2 = txt2.replace(/ /g, "");
	if(txt2==''){
		alert("Ingrese su contrase\u00F1a de guardian por favor.");
		document.getElementById("password2").focus();
		return false;
	}
	
	return true;
}
