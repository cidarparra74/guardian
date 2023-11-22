<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM var_texto WHERE idtexto = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	
	$smarty->assign('id',$id);
	$smarty->assign('idtexto',$resultado["idtexto"]);
	$smarty->assign('contenido',$resultado["contenido"]);
	$smarty->assign('esglobal',$resultado["esglobal"]);
	$smarty->assign('descri',$resultado["descripcion"]);
	$smarty->assign('eslista',$resultado["eslista"]);
	$smarty->assign('lineas',$resultado["lineas"]);
	$smarty->assign('tipo',$resultado["tipo"]);
	
	$sql = "SELECT * FROM var_texto_valores WHERE idtexto = '$id' ";
	$query= consulta($sql);
	$valores= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($valores["valor"]!=''){
		$smarty->assign('contenido',$resultado["valor"]);
		$smarty->assign('contenido2',$resultado["adicional"]);
	}
	$smarty->display('sec/variables/modificar.html');
	die();
?>
