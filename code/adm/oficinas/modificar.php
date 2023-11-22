<?php
	$id= $_REQUEST['id'];
	
	//recuperando los existentes
	$sql = "SELECT nombre FROM oficinas WHERE id_oficina!= '$id' ORDER BY nombre ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["nombre"];
		$i++;
	}
	
	//lista de almacenes
	$sql = "SELECT * FROM almacen ORDER BY nombre ";
	$query= consulta($sql);
	$almacenes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacenes[$i]= array('id_almacen' => $row["id_almacen"],
							'nombre' => $row["nombre"]);
		$i++;
	}

	$sql = "SELECT us.id_usuario, us.nombres, ofi.nombre 
			FROM usuarios us 
			LEFT JOIN oficinas ofi ON us.id_oficina = ofi.id_oficina
			WHERE us.activo = 'S' ORDER BY ofi.nombre, us.nombres ";
	$query= consulta($sql);
	$usuarios= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[$i]= array('id_usuario' => $row["id_usuario"],
							'nombres' => $row["nombre"].' / '.trim($row["nombres"]));
		$i++;
	}
	$smarty->assign('almacenes',$almacenes);
	$smarty->assign('usuarios',$usuarios);
	$smarty->assign('existentes',$existentes);
	
	$sql = "SELECT * FROM oficinas WHERE id_oficina= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('id',$id);
	$smarty->assign('nombre',$resultado["nombre"]);
	$smarty->assign('direccion',$resultado["direccion"]);
	$smarty->assign('telefonos',$resultado["telefonos"]);
	$smarty->assign('ciudad',$resultado["ciudad"]);
	$smarty->assign('pais',$resultado["pais"]);
	$smarty->assign('id_almacen',$resultado["id_almacen"]);
	$smarty->assign('id_responsable',$resultado["id_responsable"]);
	$smarty->assign('id_asesor',$resultado["id_asesor"]);
	$smarty->assign('codigo',$resultado["codigo"]);
	$smarty->assign('correos',$resultado["correos"]);
	//$smarty->assign('id_perito',$resultado["id_perito"]);
	
	
	$smarty->display('adm/oficinas/modificar.html');
	die();
?>
