<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];

$sql= "DELETE FROM sociedades WHERE id_sociedad = '$id' ";
ejecutar($sql);

?>