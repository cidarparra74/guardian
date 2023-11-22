<?php

$id_carpeta= $_REQUEST['id'];
$id_corriente= $_REQUEST['usuario'];

$anios= $_REQUEST['fechaYear'];
$meses= $_REQUEST['fechaMonth'];
$dias= $_REQUEST['fechaDay'];
$horas= $_REQUEST['horaHour'];
$minutos= $_REQUEST['horaMinute'];


$fecha= $anios."-".$meses."-".$dias." ".$horas.":".$minutos;
$fecha= "CONVERT(DATETIME,'$fecha',102)";

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$usuario= $_SESSION["idusuario"];

$sql= "INSERT INTO movimientos_carpetas(id_carpeta, arch_corr_prest, arch_corr_plazo, id_us_corriente, id_us_archivo, 
id_estado, flujo, obs_4) VALUES('$id_carpeta', $fecha_actual, $fecha, '$id_corriente', '$usuario', '4', '0', '$observacion') ";
//echo $sql;
ejecutar($sql);

?>
