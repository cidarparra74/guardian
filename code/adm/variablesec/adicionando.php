<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$idtexto= $_REQUEST['idtexto'];
$tipogarantia= $_REQUEST['bien'];
$datos= explode('.', $_REQUEST['campo']);
$adicional= '';
$tabla= $datos[0];
$campo= $datos[1];
if(isset($datos[2]))
	$adicional= $datos[2];
$sql= "INSERT INTO variable_campo(idtexto, tabla, campo, adicional, tipogarantia) 
	VALUES ('$idtexto', '$tabla', '$campo', '$adicional', '$tipogarantia') ";
ejecutar($sql);

?>
