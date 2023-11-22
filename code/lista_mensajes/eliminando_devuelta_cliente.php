<?php

$id_movimiento= $_REQUEST['id'];
$observacion= $_REQUEST['observacion'];

$sql= "UPDATE movimientos_carpetas SET flujo='1', obs_dev='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>