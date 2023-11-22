<?php
	
	$sql = "SELECT * FROM expedido ORDER BY codigo ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["descripcion"];
		$i++;
	}
	$smarty->assign('existentes',$existentes);
	$smarty->display('sec/extension/adicionar.html');
	die();
?>
