<?php
	
	$sql = "SELECT * 
	FROM tipo
	ORDER BY descripcion ";
	$query= consulta($sql);
	$tipo = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipo[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('tipo',$tipo);
	
	$sql = "SELECT * 
	FROM materia
	ORDER BY descripcion ";
	$query= consulta($sql);
	$materia = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$materia[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('materia',$materia);
	
	$sql = "SELECT * 
	FROM entidad
	ORDER BY descripcion ";
	$query= consulta($sql);
	$entidad = array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$entidad[] = array('id' => $row["codigo"],
							'desc' => $row["descripcion"]);
	}
	$smarty->assign('entidad',$entidad);
	
	
	$smarty->display('sec/clausulas/adicionar.html');
	die();
?>
