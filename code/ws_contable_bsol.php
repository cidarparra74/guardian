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

$xmlEntrada = '<registro asiento="ALTA_VALOR_GUARDIAN">
	<dato cta="1234" oper="1234" importe="1" moneda="0" idTipo="100" />
</registro>';

$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
$parametros = array('canal' => '80',
					'Usuario' => 'userGuardian',
					'Clave' => 'abcd1234',
					'idAsiento' => 'ALTA_VALOR_GUARDIAN',
					'xmlEntrada' => $xmlEntrada,);
$operacion = '';
$resulta = array();
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("Guardian_Desembolso", $parametros);
if ($oSoapClient->fault) { // Si
       // echo 'No se pudo completar la operación '.$oSoapClient->getError();
		//echo '<br>';
		//echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
} else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
               // echo 'Error!:'.$sError;
				//echo '<br>';
				//echo 'Revise al configuraci&oacute;n del Servicio WEB.';
				die();
        }else{
		$resulta = $result["Guardian_DesembolsoResult"];
		}
}

//echo "<pre>*";
//print_r($resulta[diffgram][NewDataSet][Datos]);
//echo "*</pre>";
$datos = $resulta[diffgram][NewDataSet][Datos];
//echo $datos['NumDoc'];
if(isset($datos['Operacion'])){
	$operacion = $datos['Operacion'];
	//echo $datos['Operacion'];
}
?>