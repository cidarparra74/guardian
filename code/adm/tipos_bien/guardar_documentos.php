<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

	$cantidad= $_REQUEST['cantidad_total'];
	$id= $_REQUEST['id'];


//obtenemos los docs actuales (ya registrados)
$sql= "SELECT id_documento FROM tipos_bien_documentos WHERE id_tipo_bien = $id ";
$query= consulta($sql);
$registrados = array();
while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
	$registrados[] = $row["id_documento"];
}
//recuperando todos los documentos

$iddoc = $_REQUEST["iddoc"];

foreach($iddoc as $id_documento){
	//buscamos si ya esta registrado
	$existe = 0;
	foreach($registrados as $idya){
		if($idya == $id_documento) $existe = 1;
	}
	
	//verificamos que se haya seleccionado el documento
	$aux= "tiene_".$id_documento;
	if(isset($_REQUEST["$aux"])){
		//insertamos en la tabla tipos_bien_docuementos si es que no existe ya
		if($existe==0){
			$sql_in= "INSERT INTO tipos_bien_documentos( id_tipo_bien, id_documento) ";
			$sql_in.= "VALUES('$id', '$id_documento') ";
			ejecutar($sql_in);
		}
	}else{
		//no tiene o ya no tiene, lo borramos igual
		if($existe==1){
			$sql= "DELETE FROM tipos_bien_documentos WHERE id_tipo_bien='$id' AND id_documento = $id_documento ";
			$result= ejecutar($sql);
		}
	}
}

//volvemos a la lista de documentos
include("./adm/tipos_bien/documentos.php");

?>
