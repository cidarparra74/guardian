<?php

$id= $_REQUEST['id'];
$que= $_REQUEST['que'];
if($que=='dat'){
	$titulo= $_REQUEST['titulo'];
	$descri= $_REQUEST['descri'];
	$tipo= $_REQUEST['tipo'];
	$materia= $_REQUEST['materia'];
	$entidad= $_REQUEST['entidad'];

	$sql= "UPDATE clausula SET titulo='$titulo', descri='$descri', 
				codtipo='$tipo', codmateria='$materia', codentidad='$entidad'
			WHERE idclausula='$id' ";
	ejecutar($sql);
}else{
	$contenido= $_REQUEST['contenido'];
	//reemplazamos comilla simple por dos comillas simples para no generar error en sql INSERT
	$contenido = str_replace("'","''",$contenido);
	//reemplazamos caracteres extraños
	$contenido = str_replace("&#13;","",$contenido);
	$sql= "UPDATE clausula SET contenido='$contenido' WHERE idclausula='$id' ";
	ejecutar($sql);
}
?>