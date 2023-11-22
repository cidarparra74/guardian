<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$nombre= $_REQUEST['nombre'];
$direccion= $_REQUEST['direccion'];
$id_almacen= $_REQUEST['id_almacen'];
$telefonos= $_REQUEST['telefonos'];
$pais= $_REQUEST['pais'];
$ciudad= $_REQUEST['ciudad'];
$id_responsable= $_REQUEST['id_responsable'];
$id_asesor= $_REQUEST['id_asesor'];
$codigo= $_REQUEST['codigo'];
$correos= $_REQUEST['correos'];

$sql= "UPDATE oficinas SET nombre='$nombre', direccion='$direccion', telefonos='$telefonos', 
id_almacen='$id_almacen', pais='$pais', ciudad='$ciudad', id_responsable='$id_responsable', 
id_asesor='$id_asesor', codigo='$codigo', correos='$correos'
 WHERE id_oficina='$id' ";
ejecutar($sql);

?>