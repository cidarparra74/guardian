<?php
// esto es para banco sol
//ya no se usa? ver ws_cuenta.php
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
	//o buscar en la db
	$nombres = 'Rivas Lia';
	$direccion 	= 'c.cleomedes';
		$telefonos	= '0';
		$ecivil		= 'S';
		$profesion 	= 'ing';
		$nacionalidad 	= '';
		$emision 	= '';
}else{

$WS_url=$rowws['ws_url1'];

$oSoapClient = new nusoap_client($WS_url, true);
//echo $documento;
$parametros = array( 'Pais' 		=> $Pais,
					'TipoDoc' 		=> $TipoDoc,
					'NroDoc' 		=> $ci_cliente); //aqui ya debe tener la emision, pero tambien acepta sin emision
					//para caso extranjeros fijarse correo del 23/01/2015 que resuelve con y sin emision
//$ci_cliente.$emision
$oSoapClient->loadWSDL();
$result = $oSoapClient->call("Cliente", $parametros);
if (!$oSoapClient->fault) { // Si
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
                echo 'Error!:'.$sError;
				echo '<br>';
				echo 'Revise al configuraci&oacute;n del Servicio WEB.';
				//die();
        }
}
//Comprobamos que el elemento "diffgram" es un array, de lo contrario es un conjunto vacío de registros
/*
<NewDataSet xmlns="">
<Datos diffgr:id="Datos1" diffgr:hasChanges="inserted" msdata:rowOrder="0">
<Nombres>NOMBRE1 NOMBRE2</Nombres>
<Apellidos>PATERNO MATERNO</Apellidos>
<Direccion>CALLE INNOMINADA NRO. S/N</Direccion>
<Telf1/>
<Telf2/>
<Celular/>
<Mail/>
<TipoPersona>F</TipoPersona>
<EstadoCivil>Soltero(a)</EstadoCivil>
  <LugarDoc>CB</LugarDoc> 
  <Profesion>Administrador de Negocio</Profesion> 
  <Ocupacion>Trabajador independiente</Ocupacion> 
  <Nacionalidad>BOLIVIA</Nacionalidad>
</Datos>
</NewDataSet>
 
*/
	if(is_array($result["ClienteResult"]["diffgram"])){
		$nombres 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Nombres'];
		$apellidos 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Apellidos'];
		$direccion 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Direccion'];
		$telf1 		= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Telf1'];
		$telf2 		= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Telf2'];
		$celular 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Celular'];
		$ecivil 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['EstadoCivil'];
		// 08/02/2013:
		$profesion	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Profesion'];
		$ocupacion	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Ocupacion'];
		$nacionalidad	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['Nacionalidad'];
		$emision	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['LugarDoc'];
		if($emision!='')
			$emi=$emision;
		//$tipopersona 	= $result['ClienteResult']['diffgram']['NewDataSet']['Datos']['TipoPersona'];
		$telefonos = $telf1 .' '. $telf1 .' '. $celular;
		$ecivil = substr($ecivil,0,1);
		$nombres = $apellidos .' '. $nombres;
		$direccion = str_replace("'","",$direccion);
	}else{
		$nombres 	= '';
		$direccion 	= '';
		$telefonos	= '';
		$ecivil		= '-';
		$profesion 	= '';
		$nacionalidad 	= '';
		$emision 	= '';
		
	}
}
?>