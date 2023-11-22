<?php

$id_movimiento= $_REQUEST['id'];

//fecha actual

$fecha_actual= date("Y-m-d H:i:s");

//2019-07-22 cambio en fecha actual por fecha manual por Percy
$anios= $_REQUEST['fechaYear'];
$meses= $_REQUEST['fechaMonth'];
$dias= $_REQUEST['fechaDay'];
$horas= $_REQUEST['horaHour'];
$minutos= $_REQUEST['horaMinute'];
$fecha_actual= $anios."-".$meses."-".$dias." ".$horas.":".$minutos;

$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET corr_dev=$fecha_actual, id_estado='8', obs_8='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>