<?php

$id= $_REQUEST["id"];

$ids_documento = $_REQUEST["ids_documento"];
$tipodoc = $_REQUEST["tipodoc"];
$obs = $_REQUEST["obs"];
$coment = $_REQUEST["coment"];
$estado = $_REQUEST["estado"];

if($cat=='0' or $enable_ws == 'S'){
	$fojas = $_REQUEST["fojas"];
}

if($estado =='rec'){
	// cuando es refinanciado forzamos a $estado a cambiar a 'ref', ver documentos1.php
		$sql= "DELETE FROM documentos_informe WHERE din_inf_id='$id'";
		ejecutar($sql);
}

//insertamos datos
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$i = 0;
foreach($ids_documento as $idd){
	if($cat=='0' or $enable_ws == 'S'){
		$fjs = $fojas[$i];
		$td = $tipodoc[$i];
	}else{
		$aux= "chk_".$idd;
		if(isset($_REQUEST["$aux"])){
			$fjs = '1';
			$td = '0';
		}else{
			$fjs = '0';
			$td = $tipodoc[$i];
		}
	}
	if($td!='xx'){
		$sql= "INSERT INTO documentos_informe (din_inf_id, din_doc_id, din_tip_doc, fojas, fechareg, obs, comentario)
				VALUES ($id, $idd, $td, '$fjs', $fecha_actual, '$obs[$i]', '$coment[$i]') ";
		//echo $sql; echo "<br>";
		ejecutar($sql);
	}
	$i++;
}

//
$estado = 0;
if($enable_ws == 'A'){
	//recuperar nro de caso del informe legal
	$sql = "SELECT nrocaso FROM informes_legales WHERE id_informe_legal = $id " ;
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$nrocaso= $row["nrocaso"];
	require_once('ws_estadopro_baneco.php');
}
?>