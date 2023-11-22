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

$oSoapClient = new nusoap_client($WS_url, true);
//ncas permane en$parametros incluso para los proximos WS
//$parametros = array( 'ncas' => $nrocaso);
//cambiamos para ya no usar getNumeroIdentificacion

$parametros = array( 'numeroCaso' => $nrocaso,
					'codigoAgenda' 	=> '0',
					'numeroCI' 	=> '',
					'nombre' 	=> '',
					'descripcionAgencia' 	=> '',
					'usuario' 	=> '');
					
$documento = '';

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
agenda diffgr:id="agenda1" msdata:rowOrder="0">
<codigoagenda>283364</codigoagenda> 
<numerocaso>1000537</numerocaso> 
<codigoagencia>16</codigoagencia> 
<descripcionagencia>AG.LOS POZOS(SCZ)</descripcionagencia> 
<usuario>MED</usuario> 
<numeroci>5836331SC</numeroci> 
<nombrecompleto>ZELADA CLAROS LUCIO</nombrecompleto> 
<nombre>LUCIO</nombre> 
<apellidopaterno>ZELADA</apellidopaterno> 
<apellidomaterno>CLAROS</apellidomaterno> 
<fechanacimiento>1981-09-10T00:00:00-04:00</fechanacimiento> 
<codigoestadocivil>1</codigoestadocivil> 
<codigotipopersona>1</codigotipopersona> 
<codigosexo>1</codigosexo> 
<telefonodomicilio>3334455</telefonodomicilio> 
<telefonocelular>77672465</telefonocelular> 
<codigocaedec>52204</codigocaedec> 
<codigorubro>8</codigorubro> 
<direcciondomicilio>BARRIO VILLA COCHABAMBA CALLE 1 S/N UV.96 MZ.1 LT18-19</direcciondomicilio> 
<profesion /> 
<estadocivil>SOLTERO(A)</estadocivil> 
<codigotipodocumento>1</codigotipodocumento> 
<tipodocumento>CARNET DE IDENTIDAD</tipodocumento> 
<numerodocumento>5836331</numerodocumento> 
<extensiondocumento>SC</extensiondocumento>

	[codigoagenda] => 277459
    [numerocaso] => 0
    [codigoagencia] => 12
    [descripcionagencia] => AG.LA RAMADA(SCZ)                       
    [usuario] => ZSO
    [numeroci] =>      7813866SC 
    [nombrecompleto] => QUISPE CANAVIRI ELIZABETH BLANCA                            
    [nombre] => ELIZABETH BLANCA                        
    [apellidopaterno] => QUISPE                        
    [apellidomaterno] => CANAVIRI                      
    [fechanacimiento] => 2089-07-20T00:00:00-04:00
    [codigoestadocivil] => 1
    [codigotipopersona] => 1
    [codigosexo] => 2
    [telefonocelular] => 78162994            
    [codigocaedec] => 52207
    [codigorubro] => 8
    [direcciondomicilio] => BARRIO JHONNY FERNANDEZ C/15                                
    [profesion] => SIN PROFESION                           
    [estadocivil] => SOLTERO(A)                                                  
    [!diffgr:id] => agenda1
    [!msdata:rowOrder] => 0


echo '<pre>';
//$ver = $result["getDatosClienteResult"]["diffgram"]["NewDataSet"]["agenda"];
print_r($datos);
echo '</pre>';

- <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">
- <NewDataSet xmlns="">
- <agenda diffgr:id="agenda1" msdata:rowOrder="0">
  <codigoagenda>312631</codigoagenda> 
  <numerocaso>1022432</numerocaso> 
  <codigoagencia>54</codigoagencia> 
  <descripcionagencia>AG.16 DE JULIO (LPZ)</descripcionagencia> 
  <numeroci xml:space="preserve"></numeroci> 
  <nombrecompleto>CERAMICA LA ROCA S.R.L.</nombrecompleto> 
  <nombre xml:space="preserve"></nombre> 
  <apellidopaterno xml:space="preserve"></apellidopaterno> 
  <apellidomaterno xml:space="preserve"></apellidomaterno> 
  <fechanacimiento>2010-10-20T00:00:00-04:00</fechanacimiento> 
  <codigoestadocivil>0</codigoestadocivil> 
  <codigotipopersona>8</codigotipopersona> 
  <codigosexo>0</codigosexo> 
  <telefonodomicilio xml:space="preserve"></telefonodomicilio> 
  <telefonocelular xml:space="preserve"></telefonocelular> 
  <codigocaedec>26930</codigocaedec> 
  <descripcioncaedec>Fabricacion de productos de arcilla y ceramica no refractarias para uso estructural</descripcioncaedec> 
  <codigorubro>5</codigorubro> 
  <direcciondomicilio>B. HUMACHUA AVENIDA NORUEGA 7 FRENTE A LA VIA FERREA VIACHA</direcciondomicilio> 
  <profesion /> 
  <estadocivil>ESTADO CIVIL</estadocivil> 
  <codigotipodocumento>9</codigotipodocumento> 
  <tipodocumento>IDENTIFICACION TRIBUTARIA</tipodocumento> 
  <numerodocumento /> 
  <extensiondocumento /> 
  <lugarnacimiento xml:space="preserve"></lugarnacimiento> 
  <codigociudaddomicilio>2</codigociudaddomicilio> 
  <descripcionciudaddomicilio>LA PAZ</descripcionciudaddomicilio> 
  <tipoactividad>0</tipoactividad> 
  <direccionoficina xml:space="preserve"></direccionoficina> 
  <telefonooficina>2801126</telefonooficina> 
  <zonaoficina xml:space="preserve"></zonaoficina> 
  <secuencias>0</secuencias> 
  <diasretrasomax6cuotas>0</diasretrasomax6cuotas> 
  </agenda>
  </NewDataSet>
  </diffgr:diffgram>
  </DataTable>


*/
if(isset($datos["NewDataSet"]["agenda"])){
	$datos2 = $datos["NewDataSet"]["agenda"];
	$nombres = trim($datos2['nombrecompleto']);
	$direccion = trim($datos2['direcciondomicilio']);
	$profesion = trim($datos2['profesion']);
	$ecivil = trim($datos2['estadocivil']);
	$documento = trim($datos2['numeroci']);
	$telefonos = trim($datos2['telefonodomicilio']).' '.trim($datos2['telefonocelular']); 
	if($profesion=='') $profesion = 'SIN ESPECIFICAR';
}else{
	$nombres = '';
	$direccion = '';
	$profesion = '';
	$ecivil = '';
}

if($documento!=''){

	//recuperamos otros datos para el IL
	//recuperamos monto del prestamo
	$parametros = array( 'ncas' => $nrocaso);

	//recuperamos descripcion
	$result = $oSoapClient->call("getDescripcionProducto", $parametros);
	if (!$oSoapClient->fault) { // no hay error
		$sError = $oSoapClient->getError();
		if (!$sError) {// Hay algun error ?
			$motivo = $result["getDescripcionProductoResult"];
		}
	}
}
?>