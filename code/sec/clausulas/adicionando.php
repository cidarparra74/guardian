<?php

$titulo= $_REQUEST['titulo'];
$descri= $_REQUEST['descri'];
$contenido= $_REQUEST['contenido'];
$tipo= $_REQUEST['tipo'];
$materia= $_REQUEST['materia'];
$entidad= $_REQUEST['entidad'];

	$sql= "INSERT INTO clausula (titulo, contenido, codtipo, codmateria, codentidad) 
	VALUES( '$titulo', '$contenido', '$tipo', '$materia', '$entidad') ";
	ejecutar($sql);
	
?>