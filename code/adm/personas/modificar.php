<?php

	$id= $_REQUEST['id'];
	
	//oficinas
	$sql = "SELECT al.nombre as almacen, ofi.* FROM oficinas ofi LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen ORDER BY al.nombre, ofi.nombre ";
	$query= consulta($sql);
	$oficinas = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$oficinas[] = array('id_oficina' => $row["id_oficina"],
							'nombre' => $row["almacen"].'-'.$row["nombre"]);
	}
	
	$smarty->assign('oficinas',$oficinas);
	
	//almacenes
	$sql = "SELECT nombre as almacen, id_almacen FROM almacen ORDER BY nombre";
	$query= consulta($sql);
	$almacenes = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacenes[] = array('id_almacen' => $row["id_almacen"],
							'nombre' => $row["almacen"]);
	}
	
	$smarty->assign('almacenes',$almacenes);
	
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
	$correoe= $resultado['correoe'];
	
	//recuperando los existentes
	$sql = "SELECT ci FROM personas WHERE ci != '$ci' ORDER BY ci ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["ci"];

		$i++;
	}
	
	
	$smarty->assign('id',$id);
	$smarty->assign('ci',$ci);
	$smarty->assign('nombres',$nombres);
	$smarty->assign('apellidos',$apellidos);
	$smarty->assign('telefonos',$telefonos);
	$smarty->assign('direccion',$direccion);
	$smarty->assign('tipo_rol',$tipo_rol);
	$smarty->assign('id_oficina',$id_oficina);
	$smarty->assign('correoe',$correoe);
	
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('adm/personas/modificar.html');
	die();
?>
