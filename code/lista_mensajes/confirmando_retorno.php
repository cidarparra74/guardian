<?php

$id_movimiento= $_REQUEST['id'];

//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET corr_arch_ret_conf=$fecha_actual, id_estado='7', obs_7='$observacion', flujo='0' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>