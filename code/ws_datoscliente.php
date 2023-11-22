<?php
require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	$nombres = '';
	return ;
}
$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url, true);

//lo siguiente ya esta definido en ws_nrocaso.php
//$ci_cliente='8976014SC';
$parametros = array('numeroCre' => $nrocaso);
//
$oSoapClient->loadWSDL();
//recuperamos nombre
$datos = '';
$result = $oSoapClient->call("getDatosCliente", $parametros);
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$datos = $result["getDatosClienteResult"]["diffgram"];
	}else
	echo $sError;
}
/*
<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
- <NewDataSet xmlns="">
- <Table diffgr:id="Table1" msdata:rowOrder="0">
  <codigo>14318</codigo> 
  <ci>7909394CB</ci> 
  <nombrecompleto>COSSIO CAMACHO JUAN</nombrecompleto> 
  <direcciondomicilio>COMUNIDAD SAN ISIDRO - TIRAQUE</direcciondomicilio> 
  <estadocivil>SOLTERO</estadocivil> 
  <profesion>NO ESPECIFICADO</profesion> 
  <nacionalidad>BOLIVIA</nacionalidad> 
  </Table>
  </NewDataSet>
  </diffgr:diffgram>

echo '<pre>';
print_r($datos);
echo '</pre>';
die();
*/
if(isset($datos["NewDataSet"]["Table"])){
	$datos2 = $datos["NewDataSet"]["Table"];
	$nombres = trim($datos2['nombrecompleto']);
	$direccion = trim($datos2['direcciondomicilio']);
	$profesion = trim($datos2['profesion']);
	$estadocivil = trim($datos2['estadocivil']);
	if($profesion=='') $profesion = 'SIN ESPECIFICAR';
	if(isset($datos2['telefonos'])) $telefonos = trim($datos2['telefonos']);
		else $telefonos = '';
	$documento = trim($datos2['ci']);
	$emision = substr(trim($datos2['ci']),-2);
	
}else{
	$nombres = '';
	$direccion = '';
	$profesion = '';
	$estadocivil = '';
	$telefonos = '';
	$documento = '';
	$emision = '';
	
}

?>