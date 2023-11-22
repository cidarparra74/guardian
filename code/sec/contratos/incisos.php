<?php
//session_start();
$idco = $_REQUEST['idci'];
$idcl = $_REQUEST['idcl'];


	$sql = "SELECT titulo
	FROM clausula WHERE idclausula = $idcl";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tclau',$row['titulo']);
	
	$sql = "SELECT titulo
	FROM contrato WHERE idcontrato = $idco";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tcont',$row['titulo']);

	//los incisos de la clausula del contrato:
	$sql = "SELECT idnumeral, titulo, nro_correlativo
	FROM  numeral  
	WHERE idclausula = $idcl
	ORDER BY nro_correlativo ";
	$query= consulta($sql);
	
	$incisos = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$incisos[] = array('id' => $row["idnumeral"],
							'posicion' => $row["nro_correlativo"],
							'titulo' => $row["titulo"]);
						
	}
	$smarty->assign('incisos',$incisos);
	
	$sql = "SELECT titulo FROM contrato WHERE idcontrato = $idco";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tcont',$row['titulo']);
	
	$smarty->assign('idco',$idco);
	
	$smarty->display('sec/contratos/incisos.html');
	die();
	
?>
