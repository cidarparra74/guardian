<?php

	$sql = "SELECT tipo_bien, id_banca FROM tipos_bien ORDER BY tipo_bien ";
	$query= consulta($sql);
	$tipo= array();
	$i=0;
	$existentes="";
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$existentes.= $row["tipo_bien"].$row["id_banca"].";";

		$i++;
	}	
	
	$sql = "SELECT * FROM bancas ORDER BY codigo ";
	$query= consulta($sql);
	$bancas= array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$bancas[] = array('id_banca'=>$row["id_banca"], 
		'codigo'=>$row["codigo"],
		'banca'=>$row["banca"]);
	}
	
	$smarty->assign('cat',$cat);
	$smarty->assign('bancas',$bancas);
	$smarty->assign('existentes',$existentes);
	
	$smarty->display('adm/tipos_bien/adicionar.html');
	die();
?>
