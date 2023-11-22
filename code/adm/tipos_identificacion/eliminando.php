<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$sql= "DELETE FROM tipos_identificacion WHERE id_tipo='$id' ";
ejecutar($sql);

?>