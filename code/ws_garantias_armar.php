<?php
unset($link);
require('../lib/conexionMNU.php');
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

// prueba $valor='1000211';

$oSoapClient = new nusoap_client($WS_url,true);
$parametros = array( 'numeroCaso'   => $valor);


$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("getDatosGarantias", $parametros);
if ($oSoapClient->fault) { 
	echo 'No se pudo completar la operación'.$oSoapClient->getError();
	die();
} else { 
	$sError = $oSoapClient->getError();
	if ($sError) { 
		 echo 'Error!:'.$sError;
		 die();
	}
}
/*
echo '<pre>';
$ver = $respuesta["getDatosGarantiasResult"];
print_r($ver);
echo '</pre>'; 
die();*/
	$listaGarantias = "'x'";
	$listaSeguros = "'x'";
if(isset($respuesta["getDatosGarantiasResult"]["diffgram"]["NewDataSet"]["garantia"])){
	$sihay=1; 
	$valores = $respuesta["getDatosGarantiasResult"]["diffgram"]["NewDataSet"]["garantia"];
	//listaGar ya podria estar declarada
	if(isset($valores["tipogarantia"])){
		//un solo garante
		$listaGarantias = "'".$valores["tipogarantia"]."'";
		if($valores["tiposeguro"]!='NINGUNO')
				$listaSeguros .= ",'".$valores["tipogarantia"]."'";
	}else{
		foreach($valores as $item){
			$listaGarantias .= ",'".$item["tipogarantia"]."'";
			if($item["tiposeguro"]!='NINGUNO')
				$listaSeguros .= ",'".$item["tipogarantia"]."'";
		}
	}
}else{
	$sihay=0;
}
/*
echo '<pre>';
print_r($listaGarantias);
echo '</pre>';
*/
?>