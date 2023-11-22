<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM sociedades WHERE id_sociedad = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["sociedad"];
	$smarty->assign('id',$id);
	$smarty->assign('sociedad',$descripcion);
	
	$smarty->display('adm/sociedades/modificar.html');
	die();
?>
