<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	
$id= $_REQUEST['id'];
$idtexto= $_REQUEST['idtexto'];
$tabla= $_REQUEST['tabla'];
$campo= $_REQUEST['campo'];
$adicional= $_REQUEST['adicional'];
$tipogarantia= $_REQUEST['bien'];
$sql= "UPDATE variable_campo 
SET idtexto='$idtexto', 
tabla='$tabla', 
campo='$campo', 
adicional='$adicional' , 
tipogarantia='$tipogarantia'
WHERE idtexto ='$id' ";
ejecutar($sql);

?>