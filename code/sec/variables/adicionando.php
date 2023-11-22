<?php

$idtexto= $_REQUEST['idtexto'];
$descri= $_REQUEST['descri'];
$tipo= $_REQUEST['tipo'];
$lineas= $_REQUEST['lineas'];
$esglobal= $_REQUEST['esglobal'];
$eslista= $_REQUEST['eslista'];
$contenido= $_REQUEST['contenido'];
$contenido2= $_REQUEST['contenido2'];
if($eslista == '0'){
	$sql= "INSERT INTO var_texto (idtexto, contenido, esglobal, descripcion, eslista, lineas, tipo ) 
	VALUES( '$idtexto', '$contenido', '$esglobal', '$descri', '$eslista', '$lineas', '$tipo') ";
	ejecutar($sql);
	if($contenido2!=''){
		$sql= "INSERT INTO var_texto_valores (idtexto, valor, adicional ) 
		VALUES( '$idtexto', '$contenido', '$contenido2') ";
		ejecutar($sql);
	}
}else{
	$sql= "INSERT INTO var_texto (idtexto, contenido, esglobal, descripcion, eslista, lineas, tipo ) 
	VALUES( '$idtexto', '', '$esglobal', '$descri', '1', '$lineas', '$tipo') ";
	ejecutar($sql);
	$valores = explode($contenido,'|');
	foreach($valores as $valor){
		$sql= "INSERT INTO var_texto_valores (idtexto, valor, adicional ) 
		VALUES( '$idtexto', '$valor', '$contenido2') ";
		ejecutar($sql);
	}
}

?>