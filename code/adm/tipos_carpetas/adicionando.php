<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


$tipo= $_REQUEST['tipo'];
$descripcion= $_REQUEST['descripcion'];
$cuenta_ingreso= $_REQUEST['cuenta_ingreso'];
$cuenta_devolucion= $_REQUEST['cuenta_devolucion'];
$id_tipo_bien= $_REQUEST['id_tipo_bien'];
$cuenta= $_REQUEST['cuenta'];

$sql= "INSERT INTO tipos_carpetas(tipo, descripcion, 
			cuenta_ingreso, cuenta_devolucion, id_tipo_bien, cuenta) 
			VALUES( '$tipo', '$descripcion', '$cuenta_ingreso', 
			'$cuenta_devolucion', '$id_tipo_bien', '$cuenta') ";

//echo $sql;
ejecutar($sql);

?>