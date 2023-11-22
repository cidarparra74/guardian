<?php
//session_start();
//require_once('../lib/conexionSEC.php');

$idcl = $_REQUEST['idcl'];
$idco = $_REQUEST['idcv'];

		//vinculando
		if(isset($_REQUEST['adicvinc'])){
			$idclausula = $_REQUEST['idclausula'];
			$sql = "INSERT INTO vinculo (idcontrato, idclausula, vinculo) VALUES ($idco, $idcl, $idclausula)";
			ejecutar($sql);
		}
		//desvinculando
		if(isset($_REQUEST['quitvinc'])){
			$vinc = $_REQUEST['quitvinc'];
			$sql = "DELETE FROM vinculo WHERE vinculo=$vinc and idcontrato=$idco and idclausula=$idcl";
			ejecutar($sql);
		}
		
	//las clausulas ya vinculadas:	
	$sql = "SELECT rc.vinculo, cl.titulo, cl.descri 
	FROM clausula cl
	INNER JOIN vinculo rc ON rc.vinculo = cl.idclausula
	WHERE rc.idcontrato = $idco and rc.idclausula = $idcl
	ORDER BY cl.titulo ";
	$query= consulta($sql);
	
	$vinculos = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$vinculos[] = array('id' => $row["vinculo"],
							'titulo' => $row["titulo"],
							'descri' => $row["descri"]);
	}
	$smarty->assign('vinculos',$vinculos);
	
	//las clausulas que se pueden vincular
	/*$sql = "SELECT cl.idclausula, cl.titulo, cl.descri 
	FROM clausula cl
	WHERE idclausula <> $idcl
	ORDER BY cl.titulo ";*/
	$sql = "SELECT cl.idclausula, cl.titulo, cl.descri 
	FROM clausula cl
	INNER JOIN rel_cc rc ON rc.idclausula = cl.idclausula
	WHERE rc.idcontrato = $idco and rc.idclausula <> $idcl and opcional='1'
	ORDER BY rc.posicion ";
	//echo $sql;
	$query= consulta($sql);
	
	$clausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('id' => $row["idclausula"],
							'titulo' => $row["titulo"],
							'descri' => ucfirst(strtolower($row["descri"])));
	}
	$smarty->assign('clausulas',$clausulas);
	
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
	
	$smarty->assign('idcl',$idcl);
	$smarty->assign('idco',$idco);
	
	$smarty->display('sec/contratos/vincular.html');
	die();
	
?>
