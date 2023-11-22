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

$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
$parametros = array( 'ncas' => $nrocaso);
$documento = '';
/*
<?xml version="1.0" encoding="utf-8" ?> 
  <string xmlns="http://baneco.com.bo/">5273089CB</string> 

*/
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("getNumeroIdentificacion", $parametros);
if (!$oSoapClient->fault) { // Si
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if (!$sError) { // Si
			$documento = $result["getNumeroIdentificacionResult"];
		}
}

?>