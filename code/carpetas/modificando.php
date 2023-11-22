<?php

$id= $_REQUEST['id'];
//$id_propietario= $_REQUEST['propietario'];
$id_oficina= $_REQUEST['oficina'];
$carpeta= $_REQUEST['carpeta'];
$operacion= $_REQUEST['operacion'];
$tipo_carpeta= $_REQUEST['tipo_carpeta'];

/*
//buscamos si existe la operacion
$sql= "SELECT operacion FROM prestamos WHERE operacion = '$operacion' ";
	$query = consulta($sql);
	$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
	if($operacion==$row["operacion"]){
		//existe, vemos la cuenta asociada
		$sql= "SELECT cuenta FROM tipos_bien WHERE id_tipo_bien = '$tipo_carpeta' ";
		$query = consulta($sql);
		$row= $query->fetchRow(DB_FETCHMODE_ASSOC);
		$cuenta=$row["cuenta"];
	}else{
		$smarty->display('carpetas/faltaope.html');
		die();
	}
*/
$sql= "UPDATE carpetas SET id_oficina='$id_oficina', 
	carpeta='$carpeta', id_tipo_carpeta='$tipo_carpeta', operacion='$operacion' WHERE id_carpeta='$id' ";
ejecutar($sql);
/*
$sql= "UPDATE comprobantes SET cuenta='$cuenta' WHERE id_carpeta='$id' ";
ejecutar($sql);
*/
?>
