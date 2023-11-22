<?php

$id_carpeta= $_REQUEST['id'];
$id_movimiento= $_REQUEST['id_movimiento'];
$id_corriente= $_REQUEST['usuario'];

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

//id del usuario

$usuario= $_SESSION["idusuario"];

$sql= "UPDATE movimientos_carpetas SET arch_corr_prest=$fecha_actual, arch_corr_plazo=$fecha, 
id_us_corriente='$id_corriente', id_us_archivo='$usuario', obs_4='$observacion'
 WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>
