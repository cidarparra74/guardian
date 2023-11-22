<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$nombre= $_REQUEST['nombre'];
$direccion= $_REQUEST['direccion'];
$id_almacen= $_REQUEST['id_almacen'];
$telefonos= $_REQUEST['telefonos'];
$ciudad= $_REQUEST['ciudad'];
$pais= $_REQUEST['pais'];
$id_responsable= $_REQUEST['id_responsable'];
$id_asesor= $_REQUEST['id_asesor'];
$codigo= $_REQUEST['codigo'];
$correos= $_REQUEST['correos'];
//$id_asesor= $_REQUEST['id_asesor'];
$sql= "INSERT INTO oficinas(nombre, direccion, telefonos, id_almacen, pais, ciudad, id_responsable, id_asesor, codigo, correos ) 
VALUES( '$nombre', '$direccion', '$telefonos', '$id_almacen', '$pais', '$ciudad', '$id_responsable', '$id_asesor', '$codigo', '$correos' ) ";

ejecutar($sql);

?>