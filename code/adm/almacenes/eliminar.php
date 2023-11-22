<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM almacen WHERE id_almacen= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre= $resultado["nombre"];

	
	//verificando que se pueda eliminar
	$sql= "SELECT COUNT(id_almacen) AS existe FROM oficinas WHERE id_almacen='$id' ";
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

	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	$smarty->display('adm/almacenes/eliminar.html');
	die();
?>
