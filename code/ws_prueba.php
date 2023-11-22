<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url4 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida ';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
}
$WS_url=$rowws['ws_url4'];
//echo $WS_url;
$oSoapClient = new nusoap_client($WS_url, true);
$parametros = array( 'idFinal' => $idfinal,
'idUsuario' => $login);
echo "<pre>";
print_r($parametros);
echo "</pre>";
$resulta=1;
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("GenerateDocument", $parametros);
if ($oSoapClient->fault) { // Si
        echo 'No se pudo completar la operación '.$oSoapClient->getError();
		die();
} else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
				die();
        }else{
			$resulta=0;
		echo "ok";
		}
}

?>