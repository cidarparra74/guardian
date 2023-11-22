<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM oficinas WHERE id_oficina= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre= $resultado["nombre"];
	$direccion= $resultado["direccion"];
	$telefonos= $resultado["telefonos"];
	$ciudad= $resultado["ciudad"];
	$pais= $resultado["pais"];
	$codigo= $resultado["codigo"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_oficina) AS existe FROM carpetas WHERE id_oficina='$id' ";
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
	$smarty->assign('nombre',$nombre);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('ciudad',$ciudad);
	$smarty->assign('pais',$pais);
	$smarty->assign('codigo',$codigo);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	$smarty->display('adm/oficinas/eliminar.html');
	die();
?>
