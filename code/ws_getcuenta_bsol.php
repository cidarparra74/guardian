<?php
// esto es para banco sol
// OBTENEMOS EL NRO DE CUENTA CLIENTE A PARTIR DEL NOPORTUNIDAD

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

if($rowws['ws_url1']=='php:'){
	// para datos deprueba
		$nrocaso 	= '111';
}else{

$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url, true);
//echo $documento;
$parametros = array('idOportunidad' => $noportunidad); 

$oSoapClient->loadWSDL();
$result = $oSoapClient->call("CtaOperInst", $parametros);
if (!$oSoapClient->fault) { // Si
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
				echo '<br>';
				echo 'Revise al configuraci&oacute;n del Servicio WEB.';
				//die();
        }
}
//Comprobamos que el elemento "diffgram" es un array, de lo contrario es un conjunto vacío de registros
/*

-<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"> 
-<NewDataSet xmlns=""> 
-<Datos diffgr:id="Datos1" diffgr:hasChanges="inserted" msdata:rowOrder="0">
 <Cuenta>0</Cuenta>
 <Operacion>0</Operacion>
 <Instancia>0</Instancia>
 <Estado>0</Estado>
 <Mensaje>No existe cuenta operacion para ese número de instancia</Mensaje>
 </Datos>
 </NewDataSet>
 </diffgr:diffgram>
 
*/
	if(is_array($result["CtaOperInstResult"]["diffgram"])){
		$nrocaso 	= $result['CtaOperInstResult']['diffgram']['NewDataSet']['Datos']['Cuenta'];
	}else{
		$nrocaso 	= '';		
	}
}
?>