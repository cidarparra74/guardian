<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM variable_campo WHERE idtexto = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$smarty->assign('idtexto',$resultado['idtexto']);
	$smarty->assign('tabla',$resultado['tabla']);
	$smarty->assign('campo',$resultado['campo']);
	$smarty->assign('adicional',$resultado['adicional']);
	$smarty->assign('id',$id);
	
	$smarty->display('adm/variablesec/modificar.html');
	die();
?>
