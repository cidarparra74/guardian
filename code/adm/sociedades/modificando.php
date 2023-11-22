<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$descripcion= $_REQUEST['sociedad'];

$sql= "UPDATE sociedades SET  sociedad='$descripcion' WHERE id_sociedad ='$id' ";
ejecutar($sql);

?>