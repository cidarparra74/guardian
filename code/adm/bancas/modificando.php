<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$banca= $_REQUEST['banca'];
$codigo= $_REQUEST['codigo'];

$sql= "UPDATE bancas SET banca='$banca', codigo='$codigo' WHERE id_banca ='$id' ";
ejecutar($sql);

?>