<?php

	$sql = "SELECT tipo FROM tipos_carpetas ORDER BY tipo ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["tipo"];
		$i++;
	}	
	
	$sql = "SELECT id_tipo_bien, tipo_bien FROM tipos_bien ORDER BY tipo_bien ";
	$query= consulta($sql);
	$i=0;
	$tiposbien= array();
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$tiposbien[$i]= array('id_tipo_bien' => $row["id_tipo_bien"],
		'tipo_bien' => $row["tipo_bien"]);
		$i++;
	}
	
	$smarty->assign('tiposbien',$tiposbien);
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('adm/tipos_carpetas/adicionar.html');
	die();
?>
