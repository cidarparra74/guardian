<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$descripcion= $_REQUEST['entidad'];

$sql= "INSERT INTO entidades(entidad) VALUES('$descripcion') ";
ejecutar($sql);

?>
