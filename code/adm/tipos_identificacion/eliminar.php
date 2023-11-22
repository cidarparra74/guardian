<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_identificacion WHERE id_tipo= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$identificacion= $resultado["identificacion"];
	$descripcion= $resultado["descripcion"];
	
	$puede_eliminar="si";
	
	$smarty->assign('id',$id);
	$smarty->assign('identificacion',$identificacion);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/tipos_identificacion/eliminar.html');
	die();
?>
