<?php

	$id= $_REQUEST['id'];
	
	//nombre del tipo de bien
	$sql= "SELECT tipo_bien FROM tipos_bien WHERE id_tipo_bien='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre_bien=$resultado["tipo_bien"];
	
//recpueramos la lista de documentos seleccionados para este bien
//recuperando todos los documentos q esten en  tipos_bien_documentos

$sql= "SELECT doc.id_documento, doc.documento, doc.requerido as manda, tbd.requerido, tbd.orden , tbd.imprimir
	FROM documentos doc 
	INNER JOIN tipos_bien_documentos tbd ON tbd.id_documento = doc.id_documento 
	WHERE tbd.id_tipo_bien = '$id'	 
	ORDER BY doc.requerido DESC, tbd.orden, doc.documento ";
$query= consulta($sql);

$ids_documento= array();
$i=0;
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){

	if($row["imprimir"] == '0'){
		$tiene=0;
	}else{
		$tiene=1;
	}

	$ids_documento[$i]= array('id_documento' => $row["id_documento"],
								'documento' => $row["documento"], 
								'requerido' => $row["requerido"],   
								'manda' => $row["manda"], 
								'orden' => $row["orden"], 
								'imprime' => $tiene);
	$i++;
}

$cantidad_total=$i;
	
	$lugar_primer_grupo= 0;

	$smarty->assign('ids_documento',$ids_documento);
	$smarty->assign('nombre_bien',$nombre_bien);
	$smarty->assign('id',$id);
	//$smarty->assign('cat',$cat);
	if($cat=='0')
	$smarty->display('adm/tipos_bien/documentos_imp.html');
	else
	$smarty->display('adm/tipos_bien/documentos_pro.html');
	die();
?>
