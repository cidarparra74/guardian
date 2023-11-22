<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$tipo= $_REQUEST['tipo'];
$descripcion= $_REQUEST['descripcion'];

$sql= "UPDATE tipos_documentos SET tipo='$tipo', descripcion='$descripcion' WHERE id_tipo_documento='$id' ";
ejecutar($sql);

?>