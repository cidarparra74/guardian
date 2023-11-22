var myConn = new XHConn();

if (!myConn) alert("XMLHTTP no disponible! Intente con otro navegador.");

var jalaDatos = function (oXML) {
	var texto = oXML.responseText;
	
	alert(texto);

};

function mailtest(form){
	var smtp = document.getElementById("smtp");
	var mail = document.getElementById("mailSender");
	
	if (smtp.value.length==0){
		alert("Ingrese el servidor de correo!");
		return false;
	}
	if (mail.value.length==0){
		alert("Ingrese el correo-e para el remitente!");
		return false;
	}
	var dest = prompt("Por favor indique el correo del destinatario","");
	if(dest!=''){
		var parametro = "";
		parametro = "variable=mailtest&smtp="+smtp.value+"&mail="+mail.value+"&dest="+dest+"&random=" + Math.random()*99999;
		myConn.connect("../lib/include.php", "GET", parametro , jalaDatos);
	}else{
		alert("Cancelado");
	}
}



function wdPedirCorreo() {
    var smtp = document.getElementById("smtp");
	var mail = document.getElementById("mailSender");
	ModalPopups.Custom("idCustom1",
    "Prueba de Env\u00EDo de Correo",   
	"<table>" +    
    "<tr><td>IP Servidor SMTP:</td>"+
		"<td><input type=text id='txtSMTP' class='input' size='50' value='"+smtp.value+"'></td></tr>" +
	"<tr><td>Correo Saliente:</td>"+
		"<td><input type=text id='txtMail' class='input' size='50' value='"+mail.value+"'></td></tr>" +	
    "<tr><td>Usuario:</td>"+
		"<td><input type=text id='txtUser' class='input' size='15'></td></tr>" +    
    "<tr><td>Contrase&ntilde;a:</td>"+
		"<td><input type=text id='txtPass' class='input' size='15'></td></tr>"  +	   
    "<tr><td>Correo Prueba:</td>"+
		"<td><input type=text id='txtTest' class='input' size='50'></td></tr>" +	
	"</table>" ,    
        {
            width: 500,
            buttons: "ok,cancel",
            okButtonText: "Continuar",
            cancelButtonText: "Cancelar",
            onOk: "wdContinuar()",
            onCancel: "wdCancelar()"
        }
    );
    ModalPopups.GetCustomControl("txtTest").focus(); 
}
function wdContinuar() {
    var xSMTP = ModalPopups.GetCustomControl("txtSMTP");
	var xMail = ModalPopups.GetCustomControl("txtMail");
	var xPass = ModalPopups.GetCustomControl("txtPass");
	var xUser = ModalPopups.GetCustomControl("txtUser");
	var xTest = ModalPopups.GetCustomControl("txtTest");
	if(xSMTP.value == "") {
       alert("Ingrese el servidor de correo!");
       xSMTP.focus();
	   return;
	}
	if(xMail.value == "") {
       alert("Ingrese el correo electr\u00F3nico saliente!");
       xMail.focus();
	   return;
	}
	if(xTest.value == "") {
       alert("Ingrese el correo electr\u00F3nico de prueba!");
       xTest.focus();
	   return;
	}
	var parametro = "";
	parametro = "variable=mailtest&smtp="+xSMTP+"&mail="+xMail+"&user="+xUser+"&pass="+xPass+"&dest="+xTest+"&random=" + Math.random()*99999;
	myConn.connect("../lib/include.php", "GET", parametro , jalaDatos);
    ModalPopups.Close("idCustom1");
}

function wdCancelar() {    
    ModalPopups.Cancel("idCustom1");
}