<?php

$gaveta= $_REQUEST['gaveta'];
$tipo= $_REQUEST['tipo'];
$oficina= $_REQUEST['oficina'];
$posicion_gaveta= $_REQUEST['posicion_gaveta'];

$consulta="";
if($tipo != "todos"){
	$consulta=$consulta."WHERE t.id_tipo_bien='$tipo' ";
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


$sql_del= "DELETE FROM tmp_inv_detallado_mis ";
ejecutar($sql_del);

$sql= "insert into tmp_inv_detallado_mis ";
$sql.= "SELECT p.mis, p.nombres, o.nombre, m.id_estado, m.flujo ";
$sql.= "FROM (propietarios p inner join carpetas c on c.id_propietario=p.id_propietario ";
$sql.= "inner join oficinas o on c.id_oficina=o.id_oficina inner join tipos_bien t on c.id_tipo_carpeta=t.id_tipo_bien) ";
$sql.= "left join movimientos_carpetas m on m.id_carpeta=c.id_carpeta AND (m.flujo!='1' OR m.id_estado='8') $consulta ORDER BY p.mis, m.id_estado ";
ejecutar($sql);

$smarty->display('reportes/inv_detallado_mis_imp.html');
die();
?>