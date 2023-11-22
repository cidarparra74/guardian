<?php


	$id= $_REQUEST['id'];
	$sql = "SELECT * FROM sociedades WHERE id_sociedad = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["sociedad"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_informe_legal) AS existe 
	FROM informes_legales_pj WHERE tipo_sociedad = '$id'";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$existe= $resultado["existe"];
	if($existe == 0){
		$puede_eliminar="si";	
	}else{
		$puede_eliminar="no";
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('descripcion',$descripcion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/sociedades/eliminar.html');
	die();
?>
