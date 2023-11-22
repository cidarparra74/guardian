<?php

$cantidad= $_REQUEST['cantidad'];

$id_archivo= $_REQUEST['usuario'];
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

//usuario inicio
$id_us_mandar= $_REQUEST['usuario_inicio'];

for($i=1; $i<= $cantidad; $i++){
	$aux="ids_mov_".$i;
	$id_p= $_REQUEST["$aux"];
	$sql= "UPDATE movimientos_carpetas SET auto_arch=$fecha_actual, auto_arch_plazo=$fecha, id_us_corriente='$id_us_mandar', id_us_archivo='$id_archivo', id_estado='3', obs_3='$observacion' WHERE id_movimiento_carpeta='$id_p' ";
	//echo $sql;
	ejecutar($sql);
}

?>