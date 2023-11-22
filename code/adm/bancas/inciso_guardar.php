<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

//LOS IdNUMERAL
$SVD= $_REQUEST['SVD'];
$FPS= $_REQUEST['FPS'];
$FPU= $_REQUEST['FPU'];
$TMI= $_REQUEST['TMI'];
$TVA= $_REQUEST['TVA'];
$TFI= $_REQUEST['TFI'];

$LIR= $_REQUEST['LIR'];
$LIS= $_REQUEST['LIS'];


//
$id= $_REQUEST['id'];
$idc= $_REQUEST['idc'];

//----
$sql="DELETE sec_opcional WHERE id_banca='$id' AND idcontrato = '$idc' AND tc IN ('SVD','FPS','FPU','TMI','TVA','TFI','LIR','LIS') ";
ejecutar($sql);
//----

if($SVD!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'SVD', $SVD, 0) ";
		ejecutar($sql);
}
if($FPS!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'FPS', $FPS, 0) ";
		ejecutar($sql);
}
if($FPU!='-'){		
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'FPU', $FPU, 0) ";
		ejecutar($sql);
}
if($TMI!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'TMI', $TMI, 0) ";
	ejecutar($sql);
}
if($TVA!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'TVA', $TVA, 0) ";
	ejecutar($sql);
}
if($TFI!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'TFI', $TFI, 0) ";
	ejecutar($sql);
}
if($LIR!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'LIR', $LIR, 0) ";
	ejecutar($sql);
}
if($LIS!='-'){
	$sql= "INSERT INTO sec_opcional (id_banca, idcontrato, idclausula, tc, idnumeral, opcional) VALUES('$id', '$idc', 0, 'LIS', $LIS, 0) ";
	ejecutar($sql);
}
?>
