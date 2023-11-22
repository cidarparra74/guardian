<?php

$id_movimiento= $_REQUEST['id'];

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET arch_corr_conf=$fecha_actual, id_estado='5', obs_5='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>