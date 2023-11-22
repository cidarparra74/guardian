<?php

$id_movimiento= $_REQUEST['id'];

$sql= "UPDATE movimientos_carpetas SET flujo='1' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>