<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_documentos WHERE id_tipo_documento= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo= $resultado["tipo"];
	$descripcion= $resultado["descripcion"];
	
	//recuperando los existentes
	$sql = "SELECT tipo FROM tipos_documentos WHERE tipo!= '$tipo' ORDER BY tipo ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["tipo"];
		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/tipos_documentos/modificar.html');
	die();
?>
