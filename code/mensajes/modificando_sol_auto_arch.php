<?php

$id_movimiento= $_REQUEST['id'];

$anios= $_REQUEST['fechaYear'];
$meses= $_REQUEST['fechaMonth'];
$dias= $_REQUEST['fechaDay'];
$horas= $_REQUEST['horaHour'];
$minutos= $_REQUEST['horaMinute'];
$segundos= $_REQUEST['horaSecond'];

$fecha= $anios."-".$meses."-".$dias." ".$horas.":".$minutos.":".$segundos;
$fecha= "CONVERT(DATETIME,'$fecha',102)";

//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET arch_corr_prest=$fecha_actual, arch_corr_plazo=$fecha, id_estado='4', obs_4='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";

ejecutar($sql);

?>