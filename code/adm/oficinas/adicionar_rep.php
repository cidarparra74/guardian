<?php
	//seleccionamos el nombre de la oficina
	$sql = "SELECT * FROM oficinas WHERE id_oficina= '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('oficina',$resultado["nombre"]);
	
	//seleccionamos todas las bancas, menos las que ya tiene registrada
	$sql = "SELECT * FROM bancas 
	WHERE id_banca NOT IN (SELECT DISTINCT id_banca 
	FROM representa WHERE id_oficina = $id)
	ORDER BY banca ";
	$query= consulta($sql);
	$bancas= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$bancas[$i]= array('id_banca' => $row["id_banca"],
							'banca' => $row["banca"]);
		$i++;
	}
	
	
	//para las variables que agrupan representantes 
	require('../lib/conexionSEC.php'); 
	$sql = "SELECT vt.idtexto, vt.descripcion
	FROM var_texto vt 
	WHERE vt.esglobal = 4 AND vt.eslista = 1
	ORDER BY vt.descripcion ";
	$query= consulta($sql);
	$variables= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$variables[$i]= array('idtexto' => $row["idtexto"],
							'var' => $row["descripcion"]);
		$i++;
	}
	$smarty->assign('variables',$variables);
	
	//representantes guardados en SEC
	$sql = "SELECT vt.idtexto, vtv.valor
	FROM var_texto vt 
	INNER JOIN var_texto_valores  vtv ON vtv.idtexto = vt.idtexto
	WHERE vt.esglobal = 4 AND vt.eslista=1
	ORDER BY vt.idtexto, vtv.valor  ";
	$query= consulta($sql);
	$representantes= array();
	$i=0;
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$representantes[$i]= array('idtexto' => $row["idtexto"],
							'representante' => $row["valor"]);
		$i++;
	}
	$smarty->assign('representantes',$representantes);
	
	$smarty->assign('bancas',$bancas);
	$smarty->assign('id_ofi',$id);
	$smarty->display('adm/oficinas/adicionar_rep.html');
	die();
?>
