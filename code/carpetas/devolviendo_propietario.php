<?php

$id_carpeta = $_REQUEST['id'];

//fecha actual

$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$fecha_dev= $_REQUEST['fecha_dev'];
$fecha_dev= "CONVERT(DATETIME,'$fecha_dev',103)";
$observacion= $_REQUEST['observacion'];
$idus= $_SESSION['idusuario'];

//ver lo de fechas, fecha del servidor en corr_auto, fecha del usuario en corr_dev

$sql= "INSERT INTO movimientos_carpetas 
(id_carpeta, id_us_corriente, id_us_archivo, corr_auto, corr_dev, id_estado, obs_8)
values($id_carpeta, $idus, $idus, $fecha_actual, $fecha_dev, '8', '$observacion' )";
ejecutar($sql);
//echo $sql;
//ponemos la fecha de devolucion
$sql= "UPDATE carpetas SET fecha_devolucion =  $fecha_dev WHERE id_carpeta = $id_carpeta ";
ejecutar($sql);
?>