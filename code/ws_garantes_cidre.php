<?php
unset($link);
		require('../lib/conexionMNU.php');
require_once('../lib/lib/nusoap.php');

$sql = "SELECT TOP 1 ws_url1 FROM opciones";
$queryws = consulta($sql);
$rowws = $queryws->fetchRow(DB_FETCHMODE_ASSOC);
if($rowws['ws_url1']==''){
	return;
}
$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url,true);
$parametros = array( 'numeroCaso'   => $nrocaso);
//echo $nrocaso;

$oSoapClient->loadWSDL();
$respuesta = $oSoapClient->call("getDatosCodeudores", $parametros);
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
$ver = $respuesta["getDatosCodeudoresResult"]["diffgram"]["NewDataSet"];
print_r($ver);
echo '</pre>'; 
die();


- <NewDataSet xmlns="">

- <Table diffgr:id="Table1" msdata:rowOrder="0">
  <codigo>21668</codigo> 
  <ci>6817402LP</ci> 
  <nombrecompleto>ISIDRO QUISPE MAMANI</nombrecompleto> 
  <direcciondomicilio>COM. SALLACIRCA S/N CANTON COL</direcciondomicilio> 
  <estadocivil>CASADO</estadocivil> 
  <profesion>NO ESPECIFICADO</profesion> 
  <gbagenaci>BOLIVIANO</gbagenaci> 
  </Table>

- <Table diffgr:id="Table2" msdata:rowOrder="1">
  <codigo>21670</codigo> 
  <ci>2161445LP</ci> 
  <nombrecompleto>CASILDA MARCA QUISPE</nombrecompleto> 
  <direcciondomicilio>COM. SALLACIRCA S/N CANTON COL</direcciondomicilio> 
  <estadocivil>CASADO</estadocivil> 
  <profesion>NO ESPECIFICADO</profesion> 
  <gbagenaci>BOLIVIANA</gbagenaci> 
  </Table>

  </NewDataSet>

*/
$sihay=0;
if(isset($respuesta["getDatosCodeudoresResult"]["diffgram"]["NewDataSet"]["Table"])){
	$sihay=1; 
	$valores = $respuesta["getDatosCodeudoresResult"]["diffgram"]["NewDataSet"]["Table"];
	//listaGar ya podria estar declarada
	if(!isset($listaGar)) $listaGar = array();
	if(isset($valores["ci"])){
		//un solo codeudor
		$listaGar[] = $valores["ci"];
		//if($valores["tipo"]==3)
		//	$ngarantes = 1;
	}else{
		foreach($valores as $item){
			$listaGar[] = $item["ci"];
			//if($valores["tipo"]==3)
			//	$ngarantes += 1;
		}
	}
}
$ngarantes = 0;
$respuesta = $oSoapClient->call("getDatosGarantes", $parametros);
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
if(isset($respuesta["getDatosGarantesResult"]["diffgram"]["NewDataSet"]["Table"])){
	$sihay=1; 
	$valores = $respuesta["getDatosGarantesResult"]["diffgram"]["NewDataSet"]["Table"];
	//listaGar ya podria estar declarada
	if(!isset($listaGar)) $listaGar = array();
	if(isset($valores["ci"])){
		//un solo garante
		$listaGar[] = $valores["ci"];
		//if($valores["tipo"]==3)
			$ngarantes = 1;
	}else{
		foreach($valores as $item){
			$listaGar[] = $item["ci"];
			//if($valores["tipo"]==3)
				$ngarantes += 1;
		}
	}
}

/*
echo '<pre>';
print_r($listaGar);
echo '</pre>';
*/
?>