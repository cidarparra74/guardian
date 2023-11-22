<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$descripcion= $_REQUEST['objeto'];

$sql= "INSERT INTO objetos (objeto) VALUES('$descripcion') ";
ejecutar($sql);

?>
