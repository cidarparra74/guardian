<?php

require_once('../lib/conexionSEC.php');

	//cargamos los contratos
	$sql="SELECT idcontrato, titulo FROM contrato WHERE habilitado=1";
	$query= consulta($sql);

	$contratos= array();
	
	while($row=  $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$contratos[]= array('id' =>$row["idcontrato"],
							'titulo' => $row["titulo"]);
	}
	
	$smarty->assign('contratos',$contratos);
	
require('../lib/conexionMNU.php');
	
	
	$id= $_REQUEST['id'];
	
	$sql="SELECT * FROM contratos_fijos WHERE id_banca = '$id'";
	$query= consulta($sql);
	while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		if($resultado["clase"]=='A'){
			$smarty->assign('cont_A',$resultado["idcontrato"]);
		}
		if($resultado["clase"]=='B'){
			$smarty->assign('cont_B',$resultado["idcontrato"]);
		}
		if($resultado["clase"]=='P'){
			$smarty->assign('cont_P',$resultado["idcontrato"]);
		}
	}
	
	$sql = "SELECT banca FROM bancas WHERE id_banca = '$id' ";
	$query= consulta($sql);
	$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$descripcion= $resultado["banca"];
	$smarty->assign('id',$id);
	$smarty->assign('banca',$descripcion);
	
	$smarty->display('adm/bancas/contratos.html');
	die();
?>