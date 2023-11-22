<?php
	
	$sql = "SELECT * FROM localizacion ORDER BY departamento ";
	$query= consulta($sql);
	$existentes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes[$i]= $row["localizacion"];
		$i++;
	}
	$smarty->assign('existentes',$existentes);
	$smarty->display('sec/localiza/adicionar.html');
	die();
?>
