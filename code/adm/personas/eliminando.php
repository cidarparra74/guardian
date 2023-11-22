<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];

$sql= "DELETE FROM personas WHERE id_persona='$id' ";
ejecutar($sql);

?>