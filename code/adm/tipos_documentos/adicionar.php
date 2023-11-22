<?php
	
	$sql = "SELECT tipo FROM tipos_documentos ORDER BY tipo ";
	$query= consulta($sql);
	$tipo= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$tipo[$i]= $row["tipo"];

		$i++;
	}	
	
	$smarty->assign('existentes',$tipo);
	
	$smarty->display('adm/tipos_documentos/adicionar.html');
	die();
?>
