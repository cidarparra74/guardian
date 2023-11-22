<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$cont_A= $_REQUEST['cont_A'];
$cont_B= $_REQUEST['cont_B'];
$cont_P= $_REQUEST['cont_P'];

$sql="DELETE contratos_fijos WHERE id_banca = '$id' ";
ejecutar($sql);

if($cont_A!='-'){
	$sql= "INSERT INTO contratos_fijos (id_banca, clase, idcontrato) VALUES('$id', 'A', '$cont_A') ";
	ejecutar($sql);
}

if($cont_B!='-'){
	$sql= "INSERT INTO contratos_fijos (id_banca, clase, idcontrato) VALUES('$id', 'B', '$cont_B') ";
	ejecutar($sql);
}

if($cont_P!='-'){
	$sql= "INSERT INTO contratos_fijos (id_banca, clase, idcontrato) VALUES('$id', 'P', '$cont_P') ";
	ejecutar($sql);
}

?>
