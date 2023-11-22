<?php
	//ARMADO DEL CONTRATO FINAL (XML)
//ini_set('odbc.defaultlrl','1048576');
//verificamos si el contrato tiene partes
if(isset($_REQUEST["id"])){

	$idfinal=$_REQUEST["id"];
	
	//llamar al Web Service para crear el doc en WORD
	$resulta=0;
	require('ws_sec.php');
	//mostrar el DOC
	if($resulta==0){
		//se ha generado el DOC
		$alert = "Se ha guardado el contrato, puede abrir el documento";
	}else{
		$alert = "Atenci&oacute;n! No se pudo generar el documento, codigo de error: $resulta.";
	}
}
	
?>