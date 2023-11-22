<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM bancas WHERE id_banca = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('codigo',$resultado["codigo"]);
	$smarty->assign('banca',$resultado["banca"]);
	
	$smarty->display('adm/bancas/modificar.html');
	die();
?>
