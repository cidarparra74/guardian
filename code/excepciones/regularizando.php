<?php

$id= $_REQUEST['id'];

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE documentos_excepciones SET observacion_regula='$observacion', fecha_regula=$fecha_actual, vigente='no' WHERE id_documento_excepcion='$id' ";

$query= consulta($sql);

//regularizamos los datos de este cliente
$sql= "SELECT * FROM documentos_excepciones WHERE id_documento_excepcion='$id' ";
$query= consulta($sql);
$resultado= $query->fetchRow(DB_FETCHMODE_ASSOC);
	
$id_carpeta= $resultado["id_carpeta"];
$id_documento= $resultado["id_documento"];

//actualizamo
$sql= "UPDATE documentos_propietarios SET tiene_excepcion='0' WHERE id_carpeta='$id_carpeta' AND id_documento='$id_documento' ";
$query= consulta($sql);

?>