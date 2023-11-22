<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$tipo= $_REQUEST['tipo'];
$descripcion= $_REQUEST['descripcion'];
$cuenta_ingreso= $_REQUEST['cuenta_ingreso'];
$cuenta_devolucion= $_REQUEST['cuenta_devolucion'];
$id_tipo_bien= $_REQUEST['id_tipo_bien'];
$cuenta= $_REQUEST['cuenta'];

$sql= "UPDATE tipos_carpetas SET tipo='$tipo', descripcion='$descripcion', 
cuenta_ingreso='$cuenta_ingreso', cuenta_devolucion='$cuenta_devolucion' ,
id_tipo_bien='$id_tipo_bien',
cuenta='$cuenta' 
WHERE id_tipo_carpeta='$id' ";
ejecutar($sql);

?>