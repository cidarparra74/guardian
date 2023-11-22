<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


$idr= $_REQUEST['idr'];
$nombre= $_REQUEST['nombre'];
$id= $_REQUEST['id'];

$sql= "UPDATE representa SET nombre='$nombre' WHERE id_representa='$idr' ";
ejecutar($sql);

?>