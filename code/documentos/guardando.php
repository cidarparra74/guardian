<?php


$id_carpeta= $_SESSION["documentos_id"];

//eliminando los valores anteriores
$sql= "DELETE FROM documentos_propietarios WHERE id_carpeta='$id_carpeta' ";
ejecutar($sql);
//recuperando el id maximo
		$sql= "SELECT MAX(id_documento_propietario) AS maximo FROM documentos_propietarios ";
		$result= consulta($sql);
		$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
		$id= bcadd($resultado["maximo"],1,0);
	
//recuperando todos los documentos
$cantidad= $_REQUEST["cantidad_total"];
$id_doc= $_REQUEST["id_documento"];
$id_foj= $_REQUEST["fojas"];
$id_obs= $_REQUEST["obs"];
$id_tip= $_REQUEST["tipo"];

for($i=0; $i<$cantidad; $i++){
	
	$aux = "tiene_".$i;
	$aux2 = "noobs_".$i;
	if(isset($_REQUEST["$aux"])) //no tomamos en cuenta el doc!
		$falta = 0;
	else
		$falta = 1;
	
	if(isset($_REQUEST["$aux2"])) //no tomamos en reporte de obs
		$noobs = 1;
	else
		$noobs = 0;
	
	if(	$falta == 1){
	//guardamos en la bd
		$sql= "INSERT INTO documentos_propietarios (
		id_carpeta, id_documento, id_tipo_documento, numero_hojas, 
				observacion, noobs) "; 
		$sql.= "VALUES ('$id_carpeta', $id_doc[$i], $id_tip[$i], '$id_foj[$i]', 
		'$id_obs[$i]', '$noobs') ";
	//echo $sql;
		ejecutar($sql);
		
	} //fin del documento
	$id++;
}

?>
