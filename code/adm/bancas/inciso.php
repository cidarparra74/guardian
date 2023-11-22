<?php

/*
SVD SEGURO DESGRAVAMEN

FPS   SECUENCIALES 
FPU   UNICO

TMI	TASA MIXTA
TVA	TASA VARIABLE
TFI	TASA FIJA
*/

$id= $_REQUEST['id']; //id de la banca
$idc= $_REQUEST['idc']; //id del contrato

$numerales = array();
require('../lib/conexionMNU.php');

$sql="SELECT idnumeral, tc FROM sec_opcional WHERE id_banca='$id' AND idcontrato = '$idc' AND tc IN ('SVD','FPS','FPU','TMI','TVA','TFI','LIR','LIS')";
$query= consulta($sql);
	while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		//$numerales[] = array('idinc' => $resultado["idnumeral"],
		//					'tc' => $resultado["tc"]);
		if($resultado["tc"] == 'SVD')
			$SVD = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'FPS')
			$FPS = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'FPU')
			$FPU = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'TMI')
			$TMI = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'TVA')
			$TVA = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'TFI')
			$TFI = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'LIR')
			$LIR = $resultado["idnumeral"];
		elseif($resultado["tc"] == 'LIS')
			$LIS = $resultado["idnumeral"];
		
	}
	$smarty->assign('SVD',$SVD);
	$smarty->assign('FPS',$FPS);
	$smarty->assign('FPU',$FPU);
	$smarty->assign('TMI',$TMI);
	$smarty->assign('TVA',$TVA);
	$smarty->assign('TFI',$TFI);
	$smarty->assign('LIR',$LIR);
	$smarty->assign('LIS',$LIS);
	
require('../lib/conexionSEC.php');
$incisos = array();
//cl.titulo, cl.idclausula,  , nu.excluyente , nu.nro_correlativo
$sql= "SELECT nu.idnumeral, nu.titulo as inciso
	FROM numeral nu 
	WHERE nu.idclausula IN 
	(SELECT r.idclausula 
	FROM rel_cc r 
	WHERE r.idcontrato= $idc  
	) ORDER BY nu.idclausula, nu.nro_correlativo";

	$query= consulta($sql);
	while($resultado= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		
		$incisos[] = array('idinc' => $resultado["idnumeral"],
							'inciso' => $resultado["inciso"]);
	
	}
	$smarty->assign('incisos',$incisos);

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
	
	$smarty->display('adm/bancas/inciso.html');
	die();
?>