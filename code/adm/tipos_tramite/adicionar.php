<?php

	
	$sql = "SELECT descripcion FROM tipos_tramite ORDER BY descripcion ";
	$result= $link->query($sql);
	$tipo= array();
	$i=0;
	$existentes="";
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["descripcion"].";";

		$i++;
	}	
	
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('administrador/tipos_tramite/adicionar.html');
	die();
?>