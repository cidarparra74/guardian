<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$tipo= strtoupper($_REQUEST['tipo']);
$descripcion= strtoupper($_REQUEST['descripcion']);
$con_inf_legal= $_REQUEST['con_inf_legal'];
$con_recepcion= $_REQUEST['con_recepcion'];
$bien= $_REQUEST['bien'];
$cuenta= $_REQUEST['cuenta'];
$con_perito= $_REQUEST['con_perito'];
$id_banca= $_REQUEST['id_banca'];
//
$sql= "INSERT INTO tipos_bien(tipo_bien, descripcion, con_inf_legal, bien,con_recepcion, cuenta, con_perito, categoria, id_banca) 
VALUES('$tipo', '$descripcion', '$con_inf_legal', '$bien', '$con_recepcion', '$cuenta', '$con_perito', '$cat', '$id_banca') ";

ejecutar($sql);

?>