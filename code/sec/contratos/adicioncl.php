<?php
//session_start();
//require_once('../lib/conexionSEC.php');


	if(isset($_REQUEST['idcontra_x'])){
		$idco = $_REQUEST['idcontra_x'];
		if(isset($_REQUEST['adicclau_x'])){
			//adicionando
			$idcla = $_REQUEST['idclausula'];
			$idpos = $_REQUEST['posicion'];
			$opci = $_REQUEST['opcional'];
			$sql="INSERT INTO rel_cc (idcontrato, idclausula, posicion, opcional)
					VALUES ($idco, $idcla, $idpos, $opci)";
			ejecutar($sql);
		}
	}else die("no existe id contrato");
	
	
	$sql = "SELECT cl.idclausula, cl.titulo, cl.descri 
	FROM clausula cl
	WHERE idclausula not in (SELECT idclausula FROM rel_cc WHERE idcontrato= $idco)
	ORDER BY cl.titulo ";
	$query= consulta($sql);
	
	$clausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('id' => $row["idclausula"],
							'titulo' => $row["titulo"],
							'descri' => ucfirst(strtolower($row["descri"])));
	}
	$smarty->assign('clausulas',$clausulas);
	
	$sql = "SELECT titulo
	FROM contrato WHERE idcontrato = $idco";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tcont',$row['titulo']);
	
	$smarty->assign('idco',$idco);
	
	$smarty->display('sec/contratos/adicioncl.html');
	die();
	
?>
