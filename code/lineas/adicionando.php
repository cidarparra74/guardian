<?php
require_once('../lib/fechas.php');
	
$fecha = $_REQUEST['fecha'];

$fechaok = dateYMD($fecha);
$fechaok = "CONVERT(DATETIME,'$fechaok',102)";

$numero= $_REQUEST['numero'];
$importe= $_REQUEST['importe'];
$moneda= $_REQUEST['moneda'];
$tipo= $_REQUEST['tipo'];
$escritura= $_REQUEST['escritura'];
$notario= $_REQUEST['notario'];
$id= $_REQUEST['id'];

$sql= "INSERT INTO lineas (numero, importe, moneda, tipo, escritura, fechaesc, notario, id_propietario)
 VALUES('$numero', '$importe', '$moneda', '$tipo', '$escritura', $fechaok, '$notario', '$id') ";
ejecutar($sql);

?>
