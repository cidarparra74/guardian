<?php

require_once("../lib/setup.php");
$smarty = new bd;	
require_once('../lib/fechas.php');
//cargando para el overlib
	require_once("../lib/cargar_overlib.php");
	//18/07/2015
	require_once('../lib/verificar.php');


$id_dp = $_REQUEST['id_dp'];

/**********************valores para la ventana*************************/
/**********************valores para la ventana*************************/
	//guardando los documentos
	if(isset($_REQUEST['guardar_segx'])){
		//include("archivo/documentos/guardaseg.php");
		$seg_cia = $_REQUEST["seg_cia"];
		$seg_poliza = $_REQUEST["seg_poliza"];
		$seg_vence = dateYMD($_REQUEST["seg_vence"]);
		$seg_plazo = dateYMD($_REQUEST["seg_plazo"]);
		$seg_monto = $_REQUEST["seg_monto"];
		$seg_obs = $_REQUEST["seg_obs"];
		$seg_vence = fechaSQL($seg_vence);
		$seg_plazo = fechaSQL($seg_plazo);
		$sql= "UPDATE documentos_propietarios SET 
				seg_cia = '$seg_cia', 
				seg_poliza = '$seg_poliza', 
				seg_vence = $seg_vence, 
				seg_plazo = $seg_plazo,
				seg_monto = '$seg_monto', 
				seg_obs = '$seg_obs' 
				WHERE id_documento_propietario = '$id_dp' ";
				echo $sql;
		$result = $link->query($sql);
		if($result !=1) {echo "error en $sql ";}
		$smarty->assign('hay',  'fin');
		$smarty->display('documentos/seguro.html');
		die();
	}
/**********************valores por defecto*************************/
/**********************valores por defecto*************************/
if($id_dp != '0'){

	// entidades
	$sql = "SELECT * FROM entidades ORDER BY entidad";
	$result= $link->query($sql);
	$entidades= array();
	
	while($row= $result->fetchRow(DB_FETCHMODE_ASSOC)){
		$entidades[]= array('id' =>$row["id"],
							'entidad' => $row["entidad"]);
	}
	
	$smarty->assign('entidades',$entidades);
	//recuperando los datos para la ventana, los documentos que tiene el propietario
	$sql= "SELECT dp.seg_cia, dp.seg_poliza, dp.seg_vence, dp.seg_plazo, dp.seg_monto, dp.seg_obs  ".
		  " FROM documentos_propietarios dp ".
		  " WHERE dp.id_documento_propietario = '$id_dp'  ";

	$result = $link->query($sql);
	$row = $result->fetchRow(DB_FETCHMODE_ASSOC);
		$seg_cia = $row["seg_cia"];
		$seg_poliza = $row["seg_poliza"];
		$seg_vence = $row["seg_vence"];
		$seg_plazo = $row["seg_plazo"];
		$seg_monto = $row["seg_monto"];
		$seg_obs = $row["seg_obs"];
	
	if($seg_vence!= ""){
		$aux_c = explode(" ",$seg_vence);
		$aux_d = $aux_c[0];
		//$fechav = $bd_fechas->formar_fecha($aux_d, "-", "dd/MMM/yyyy", "yyyy-mm-dd");
		$fechav = dateDMY($aux_d);
	}else{
		$fechav = "";
	}
	if($seg_plazo!= ""){
		$aux_c = explode(" ",$seg_plazo);
		$aux_d = $aux_c[0];
		//$fechap = $bd_fechas->formar_fecha($aux_d, "-", "dd/MMM/yyyy", "yyyy-mm-dd");
		$fechap = dateDMY($aux_d);
	}else{
		$fechap = "";
	}
	
	$smarty->assign('seg_cia',   $seg_cia);
	$smarty->assign('seg_poliza',$seg_poliza);
	$smarty->assign('seg_vence', $fechav);
	$smarty->assign('seg_plazo', $fechap);
	$smarty->assign('seg_monto', $seg_monto);
	$smarty->assign('seg_obs',   $seg_obs);
	$smarty->assign('id_dp',     $id_dp);
	$smarty->assign('hay',  'si');
	
}else{
	$smarty->assign('hay',  'no');
}
	$smarty->display('documentos/seguro.html');
	die();

?>
