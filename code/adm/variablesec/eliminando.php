<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];

$sql= "DELETE FROM variable_campo WHERE idtexto ='$id' ";
ejecutar($sql);

?>