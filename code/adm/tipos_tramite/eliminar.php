<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_tramite WHERE id_tipo_tramite= '$id' ";
	$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);

	$descripcion= $resultado["descripcion"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT count(id_tipo_tramite) AS existe FROM tramites WHERE id_tipo_tramite='$id' ";
	$result= $link->query($sql);
	$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
	$existe= $resultado["existe"];
	if($existe == 0){
		$puede_eliminar="si";	
	}
	else{
		$puede_eliminar="no";
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('descripcion',$descripcion);
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('administrador/tipos_tramite/eliminar.html');
	die();
?>