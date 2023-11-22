<?php

$id_movimiento= $_REQUEST['id'];

//fecha actual

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET neg_auto_corr=$fecha_actual, id_estado='2', obs_2='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";

ejecutar($sql);

?>