<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida ';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
}
$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url,true);
$parametros = array( 'numeroCaso'   => $valor);


$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("getHojaVariablesGuardian", $parametros);
if ($oSoapClient->fault) { 
	echo 'No se pudo completar la operación: '.$oSoapClient->getError();

	die();
} else { 
	$sError = $oSoapClient->getError();
	if ($sError) { 
		 echo 'Error!:'.$sError;
		 die();
	}
}
//echo '<pre>';
//$ver = $respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"];
//print_r($ver);
//echo '</pre>'; 
if(isset($respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"])){
	$sihay=1;
/*

- <hojaVariables diffgr:id="hojaVariables1" msdata:rowOrder="0">
  <tipocartera>1</tipocartera> 
  <importeprestamo>1000.00</importeprestamo> 
  <monedaprestamo>1</monedaprestamo> 
  <cuentadesembolso /> 
  <destinocredito>prueba</destinocredito> 
  <numerocuotas>0</numerocuotas> 
  <tasa1>0.0000</tasa1> 
  <tasa2>0.00</tasa2> 
  <cuotastasafija>0</cuotastasafija> 
  <cuentadebito /> 
  <numerolinea>0</numerolinea> 
  <importelinea>0.00</importelinea> 
  <monedalinea>0</monedalinea> 
  <plazomeses>0</plazomeses> 
  <plazodias>0</plazodias> 
  <segurodegravamen>S</segurodegravamen> 
  <atributoobligatorio>PRESTATARIO y/o DEUDOR</atributoobligatorio> 
  <frecuenciapagok>MENSUAL</frecuenciapagok> 
  <objetocredito>LIBRE DISPONIBILIDAD</objetocredito> 
  <linearotativa>N</linearotativa> 
  <tipogarantia>HIPOTECARIA URBANA</tipogarantia>
  
 a partir de 10/2013:
  
  <banca>MICROFINANZAS</banca> 
  
 a partir de 20/02/2014:
  
  <codigoagencia>10</codigoagencia> 
  </hojaVariables>
 

  
  bancas:
				BANCA EMPRESARIAL
				BANCA PERSONAS
				PEQUENA EMPRESA
				MEDIANA EMPRESA
				RECUPERACIONES (SCZ)
				RECUPERACIONES (LPZ)
				RECUPERACIONES (CBB)
				MI SOCIO
				MICROFINANZAS
				OTROS


*/  
	$valores = $respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"];
	
	$tipocartera = 		$valores["tipocartera"];		if($tipocartera =='') $tipocartera = '0';
	$importeprestamo = 	$valores["importeprestamo"];		if($importeprestamo =='') $importeprestamo = '0';
	$monedaprestamo = 	$valores["monedaprestamo"];		if($monedaprestamo =='') $monedaprestamo = '0';
	$cuentadesembolso = $valores["cuentadesembolso"];
		//
	$destinocredito = 	$valores["destinocredito"];
		//
	$numerocuotas = 	$valores["numerocuotas"];		if($numerocuotas =='') $numerocuotas = '0';
	$tasa1 = 			$valores["tasa1"];				if($tasa1 =='') $tasa1 = '0';
	$tasa2 = 			$valores["tasa2"];				if($tasa2 =='') $tasa2 = '0';
	$cuotastasafija = 	$valores["cuotastasafija"];		if($cuotastasafija =='') $cuotastasafija = '0';
	$cuentadebito = 	$valores["cuentadebito"];
		//
	$numerolinea = 		$valores["numerolinea"];		if($numerolinea =='') $numerolinea = '0';
		//
	if(isset($valores["importelinea"])){
		$importelinea = 	$valores["importelinea"];	if($importelinea =='') $importelinea = '0';
		$monedalinea = 		$valores["monedalinea"];	if($monedalinea =='') $monedalinea = '0';
	}else{
		//temporalmente asignamos valos de importe prestamo
		$importelinea = 	$valores["importeprestamo"];	if($importelinea =='') $importelinea = '0';
		$monedalinea = 		$valores["monedaprestamo"];		if($monedalinea =='') $monedalinea = '0';
	}
		
	$plazomeses = 		$valores["plazomeses"]; 		if($plazomeses =='') $plazomeses = '0';
	$plazodias = 		$valores["plazodias"];			if($plazodias =='') $plazodias = '0';
		
	$segurodegravamen = 	$valores["segurodegravamen"];
	$atributoobligatorio = 	$valores["atributoobligatorio"]; //este es para partes, pero solo para titular
	$frecuenciapagok = 		$valores["frecuenciapagok"];
	$objetocredito = 		$valores["objetocredito"];
	
	$linearotativa = 		$valores["linearotativa"];
	$tipogarantia  = 		$valores["tipogarantia"];
	$banca  		= 		trim($valores["banca"]);
	$id_banca =  99;
	if($banca=='MICROFINANZAS')
		$id_banca =  1;
	elseif($banca=='PEQUENA EMPRESA')
		$id_banca =  2;
	elseif($banca=='MEDIANA EMPRESA')
		$id_banca =  3;
	elseif($banca=='BANCA EMPRESARIAL')
		$id_banca =  4;
	elseif($banca=='BANCA PERSONAS')
		$id_banca =  5;
	elseif($banca=='RECUPERACIONES (SCZ)')
		$id_banca =  6;
	elseif($banca=='RECUPERACIONES (LPZ)')
		$id_banca =  7;
	elseif($banca=='RECUPERACIONES (CBB)')
		$id_banca =  8;
	elseif($banca=='MI SOCIO')
		$id_banca =  9;
	elseif($banca=='OTROS')
		$id_banca = 10;
	
	$cagencia  		= 		$valores["codigoagencia"];
	//vemos a que oficina en guardian corresponde esta agencia
	$sqla = "SELECT id_oficina FROM oficinas WHERE codigo = '$cagencia'";
	$queryws = consulta($sqla);
	$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
	if($rowws['id_oficina']=='')
		$agencia = '0';
	else
		$agencia = $rowws['id_oficina'];
	
$WS_url=$rowws['ws_url1'];
}else{
	$sihay=0;
	$tipocartera = 		'';
	$importeprestamo = 	'';
	$monedaprestamo = 	'';
	$cuentadesembolso = '';
	$destinocredito = 	'';
	$numerocuotas = 	'';
	$tasa1 = 			'';
	$tasa2 = 			'';
	$cuotastasafija = 	'';
	$cuentadebito = 	'';
	$numerolinea = 		'';
	$importelinea = 	'';
	$monedalinea = 		'';
	$plazomeses = 		'';
	$plazodias = 		'';
	$segurodegravamen = '';
	$atributoobligatorio = 	'';
	$frecuenciapagok = 	'';
	$objetocredito = 	'';
	$linearotativa = 	'';
	$tipogarantia  = 	'';
	$banca  = 			'';
	$agencia  = 		'';
}

?>