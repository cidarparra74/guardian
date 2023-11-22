<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	
$tipo= $_REQUEST['tipo'];
$descripcion= $_REQUEST['descripcion'];

$sql= "INSERT INTO tipos_identificacion(identificacion, descripcion) VALUES('$tipo', '$descripcion') ";
ejecutar($sql);

?>
