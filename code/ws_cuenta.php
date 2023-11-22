<?php

	// $Pais 		= '1';
	// $TipoDoc 		= '1';
	// $documento		= '123456CB';
// return

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
	$documento = '3204124'; 
	$emision = 'SC'; 
	$TipoDoc = '1'; 
	$nombres = 'avendaño';
	$Pais 		= '1';
	$direccion 	= 'CALLE';
	$telefonos	= '';
	$ecivil		= 'S';
	$profesion 	= 'PROFESION';
		$nacionalidad	=  'BOL';
}else{
	$WS_url=$rowws['ws_url1'];

	$oSoapClient = new nusoap_client($WS_url, true);

	$parametros = array( 'Cuenta' => $cuenta);

	$oSoapClient->loadWSDL();
	$result = $oSoapClient->call("CuentaCartera", $parametros);
	if ($oSoapClient->fault) { // Si
			echo 'No se pudo completar la operación '.$oSoapClient->getError();
			echo '<br>';
			echo 'Revise al configuraci&oacute;n del Servicio WEB.';
			die();
	} else { // No
			$sError = $oSoapClient->getError();
			// Hay algun error ?
			if ($sError) { // Si
					echo 'Error!:'.$sError;
					echo '<br>';
					echo 'Revise al configuraci&oacute;n del Servicio WEB.';
					die();
			}
	}
	/*
	
<?xml version="1.0" encoding="UTF-8"?>
<DataSet xmlns="http://tempuri.org/"><xs:schema xmlns="" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:xs="http://www.w3.org/2001/XMLSchema" id="NewDataSet"><xs:element msdata:UseCurrentLocale="true" msdata:IsDataSet="true" name="NewDataSet"><xs:complexType><xs:choice maxOccurs="unbounded" minOccurs="0"><xs:element name="Datos"><xs:complexType><xs:sequence><xs:element name="Nombres" minOccurs="0" type="xs:string"/><xs:element name="Apellidos" minOccurs="0" type="xs:string"/><xs:element name="Direccion" minOccurs="0" type="xs:string"/><xs:element name="Telf1" minOccurs="0" type="xs:string"/><xs:element name="Telf2" minOccurs="0" type="xs:string"/><xs:element name="Celular" minOccurs="0" type="xs:string"/><xs:element name="Mail" minOccurs="0" type="xs:string"/><xs:element name="TipoPersona" minOccurs="0" type="xs:string"/><xs:element name="EstadoCivil" minOccurs="0" type="xs:string"/><xs:element name="Agencia" minOccurs="0" type="xs:string"/><xs:element name="Asesor" minOccurs="0" type="xs:string"/><xs:element name="Pais" minOccurs="0" type="xs:string"/><xs:element name="Tdoc" minOccurs="0" type="xs:string"/><xs:element name="Documento" minOccurs="0" type="xs:string"/><xs:element name="LugarDoc" minOccurs="0" type="xs:string"/></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"><NewDataSet xmlns="">
<Datos diffgr:id="Datos1" diffgr:hasChanges="inserted" msdata:rowOrder="0">
<Nombres>ALEXANDRA</Nombres>
<Apellidos>RIVERO ESPEJO</Apellidos>
<Direccion>Avenida CIRCUNVALACION BEIJING Nro.S/N</Direccion>
<Telf1/>
<Telf2/>
<Celular/>
<Mail/>
<TipoPersona>F</TipoPersona>
<EstadoCivil>Soltero(a)</EstadoCivil>
<Agencia/>
<Asesor/>
<Tdoc>1</Tdoc>
<Documento>4505447</Documento>
<LugarDoc>CB</LugarDoc>
</Datos>
</NewDataSet></diffgr:diffgram></DataSet>

doc nal complemento:
<Tdoc>1</Tdoc>
<Documento>1234567-1A</Documento>   
<LugarDoc>LP</LugarDoc>

Documento extranjero: 

<Tdoc>3</Tdoc>
<Documento>E-1234567</Documento>   
<LugarDoc>PE</LugarDoc>

	*/
	//Comprobamos que el elemento "diffgram" es un array, de lo contrario es un conjunto vacío de registros
	if(is_array($result["CuentaCarteraResult"]["diffgram"])){
		$Pais 		= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Pais'];
		$TipoDoc 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Tdoc'];
		$documento 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Documento'];
		$emision	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['LugarDoc'];
		$nombres 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Nombres'];
		$apellidos 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Apellidos'];
		$direccion 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Direccion'];
		$telf1 		= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Telf1'];
		$telf2 		= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Telf2'];
		$celular 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Celular'];
		$ecivil 	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['EstadoCivil'];
		// 08/02/2013:
		$profesion	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Profesion'];
		$ocupacion	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Ocupacion'];
		$nacionalidad = $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['Nacionalidad'];
		$emision	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['LugarDoc'];
		
		$personanatural	= $result['CuentaCarteraResult']['diffgram']['NewDataSet']['Datos']['TipoPersona'];
		if($emision!='')
			$emi=$emision;
		$telefonos = $telf1 .' '. $telf1 .' '. $celular;
		$ecivil = substr($ecivil,0,1);
		if($apellidos!='') //no es pers juridica
		$nombres = $apellidos .' '. $nombres;
		 
		
}else{
		$Pais 		= '';
		$TipoDoc 		= '';
		$documento		= '';
		$emision	= '';
		$nombres 	= '';
		$direccion 	= '';
		$telefonos	= '';
		$ecivil		= '-';
		$profesion 	= '';
		$nacionalidad	=  '';
	}

}
?>