<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_tramite WHERE id_tipo_tramite= '$id' ";
	$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["descripcion"];
	
	//recuperando los existentes
	$sql = "SELECT descripcion FROM tipos_tramite WHERE id_tipo_tramite!= '$id' ORDER BY descripcion ";
	$result= $link->query($sql);
	$existentes="";
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["descripcion"].";";

		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('administrador/tipos_tramite/modificar.html');
	die();
?>