<?php

require_once('../lib/lib/nusoap.php');

//la url ya esta en sesion
if(!isset($_SESSION['ws_url4'])){
	echo 'No se pudo completar la operación, URL no definida ';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB para el SEC.';
        die();
}
$WS_url=$_SESSION['ws_url4'];
if(!isset($glogin)) $glogin = $_SESSION['glogin'];

$oSoapClient = new nusoap_client($WS_url, true);
//$parametros = array( 'idFinal' => $idfinal, 'idUsuario' => $glogin);
//vemos tamaño de fuente
if(isset($tfuente)){
	$tamanio = $tfuente;
}else{
	$tamanio = '12';
}
$parametros = array( 'idFinal' => $idfinal, 'tfuente' => $tamanio);
$resulta=1;
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("GenerateDocument", $parametros);
if ($oSoapClient->fault) { // Si
        echo "No se pudo completar la operación $idfinal : ".$oSoapClient->getError();
		//die();
} else { // No
	$sError = $oSoapClient->getError();
	// Hay algun error ?
	if ($sError) { // Si
		echo 'Error!: '.$sError;
	}else{
		$resulta=0;
		//print_r($result);
	}
}

?>