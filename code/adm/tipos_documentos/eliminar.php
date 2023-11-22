<?php

	$id= $_REQUEST['id'];
	$sql = "SELECT * FROM tipos_documentos WHERE id_tipo_documento= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo= $resultado["tipo"];
	$descripcion= $resultado["descripcion"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_tipo_documento) AS existe FROM documentos_propietarios WHERE id_tipo_documento='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$existe= $resultado["existe"];
	if($existe == 0){
		$puede_eliminar="si";	
	}
	else{
		$puede_eliminar="no";
	}
	
	
	$smarty->assign('id',$id);
	$smarty->assign('tipo',$tipo);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	$smarty->display('adm/tipos_documentos/eliminar.html');
	die();
?>
