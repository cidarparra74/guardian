<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM propietarios WHERE id_propietario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);

	$nombres= $resultado["nombres"];
	//$mis= $resultado["mis"];
	$ci= $resultado["ci"];
	$telefonos= $resultado["telefonos"];
	$direccion= $resultado["direccion"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_propietario) AS existe FROM carpetas WHERE id_propietario='$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$existe= $resultado["existe"];
	if($existe == 0){
		$sql= "SELECT MAX(id_propietario) AS existe FROM informes_legales WHERE id_propietario='$id' ";
		$query= consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$existe= $resultado["existe"];
		if($existe == 0){
			$puede_eliminar="si";
		}else{
			$puede_eliminar="no";
		}
	}else{
		$puede_eliminar="no";
	}
	
	
	$smarty->assign('id',$id);
	$smarty->assign('ci',$ci);
	$smarty->assign('nombres',$nombres);
	//$smarty->assign('mis',$mis);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('personas/eliminar.html');
	die();
?>
