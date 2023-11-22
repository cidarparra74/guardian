<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM objetos WHERE id_objeto = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["objeto"];
	$smarty->assign('id',$id);
	$smarty->assign('objeto',$descripcion);
	
	$smarty->display('adm/objetos/modificar.html');
	die();
?>
