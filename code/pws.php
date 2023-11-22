<?php

require_once('../lib/lib/nusoap.php');

$oSoapClient = new nusoap_client('http://21.10.40.9/wsBec/FlujoCredito.asmx?wsdl',true);
$parametros = array( 'numeroCaso'   => '1');


$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("getHojaVariablesGuardian", $parametros);
if ($oSoapClient->fault) { 
	echo 'No se pudo completar la operación'.$oSoapClient->getError();
	die();
} else { 
	$sError = $oSoapClient->getError();
	if ($sError) { 
		 echo 'Error!:'.$sError;
		 die();
	}
}

if(is_array($respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"])){
	$valores = $respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"];
	
	$tipocartera = 		$valores["tipocartera"];
	$importeprestamo = 	$valores["importeprestamo"];
	$monedaprestamo = 	$valores["monedaprestamo"];
	$cuentadesembolso = $valores["cuentadesembolso"];
	$destinocredito = 	$valores["destinocredito"];
	$numerocuotas = 	$valores["numerocuotas"];
	$tasa1 = 			$valores["tasa1"];
	$tasa2 = 			$valores["tasa2"];
	$clasificacion = 	$valores["clasificacion"];
	$cuotastasafija = 	$valores["cuotastasafija"];
	$cuentadebito = 	$valores["cuentadebito"];
	$numerolinea = 		$valores["numerolinea"];
	$importelinea = 	$valores["importelinea"];
	$monedalinea = 		$valores["monedalinea"];
	$plazomeses = 		$valores["plazomeses"];
	$plazodias = 		$valores["plazodias"];
}else{
	$tipocartera = 		'';
	$importeprestamo = 	'';
	$monedaprestamo = 	'';
	$cuentadesembolso = '';
	$destinocredito = 	'';
	$numerocuotas = 	'';
	$tasa1 = 			'';
	$tasa2 = 			'';
	$clasificacion = 	'';
	$cuotastasafija = 	'';
	$cuentadebito = 	'';
	$numerolinea = 		'';
	$importelinea = 	'';
	$monedalinea = 		'';
	$plazomeses = 		'';
	$plazodias = 		'';
}
?>