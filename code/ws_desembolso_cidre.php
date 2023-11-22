<?php

require_once('../lib/lib/nusoap.php');
$operacion = '';
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

$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
$parametros = array( 'numeroCre' => $nrocaso);

//$cuenta = '';
$resulta = array();
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("getPrestamo", $parametros);
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
		$resulta = $result["getPrestamoResult"];
		}
}

$datos = $resulta["diffgram"]["NewDataSet"]["Table"];
/*
echo "<pre>";
print_r($datos);
echo "</pre>";
*/
/*
<NewDataSet xmlns="">
- <Table diffgr:id="Table1" msdata:rowOrder="0">
   <monto>9000.00</monto>
   <moneda>2</moneda>
   <plazo>120.00</plazo>
   <unidadplazo>MES(ES)</unidadplazo>
   <cuotas>0</cuotas>
   <tipotasa>FIJA</tipotasa>
   <tasabase>7.500</tasabase>
   <tipotre />
   <pagokapital>30.00</pagokapital>
   <unidadpagokapital>DIARIO</unidadpagokapital>
   <pagointeres>30.00</pagointeres>
   <unidadpagointeres>DIA(S)</unidadpagointeres>
   <gracia>0.00</gracia>
   <unidadgracia>DIA(S)</unidadgracia>
   <segurodes>0.44400</segurodes>
   </Table>
   </NewDataSet>

*/
if(isset($datos['monto'])){

	$monto = $datos['monto'];
	$moneda = $datos['moneda'];
	$destinocre = $datos['destinocre'];
	//
	$uplazo = $datos['unidadplazo'];
	$plazo = $datos['plazo'];
	$cuotas = $datos['cuotas'];
	$tipotasa = $datos['tipotasa'];
	$tasabase = $datos['tasabase'];
	$tipotre = $datos['tipotre'];
	$pagokapital = $datos['pagokapital'];
	$upagokapital = $datos['unidadpagokapital'];
	$gracia = $datos['gracia'];
	$pagointeres = $datos['pagointeres'];
	$upagointeres = $datos['unidadpagointeres'];
	$ugracia = $datos['unidadgracia'];
	$operacion = 'ok';
}elseif(isset($datos['Descripcion'])){
	$Descripcion = $datos['Descripcion'];
	
}

?>