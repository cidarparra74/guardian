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

$oSoapClient = new nusoap_client($WS_url,true);
$parametros = array( 'numeroCaso'   => $nrocaso);
//$nrocaso

$oSoapClient->loadWSDL();
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

/*
<garantes diffgr:id="garantes1" msdata:rowOrder="0">
  <tipo>3</tipo> 
  <codigoagenda>280719</codigoagenda> 
  <documentoidentidad>3933648SC</documentoidentidad> 
</garantes>






<?xml version="1.0" encoding="utf-8" ?> 
- <DataTable xmlns="http://baneco.com.bo/">
- <xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata">
- <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:MainDataTable="garantes" msdata:UseCurrentLocale="true">
- <xs:complexType>
- <xs:choice minOccurs="0" maxOccurs="unbounded">
- <xs:element name="garantes">
- <xs:complexType>
- <xs:sequence>
  <xs:element name="tipo" type="xs:int" minOccurs="0" /> 
  <xs:element name="codigoagenda" type="xs:int" minOccurs="0" /> 
  <xs:element name="documentoidentidad" type="xs:string" minOccurs="0" /> 
  </xs:sequence>
  </xs:complexType>
  </xs:element>
  </xs:choice>
  </xs:complexType>
  </xs:element>
  </xs:schema>
- <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
- <NewDataSet xmlns="">
- <garantes diffgr:id="garantes1" msdata:rowOrder="0">
  <tipo>3</tipo> 
  <codigoagenda>339853</codigoagenda> 
  <documentoidentidad>4440154CB</documentoidentidad> 
  </garantes>
- <garantes diffgr:id="garantes2" msdata:rowOrder="1">
  <tipo>3</tipo> 
  <codigoagenda>339850</codigoagenda> 
  <documentoidentidad>3567293CB</documentoidentidad> 
  </garantes>
  </NewDataSet>
  </diffgr:diffgram>
  </DataTable>

*/
$ngarantes = 0;
if(isset($respuesta["getDatosGarantesResult"]["diffgram"]["NewDataSet"]["garantes"])){
	$sihay=1; 
	$valores = $respuesta["getDatosGarantesResult"]["diffgram"]["NewDataSet"]["garantes"];
	//listaGar ya podria estar declarada
	if(!isset($listaGar)) $listaGar = array();
	if(isset($valores["documentoidentidad"])){
		//un solo garante
		$listaGar[] = $valores["documentoidentidad"];
		if($valores["tipo"]==3)
			$ngarantes = 1;
	}else{
		foreach($valores as $item){
			$listaGar[] = $item["documentoidentidad"];
			if($valores["tipo"]==3)
				$ngarantes += 1;
		}
	}
}else{
	$sihay=0;
}
/*
echo '<pre>';
print_r($listaGar);
echo '</pre>';
*/
?>