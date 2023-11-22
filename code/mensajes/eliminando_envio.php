<?php

$id_movimiento= $_REQUEST['id'];

$sql= "UPDATE movimientos_carpetas SET auto_arch=null, neg_auto_corr=null, auto_arch_plazo=null, id_us_corriente=id_us_inicio, 
id_us_archivo=null, id_estado='1', obs_2=null, obs_3=null WHERE id_movimiento_carpeta='$id_movimiento' ";

ejecutar($sql);

?>