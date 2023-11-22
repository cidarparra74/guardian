<?php

$id= $_REQUEST["id"];

//tipo_bien_old es para cuando cambian el tipo de garantia
$tipo_bien_old= $_REQUEST["id_tipo_bien_old"];
$tipo_bien= $_REQUEST["tipo_bien"];
$nrobien= $_REQUEST["nrobien"];
$recepcionadox= $_REQUEST["recepcionadox"];
$fecha= date("Y-m-d H:i:s");
$fecha="CONVERT(DATETIME,'$fecha',102)";
if(isset($_REQUEST["motivo"]))
	$motivo= $_REQUEST["motivo"];
//para bsol se jala de un combo:
if(isset($_REQUEST["motivo_id"])){
	$motivo= '';
	$id_objeto= $_REQUEST["motivo_id"];
	if($id_objeto!='--'){
		$sql= "SELECT * FROM objetos WHERE id_objeto='$id_objeto' ";
		$query = consulta($sql);
		$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$motivo=$resultado["objeto"];
		//
		$sql= "UPDATE informes_legales SET motivo='$motivo' , nrobien='$nrobien', fecha_recepcion = $fecha, inf_agencia = '$recepcionadox'
		WHERE id_informe_legal='$id' ";
		ejecutar($sql);
	}else{
		//no ha cambiado el objeto/motivo
		$sql= "UPDATE informes_legales SET nrobien='$nrobien', fecha_recepcion = $fecha, inf_agencia = '$recepcionadox'
		WHERE id_informe_legal='$id' ";
		ejecutar($sql);
	}
}else{ 
	//actualizando
	$sql= "UPDATE informes_legales SET  motivo='$motivo' , 
	nrobien='$nrobien', fecha_recepcion = $fecha, inf_agencia = '$recepcionadox'
	WHERE id_informe_legal='$id' ";
	ejecutar($sql);
}
if($tipo_bien_old != $tipo_bien){
	//se ha cambiado el tipo de garantia, valido solo para bsol
	$sql= "UPDATE informes_legales SET id_tipo_bien = $tipo_bien WHERE id_informe_legal='$id' ";
	ejecutar($sql);
	//eliminamos documentos que no corresponden a la nueva garantia
	$sql="DELETE FROM documentos_informe WHERE din_inf_id = $id and din_doc_id not in (
		SELECT tip.id_documento 
		FROM tipos_bien_documentos tip 
		WHERE tip.id_tipo_bien = $tipo_bien) ";
		ejecutar($sql);
}
?>