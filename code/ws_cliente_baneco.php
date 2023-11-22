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
//SI NO TENGO NRO DE CI PERO SI NRO DE CASO, PUEDO OBTENER EL CI

//$oSoapClient = new nusoap_client('http://21.10.0.12/wsbec/flujocredito.asmx?wsdl', true);
$oSoapClient = new nusoap_client($WS_url, true);

//lo siguiente ya esta definido en ws_nrocaso.php
//$ci_cliente='8976014SC';
$parametros = array( 'ndid' => $ci_cliente,
					'cage' 	=> '0');
//
$oSoapClient->loadWSDL();
//recuperamos nombre
$nombres = '';
$result = $oSoapClient->call("getNombreCliente", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$nombres = $result["getNombreClienteResult"];
	}
	//echo $sError;
}
//else echo 'falla';

//recuperamos direccion
$direccion = '';
$result = $oSoapClient->call("getDireccionTrabajoCliente", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError) {// Hay algun error ?
		$direccion = $result["getDireccionTrabajoClienteResult"];
	}
}
//recuperamos estado civil
$ecivil = ' ';
$result = $oSoapClient->call("getEstadoCivilCliente", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError) {// Hay algun error ?
		$ecivil = $result["getEstadoCivilClienteResult"];
	}
}
$ecivil = substr($ecivil,0,1) ;

//recuperamos profesion
$parametros = array( 'ndid' => $ci_cliente);
$profesion = ' ';
$result = $oSoapClient->call("getProfesionCliente", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){// Hay algun error ?
		$profesion = $result["getProfesionClienteResult"];
	}
}
/*
echo $ci_cliente; echo '<br>';
echo $nombres; echo '<br>';
echo $direccion; echo '<br>';
echo $ecivil; echo '<br>';
echo $profesion; echo '<br>';

*/
//recuperamos telefono
$telefonos=''; //no hay ws

?>