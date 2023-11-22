<?php
require_once('../lib/conexionMNU.php');

	$sql= "SELECT MAX(il.id_informe_legal) AS id FROM informes_legales il 
	LEFT JOIN tipos_bien tb ON tb.id_tipo_bien = il.id_tipo_bien
	WHERE tb.categoria = 1 AND nrocaso = '1022396' ";
	
	$query = consulta($sql);
	
	$resultado = '';
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$id = $row['id'];
	//vemos si se encontro el il con el nrocaso proporcionado
	if($id!=''){
		$sql= "SELECT do.documento FROM tipos_bien_documentos tb
		LEFT JOIN (informes_legales il 
		INNER JOIN documentos_informe di ON di.din_inf_id = il.id_informe_legal AND il.id_informe_legal = $id) 
		ON tb.id_tipo_bien = il.id_tipo_bien  AND tb.id_documento = di.din_doc_id 
		INNER JOIN documentos do ON do.id_documento = tb.id_documento 
		WHERE tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales WHERE id_informe_legal = $id) 
		AND di.din_id is null AND tb.requerido = 1";
		$query = consulta($sql);
		
		while($data = $query->fetchRow(DB_FETCHMODE_ASSOC)){
			
			$resultado .= $data['documento'].'|';
		}
	}else{
		$resultado= ''; 
	}
	
	echo $resultado; 
	
?>
