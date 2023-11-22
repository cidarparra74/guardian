<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$idcla= $_REQUEST['idcla'];
$id= $_REQUEST['id'];
$idc= $_REQUEST['idc'];


$sql="DELETE sec_opcional WHERE id_banca='$id' AND idcontrato = '$idc' AND tc = 'SEG' ";
ejecutar($sql);


$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', '$idcla', 'SEG', 0, 0) ";
	ejecutar($sql);


?>
