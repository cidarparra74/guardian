<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$descripcion= $_REQUEST['sociedad'];

$sql= "INSERT INTO sociedades (sociedad) VALUES('$descripcion') ";
ejecutar($sql);

?>
