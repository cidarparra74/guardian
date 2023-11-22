<?php

	//recuperando las gavetas del sistema
	$sql= "SELECT * FROM gavetas ORDER BY id_gaveta ";
	$result= consulta($sql);
	$ids_gaveta= array();
	$gaveta= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_gaveta[$i]= $row["id_gaveta"]-1;
		$gaveta[$i]= $row["gaveta"];
		$i++;
	}

	//recuperando los tipos de carpetas
	$sql= "SELECT id_tipo_bien, tipo_bien FROM tipos_bien ORDER BY tipo_bien ";
	$result= consulta($sql);
	$ids_tipo= array();
	$tipo= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo[$i]= $row["id_tipo_bien"];
		$tipo[$i]= $row["tipo_bien"];
		
		$i++;
	}
	
	$id_almacen = $_SESSION['id_almacen'];
	//recuperando las agencias del sistema
	$sql= "SELECT id_oficina, nombre FROM oficinas WHERE id_almacen='$id_almacen' ORDER BY nombre";
	$result= consulta($sql);
	$ids_oficina= array();
	$oficina= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_oficina[$i]= $row["id_oficina"];
		$oficina[$i]= $row["nombre"];
		
		$i++;
	}
		/*$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);*/
	$smarty->assign('ids_gaveta',$ids_gaveta);
	$smarty->assign('gaveta',$gaveta);
	
	$smarty->assign('ids_tipo',$ids_tipo);
	$smarty->assign('tipo',$tipo);
	
	$smarty->assign('ids_oficina',$ids_oficina);
	$smarty->assign('oficina',$oficina);
	
	
	$smarty->display('reportes/inv_sobres_boveda.html');
	die();
?>