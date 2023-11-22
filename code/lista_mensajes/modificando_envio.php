<?php

$id_carpeta= $_REQUEST['id'];
$id_movimiento= $_REQUEST['id_movimiento'];
$id_corriente= $_REQUEST['usuario'];

//fecha actual
$fecha_actual= date("Y-m-d H:i:s");
$fecha_actual= "CONVERT(DATETIME,'$fecha_actual',102)";

$observacion= $_REQUEST['observacion'];

//id del usuario
$login_acc= $_SESSION['nombreUsu'];
$password_acc= md5($_SESSION['passwordUsu']);
$sql= "SELECT id_usuario FROM usuarios WHERE login='$login_acc' AND password='$password_acc' ";
$result= consulta($sql);
$resultado= $result->fetchRow(DB_FETCHMODE_ASSOC);
$usuario= $resultado["id_usuario"];

//plazo
$anios= $_REQUEST['fechaYear'];
$meses= $_REQUEST['fechaMonth'];
$dias= $_REQUEST['fechaDay'];
$horas= $_REQUEST['horaHour'];
$minutos= $_REQUEST['horaMinute'];
$segundos= $_REQUEST['horaSecond'];

$fecha= $anios."-".$meses."-".$dias." ".$horas.":".$minutos.":".$segundos;
$fecha= "CONVERT(DATETIME,'$fecha',102)";

$sql= "UPDATE movimientos_carpetas SET arch_corr_prest=$fecha_actual, arch_corr_plazo=$fecha, id_us_corriente='$id_corriente', obs_4='$observacion' WHERE id_movimiento_carpeta='$id_movimiento' ";
ejecutar($sql);

?>
