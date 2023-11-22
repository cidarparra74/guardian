<?php

	$id= $_REQUEST['id'];
	
	//nombre del tipo de bien
	$sql= "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre_bien=$resultado["tipo_bien"];
	
//recpueramos la lista total de documentos
//recuperando todos los documentos
$sql= "SELECT doc.id_documento, doc.documento, doc.requerido, tbd.id_tipo_bien
	FROM documentos DOC LEFT JOIN 
	(SELECT id_tipo_bien, id_documento FROM tipos_bien_documentos WHERE id_tipo_bien = $id) 
	tbd ON tbd.id_documento = doc.id_documento
	ORDER BY requerido DESC, documento ASC";
$query= consulta($sql);

$ids_documento= array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	if($row["id_tipo_bien"] == NULL){
		$tiene=0;
	}else{
		$tiene=1;
	}

	$ids_documento[$i]= array('id_documento' => $row["id_documento"],
								'documento' => $row["documento"], 
								'requerido' => $row["requerido"], 
								'tiene' => $tiene);
	$i++;
}

$cantidad_total=$i;
	
	$lugar_primer_grupo= 0;

	$smarty->assign('ids_documento',$ids_documento);
	$smarty->assign('nombre_bien',$nombre_bien);
	$smarty->assign('id',$id);
	
	$smarty->display('adm/tipos_bien/documentos.html');
	die();
?>
