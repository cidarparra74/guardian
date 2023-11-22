<?php
	//18/07/2015
	require_once('../lib/verificar.php');
	

$id= $_REQUEST['id'];
$nombres= strtoupper($_REQUEST['nombres']);
$apellidos= strtoupper($_REQUEST['apellidos']);
$ci= $_REQUEST['ci'];
$telefonos= $_REQUEST['telefonos'];
$direccion= $_REQUEST['direccion'];
$tipo_rol= $_REQUEST['tipo_rol'];
$id_oficina= $_REQUEST['id_oficina'];
$correoe= $_REQUEST['correoe'];

$sql= "UPDATE personas SET ci='$ci', nombres='$nombres', apellidos='$apellidos', direccion='$direccion', 
telefonos='$telefonos', tipo_rol='$tipo_rol', id_oficina='$id_oficina', correoe='$correoe' WHERE id_persona='$id' ";
ejecutar($sql);

//si ya tenia asignada oficinas en OTRo recinto las tenemos que borrar

		$sql="DELETE op FROM oficina_persona op
		INNER JOIN oficinas fi on fi.id_oficina = op.id_oficina
		WHERE op.id_responsable = $id and fi.id_almacen <> $id_oficina";
		ejecutar($sql);
		
		
?>