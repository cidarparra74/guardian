ModalPopups.SetDefaults( { 
yesButtonText: "Si", 
cancelButtonText: "Cancelar", 
shadow: false, 
titleBackColor:  "#CCE6FF", 
titleFontColor:  "#404040", 
popupBackColor:  "#ffffff",
footerBackColor: "#CCE6FF", 
footerFontColor: "#404040"
} );

function wdPedirChPass(idu) {
	//window.parent.frames[2].focus();
	//window.parent.frames[2].location="../code/_intro.php";
	//window.parent.frames[2].location="../code/password.php";
	//alert(window.top.frames[1].name); //eestamos en el primer frameset
	//alert(window.top.frames[1].document.frames[0].name);  //es el topframe (segundo frameset)
	//alert(window.top.frames[1].document.getElementById('wframe').name);
	wf=window.top.frames[1].document.getElementById('wframe');
	wf.rows=(wf.rows=='105,*') ? '*,0' : '105,*';
	
	
	ModalPopups.Custom("idCustom1CP",
     "Cambiar Contrase\u00F1a",   
     "<table>" +    
     "<tr><td align='right'>Contrase\u00F1a Actual:<input type='password' id='txtact' maxlength='25' class='input' size='15'></td>" +
     "<td align='right'>Contrase\u00F1a Nueva:<input type='password' id='txtnue' maxlength='25' class='input' size='15'></td>" +    
     "<td align='right'>Repita Contrase\u00F1a:<input type='password' id='txtrep' maxlength='25' class='input' size='15'></td></tr>" +
     "<tr><td colspan='3'>&nbsp;</td></tr></table>" ,    
        {
            width: 500,
            buttons: "ok,cancel",
            okButtonText: "Cambiar",
            cancelButtonText: "Cancelar",
            onOk: "wdContinuarCP("+idu+")",
            onCancel: "wdCancelarCP()"
        }
    );
    ModalPopups.GetCustomControl("txtact").focus(); 
	
}
function wdContinuarCP(idu) {
    var xact = ModalPopups.GetCustomControl("txtact");
	var xnue = ModalPopups.GetCustomControl("txtnue");
	var xrep = ModalPopups.GetCustomControl("txtrep");
	
	if(xact.value == "") {
       alert("Ingrese la contrase\u00F1a actual!");
       xact.focus();
	   return;
	}
	//xnue = xnue.replace(/ /g, "");
	if(xnue.value == "") {
       alert("Ingrese la contrese\u00F1a nueva!");
       xnue.focus();
	   return;
	}
	if(xnue.length < 6){
		alert("Ingrese la contrase\u00F1a con 6 o m\u00E1s caracteres");
		xnue.focus();
		return false;
	}
	if(xnue.value != xrep.value){
       alert("Las contrase\u00F1as no son iguales!");
       xnue.focus();
	   return;
	}
	var parametro = "";
	parametro = "variable=chpass&act="+xact.value+"&nue="+xnue.value+"&id="+idu;
	//	alert(parametro);
	myConn.connect("../lib/include.php", "GET", parametro , confirmarCP);
    
}

function wdCancelarCP() {
	wf=window.top.frames[1].document.getElementById('wframe');
	wf.rows=(wf.rows=='105,*') ? '*,0' : '105,*';
    ModalPopups.Cancel("idCustom1CP");
}

var confirmarCP = function (oXML) {
    var texto = oXML.responseText;
	//alert(texto);
	if(texto=='0'){
		alert("Se ha cambiado la contrase\u00F1a con \u00E9xito");
		ModalPopups.Close("idCustom1CP");
		wf=window.top.frames[1].document.getElementById('wframe');
		wf.rows=(wf.rows=='105,*') ? '*,0' : '105,*';
	}else{
		alert("No se pudo cambiar, revise la contrase\u00F1a actual.");
	}
	
}
