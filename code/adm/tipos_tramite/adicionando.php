<?php
//print_r($_REQUEST);
//die();


$descripcion= $_REQUEST['descripcion'];

$sql= "INSERT INTO tipos_tramite( descripcion) VALUES('$descripcion') ";
$link->query($sql);

?>