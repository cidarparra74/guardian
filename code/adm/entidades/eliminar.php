<?php


	$id= $_REQUEST['id'];
	$sql = "SELECT * FROM entidades WHERE id = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["entidad"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(seg_cia) AS existe FROM documentos_propietarios WHERE seg_cia='$id' ";
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
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/entidades/eliminar.html');
	die();
?>
