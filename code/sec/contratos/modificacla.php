<?php
	$idcla= $_REQUEST['id'];
	$idcon= $_REQUEST['modificacla'];
	
	$sql = "SELECT rc.posicion, rc.opcional, rc.sintitulo, rc.dependiente,
	cl.titulo as clausula, cl.descri, co.titulo as contrato, rc.tipopersona
	FROM rel_cc rc
	INNER JOIN clausula cl ON cl.idclausula = rc.idclausula
	INNER JOIN contrato co ON co.idcontrato = rc.idcontrato
	WHERE rc.idclausula = '$idcla' and rc.idcontrato = '$idcon'";
	
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
	$smarty->assign('idcla',$idcla);
	$smarty->assign('idcon',$idcon);
	$smarty->assign('opcional',$resultado["opcional"]);
	$smarty->assign('posicion',$resultado["posicion"]);
	$smarty->assign('clausula',$resultado["clausula"]);
	$smarty->assign('descri',$resultado["descri"]);
	$smarty->assign('contrato',$resultado["contrato"]);
	$smarty->assign('sintitulo',$resultado["sintitulo"]);
	$smarty->assign('dependiente',$resultado["dependiente"]);
	$smarty->assign('tipopersona',$resultado["tipopersona"]);
	
	//las clausulas del contrato:
	$sql = "SELECT cl.idclausula, rc.posicion, cl.titulo
	FROM clausula cl 
	INNER JOIN rel_cc rc ON rc.idclausula = cl.idclausula 
	WHERE rc.idcontrato = $idcon and rc.idclausula <> $idcla 
	ORDER BY rc.posicion ";
	$query= consulta($sql);
	$clausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('id' => $row["idclausula"],
							'posicion' => $row["posicion"],
							'titulo' => $row["titulo"]);
						
	}
	$smarty->assign('clausulas',$clausulas);
	
	$smarty->display('sec/contratos/modificacla.html');

	die();
?>
