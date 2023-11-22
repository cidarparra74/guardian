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
	$sql= "SELECT id_tipo_carpeta, tipo FROM tipos_carpetas ORDER BY id_tipo_carpeta ";
	$result= consulta($sql);
	$ids_tipo= array();
	$tipo= array();
	$i=0;
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$ids_tipo[$i]= $row["id_tipo_carpeta"];
		$tipo[$i]= $row["tipo"];
		
		$i++;
	}
	
	//recuperando las agencias del sistema
	$sql= "SELECT id_oficina, nombre FROM oficinas ORDER BY id_oficina ";
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
	
	$smarty->assign('ids_tipo',$ids_tipo);
	$smarty->assign('tipo',$tipo);
	
	$smarty->assign('ids_oficina',$ids_oficina);
	$smarty->assign('oficina',$oficina);
	
	
	$smarty->display('reportes/fechas_creacion_pc.html');
	die();
?>