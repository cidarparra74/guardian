<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$descripcion= $_REQUEST['entidad'];

$sql= "UPDATE entidades SET  entidad='$descripcion' WHERE id ='$id' ";
ejecutar($sql);

?>