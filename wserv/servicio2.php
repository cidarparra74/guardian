<?php

require_once('../lib/conexionMNU.php');

//librería nusoap
require_once('../lib/lib/nusoap.php');

//funcion para obtener la cantidad de docs faltantes
function cantfaltantes ( $nrocaso ) {

  return 10;
}
 
//funcion para devolver los documentos faltantes
function docsfaltantes( $nrocaso ) {
 
	return "hola"; 
}


//instanciamos un nuevo servidor soap
$server = new soap_server;
 
//Namespace
 $ns = 'urn:'.$_SERVER['SCRIPT_URI']; 
 
//asignamos el nombre y namespace al servicio
$server->configureWSDL("Guardian Pro",$ns);


//registramos la segunda función
$server->register('docsfaltantes',
  array('nrocaso' => 'xsd:string' ),
  array('return' => 'xsd:string'),
  $ns, false,
  'rpc',
  'encode',
  'Cantidad de documentos faltantes') ;

//registramos la segunda función
$server->register('cantfaltantes',
  array('nrocaso' => 'xsd:string' ),
  array('return' => 'xsd:decimal'),
  $ns, false,
  'rpc',
  'literal',
  'Documentos faltantes') ;

  
if (isset($HTTP_RAW_POST_DATA)) {
  $input = $HTTP_RAW_POST_DATA;
}else{
  $input = implode("rn", file('php://input'));
}
 
$server->service($input);

//$server->wsdl->schemaTargetNamespace = $_SERVER['SCRIPT_URI']; 
//$server->service($HTTP_RAW_POST_DATA); 

?>