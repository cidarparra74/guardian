<?php



$id= $_REQUEST['id']; //id de la banca
$idc= $_REQUEST['idc']; //id del contrato

$idcla = 0;
	$sql="SELECT idclausula FROM sec_opcional WHERE idcontrato = '$idc' AND tc = 'SEG' ";
	$query= consulta($sql);
	while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$idcla = $resultado["idclausula"];
	
	}
	$smarty->assign('idcla',$idcla);
	
	require_once('../lib/conexionSEC.php');
//jalamos clausulas del contrato elegido
$sql= "SELECT r.posicion, r.idclausula, c.titulo FROM rel_cc r, clausula c 
		WHERE r.idcontrato = $idc AND r.idclausula=c.idclausula AND r.opcional =1 ORDER BY r.posicion";
		//echo $sql;
		$query = consulta($sql);
		$opcionales=array();
		while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
			
			$opcionales[]= array('id' => $row["idclausula"],
									'titulo' => $row["titulo"]);
		}
	
	$smarty->assign('opcionales',$opcionales);
	
	$sql = "SELECT titulo FROM contrato WHERE idcontrato = '$idc' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$titulo= $resultado["titulo"];
	$smarty->assign('idc',$idc);
	$smarty->assign('titulo',$titulo);
	
require('../lib/conexionMNU.php');
	
	$sql = "SELECT banca FROM bancas WHERE id_banca = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$banca= $resultado["banca"];
	$smarty->assign('id',$id);
	$smarty->assign('banca',$banca);
	
	$smarty->display('adm/bancas/clausula.html');
	die();
?>