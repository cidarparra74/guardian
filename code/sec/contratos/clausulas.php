<?php
//session_start();
$idcc = $_REQUEST['idcc'];

//*************
/*
	if(isset($_REQUEST['idcontra_x'])){
		$idcc = $_REQUEST['idcontra_x'];
		if(isset($_REQUEST['adicclau_x'])){
			//adicionando
			$idcla = $_REQUEST['idclausula'];
			$idpos = $_REQUEST['posicion'];
			$opci = $_REQUEST['opcional'];
			$sql="INSERT INTO rel_cc (idcontrato, idclausula, posicion, opcional)
					VALUES ($idcc, $idcla, $idpos, $opci)";
			ejecutar($sql);
		}
	}
	
	
	$sql = "SELECT cl.idclausula, cl.titulo, cl.descri 
	FROM clausula cl
	WHERE idclausula not in (SELECT idclausula FROM rel_cc WHERE idcontrato= $idcc)
	ORDER BY cl.titulo ";
	$query= consulta($sql);
	
	$addclausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$addclausulas[] = array('id' => $row["idclausula"],
							'titulo' => $row["titulo"],
							'descri' => ucfirst(strtolower($row["descri"])));
	}
	$smarty->assign('addclausulas',$addclausulas);
	*/
//*************

	//las clausulas del contrato:
	$sql = "SELECT cl.idclausula, rc.posicion, cl.titulo, cl.descri, count(*) as incisos 
	FROM clausula cl
	INNER JOIN rel_cc rc ON rc.idclausula = cl.idclausula
	LEFT JOIN numeral nu ON nu.idclausula = cl.idclausula
	WHERE rc.idcontrato = $idcc
	GROUP BY cl.idclausula, cl.titulo, cl.descri, rc.posicion
	ORDER BY rc.posicion ";
	$query= consulta($sql);
	
	$clausulas = array();
	
	while($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$clausulas[] = array('id' => $row["idclausula"],
							'posicion' => $row["posicion"],
							'titulo' => $row["titulo"],
							'descri' => $row["descri"],
							'incisos' => $row["incisos"]);
						
	}
	$smarty->assign('clausulas',$clausulas);
	
	$sql = "SELECT titulo
	FROM contrato WHERE idcontrato = $idcc";
	$query= consulta($sql);
	$row = $query->fetchRow(DB_FETCHMODE_ASSOC);
	$smarty->assign('tcont',$row['titulo']);
	
	$smarty->assign('idcc',$idcc);
	
	$smarty->display('sec/contratos/clausulas.html');
	die();
	
?>
