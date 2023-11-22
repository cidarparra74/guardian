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
					'numeroCI' 	=> $ci_cliente,
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

- <NewDataSet xmlns="">
- <agenda diffgr:id="agenda1" msdata:rowOrder="0">
  <codigoagenda>295838</codigoagenda>
  <numerocaso>0</numerocaso> 
  <codigoagencia>31</codigoagencia> 
  <descripcionagencia>AG.LA CANCHA(CBBA)</descripcionagencia> 
  <usuario>GHP</usuario> 
  <numeroci>4526136CB</numeroci> 
  <nombrecompleto>CASTRO HIDALGO MONICA ANDREA</nombrecompleto> 
  <nombre>MONICA ANDREA</nombre> 
  <apellidopaterno>CASTRO</apellidopaterno> 
  <apellidomaterno>HIDALGO</apellidomaterno> 
  <fechanacimiento>1980-05-05T00:00:00-04:00</fechanacimiento> 
  <codigoestadocivil>1</codigoestadocivil> 
  <codigotipopersona>1</codigotipopersona> 
  <codigosexo>2</codigosexo> 
  <telefonocelular>76929951</telefonocelular> 
  <codigocaedec>52396</codigocaedec> 
  <descripcioncaedec>Venta al por menor de articulos artesanales</descripcioncaedec> 
  <codigorubro>8</codigorubro> 
  <direcciondomicilio>ZONA CERRO VERDE AV. LOS ANDES NRO 70</direcciondomicilio> 
  <profesion /> 
  <estadocivil>SOLTERO(A)</estadocivil> 
  <codigotipodocumento>1</codigotipodocumento> 
  <tipodocumento>CARNET DE IDENTIDAD</tipodocumento> 
  <numerodocumento>4526136</numerodocumento> 
  <extensiondocumento>CB</extensiondocumento> 
  <lugarnacimiento>BOLIVIANA</lugarnacimiento> 
  <codigociudaddomicilio>3</codigociudaddomicilio> 
  <descripcionciudaddomicilio>COCHABAMBA</descripcionciudaddomicilio> 
  <direccionoficina>ZONA CERRO VERDE AV. LOS ANDES NRO 70</direccionoficina> 
  <telefonooficina /> 
  <zonaoficina>CBBA</zonaoficina> 
  <secuencias>2</secuencias> 
  <diasretrasomax6cuotas>7</diasretrasomax6cuotas> 

  </agenda>
 

echo '<pre>';
//$ver = $result["getDatosClienteResult"]["diffgram"]["NewDataSet"]["agenda"];
print_r($datos);
echo '</pre>';
*/
if(isset($datos["NewDataSet"]["agenda"])){
	$datos2 = $datos["NewDataSet"]["agenda"];
	$nombre = trim($datos2['nombrecompleto']);
	$direccion = utf8_decode(trim($datos2['direcciondomicilio']));
	$profesion = trim($datos2['profesion']);
	$nacionalidad = trim($datos2['lugarnacimiento']);
	$estadocivil = trim($datos2['estadocivil']);
	if($profesion=='') $profesion = 'SIN ESPECIFICAR';
	$emi = substr(trim($datos2['numeroci']),-2,2);
	$emision = trim($datos2['extensiondocumento']);
}else{
	$nombre = '';
	$direccion = '';
	$profesion = '';
	$estadocivil = '';
	$nacionalidad = 'BOLIVIANA';
	$emision = '';
}

?>