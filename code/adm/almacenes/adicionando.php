<?php

	//18/07/2015
	require_once('../lib/verificar.php');
	
$nombre= strtoupper($_REQUEST['nombre']);
$id_usautoriza= 0; //$_REQUEST['id_usautoriza'];
$id_usarchivo= 0; //$_REQUEST['id_usarchivo'];
$correoe= $_REQUEST['correoe'];

$sql= "INSERT INTO almacen (nombre, id_usautoriza, id_usarchivo, correoe ) 
VALUES( '$nombre', '$id_usautoriza', '$id_usarchivo', '$correoe' ) ";
ejecutar($sql);

?>