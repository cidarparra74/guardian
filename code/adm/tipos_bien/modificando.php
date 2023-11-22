<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$tipo= strtoupper($_REQUEST['tipo']);
$descripcion= strtoupper($_REQUEST['descripcion']);
$con_inf_legal= $_REQUEST['con_inf_legal'];
$con_recepcion= $_REQUEST['con_recepcion'];
$bien= $_REQUEST['bien'];
$cuenta= $_REQUEST['cuenta'];
$con_perito= $_REQUEST['con_perito'];
$id_banca= $_REQUEST['id_banca'];

$sql= "UPDATE tipos_bien SET tipo_bien='$tipo', descripcion='$descripcion', con_inf_legal='$con_inf_legal', bien='$bien', con_recepcion='$con_recepcion', cuenta='$cuenta', con_perito='$con_perito', id_banca='$id_banca' WHERE id_tipo_bien='$id' ";

ejecutar($sql);

?>