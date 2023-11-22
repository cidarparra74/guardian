<?php
 
require_once('../lib/lib/nusoap.php');
 
$oSoapClient = new nusoap_client('http://21.10.40.9/wsBec/FlujoCredito.asmx?wsdl',true);
$parametros = array( 'numeroCaso'   => '1');
 

$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("getHojaVariablesGuardian", $parametros);
if ($oSoapClient->fault) { // Si
         echo 'No se pudo completar la operación'.$oSoapClient->getError();
         die();
} else { // No
         $sError = $oSoapClient->getError();
         // Hay algun error ?
         if ($sError) { // Si
                 echo 'Error!:'.$sError;
         }
}
echo '<br>esto es lo que me tendrias que mandar para ver como jala los datos:<br>';
if(is_array($respuesta["getHojaVariablesGuardianResult"]["diffgram"])){
 echo '<pre>';
 print_r( $respuesta["getHojaVariablesGuardianResult"]["diffgram"]["NewDataSet"]["hojaVariables"] );
 echo '</pre>';
}else{
 echo 'no sale';
}
?>