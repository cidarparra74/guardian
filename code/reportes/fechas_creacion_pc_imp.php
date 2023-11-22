<?php

$gaveta= $_REQUEST['gaveta'];
$tipo= $_REQUEST['tipo'];
$oficina= $_REQUEST['oficina'];
$estado= $_REQUEST['estado'];
$posicion_gaveta= $_REQUEST['posicion_gaveta'];

//fechas de creacion
$anios= $_REQUEST['fecha1Year'];
$meses= $_REQUEST['fecha1Month'];
$dias= $_REQUEST['fecha1Day'];
$titulo= "Del: $dias / $meses / $anios  ";


$fecha_aux= $anios."-".$meses."-".$dias." 00:00:00";
$fecha_inicio= "CONVERT(DATETIME,'$fecha_aux',102)";

$anios= $_REQUEST['fecha2Year'];
$meses= $_REQUEST['fecha2Month'];
$dias= $_REQUEST['fecha2Day'];
$titulo=$titulo."AL: $dias / $meses / $anios ";

$fecha_aux= $anios."-".$meses."-".$dias." 00:00:00";
$fecha_fin= "CONVERT(DATETIME,'$fecha_aux',102)";


$consulta="";
if($tipo != "todos"){
	if($consulta == ""){
		$consulta=$consulta."WHERE t.id_tipo_bien='$tipo' ";
	}
}
if($oficina != "todos"){
	if($consulta == ""){
		$consulta=$consulta."WHERE o.id_oficina='$oficina' ";
	}
	else{
		$consulta=$consulta."AND o.id_oficina='$oficina' ";
	}
}
if($gaveta != "todos"){
	if($consulta == ""){
		if($posicion_gaveta == "todos"){
			$consulta=$consulta."WHERE p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta=$consulta."WHERE p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
	else{
		if($posicion_gaveta == "todos"){
			$consulta=$consulta."AND p.mis LIKE '%$gaveta' ";
		}
		else{
			$consulta=$consulta."AND p.mis LIKE '%$posicion_gaveta$gaveta' ";
		}
	}
}


$sql_del= "DELETE FROM tmp_fechas_creacion_pc ";
ejecutar($sql_del);

if($consulta == ""){
	$consulta= "WHERE p.creacion_propietario>= $fecha_inicio AND p.creacion_propietario<=$fecha_fin AND c.creacion_carpeta>=$fecha_inicio AND c.creacion_carpeta<=$fecha_fin ";
}
else{
	$consulta= "AND p.creacion_propietario>= $fecha_inicio AND p.creacion_propietario<=$fecha_fin AND c.creacion_carpeta>=$fecha_inicio AND c.creacion_carpeta<=$fecha_fin ";
}

$sql= "INSERT INTO tmp_fechas_creacion_pc SELECT p.mis, p.nombres, t.tipo, o.nombre, m.id_estado, m.flujo, p.creacion_propietario, c.creacion_carpeta FROM (propietarios p INNER JOIN carpetas c ON c.id_propietario=p.id_propietario INNER JOIN oficinas o ON c.id_oficina=o.id_oficina INNER JOIN tipos_bien t ON c.id_tipo_carpeta=t.id_tipo_bien) ";
$sql.= "LEFT JOIN movimientos_carpetas m ON m.id_carpeta=c.id_carpeta AND m.flujo!='1' ";
$sql.= "$consulta ORDER BY c.id_carpeta ";
ejecutar($sql);

$smarty->assign('titulo',$titulo);

$smarty->display('reportes/fechas_creacion_pc_imp.html');
die();
?>