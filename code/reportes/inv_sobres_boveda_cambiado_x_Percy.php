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
	

	
	//recuperando las agencias del sistema
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
	
	$smarty->assign('ids_gaveta',$ids_gaveta);
	$smarty->assign('gaveta',$gaveta);
	
	
	$smarty->assign('ids_oficina',$ids_oficina);
	$smarty->assign('oficina',$oficina);
	
	$smarty->display('reportes/inv_sobres_boveda.html');
	die();
?>