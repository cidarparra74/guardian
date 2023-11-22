<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$sql= "DELETE FROM tipos_documentos WHERE id_tipo_documento='$id' ";
ejecutar($sql);

?>