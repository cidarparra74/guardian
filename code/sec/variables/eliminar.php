<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM localizacion WHERE localizacion = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('id',$id);
	$smarty->assign('loca',$resultado["localizacion"]);
	$smarty->assign('depto',$resultado["departamento"]);

	$smarty->display('sec/localiza/eliminar.html');
	die();
?>
