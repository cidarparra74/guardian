<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

	$cantidad= $_REQUEST['cantidad_total'];
	$id= $_REQUEST['id'];
	
//eliminamos lo actual de este tipo de bien
$sql= "DELETE FROM tipos_carpeta_documentos WHERE id_tipo_carpeta='$id' ";
//echo "$sql";
ejecutar($sql);


//recuperando todos los documentos
$sql= "SELECT id_documento FROM documentos ORDER BY id_documento ";
$query= consulta($sql);

while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$id_documento= $row["id_documento"];
	
	//verificamos que se haya seleccionado el documento
	$aux= "tiene_".$id_documento;
	if(isset($_REQUEST["$aux"])){
		//insertamos en la tabla tipos_bien_docuementos
		$sql_in= "INSERT INTO tipos_carpeta_documentos(id_tipo_carpeta, id_documento) ";
		$sql_in.= "VALUES('$id', '$id_documento') ";
		ejecutar($sql_in);
	}
}

//volvemos a la lista de documentos
include("./adm/tipos_carpetas/documentos.php");

?>
