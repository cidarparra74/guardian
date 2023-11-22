<?php

	$sql = "SELECT ci FROM personas ORDER BY ci ";
	$query = consulta($sql);
	$ci= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$ci[$i]= $row["ci"];
		$i++;
	}	
	//oficinas
	$sql = "SELECT al.nombre as almacen, ofi.* FROM oficinas ofi LEFT JOIN almacen al ON ofi.id_almacen = al.id_almacen ORDER BY al.nombre, ofi.nombre ";
	$query= consulta($sql);
	$oficinas = array();
	$oficinas[] = array('id_oficina' => 0,
							'nombre' => '(Varias)');
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
	
	$smarty->assign('existentes',$ci);
	
	$smarty->display('adm/personas/adicionar.html');
	die();
?>
