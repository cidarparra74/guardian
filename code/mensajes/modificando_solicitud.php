<?php


$id_movimiento= $_REQUEST['id_movimiento'];
$id_autoriza= $_REQUEST['usuario'];

//fecha actual

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$usuario= $_SESSION["idusuario"];

$sql= "UPDATE movimientos_carpetas SET corr_auto=$fecha_actual, id_us_corriente='$usuario', id_us_autoriza='$id_autoriza', 
obs_1='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>
