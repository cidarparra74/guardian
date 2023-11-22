<?php
	$id= $_REQUEST['id'];
	$idb= $_REQUEST['idb'];
	
	//que bancas para esta oficina
	$sql = "SELECT fi.nombre as oficina, bc.banca, re.nombre 
	FROM representa re 
	LEFT JOIN bancas bc ON bc.id_banca = re.id_banca
	LEFT JOIN oficinas fi ON fi.id_oficina = re.id_oficina 
	WHERE fi.id_oficina = $id AND bc.id_banca = $idb
	ORDER BY re.nombre ";
	$query= consulta($sql);
	
	$representa = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$representa[] = array('oficina' => $row["oficina"], 
							'banca' => $row["banca"],
							'nombre' => $row["nombre"]);
	}
	$smarty->assign('representa',$representa);
	
	$smarty->assign('id_ban',$idb);
	$smarty->assign('id_ofi',$id);
	$smarty->display('adm/oficinas/eliminar_rep.html');
	die();
?>
