<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url2, ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url2']==''){
	echo 'No se pudo completar la operación, URL no definida ';
	echo '<br>';
	echo 'Revise al configuraci&oacute;n del Servicio WEB.';
    die();
}
if($rowws['ws_url1']=='php:'){
	$mensaje = 'NOK';
	echo "PRUEBA. sin ws";
}else{

	$WS_url=$rowws['ws_url2'];
		 
	//echo "entra";
	//$WS_url="http://applpre03srv/wsInterfacesBSol/contabiliza.asmx?wsdl";

	//echo htmlentities($xmlEntrada);

	$oSoapClient = new nusoap_client($WS_url, true);
	//ncas permane en$parametros incluso para los proximos WS
	$parametros = array('canal' => '217',
						'Usuario' => 'userGuardian',
						'Clave' => 'abcd1234',
						'idAsiento' => $idAsiento,
						'xmlEntrada' => $xmlEntrada);
	/*
	echo "<pre>";
	print_r($parametros);
	echo "</pre>";
	echo "<br>";
	echo htmlentities($xmlEntrada);
	echo "<br>";
	*/
	$resulta = array();
	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("Execute", $parametros);
	if ($oSoapClient->fault) { // Si
			die('fault');
	}else{ // No
			$sError = $oSoapClient->getError();
			// Hay algun error ?
			if ($sError) { // Si
				die('Error en WS execute: '.$sError);
			}else{
			$resulta = $result["estado"];

			}
	}
	/*
	echo "<pre>";
	print_r($result);
	echo "</pre>";
	*/
	if(isset($resulta)){
		$mensaje = $resulta;
		//if($mensaje!='OK'){
		//	echo $mensaje.'<br>';
		//	echo 'ACCION CANCELADA: No se pudo contabilizar por error en el WS. Intente nuevamente.';
		//}
	}
	//echo $mensaje;
}
?>