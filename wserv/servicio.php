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
function docsfaltantes( $nrocaso ) {
	$sql= "SELECT MAX(il.id_informe_legal) AS id FROM informes_legales il 
	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE tb.categoria = 1 AND nrocaso = '$nrocaso'";
		$query = consulta($sql);
	$resultado = '';
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id = $row['id'];
	//vemos si se encontro el il con el nrocaso proporcionado
	if($id != ''){
		$sql= "SELECT do.documento FROM tipos_bien_documentos tb
		LEFT JOIN (informes_legales il 
		INNER JOIN documentos_informe di ON di.din_inf_id = il.id_informe_legal AND il.id_informe_legal = $id) 
		ON tb.id_tipo_bien = il.id_tipo_bien  AND tb.id_documento = di.din_doc_id 
		INNER JOIN documentos do ON do.id_documento = tb.id_documento 
		WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales WHERE id_informe_legal = $id) 
		AND di.din_id is null AND tb.requerido = 1";
		$query = consulta($sql);
		while($data = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$resultado .= $data["documento"].'|';
		}
		$sql= "SELECT doc.documento
		FROM documentos doc
		INNER JOIN documentos_informe din ON din.din_doc_id = doc.id_documento
		WHERE din.din_tip_doc = '0' AND din.din_inf_id = '$id'";
		$query = consulta($sql);
		while($data = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			$resultado .= $data["documento"].'|';
		}
	}else{
		$resultado = ''; 
	} 
	return $resultado; 
}


//funcion para aprobar la conformidad doc. el nro de caso
function aprobarconformidad( $IdRecepcionGuardian ) {
	$resultado = 0;
	$sql= "UPDATE informes_legales SET estado='cat' WHERE id_informe_legal='$IdRecepcionGuardian' ";	
	$resultado = ejecutarWS($sql);
	return $resultado; //devuelve 0=ok, 1=no se actualizo
}


//funcion para rechazar la conformidad doc. el nro de caso
function rechazarconformidad( $IdRecepcionGuardian ) {
	$resultado = 0;
	$sql= "UPDATE informes_legales SET estado='rec' WHERE id_informe_legal='$IdRecepcionGuardian' ";	
	$resultado = ejecutarWS($sql);
	return $resultado; //devuelve 0=ok, 1=no se actualizo
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
  'literal',
  'Cantidad de documentos faltantes') ;
  
//registramos la segunda función
$server->register('cantfaltantes',
  array('nrocaso' => 'xsd:string' ),
  array('return' => 'xsd:decimal'),
  $ns, false,
  'rpc',
  'literal',
  'Documentos faltantes') ;
  
//registramos 
$server->register('aprobarconformidad',
  array('IdRecepcionGuardian' => 'xsd:integer' ),
  array('return' => 'xsd:integer'),
  $ns, false,
  'rpc',
  'literal',
  'Aprobar la conformidad documento') ;
  
//registramos 
$server->register('rechazarconformidad',
  array('IdRecepcionGuardian' => 'xsd:integer' ),
  array('return' => 'xsd:integer'),
  $ns, false,
  'rpc',
  'literal',
  'Rechazar la conformidad documento') ;
  
if (isset($HTTP_RAW_POST_DATA)) {
  $input = $HTTP_RAW_POST_DATA;
}else{
  $input = implode("rn", file('php://input'));
}
 
$server->service($input);

?>