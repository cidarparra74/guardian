<?php

	$id= $_REQUEST['id'];
	//$sql= "UPDATE tipos_bien_documentos SET imprimir='0', requerido = '0' WHERE id_tipo_bien = $id";
	//ejecutar($sql);

//recuperando todos los documentos
//$sql= "SELECT id_documento FROM tipos_bien_documentos WHERE id_tipo_bien = $id";
//$query= consulta($sql);
$iddoc = $_REQUEST["iddoc"];
//while($row= $query->fetchRow(DB_FETCHMODE_ASSOC)){
foreach($iddoc as $id_documento){

	//verificamos que se haya seleccionado el documento
	$aux1= "tiene_".$id_documento;
	$aux2= "req_".$id_documento;
	$aux3= "ord_".$id_documento;
	if(isset($_REQUEST["$aux1"])){
		$imp = "1";
	}else{
		$imp = "0";
	}
	if(isset($_REQUEST["$aux2"])){	
		$req = "1";
	}else{
		$req = "0";
	}
	$orden = $_REQUEST["$aux3"];
	//actualizamos en la tabla tipos_bien_documentos
		$sql_in= "UPDATE tipos_bien_documentos SET imprimir = '$imp', requerido = '$req', orden = '$orden'  
		WHERE id_tipo_bien = $id AND id_documento = $id_documento";
		ejecutar($sql_in);
		//echo $sql_in; echo "<br />";
}

//volvemos a la lista de documentos
//include("./adm/tipos_bien/documentos.php");

?>
