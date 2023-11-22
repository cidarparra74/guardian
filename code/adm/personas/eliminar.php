<?php


	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM personas WHERE id_persona = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$ci= $resultado["ci"];
	$nombres= $resultado["nombres"];
	$apellidos= $resultado["apellidos"];
	$telefonos= $resultado["telefonos"];
	$direccion= $resultado["direccion"];
	$tipo_rol= $resultado['tipo_rol'];
	$id_oficina= $resultado['id_oficina'];
	
	//oficinas
	$sql = "SELECT al.nombre as almacen, ofi.nombre FROM oficinas ofi LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen WHERE id_oficina = $id_oficina ";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$oficina = $row["almacen"].'-'.$row["nombre"];
	
	$smarty->assign('oficina',$oficina);
	
	$puede_eliminar="si";
	
	$smarty->assign('id',$id);
	$smarty->assign('ci',$ci);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('apellidos',$apellidos);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('tipo_rol',$tipo_rol);
	
	$smarty->assign('puede_eliminar',$puede_eliminar);
	
	$smarty->display('adm/personas/eliminar.html');
	die();
?>
