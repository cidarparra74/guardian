<?php
require_once('../lib/lib/nusoap.php');

$sqll = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sqll);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida ';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
}
$WS_url=$rowws['ws_url1']; 
//SI NO TENGO NRO DE CI PERO SI NRO DE CASO, PUEDO OBTENER EL CI
//
$oSoapClient = new nusoap_client($WS_url, true);
//
//lo siguiente ya esta definido en _autentifica.php && loginbec.php
//
$parametros = array( 'Usuario' => $login);
//
$oSoapClient->loadWSDL();
//recuperamos nombre
$cod_banca = '0';
$result = $oSoapClient->call("BuscarBanca", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$cod_banca = $result["BuscarBancaResult"];
	}else
		echo $sError;
}

if($cod_banca > 0){
// verificamos que exista la banca
	$sqll = "SELECT id_banca FROM bancas WHERE codigo = '$cod_banca' ";
	$queryws = consulta($sqll);
	$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
	if($rowws['id_banca'] != ' '){
		$id_banca = $rowws['id_banca'];
	}else{
		//aqui se podria cargar la banca desde un web service
	}
}

?>