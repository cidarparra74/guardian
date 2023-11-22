<?php

$id_carpeta= $_REQUEST['id'];
$id_movimiento= $_REQUEST['id_movimiento'];

//$sql= "DELETE FROM movimientos_carpetas WHERE id_movimiento_carpeta='$id_movimiento' ";
$sql= "UPDATE movimientos_carpetas SET flujo='1' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>