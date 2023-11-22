<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM entidades WHERE id = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["entidad"];
	$smarty->assign('id',$id);
	$smarty->assign('entidad',$descripcion);
	
	$smarty->display('adm/entidades/modificar.html');
	die();
?>
