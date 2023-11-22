<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$tipo= $_REQUEST['tipo'];
$descripcion= $_REQUEST['descripcion'];

$sql= "UPDATE tipos_identificacion SET identificacion='$tipo', descripcion='$descripcion' WHERE id_tipo='$id' ";

ejecutar($sql);

?>