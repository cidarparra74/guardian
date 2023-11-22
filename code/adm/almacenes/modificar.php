<?php
	$id= $_REQUEST['id'];
	
	$sql = "SELECT * FROM almacen WHERE id_almacen= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nombre= $resultado["nombre"];
	$correoe= $resultado["correoe"];
	$id_usautoriza= $resultado["id_usautoriza"];
	$id_usarchivo= $resultado["id_usarchivo"];
	
	//recuperando los existentes
	$sql = "SELECT nombre FROM almacen WHERE id_almacen!= '$id' ORDER BY nombre ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["nombre"];
		$i++;
	}
	
	$smarty->assign('id',$id);
	$smarty->assign('nombre',$nombre);
	$smarty->assign('correoe',$correoe);
	$smarty->assign('id_usautoriza',$id_usautoriza);
	$smarty->assign('id_usarchivo',$id_usarchivo);

	//solo de las oficinas del recinto
	$sql = "SELECT us.id_usuario, us.nombres, ofi.nombre FROM usuarios us
		INNER JOIN oficinas ofi ON ofi.id_oficina = us.id_oficina
	WHERE us.activo = 'S' AND ofi.id_almacen = '$id' ORDER BY ofi.nombre, us.nombres ";
	$query= consulta($sql);
	$usuarios= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$usuarios[$i]= array('id_usuario' => $row["id_usuario"],
							'nombres' => trim($row["nombre"]).' / '.trim($row["nombres"]));
		$i++;
	}
	$smarty->assign('usuarios',$usuarios);
	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/almacenes/modificar.html');
	die();
?>
