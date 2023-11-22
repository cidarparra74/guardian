<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];

$sql= "DELETE FROM permisos WHERE id_tipo_carpeta='$id' ";
ejecutar($sql);

$sql= "DELETE FROM tipos_carpetas WHERE id_tipo_carpeta='$id' ";
ejecutar($sql);

?>