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
if($rowws['ws_url1']=='php:'){
	//para pruebas victor
	$operacion = '10010';
	$cuenta = '100';
	$monto = '1000';
	$moneda = '1';
	$destinocre = 'PRUEBAS';
	$agencia = '1';
	$papel = '1';
}else{

	$WS_url=$rowws['ws_url1'];
	$oSoapClient = new nusoap_client($WS_url, true);
	//ncas permane en$parametros incluso para los proximos WS
	$parametros = array( 'Instancia' => $nrocaso);
	$operacion = '';
	$cuenta = '';
	$resulta = array();
	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("Guardian_Desembolso", $parametros);
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
			$resulta = $result["Guardian_DesembolsoResult"];
			}
	}

	$datos = $resulta['diffgram']['NewDataSet']['Datos'];
/*
echo "<pre>";
print_r($datos);
echo "</pre>";
*/
/*
<Cuenta>357760</Cuenta> 
  <Agencia>216</Agencia> 
  <Operacion>768464</Operacion> 
  <Moneda>0</Moneda> 
  <Modulo>SOL INDIVIDUAL</Modulo> 
  <Papel>0</Papel> 
  <FechaAlta>20110920</FechaAlta> 
  <Monto>35000</Monto> 
  <Producto>SOL INDIVIDUAL</Producto> 
  <DestinoCre>CAPITAL DE INVERSION ACT. PRINCIPAL</DestinoCre> 
  <FechaVenc>20140408</FechaVenc> 
  <Asesor>ROMAN GAMBOA NINOSCA</Asesor> 
*/
	if(isset($datos['Operacion'])){
		$operacion = $datos['Operacion'];
		$cuenta = $datos['Cuenta'];
		$monto = $datos['Monto'];
		$moneda = $datos['Moneda'];
		$destinocre = $datos['DestinoCre'];
		//
		$agencia = $datos['Agencia'];
		$papel = $datos['Papel'];
	}elseif(isset($datos['Descripcion'])){
		$Descripcion = $datos['Descripcion'];
		
	}
}
?>