<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_bien WHERE id_tipo_bien= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo= $resultado["tipo_bien"];
	$descripcion= $resultado["descripcion"];
	
	$puede_eliminar="si";
	$smarty->assign('id',$id);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/tipos_bien/eliminar.html');
	die();
?>
