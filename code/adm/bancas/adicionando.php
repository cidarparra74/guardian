<?php

	//18/07/2015
	require_once('../lib/verificar.php');
	

$banca= $_REQUEST['banca'];
$codigo= $_REQUEST['codigo'];

$sql= "INSERT INTO bancas (banca, codigo) VALUES('$banca', '$codigo') ";
ejecutar($sql);

?>
