<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$sql= "DELETE FROM oficinas WHERE id_oficina='$id' ";
ejecutar($sql);

?>