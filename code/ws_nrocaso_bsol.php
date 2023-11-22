<?php

require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	echo 'No se pudo completar la operación, URL no definida.';
		echo '<br>';
		echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
}
$WS_url=$rowws['ws_url1'];
//
$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
if(!isset($nrocaso)||$nrocaso==''){
	echo "El número de instancia no existe! $nrocaso ";
	//return;
}
$parametros = array( 'Instancia' => $nrocaso);
$documento = '';
$resulta = array();
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("Guardian_Consulta", $parametros);
if ($oSoapClient->fault) { // Si
        echo 'No se pudo completar la operación '.$oSoapClient->getError();
		//echo '<br>';
		//echo 'Revise al configuraci&oacute;n del Servicio WEB.';
        die();
} else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
				//echo '<br>';
				//echo 'Revise al configuraci&oacute;n del Servicio WEB.';
				die();
        }else{
		$resulta = $result["Guardian_ConsultaResult"];
		}
}
/*
 <Datos diffgr:id="Datos1" msdata:rowOrder="0" diffgr:hasChanges="inserted">
  <Cuenta>186644</Cuenta> 
  <Nombre>PACHECO MOLLO ESTEBAN</Nombre> 
  <PaisDoc>1</PaisDoc> 
  <TipoDoc>1</TipoDoc> 
  <NumDoc>3064120OR</NumDoc> 
  <Estado>Operación Cancelada</Estado> 
  </Datos>

  con el número de instancia 5396494
Se tiene la siguiente respuesta:
 
Cuenta: 578391
Nombre: Quiroz Flores Aida
Paisdoc: 1
Tipodoc:1
Numdoc: 8096262-1M
Producto: sol individual
Destinocre: capital de operación act. Principal
Estado: renegociado.

*/
//echo "<pre>";
//print_r($resulta[diffgram][NewDataSet][Datos]);
//echo "*</pre>";
$datos = $resulta[diffgram][NewDataSet][Datos];
//echo $datos['NumDoc'];
if(isset($datos['NumDoc'])){
	$documento = $datos['NumDoc'];
	$producto = $datos['Producto'];
	$destinocre = $datos['DestinoCre'];
	$cuenta = $datos['Cuenta'];
	//para decontabilizar.php
	$estadoLit = $datos['Estado'];
	$nombreCli = $datos['Nombre'];
	$paisDoc = $datos['PaisDoc'];
	$tipoDoc = $datos['TipoDoc'];
	//para bsol tenemos la descripcion
		$motivo = $producto.' '.$destinocre;
		$smarty->assign('motivo',$motivo);
}
?>