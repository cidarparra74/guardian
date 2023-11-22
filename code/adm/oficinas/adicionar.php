<?php
	
	$sql = "SELECT nombre FROM oficinas ORDER BY nombre ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["nombre"];
		$i++;
	}	
	$sql = "SELECT * FROM almacen ORDER BY nombre ";
	$query= consulta($sql);
	$almacenes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$almacenes[$i]= array('id_almacen' => $row["id_almacen"],
							'nombre' => $row["nombre"]);
		$i++;
	}
	$sql = "SELECT id_usuario, nombres FROM usuarios WHERE activo = 'S' ORDER BY nombres ";
	$query= consulta($sql);
	$usuarios= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[$i]= array('id_usuario' => $row["id_usuario"],
							'nombres' => $row["nombres"]);
		$i++;
	}
	$smarty->assign('existentes',$existentes);
	$smarty->assign('almacenes',$almacenes);
	$smarty->assign('usuarios',$usuarios);
	$smarty->display('adm/oficinas/adicionar.html');
	die();
?>
