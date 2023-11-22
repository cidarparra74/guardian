<?php


	$id= $_REQUEST['id'];
	$sql = "SELECT * FROM bancas WHERE id_banca = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('codigo',$resultado["codigo"]);
	$smarty->assign('banca',$resultado["banca"]);
	
	//verificando que se pueda eliminar
	$sql= "SELECT COUNT(*) AS existe FROM contratos_fijos WHERE id_banca='$id' ";
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
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/bancas/eliminar.html');
	die();
?>
