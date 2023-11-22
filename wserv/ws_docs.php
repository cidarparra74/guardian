<?php
require_once('../lib/lib/nusoap.php');

$oSoapClient = new nusoap_client('http://21.10.0.75/guardianpro/wserv/servicio.php?wsdl', true);
//$oSoapClient = new nusoap_client($WS_url, true);

$parametros = array( 'nrocaso' => '1022396');
//

$oSoapClient->loadWSDL();
//recuperamos nombre
$resulta = '    ';
$result = $oSoapClient->call("docsfaltantes", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$resulta = $result["docsfaltantesResult"];
	}else{
	 echo 'Error!:'.$sError;
	}
}
var_dump($result);
?>