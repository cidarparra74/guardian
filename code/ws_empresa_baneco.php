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
$WS_url=$rowws['ws_url1']; 
//SI NO TENGO NRO DE CI PERO SI NRO DE CASO, PUEDO OBTENER EL CI

//$oSoapClient = new nusoap_client('http://21.10.0.12/wsbec/flujocredito.asmx?wsdl', true);
$oSoapClient = new nusoap_client($WS_url, true);

//lo siguiente ya esta definido en ws_nrocaso.php
//$ci_cliente='8976014SC';
$parametros = array( 'numeroCaso' => '0',
					'codigoAgenda' 	=> '0',
					'numeroCI' 	=> $documento,
					'nombre' 	=> '',
					'descripcionAgencia' 	=> '',
					'usuario' 	=> '');
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
NewDataSet xmlns="">
- <agenda diffgr:id="agenda1" msdata:rowOrder="0">
  <codigoagenda>10278</codigoagenda> 
  <numerocaso>0</numerocaso> 
  <codigoagencia>10</codigoagencia> 
  <descripcionagencia>SANTA CRUZ</descripcionagencia> 
  <usuario>WPL</usuario> 
  <numeroci>1028627025</numeroci> 
  <nombrecompleto>HIPERMAXI S.A.</nombrecompleto> 
  <fechanacimiento>1995-01-01T00:00:00-04:00</fechanacimiento> 
  <codigotipopersona>5</codigotipopersona> 
  <telefonodomicilio>342-5353</telefonodomicilio> 
  <telefonocelular>342-5353</telefonocelular> 
  <codigocaedec>52111</codigocaedec> 
  <descripcioncaedec>Venta al por menor en supermercados con surtido compuesto y predominio de productos alimenticios y bebidas.</descripcioncaedec> 
  <codigorubro>8</codigorubro> 
  <direcciondomicilio>AV.BANZER S/N Z.3ER.A.INTERNO</direcciondomicilio> 
  <profesion /> 
  <codigotipodocumento>9</codigotipodocumento> 
  <tipodocumento>IDENTIFICACION TRIBUTARIA</tipodocumento> 
  <numerodocumento>1028627025</numerodocumento> 
  <extensiondocumento /> 
  <lugarnacimiento>BOLIVIANA</lugarnacimiento> 
  <codigociudaddomicilio>0</codigociudaddomicilio> 
  <descripcionciudaddomicilio /> 
  <telefonooficina>342-2653</telefonooficina> 
  <zonaoficina /> 
  <secuencias>17</secuencias> 
  <diasretrasomax6cuotas>0</diasretrasomax6cuotas> 
  </agenda>
  </NewDataSet>
 

<codigotipopersona:  
1	PERSONA NATURAL
2	SUCESION INDIVISA
3	EMPRESA UNIPERSONAL
4	SOCIEDAD COLECTIVA
5	SOCIEDAD ANONIMA
6	SOCIEDAD EN COMANDITA SIMPLE
7	SOCIEDAD EN COMANDITA POR ACCIONES
8	SOCIEDAD DE RESPONSABILIDAD LIMITADA
9	ASOCIACION ACCIDENTAL O D/CUENTAS EN PAR
10	SOCIEDAD O ENTIDAD CONSTITUIDA EN EL EXT
11	COOPERATIVAS O MUTUALES
12	SOCIEDAD SOCIAL, CULTURAL Y DEPORTIVA
13	ASOC. O FUNDACIONES RELIGIOSAS Y/O EDUCA
14	EMPRESAS PUBLICAS
15	EMP.PUBLICAS DESCENTRALIZADAS MUNICIP.
16	SOCIEDAD DE ECONOMIA MIXTA
17	OTRAS NO ESPECIFICADAS

*/
if(isset($datos["NewDataSet"]["agenda"])){
	$datos2 = $datos["NewDataSet"]["agenda"];
	$nombres = trim($datos2['nombrecompleto']);
	$direccion = utf8_decode(trim($datos2['direcciondomicilio']));
	$telefonos = trim($datos2['telefonodomicilio']).' '.trim($datos2['telefonooficina']);
	$nacionalidad = trim($datos2['lugarnacimiento']);
}else{
	$nombres = '';
	$direccion = '';
	$telefonos = '';
	$nacionalidad = 'BOLIVIANA';
}

?>