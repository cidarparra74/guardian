<?php 

require_once('../lib/lib/nusoap.php');

// no lo tengo registrado el de Flujocredito pero a continuacion lo cambias a lo que corresponda:
$oSoapClient = new nusoap_client('http://localhost/wsbt/sarc.asmx?wsdl', true);


//aqui pones el nro de caso para testear
$parametros = array( 'ncaso' 		=> '1000039');

//esto solo para verificar q pasa bien el ncaso
echo '<pre>';
print_r($parametros );
echo '</pre>';


$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("hojaVariables", $parametros);
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
//esto es lo que me tendrias que mandar para ver como jala los datos
echo '<br>esto es lo que me tendrias que mandar para ver como jala los datos:<br>';
echo '<pre>';
print_r( $respuesta );
echo '</pre>';
?>