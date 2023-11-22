<?php

require_once('../lib/conexionMNU.php');

//librería nusoap
require_once('../lib/lib/nusoap.php');

//funcion para obtener la cantidad de docs faltantes
function cantfaltantes ( $nrocaso ) {
	$sql= "SELECT MAX(il.id_informe_legal) AS id FROM informes_legales il 
	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE tb.categoria = 1 AND nrocaso = '$nrocaso'";
	
	$query = consulta($sql);
	$resultado = '';
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id = $row['id'];
	
	//vemos si se encontro el il con el nrocaso proporcionado
	if($id != ''){
		$sql= "SELECT COUNT(*) cant FROM tipos_bien_documentos tb
		LEFT JOIN (informes_legales il 
		INNER JOIN documentos_informe di ON di.din_inf_id = il.id_informe_legal AND il.id_informe_legal = '$id') 
		ON tb.id_tipo_bien = il.id_tipo_bien  AND tb.id_documento = di.din_doc_id 
		WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales WHERE id_informe_legal = '$id') 
		AND di.din_id is null AND tb.requerido = 1";
	}else{
		$resultado = 0; 
	}
	$query = consulta($sql);
	$data = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$resultado = $data['cant'];
  return $resultado;
}
 
//funcion para devolver los documentos faltantes
function docsfaltantes ( $nrocaso ) {

	$sql= "SELECT MAX(il.id_informe_legal) AS id FROM informes_legales il 
	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE tb.categoria = 1 AND nrocaso = '$nrocaso'";
	
	$query = consulta($sql);
	$resultado = array();
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id = $row['id'];
	//vemos si se encontro el il con el nrocaso proporcionado
	if($id!=''){
		$sql= "SELECT do.id_codumento as id, do.documento FROM tipos_bien_documentos tb
		LEFT JOIN (informes_legales il 
		INNER JOIN documentos_informe di ON di.din_inf_id = il.id_informe_legal AND il.id_informe_legal = $id) 
		ON tb.id_tipo_bien = il.id_tipo_bien  AND tb.id_documento = di.din_doc_id 
		INNER JOIN documentos do ON do.id_documento = tb.id_documento 
		WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales WHERE id_informe_legal = $id) 
		AND di.din_id is null AND tb.requerido = 1";
		$query = consulta($sql);
		
		while($data = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$resultado[] = $data;
			
		}
	}else{
		$resultado[]= ''; 
	}
	return array("Docs" => $resultado); 
	//return $resultado; 
}

//instanciamos un nuevo servidor soap
$server = new soap_server;
 
//Namespace
 $ns = 'urn:'.$_SERVER['SCRIPT_URI']; 
 
//asignamos el nombre y namespace al servicio
$server->configureWSDL("Guardian Pro",$ns);

$server->wsdl->addComplexType( 
'Docs', 
'complexType', 
'struct', 
'all', 
'', 
array( 
'id'=>array('name'=>'id','type'=>'xsd:int'),
 'documento'=>array('name'=>'documento','type'=>'xsd:string'))
);

$server->wsdl->addComplexType( 
'DocsArray', 
'complexType', 
'array', 
'', 
'SOAP-ENC:Array', 
array(), 
array( array(
'ref'=>'SOAP-ENC:arrayType',
'wsdl:arrayType'=>'tns:Docs[]') 
), 
'tns:Contact' ); 

 
$server->register(
'docsfaltantes', 
array(
	'id' => 'xsd:int', 
	'documento' => 'xsd:string'), // input parameters
 array(
 'return' => 'tns:DocsArray'), 
 'urn:'.$_SERVER['SCRIPT_URI'], // namespace 
 'urn:'.$_SERVER['SCRIPT_URI']."#docsfaltantes",  // soapaction 
 'rpc', // style 
 'encoded', // use 
 'Documentos Faltantes');
 

//registramos la segunda función
$server->register('cantfaltantes',
  array('nrocaso' => 'xsd:string' ),
  array('return' => 'xsd:decimal'),
  $ns, false,
  'rpc',
  'literal',
  'Cantidad de documentos faltantes') ;
 
  
if (isset($HTTP_RAW_POST_DATA)) {
  $input = $HTTP_RAW_POST_DATA;
}else{
  $input = implode("rn", file('php://input'));
}
 
$server->service($input);
?>