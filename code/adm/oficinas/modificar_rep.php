<?php
	$idr= $_REQUEST['idr'];
	
	$sql = "SELECT * FROM representa WHERE id_representa= '$idr' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$id = $resultado["id_oficina"];
	$idb = $resultado["id_banca"];
	$nom = $resultado["nombre"];
	$idt = $resultado["idtexto"];
	$smarty->assign('idr',$idr);
	$smarty->assign('id_ofi',$id);
	$smarty->assign('nombre',$nom);	
	
	
	
	//seleccionamos la oficina
	$sql = "SELECT * FROM oficinas WHERE id_oficina= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('oficina',$resultado["nombre"]);
	
	//seleccionamos la banca
	$sql = "SELECT * FROM bancas WHERE id_banca= '$idb' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('banca',$resultado["banca"]);	
	
	//representantes guardados en SEC
	require('../lib/conexionSEC.php'); 
	$sql = "SELECT vt.idtexto, vtv.valor
	FROM var_texto vt 
	INNER JOIN var_texto_valores  vtv ON vtv.idtexto = vt.idtexto
	WHERE vt.esglobal = 4 AND vt.idtexto='$idt'
	ORDER BY vt.idtexto, vtv.valor";
	$query= consulta($sql);
	$representantes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$representantes[$i]= array('idtexto' => $row["idtexto"],
							'representante' => $row["valor"]);
		$i++;
	}
	$smarty->assign('representantes',$representantes);
	
	
	//$smarty->assign('idr',$idr);
	$smarty->display('adm/oficinas/modificar_rep.html');
	die();
?>
