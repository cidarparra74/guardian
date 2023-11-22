<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$descripcion= $_REQUEST['objeto'];

$sql= "UPDATE objetos SET  objeto='$descripcion' WHERE id_objeto ='$id' ";
ejecutar($sql);

?>