<?php

$id= $_REQUEST['idcaso'];

$importePrestamo= $_REQUEST['importePrestamo'];
$monedaPrestamo= $_REQUEST['monedaPrestamo'];
$CuentaDesembolso= $_REQUEST['CuentaDesembolso'];
$destinoCredito= $_REQUEST['destinoCredito'];
$numeroCuotas= $_REQUEST['numeroCuotas'];
$Tasa1= $_REQUEST['Tasa1'];
$Tasa2= $_REQUEST['Tasa2'];
$Teac= $_REQUEST['Teac'];
$Cuota= $_REQUEST['Cuota'];
$cuotasTasaFija= $_REQUEST['cuotasTasaFija'];
$numeroLinea= $_REQUEST['numeroLinea'];
$importeLinea= $_REQUEST['importeLinea'];
$monedaLinea= $_REQUEST['monedaLinea'];
$plazoMeses= $_REQUEST['plazoMeses'];

$sql= "UPDATE ncaso_cfinal SET importePrestamo='$importePrestamo', monedaPrestamo='$monedaPrestamo', 
CuentaDesembolso='$CuentaDesembolso', destinoCredito='$destinoCredito', 
numeroCuotas='0$numeroCuotas', Tasa1='0$Tasa1', Tasa2='0$Tasa2', plazoMeses='0$plazoMeses',
cuotasTasaFija='0$cuotasTasaFija', numeroLinea='0$numeroLinea', importeLinea='0$importeLinea', monedaLinea='$monedaLinea', teac='0$Teac', cuota='0$Cuota'
WHERE nrocaso='$id' AND idfinal = 0 ";
ejecutar($sql);

?>