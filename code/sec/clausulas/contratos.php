<?php
/*
  contratos que contienen la clausula
*/
	

	//id de la clausula que queremos buscar en los contratos
	$idc = $_REQUEST['id'];

//********
		
//************************************************************************************
	//mostramos todos los contratos
	$sql = "SELECT co.idcontrato, co.titulo FROM rel_cc cc
			INNER JOIN contrato co ON co.idcontrato = cc.idcontrato 
			WHERE cc.idclausula = '$idc' 
			ORDER BY titulo";
	$miscontratos= array();
	$query = consulta($sql);
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$miscontratos[]= array('id' => $row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	$smarty->assign('miscontratos',$miscontratos);
	$smarty->display('sec/clausulas/contratos.html');
	die();

?>