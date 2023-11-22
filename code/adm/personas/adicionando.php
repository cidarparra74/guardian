<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


$nombres= strtoupper($_REQUEST['nombres']);
$apellidos= strtoupper($_REQUEST['apellidos']);
$ci= $_REQUEST['ci'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$tipo_rol= $_REQUEST['tipo_rol'];
$id_oficina= $_REQUEST['id_oficina']; // en realidad corresponde a almacen
$correoe= $_REQUEST['correoe'];

$sql= "INSERT INTO personas( ci, nombres, apellidos, direccion, telefonos, tipo_rol, id_oficina, 
correoe) VALUES( '$ci', '$nombres', '$apellidos', '$direccion', '$telefonos', '$tipo_rol', 
'$id_oficina', '$correoe' ) ";
ejecutar($sql);

?>