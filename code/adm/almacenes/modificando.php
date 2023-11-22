<?php

	//18/07/2015
	require_once('../lib/verificar.php');
	
$id= $_REQUEST['id'];
$nombre= $_REQUEST['nombre'];
$id_usautoriza= $_REQUEST['id_usautoriza'];
$id_usarchivo= $_REQUEST['id_usarchivo'];
$correoe= $_REQUEST['correoe'];

$sql= "UPDATE almacen SET nombre='$nombre', 
correoe='$correoe',
id_usautoriza='$id_usautoriza', 
id_usarchivo='$id_usarchivo' 
WHERE id_almacen='$id' ";
ejecutar($sql);

?>