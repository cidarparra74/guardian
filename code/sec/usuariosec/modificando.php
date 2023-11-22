<?php

$id= $_REQUEST['id'];
$nombre= $_REQUEST['nombre'];
$paterno= $_REQUEST['paterno'];
$materno= $_REQUEST['materno'];
$loca= $_REQUEST['loca'];
$idperfil= $_REQUEST['idperfil'];
$estado= $_REQUEST['estado'];



$sql= "UPDATE usuario SET nombres='$nombre', appaterno='$paterno', apmaterno='$materno', 
	estado='$estado', idperfil='$idperfil', localizacion='$loca' 
	WHERE login='$id' ";
ejecutar($sql);
//echo $sql;
?>