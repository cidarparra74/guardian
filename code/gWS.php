<?php 

require_once('../lib/lib/nusoap.php');

//$oSoapClient = new nusoap_client('http://localhost/WSBT/sarc.asmx?wsdl&op=Cliente', true);
$oSoapClient = new nusoap_client('http://localhost/wsbt/sarc.asmx?wsdl', true);

$parametros = array( 'Pais' 		=> 1,
					'TipoDoc' 		=> 1,
					'NroDoc' 		=> '77777CB');

echo '<pre>';
print_r($parametros );
echo '</pre>';

$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("Cliente", $parametros);
if ($oSoapClient->fault) { // Si
        echo 'No se pudo completar la operación '.$oSoapClient->getError();
        die();
} else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
        }
}
echo '<br>';
echo '<pre>';
print_r( $respuesta );
echo '</pre>';

//--------para la cueta

$parametros = array( 'Cuenta' => '469712');

echo '<pre>';
print_r($parametros );
echo '</pre>';

$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("CuentaCartera", $parametros);
if ($oSoapClient->fault) { // Si
        echo 'No se pudo completar la operación '.$oSoapClient->getError();
        die();
} else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
        }
}
echo '<br>';
echo '<pre>';
print_r( $respuesta );
echo '</pre>';
?>