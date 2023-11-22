<?php

	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM tipos_carpetas WHERE id_tipo_carpeta= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$tipo= $resultado["tipo"];
	$descripcion= $resultado["descripcion"];
	$cuenta_ingreso= $resultado["cuenta_ingreso"];
	$cuenta_devolucion= $resultado["cuenta_devolucion"];
	
	//verificando que se pueda eliminar
	$sql= "SELECT MAX(id_tipo_carpeta) AS existe FROM carpetas WHERE id_tipo_carpeta='$id' ";
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
	$smarty->assign('cuenta_ingreso',$cuenta_ingreso);
	$smarty->assign('cuenta_devolucion',$cuenta_devolucion);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/tipos_carpetas/eliminar.html');
	die();
?>
