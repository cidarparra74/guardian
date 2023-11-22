<?php


require_once('./lib/lib/nusoap.php');


function getsvrtime ( $zona ) {
	
	$resultado = time("H:i:s");
    return $resultado;
}
 

function getsvrdate ( $zona ) {
	
    date_default_timezone_set('America/La_Paz');
	
	$resultado = date("d/m/Y H:i:s");
    return $resultado;
}
 
 
function getmonaversion( $digita ) {

	$resultado = '2.9.3';
 
	return $resultado; 
}



//instanciamos un nuevo servidor soap
$server = new soap_server;

//Namespace
 $ns = 'urn:'.$_SERVER['SCRIPT_URI']; 
 
//asignamos el nombre y namespace al servicio
$server->configureWSDL("Alg Utils",$ns);

$server->register('getmonaversion',
  array('digita' => 'xsd:string' ),
  array('return' => 'xsd:string'),
  $ns, false,
  'rpc',
  'literal',
  'Version actual de Monalisa ') ;
  
$server->register('getsvrtime',
  array('zona' => 'xsd:string' ),
  array('return' => 'xsd:string'),
  $ns, false,
  'rpc',
  'literal',
  'Hora del servidor') ;
  
$server->register('getsvrdate',
  array('zona' => 'xsd:string' ),
  array('return' => 'xsd:string'),
  $ns, false,
  'rpc',
  'literal',
  'Fecha del servidor') ;
  

  
if (isset($HTTP_RAW_POST_DATA)) {
  $input = $HTTP_RAW_POST_DATA;
}else{
  $input = implode("rn", file('php://input'));
}
 
$server->service($input);

?>