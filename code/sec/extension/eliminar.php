<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT descripcion FROM expedido WHERE codigo = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('id',$id);
	$smarty->assign('depto',$resultado["descripcion"]);

	$smarty->display('sec/extension/eliminar.html');
	die();
?>
