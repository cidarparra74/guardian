<?php
$id= $_REQUEST['id'];
	
	$loscont = '0';
	$sql="SELECT * FROM contratos_fijos WHERE id_banca = '$id'";
	$query= consulta($sql);
	while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$loscont .= ','.$resultado["idcontrato"];
		
		if($resultado["clase"]=='A'){
			$cont_A=$resultado["idcontrato"];
		}
		if($resultado["clase"]=='B'){
			$cont_B=$resultado["idcontrato"];
		}
		if($resultado["clase"]=='P'){
			$cont_P=$resultado["idcontrato"];
		}
		
	}
	
require_once('../lib/conexionSEC.php');

	//cargamos los contratos
	$sql="SELECT idcontrato, titulo FROM contrato WHERE idcontrato IN ($loscont) ";
	$query= consulta($sql);

	$contratos= array();
	$clase = '-';
	
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		if($row["idcontrato"] == $cont_A)
			$clase = 'A';
		if($row["idcontrato"] == $cont_B)
			$clase = 'B';
		if($row["idcontrato"] == $cont_P)
			$clase = 'P';
		$contratos[]= array('id' =>$row["idcontrato"],
							'clase' => $clase,
							'titulo' => $row["titulo"]);
	}
	
	$smarty->assign('contratos',$contratos);
	
require('../lib/conexionMNU.php');
	
	
	
	
	$sql = "SELECT banca FROM bancas WHERE id_banca = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["banca"];
	$smarty->assign('id',$id);
	$smarty->assign('banca',$descripcion);
	
	$smarty->display('adm/bancas/contratosver.html');
	die();
?>