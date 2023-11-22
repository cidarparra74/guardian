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
$idl= $_REQUEST['idl'];

$sql= "UPDATE lineas SET numero='$numero', 
importe='$importe', 
moneda='$moneda', 
tipo='$tipo', 
escritura='$escritura', 
notario='$notario', 
fechaesc=$fechaok 
WHERE id_linea ='$idl' ";
ejecutar($sql);

?>