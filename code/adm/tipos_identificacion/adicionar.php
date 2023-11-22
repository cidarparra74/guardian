<?php

	$sql = "SELECT identificacion FROM tipos_identificacion ORDER BY identificacion ";
	$query= consulta($sql);
	$i=0;
	$existentes="";
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["identificacion"].";";
		$i++;
	}	
	
	$smarty->assign('existentes',$existentes);
	$smarty->display('adm/tipos_identificacion/adicionar.html');
	die();
?>
