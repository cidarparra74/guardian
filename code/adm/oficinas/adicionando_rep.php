<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id_oficina= $_REQUEST['id'];
$id_banca= $_REQUEST['id_banca'];
$i=2;
$registro = "nombre1";
//var_dump($_REQUEST);
//para los representantes guardados en SEC
while(isset($_REQUEST[$registro])){
	$repre = explode('|', $_REQUEST[$registro]); 
	$idtexto = $repre[0];
	$nombre = $repre[1];
	$sql= "INSERT INTO representa (id_oficina, id_banca, idtexto, nombre) 
	VALUES ( '$id_oficina', '$id_banca', '$idtexto', '$nombre' ) ";
	$registro = "nombre".$i;
	ejecutar($sql);
	$i++;
}


?>