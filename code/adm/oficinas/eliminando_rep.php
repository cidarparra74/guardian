<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	


$id= $_REQUEST['id'];
$idb= $_REQUEST['idb'];
$sql= "DELETE FROM representa WHERE id_oficina='$id' AND id_banca = '$idb' ";
//echo $sql; die();
ejecutar($sql);

?>