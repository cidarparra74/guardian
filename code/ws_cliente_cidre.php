<?php
require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones"; $queryws = consulta($sql); $rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	$nombres = '';
	return ;
}
$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url, true);

//lo siguiente ya esta definido en ws_nrocaso.php 
//$ci_cliente='8976014SC'; 
$parametros = array('numeroCI' => $ci_cliente); 
$oSoapClient->loadWSDL(); 
//recuperamos nombre 
//
$datos = ''; 
$result = $oSoapClient->call("getDatosCi", $parametros); 
if (!$oSoapClient->fault) { // no hay error
	$sError = $oSoapClient->getError();
	if (!$sError){
		$datos = $result["getDatosCiResult"]["diffgram"];
	}else
	echo $sError;
}
/*
<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"
xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
- <NewDataSet xmlns="">
- <Table diffgr:id="Table1" msdata:rowOrder="0">
  <ci>7909394CB</ci>
  <nombrecompleto>COSSIO CAMACHO JUAN</nombrecompleto>
  <direcciondomicilio>COMUNIDAD SAN ISIDRO - TIRAQUE</direcciondomicilio>
  <estadocivil>SOLTERO</estadocivil>
  <profesion>NO ESPECIFICADO</profesion>
  </Table>
  </NewDataSet>
  </diffgr:diffgram>
*/
/**/

if(isset($datos["NewDataSet"])){
	if(isset($datos["NewDataSet"]["Table"][0])){
	$datos2 = $datos["NewDataSet"]["Table"][0];
	$nombres = trim($datos2['nombrecompleto']);
	$direccion = trim($datos2['direcciondomicilio']);
	$profesion = trim($datos2['profesion']);
	$estadocivil = trim($datos2['estadocivil']);
	if($profesion=='') $profesion = 'SIN ESPECIFICAR';
	if(isset($datos2['telefonos'])) $telefonos = trim($datos2['telefonos']);
		else $telefonos = '';
	$documento = trim($datos2['ci']);
	$emision = substr(trim($datos2['ci']),-2);
	$personanatural = 1;
	}elseif(isset($datos["NewDataSet"]["Table"])){
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
		$personanatural = 1;
	}
} else {

	//buscamos por nit

	//
	$parametros = array('numeroNIT' => $ci_cliente);
	//
	$oSoapClient->loadWSDL();
	//recuperamos nombre
	$datos = '';
	$result = $oSoapClient->call("getDatosNit", $parametros);
	if (!$oSoapClient->fault) { // no hay error
		$sError = $oSoapClient->getError();
		if (!$sError){
			$datos = $result["getDatosNitResult"]["diffgram"];
		}else
		echo $sError;
	}
/*	echo 'nit';
echo '<pre>';
print_r($datos);
echo '</pre>';
echo count($datos);
//die();
*/
	/*
	- <diffgr:diffgram
xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"
xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
- <NewDataSet xmlns="">
- <Table diffgr:id="Table1" msdata:rowOrder="0">
  <ci>1022349023</ci>
  <nombrecompleto>BECLA S.R.L.</nombrecompleto>
  <direcciondomicilio>AV. GENERAL GALINDO N. 1379 COCHABAMBA, CERCADO, PRIMERA SECCION-COCHABAMBA</direcciondomicilio>
  </Table>
  </NewDataSet>
  </diffgr:diffgram>

Array
(
    [NewDataSet] => Array
        (
            [Table] => Array
                (
                    [0] => Array
                        (
                            [ci] => 1021711024
                            [nombrecompleto] => ASOCIACION DE PRODUCTORES DE LECHE INDEPENDIENTES (APLI)
                            [!diffgr:id] => Table1
                            [!msdata:rowOrder] => 0
                        )

                    [1] => Array
                        (
                            [ci] => 1021711024
                            [nombrecompleto] => ASOCIACION APLI
                            [direcciondomicilio] => C/ VILLA EDUARDO LOPEZ S/N ZON COCHABAMBA, CERCADO, PRIMERA SECCION-COCHABAMBA
                            [!diffgr:id] => Table2
                            [!msdata:rowOrder] => 1
                        )

                )

        )

)

*/
	if(isset($datos["NewDataSet"])){
		if(isset($datos["NewDataSet"]["Table"][0])){
			$datos2 = $datos["NewDataSet"]["Table"][0];
			$nombres = trim($datos2['nombrecompleto']);
			$direccion = trim($datos2['direcciondomicilio']);
			$documento = trim($datos2['ci']);
			$emision = '';
			$profesion = '';
			$estadocivil = '';
			$telefonos = '';
			$personanatural = 2;
		}elseif(isset($datos["NewDataSet"]["Table"])){
			$datos2 = $datos["NewDataSet"]["Table"];
			$nombres = trim($datos2['nombrecompleto']);
			$direccion = trim($datos2['direcciondomicilio']);
			$documento = trim($datos2['ci']);
			$emision = '';
			$profesion = '';
			$estadocivil = '';
			$telefonos = '';
			$personanatural = 2;
		}
	}else{
		$nombres = '';
		$direccion = '';
		$profesion = '';
		$estadocivil = '';
		$telefonos = '';
		$documento = '';
		$emision = '';
		$personanatural = 0;
	}
}

?>