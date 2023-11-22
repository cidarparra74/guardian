<?php

if(!isset($_REQUEST["gorep"])){

	$smarty->assign('id',$_REQUEST['id']);

	$smarty->display('ver_informe_legal/imprimir_recepcion.html');
	die();
}

//entra al reporte
$id = $_REQUEST['query1'];
$sql="SELECT ile.nrocaso,  pr.nombres as cliente, ile.montoprestamo, ile.nrobien, 
usr.nombres, ile.motivo, convert(varchar,fecha_recepcion,103) as fecha,
tbi.tipo_bien, pr.ci, pr.emision, per.nombres+' '+per.apellidos as perito, 
per.direccion, per.telefonos, tbi.id_tipo_bien, ile.noportunidad, ile.inf_agencia
FROM informes_legales ile 
INNER JOIN usuarios usr ON ile.id_us_comun = usr.id_usuario
INNER JOIN tipos_bien tbi ON ile.id_tipo_bien = tbi.id_tipo_bien
INNER JOIN propietarios pr ON pr.id_propietario = ile.id_propietario
LEFT JOIN personas per ON ile.id_perito = per.id_persona
WHERE ile.id_informe_legal='$id'";
$query = consulta($sql);
$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	$nrocaso = $row["nrocaso"];
	$noportunidad = $row["noportunidad"];
	$cliente = $row["cliente"];
	$ci = $row["ci"].' '.$row["emision"];
	//$montoprestamo = $row["montoprestamo"];
	$motivo = $row["motivo"];
	$nombres = $row["nombres"];
	$nrobien = $row["nrobien"];
	$recepcionadox=$row["inf_agencia"];
	$fecha = $row["fecha"];
	$tipo_bien = $row["tipo_bien"];
	$id_tipo_bien = $row["id_tipo_bien"];  // para docs faltantes
	//$perito = $row["perito"];
	$smarty->assign('perito',$row["perito"]);
	$smarty->assign('direccion',$row["direccion"]);
	$smarty->assign('telefonos',$row["telefonos"]);

//los documentos que se registraron de este cliente
$sql="SELECT doc.documento, tip.tipo, convert(varchar,fechareg,103) as frecep, 
din.fojas, din.obs, din.comentario, doc.id_documento
FROM documentos doc
INNER JOIN documentos_informe din ON din.din_doc_id = doc.id_documento
LEFT JOIN tipos_documentos tip ON tip.id_tipo_documento = din.din_tip_doc
WHERE din.din_inf_id = '$id' ORDER BY din.fechareg, din.obs, doc.documento ";
//echo $sql; die();
$query = consulta($sql);
$docus=array();
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$docus[] = array('documento' => $row["documento"],
					'tipo' => $row["tipo"],
					'id_documento' => $row["id_documento"],
					'fojas' => $row["fojas"],
					'obs' => $row["obs"],
					'frecep' => $row["frecep"],
					'comentario' => $row["comentario"]);
}
//para ver q reporte tomar
//para caso de que sea rec. comercial
if(isset($cat) and $cat == '1'){
	$rep_recepcion = "imprimiendo_recepcionCOM.html";
	//VEMOS DOCS FALTANTES
	//recuperamos la lista total de documentos
	$sql= "SELECT doc.id_documento, doc.documento FROM tipos_bien_documentos tb 
INNER JOIN documentos doc ON tb.id_documento = doc.id_documento 
	WHERE  tb.requerido='1' AND tb.id_tipo_bien = (SELECT id_tipo_bien FROM informes_legales 
	WHERE id_informe_legal = '$id') AND tb.id_documento NOT IN (
	SELECT di.din_doc_id FROM documentos_informe di WHERE di.din_inf_id = '$id')
ORDER BY tb.orden";
	
	$query = consulta($sql);
	$faltan = array();
	while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
		
		$doc = $row['documento'];
		$faltan[] = $doc;
		
	}
	//var_dump($faltan);
	$smarty->assign('faltan',$faltan);
}else{
	$rep_recepcion = '';
	$sql = "SELECT rep_recepcion FROM opciones ";
	$query= consulta($sql);
	if($row = $query->fetchRow(DB_FETCHMODE_ASSOC)){
		$rep_recepcion = trim($row["rep_recepcion"]);
	}
	if($rep_recepcion == '' || !file_exists("../templates/ver_informe_legal/".$rep_recepcion)){
		$rep_recepcion = "imprimiendo_recepcionBSOL.html";
	}
}

$sql= "SELECT ofi.nombre FROM informes_legales il ".
			" INNER JOIN oficinas ofi ON ofi.id_oficina = il.id_oficina ".
			" WHERE il.id_informe_legal='$id' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('oficina',$resultado['nombre']);

$sql= "SELECT logo01 FROM opciones";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$smarty->assign('logo',$resultado['logo01']);

$smarty->assign('id',$id);
$smarty->assign('ci',$ci);
$smarty->assign('nrocaso',$nrocaso);
$smarty->assign('noportunidad',$noportunidad);
$smarty->assign('cliente',$cliente);
$smarty->assign('motivo',$motivo);
$smarty->assign('nombres',$nombres);
$smarty->assign('nrobien',$nrobien);
$smarty->assign('recepcionadox',$recepcionadox);
$smarty->assign('fecha',$fecha);
$smarty->assign('tipo_bien',$tipo_bien);

//$smarty->assign('cat',$cat);

$smarty->assign('docus',$docus);

//echo $rep_recepcion; die();

$smarty->display('ver_informe_legal/'.$rep_recepcion);
	die();


?>