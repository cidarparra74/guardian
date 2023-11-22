<?php
/*	
	$sql = "SELECT nombre FROM almacen ORDER BY nombre ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["nombre"];
		$i++;
	}
	*/
	/*
	$sql = "SELECT id_usuario, nombres FROM usuarios WHERE activo = 'S' ORDER BY nombres ";
	$query= consulta($sql);
	$usuarios= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[$i]= array('id_usuario' => $row["id_usuario"],
							'nombres' => $row["nombres"]);
		$i++;
	}
	$smarty->assign('usuarios',$usuarios);
	*/
//	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/almacenes/adicionar.html');
	die();
?>
